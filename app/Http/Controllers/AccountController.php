<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Vendor;
use App\Models\Country;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\AccountsImport;
use App\Jobs\RecalculateAccountBalances;

class AccountController extends Controller
{
    private $incomeTypes = ['Received', 'Receivable'];
    private $expenseTypes = ['Payment', 'Payable', 'Purchase', 'Salary', 'Office costs'];

    /* =======================================================
     * INDEX
     * ======================================================= */
    public function index(Request $request)
    {
        $query = Account::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('vendor')) {
            $query->where('vendor_name', $request->vendor);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('entry_type')) {
            $query->where('entry_type', $request->entry_type);
        }

        $totals = (clone $query)
            ->selectRaw("SUM(CASE WHEN entry_type IN ('" . implode("','", $this->incomeTypes) . "') THEN amount ELSE 0 END) as total_income")
            ->selectRaw("SUM(CASE WHEN entry_type IN ('" . implode("','", $this->expenseTypes) . "') THEN amount ELSE 0 END) as total_expense")
            ->selectRaw("SUM(CASE WHEN entry_type IN ('" . implode("','", $this->incomeTypes) . "') THEN amount 
                             WHEN entry_type IN ('" . implode("','", $this->expenseTypes) . "') THEN -amount ELSE 0 END) as total_balance")
            ->first();

        $income  = $totals->total_income ?? 0;
        $expense = $totals->total_expense ?? 0;
        $balance = $totals->total_balance ?? 0;

        $accounts = $query
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        $activeCountries = Country::where('status', true)
            ->orderBy('name')
            ->get();

        $activeVendors = Vendor::where('status', true)
            ->orderBy('name')
            ->get();

        $entryTypes = Account::distinct()
            ->pluck('entry_type')
            ->filter()
            ->sort()
            ->values();

        return view('client.accounts.index', compact(
            'income',
            'expense',
            'balance',
            'accounts',
            'activeCountries',
            'activeVendors',
            'entryTypes'
        ));
    }

    /* =======================================================
     * STORE
     * ======================================================= */
    public function store(Request $request)
    {
        $validated = $request->validate($this->accountRules());

        $filePath = $request->hasFile('document')
            ? $request->file('document')->store('accounts', 'public')
            : null;

        Account::create([
            ...$validated,
            'document' => $filePath,
            'last_status' => $validated['last_status'] ?? 'Pending',
            'balance' => 0
        ]);

        RecalculateAccountBalances::dispatchSync($validated['vendor_name']);

        return back()->with('success', 'Entry Added');
    }

    /* =======================================================
     * UPDATE
     * ======================================================= */
    public function update(Request $request, int $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate($this->accountRules());
        $data = $request->except('document');

        if ($request->hasFile('document')) {
            if ($account->document) {
                Storage::disk('public')->delete($account->document);
            }
            $data['document'] = $request->file('document')->store('accounts', 'public');
        }

        $data['last_status'] = $validated['last_status'] ?? 'Pending';
        $account->update($data);

        RecalculateAccountBalances::dispatchSync($account->vendor_name);

        return back()->with('success', 'Updated');
    }

    /* =======================================================
     * DELETE
     * ======================================================= */
    public function destroy(int $id)
    {
        $account = Account::findOrFail($id);

        if ($account->document) {
            Storage::disk('public')->delete($account->document);
        }

        $account->delete();

        RecalculateAccountBalances::dispatchSync($account->vendor_name);

        return back()->with('success', 'Deleted');
    }

    /* =======================================================
     * VENDOR LIST
     * ======================================================= */
    public function vendorlist(Request $request)
    {
        // Optimized to use a single aggregate query instead of N+1 queries in a loop
        $data = Account::select('vendor_name as name')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw("SUM(CASE WHEN entry_type IN ('" . implode("','", $this->incomeTypes) . "') THEN amount 
                             WHEN entry_type IN ('" . implode("','", $this->expenseTypes) . "') THEN -amount ELSE 0 END) as total")
            ->groupBy('vendor_name')
            ->orderBy('vendor_name')
            ->get();
           
        return view('client.accounts.vendors', compact('data'));
    }

    /* =======================================================
     * LEDGER
     * ======================================================= */
    public function ledger(string $vendor, Request $request)
    {
        $this->ensureVendorLedgerExists($vendor);
        $request->validate($this->ledgerFilterRules());

        $query = Account::where('vendor_name', $vendor);
        $query = $this->applyFilters($query, $request);

        $openingBalance = 0;

        if ($request->filled('from_date')) {
            $openingBalance = Account::where('vendor_name', $vendor)
                ->whereDate('date', '<', $request->from_date)
                ->selectRaw("SUM(CASE WHEN entry_type IN ('" . implode("','", $this->incomeTypes) . "') THEN amount 
                                 WHEN entry_type IN ('" . implode("','", $this->expenseTypes) . "') THEN -amount ELSE 0 END) as balance")
                ->value('balance') ?? 0;
        }

        $accounts = $query->orderBy('date')->orderBy('id')->get();

        $runningBalance = $openingBalance;

        foreach ($accounts as $acc) {
            if (in_array($acc->entry_type, $this->incomeTypes)) {
                $runningBalance += $acc->amount;
            } elseif (in_array($acc->entry_type, $this->expenseTypes)) {
                $runningBalance -= $acc->amount;
            }
            $acc->running_balance = $runningBalance;
        }

        return view('client.accounts.ledger', compact(
            'accounts',
            'vendor',
            'openingBalance'
        ));
    }

    /* =======================================================
     * FILTER HELPER
     * ======================================================= */
    private function applyFilters($query, $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        if ($request->filled('entry_type')) {
            $query->where('entry_type', $request->entry_type);
        }

        return $query;
    }

    /* =======================================================
     * VALIDATION RULES
     * ======================================================= */
    private function accountRules(): array
    {
        return [
            'date' => 'required|date',
            'entry_type' => 'required|string|max:255',
            'vendor_name' => 'required|string|max:255',
            'vendor_type' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:255',
            'details' => 'nullable|string|max:1000',
            'country' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'last_status' => 'nullable|in:Pending,Approved,Rejected,Processing,Completed',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    private function ledgerFilterRules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'entry_type' => 'nullable|string|max:255',
        ];
    }

    private function ensureVendorLedgerExists(string $vendor): void
    {
        if (!Account::where('vendor_name', $vendor)->exists()) {
            abort(404, 'Vendor ledger not found.');
        }
    }

    /* =======================================================
     * EXPORT PDF
     * ======================================================= */
    public function exportLedgerPDF(string $vendor, Request $request)
    {
        $this->ensureVendorLedgerExists($vendor);
        $request->validate($this->ledgerFilterRules());

        $fromDate = $request->from_date;
        $toDate   = $request->to_date;

        $openingBalance = Account::where('vendor_name', $vendor)
            ->when($fromDate, fn($q) => $q->whereDate('date', '<', $fromDate))
            ->selectRaw("SUM(CASE WHEN entry_type IN ('" . implode("','", $this->incomeTypes) . "') THEN amount 
                             WHEN entry_type IN ('" . implode("','", $this->expenseTypes) . "') THEN -amount ELSE 0 END) as balance")
            ->value('balance') ?? 0;

        $accounts = Account::where('vendor_name', $vendor)
            ->when($fromDate, fn($q) => $q->whereDate('date', '>=', $fromDate))
            ->when($toDate,   fn($q) => $q->whereDate('date', '<=', $toDate))
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $sortedAccounts = $accounts;
        $incomeTypes = $this->incomeTypes;
        $expenseTypes = $this->expenseTypes;

        $pdf = Pdf::loadView('admin.accounts.pdf', compact(
            'accounts',
            'sortedAccounts',
            'vendor',
            'openingBalance',
            'incomeTypes',
            'expenseTypes'
        ));

        return $pdf->download("ledger_{$vendor}.pdf");
    }
    
    /* =======================================================
     * MONTHLY REPORT
     * ======================================================= */
    public function monthlyReport(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'generated_at' => 'nullable|date'
        ]);

        $generatedAt = $request->generated_at ? \Illuminate\Support\Carbon::parse($request->generated_at) : now();
        $reportLabel = '';
        $accountsQuery = Account::query();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $accountsQuery->whereBetween('date', [$fromDate, $toDate]);
            $reportLabel = date('F j, Y', strtotime($fromDate)) . ' - ' . date('F j, Y', strtotime($toDate));
        } else {
            $month = $request->month ?? now()->format('Y-m');
            $accountsQuery->whereMonth('date', date('m', strtotime($month)))
                ->whereYear('date', date('Y', strtotime($month)));
            $reportLabel = date('F Y', strtotime($month));
        }

        $accounts = $accountsQuery->orderBy('date')->get();

        return view('client.accounts.report', compact('accounts', 'reportLabel', 'generatedAt'));
    }
    
    /* =======================================================
     * EXPORT EXCEL
     * ======================================================= */
    public function exportExcel(Request $request)
    {
        $query = Account::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('vendor')) {
            $query->where('vendor_name', $request->vendor);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('entry_type')) {
            $query->where('entry_type', $request->entry_type);
        }

        $accounts = $query->orderBy('date', 'desc')->get();

        // Create a temporary export class or modify AccountsExport
        // For simplicity, create inline
        $export = new class($accounts) implements FromCollection, WithHeadings, WithMapping {
            private $accounts;

            public function __construct($accounts)
            {
                $this->accounts = $accounts;
            }

            public function collection()
            {
                return $this->accounts;
            }

            public function headings(): array
            {
                return [
                    'Date',
                    'Entry Type',
                    'Vendor Type',
                    'Vendor Name',
                    'Purpose',
                    'Details',
                    'Country',
                    'Last Status',
                    'Amount',
                    'Balance',
                ];
            }

            public function map($account): array
            {
                return [
                    $account->date,
                    $account->entry_type,
                    $account->vendor_type,
                    $account->vendor_name,
                    $account->purpose,
                    $account->details,
                    $account->country,
                    $account->last_status,
                    number_format($account->amount, 2),
                    number_format($account->balance, 2),
                ];
            }
        };

        return Excel::download($export, 'accounts.xlsx');
    }
    
    /* =======================================================
     * IMPORT EXCEL
     * ======================================================= */
    public function importExcel(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new AccountsImport, $request->file('document'));
            RecalculateAccountBalances::dispatchSync(); // Pass null to recalculate all after mass import
            return back()->with('success', 'Accounts imported successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', 'Import failed: ' . $exception->getMessage());
        }
    }
    
    /* =======================================================
     * EXPORT PDF
     * ======================================================= */
    public function exportPDF(Request $request)
    {
        $query = Account::query();

        $incomeTypes = ['Received', 'Receivable'];
        $expenseTypes = ['Payment', 'Payable', 'Purchase', 'Salary', 'Office costs'];

        // FILTERS
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                ->orWhere('purpose', 'like', "%{$search}%")
                ->orWhere('details', 'like', "%{$search}%")
                ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('vendor')) {
            $query->where('vendor_name', $request->vendor);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('entry_type')) {
            $query->where('entry_type', $request->entry_type);
        }

        // ✅ DATE RANGE (IMPORTANT FOR OPENING BALANCE)
        $fromDate = $request->from_date;
        $toDate   = $request->to_date;

        // ✅ OPENING BALANCE (BEFORE RANGE)
        $openingBalance = 0;

        if ($fromDate) {
            $openingBalance = Account::when($request->vendor, fn($q) => $q->where('vendor_name', $request->vendor))
                ->whereDate('date', '<', $fromDate)
                ->selectRaw("SUM(CASE WHEN entry_type IN ('" . implode("','", $this->incomeTypes) . "') THEN amount 
                                 WHEN entry_type IN ('" . implode("','", $this->expenseTypes) . "') THEN -amount ELSE 0 END) as balance")
                ->value('balance') ?? 0;
        }

        // ✅ FILTERED DATA
        $accounts = $query
            ->when($fromDate, fn($q) => $q->whereDate('date', '>=', $fromDate))
            ->when($toDate, fn($q) => $q->whereDate('date', '<=', $toDate))
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $sortedAccounts = $accounts;
        $incomeTypes = $this->incomeTypes;
        $expenseTypes = $this->expenseTypes;

        $pdf = Pdf::loadView('admin.accounts.pdf', compact(
            'accounts',
            'sortedAccounts',
            'openingBalance',
            'incomeTypes',
            'expenseTypes'
        ));

        return $pdf->download('accounts.pdf');
    }
}