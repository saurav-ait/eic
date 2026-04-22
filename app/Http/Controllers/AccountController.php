<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AccountsImport;
use App\Models\Account;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Exports\AccountsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Country;
use App\Models\Vendor;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $income = Account::whereIn('entry_type', ['Received','Receivable'])->sum('amount');

        $expense = Account::whereIn('entry_type', [
            'Payment','Payable','Purchase','Salary','Office costs'
        ])->sum('amount');

        $balance = Account::latest()->value('balance') ?? 0;

        // Build query with filters
        $query = Account::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        // Vendor filter
        if ($request->filled('vendor')) {
            $query->where('vendor_name', $request->vendor);
        }

        // Country filter
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        // Entry type filter
        if ($request->filled('entry_type')) {
            $query->where('entry_type', $request->entry_type);
        }

        // Compute totals for the current filtered set
        $income = (clone $query)->whereIn('entry_type', ['Received','Receivable'])->sum('amount');
        $expense = (clone $query)->whereIn('entry_type', [
            'Payment','Payable','Purchase','Salary','Office costs'
        ])->sum('amount');
        $balance = (clone $query)->latest('date')->value('balance') ?? 0;

        $accounts = $query->orderBy('date', 'desc')->paginate(20)->withQueryString();

        $activeCountries = Country::where('status', true)->orderBy('name')->get();
        $activeVendors = Vendor::where('status', true)->orderBy('name')->get();

        // Get unique entry types for filter dropdown
        $entryTypes = Account::distinct()->pluck('entry_type')->filter()->sort()->values();

        return view('client.accounts.index', compact(
            'income','expense','balance','accounts','activeCountries','activeVendors','entryTypes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'entry_type' => 'required',
            'amount' => 'required|numeric',
            'last_status' => 'nullable|in:,Pending,Approved,Rejected,Processing,Completed',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        // Upload file
        $filePath = null;
        if ($request->hasFile('document')) {
            $filePath = $request->file('document')->store('accounts', 'public');
        }

        Account::create([
            ...$request->except('document'),
            'document' => $filePath,
            'last_status' => $request->last_status ?? 'Pending',
            'balance' => 0 // Temporary, will recalculate
        ]);

        $this->recalculateBalances();

        return back()->with('success', 'Entry Added');
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $request->validate([
            'date' => 'required|date',
            'entry_type' => 'required',
            'amount' => 'required|numeric',
            'last_status' => 'nullable|in:,Pending,Approved,Rejected,Processing,Completed',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $data = $request->except('document');

        // Handle document upload
        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($account->document) {
                Storage::disk('public')->delete($account->document);
            }
            $data['document'] = $request->file('document')->store('accounts', 'public');
        }

        // Set status to Pending if not provided
        if (!$data['last_status']) {
            $data['last_status'] = 'Pending';
        }

        $account->update($data);

        $this->recalculateBalances();

        return back()->with('success', 'Entry Updated Successfully');
    }

    public function destroy($id)
    {
        Account::findOrFail($id)->delete();

        $this->recalculateBalances();

        return back()->with('success', 'Entry Deleted Successfully');
    }

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

    public function exportPDF(Request $request)
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

        $pdf = PDF::loadView('admin.accounts.pdf', compact('accounts'));

        return $pdf->download('accounts.pdf');
    }

    public function ledger($vendor)
    {
        $accounts = Account::when($vendor, function ($q) use ($vendor) {
                $q->where('vendor_name', $vendor);
            })
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        // Calculate running balance for this vendor's ledger
        $runningBalance = 0;
        $incomeTypes = ['Received', 'Receivable'];
        $expenseTypes = ['Payment', 'Payable', 'Purchase', 'Salary', 'Office costs'];

        foreach ($accounts as $account) {
            if (in_array($account->entry_type, $incomeTypes)) {
                $runningBalance += $account->amount;
            } elseif (in_array($account->entry_type, $expenseTypes)) {
                $runningBalance -= $account->amount;
            }
            $account->running_balance = $runningBalance;
        }

        return view('client.accounts.ledger', compact('accounts','vendor'));
    }

    public function exportLedgerPDF($vendor)
    {
        $accounts = Account::where('vendor_name', $vendor)
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        // Re-using your existing PDF view logic
        // Ensure the view 'admin.accounts.pdf' is prepared to handle $vendor variable if needed
        $pdf = Pdf::loadView('admin.accounts.pdf', compact('accounts', 'vendor'));

        return $pdf->download("ledger_{$vendor}.pdf");
    }

    public function vendorlist()
    {
        $vendors = Account::select('vendor_name')
            ->whereNotNull('vendor_name')
            ->where('vendor_name', '!=', '')
            ->distinct()
            ->pluck('vendor_name');

        return view('client.accounts.vendors', compact('vendors'));
    }

    private function recalculateBalances()
    {
        $accounts = Account::orderBy('date')->orderBy('id')->get();

        $runningBalance = 0;

        $incomeTypes = ['Received', 'Receivable'];
        $expenseTypes = ['Payment', 'Payable', 'Purchase', 'Salary', 'Office costs'];

        foreach ($accounts as $account) {
            if (in_array($account->entry_type, $incomeTypes)) {
                $runningBalance += $account->amount;
            } elseif (in_array($account->entry_type, $expenseTypes)) {
                $runningBalance -= $account->amount;
            }

            $account->update(['balance' => $runningBalance]);
        }
    }
}
