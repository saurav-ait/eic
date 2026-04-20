<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
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

    public function exportExcel()
    {
        return Excel::download(new AccountsExport, 'accounts.xlsx');
    }

    public function exportPDF()
    {
        $accounts = Account::all();

        $pdf = PDF::loadView('admin.accounts.pdf', compact('accounts'));

        return $pdf->download('accounts.pdf');
    }

    public function ledger(Request $request)
    {
        $vendor = $request->vendor_name;

        $accounts = Account::when($vendor, function ($q) use ($vendor) {
                $q->where('vendor_name', $vendor);
            })
            ->orderBy('date')
            ->get();

        $runningBalance = 0;

        $accounts->map(function ($item) use (&$runningBalance) {

            $incomeTypes = ['Received','Receivable'];
            $expenseTypes = ['Payment','Payable','Purchase','Salary','Office costs'];

            if (in_array($item->entry_type, $incomeTypes)) {
                $runningBalance += $item->amount;
            } else {
                $runningBalance -= $item->amount;
            }

            $item->running_balance = $runningBalance;

            return $item;
        });

        return view('client.accounts.ledger', compact('accounts','vendor'));
    }
    public function vendorlist()
    {
        $vendors = Account::select('vendor_name')->distinct()->pluck('vendor_name');

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
            } else {
                $runningBalance -= $account->amount;
            }

            $account->update(['balance' => $runningBalance]);
        }
    }
}
