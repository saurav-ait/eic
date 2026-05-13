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

        $income = (clone $query)
            ->whereIn('entry_type', $this->incomeTypes)
            ->sum('amount');

        $expense = (clone $query)
            ->whereIn('entry_type', $this->expenseTypes)
            ->sum('amount');

        $balance = $income - $expense;

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
        $request->validate([
            'date' => 'required|date',
            'entry_type' => 'required',
            'amount' => 'required|numeric',
            'last_status' => 'nullable',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $filePath = $request->hasFile('document')
            ? $request->file('document')->store('accounts', 'public')
            : null;

        Account::create([
            ...$request->except('document'),
            'document' => $filePath,
            'last_status' => $request->last_status ?? 'Pending',
            'balance' => 0
        ]);

        $this->recalculateBalances();

        return back()->with('success', 'Entry Added');
    }

    /* =======================================================
     * UPDATE
     * ======================================================= */
    public function update(Request $request, int $id)
    {
        $account = Account::findOrFail($id);

        $request->validate([
            'date' => 'required|date',
            'entry_type' => 'required',
            'amount' => 'required|numeric',
            'last_status' => 'nullable',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $data = $request->except('document');

        if ($request->hasFile('document')) {
            if ($account->document) {
                Storage::disk('public')->delete($account->document);
            }
            $data['document'] = $request->file('document')->store('accounts', 'public');
        }

        $account->update($data);

        $this->recalculateBalances();

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

        $this->recalculateBalances();

        return back()->with('success', 'Deleted');
    }

    /* =======================================================
     * VENDOR LIST
     * ======================================================= */
    public function vendorlist(Request $request)
    {
        $data = Account::selectRaw('vendor_name as name, COUNT(*) as count')
            ->groupBy('vendor_name')
            ->get()
            ->map(function ($row) {
                $total = Account::where('vendor_name', $row->name)->get()
                    ->reduce(function ($carry, $item) {
                        if (in_array($item->entry_type, $this->expenseTypes)) return $carry + $item->amount;
                        if (in_array($item->entry_type, $this->incomeTypes)) return $carry - $item->amount;
                        return $carry;
                    }, 0);
                return ['name' => $row->name, 'count' => $row->count, 'total' => $total];
            });

        return view('client.accounts.vendors', compact('data'));
    }

    /* =======================================================
     * LEDGER
     * ======================================================= */
    public function ledger(string $vendor, Request $request)
    {
        $query = Account::where('vendor_name', $vendor);
        $query = $this->applyFilters($query, $request);

        $openingBalance = Account::where('vendor_name', $vendor)
            ->when($request->from_date, fn($q) =>
                $q->whereDate('date', '<', $request->from_date)
            )
            ->get()
            ->reduce(function ($carry, $item) {
                if (in_array($item->entry_type, $this->expenseTypes)) {
                    return $carry + $item->amount;  // debit increases balance (you owe more)
                }
                if (in_array($item->entry_type, $this->incomeTypes)) {
                    return $carry - $item->amount;  // credit decreases balance (you owe less)
                }
                return $carry;
            }, 0);

        $accounts = $query->orderBy('date')->orderBy('id')->get();

        $runningBalance = $openingBalance;

        foreach ($accounts as $acc) {
            if (in_array($acc->entry_type, $this->expenseTypes)) {
                $runningBalance += $acc->amount;  // debit: you owe more
            } elseif (in_array($acc->entry_type, $this->incomeTypes)) {
                $runningBalance -= $acc->amount;  // credit: you owe less
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
     * BALANCE RECALCULATION
     * ======================================================= */
    private function recalculateBalances()
    {
        $vendors = Account::distinct()->pluck('vendor_name');

        foreach ($vendors as $vendor) {
            $accounts = Account::where('vendor_name', $vendor)
                ->orderBy('date')->orderBy('id')->get();

            $balance = 0;

            foreach ($accounts as $acc) {
                if (in_array($acc->entry_type, $this->expenseTypes)) {
                    $balance += $acc->amount;
                } elseif (in_array($acc->entry_type, $this->incomeTypes)) {
                    $balance -= $acc->amount;
                }

                $acc->update(['balance' => $balance]);
            }
        }
    }

    /* =======================================================
     * EXPORT PDF
     * ======================================================= */
    public function exportLedgerPDF(string $vendor, Request $request)
    {
        $fromDate = $request->from_date;
        $toDate   = $request->to_date;

        $openingBalance = Account::where('vendor_name', $vendor)
            ->when($fromDate, fn($q) => $q->whereDate('date', '<', $fromDate))
            ->orderBy('date')
            ->orderBy('id')
            ->get()
            ->reduce(function ($carry, $item) {
                if (in_array($item->entry_type, $this->incomeTypes)) {
                    return $carry - $item->amount;  // credit: you owe less
                }
                if (in_array($item->entry_type, $this->expenseTypes)) {
                    return $carry + $item->amount;  // debit: you owe more
                }
                return $carry;
            }, 0);

        $accounts = Account::where('vendor_name', $vendor)
            ->when($fromDate, fn($q) => $q->whereDate('date', '>=', $fromDate))
            ->when($toDate,   fn($q) => $q->whereDate('date', '<=', $toDate))
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $pdf = Pdf::loadView('admin.accounts.pdf', compact(
            'accounts',
            'vendor',
            'openingBalance'
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
            $this->recalculateBalances();
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
                ->orderBy('date')
                ->orderBy('id')
                ->get()
                ->reduce(function ($carry, $item) use ($incomeTypes, $expenseTypes) {
                    if (in_array($item->entry_type, $incomeTypes)) {
                        return $carry - $item->amount;  // credit decreases balance
                    }
                    if (in_array($item->entry_type, $expenseTypes)) {
                        return $carry + $item->amount;  // debit increases balance
                    }
                    return $carry;
                }, 0);
        }

        // ✅ FILTERED DATA
        $accounts = $query
            ->when($fromDate, fn($q) => $q->whereDate('date', '>=', $fromDate))
            ->when($toDate, fn($q) => $q->whereDate('date', '<=', $toDate))
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $pdf = PDF::loadView('admin.accounts.pdf', compact(
            'accounts',
            'openingBalance'
        ));

        return $pdf->download('accounts.pdf');
    }
}