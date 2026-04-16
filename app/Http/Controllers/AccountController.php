<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountController extends Controller
{
    public function index()
    {
        $income = Account::whereIn('entry_type', ['Received','Receivable'])->sum('amount');

        $expense = Account::whereIn('entry_type', [
            'Payment','Payable','Purchase','Salary','Office costs'
        ])->sum('amount');

        $balance = Account::latest()->value('balance') ?? 0;

        $accounts = Account::orderBy('date', 'desc')->paginate(20);

        return view('client.accounts.index', compact('income','expense','balance','accounts'));
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
        $month = $request->month ?? now()->format('Y-m');

        $accounts = Account::whereMonth('date', date('m', strtotime($month)))
            ->whereYear('date', date('Y', strtotime($month)))
            ->get();

        return view('client.accounts.report', compact('accounts','month'));
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
