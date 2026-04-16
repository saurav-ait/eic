<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Passport;
use App\Models\JobCategory;
use App\Models\JobSubcategory;
use App\Models\Services;
use App\Models\Status;
use App\Models\Account;


class DashboardController extends Controller
{

    public function index()
    {
        $servicesCount      = Services::count();
        $categoriesCount    = JobCategory::count();
        $subcategoriesCount = JobSubcategory::count();
        $passportsCount     = Passport::count();

        $income = Account::whereIn('entry_type', ['Received','Receivable'])->sum('amount');
        $expense = Account::whereIn('entry_type', [
            'Payment','Payable','Purchase','Salary','Office costs'
        ])->sum('amount');
        $balance = Account::latest()->value('balance') ?? 0;

        $recentAssignments = Passport::with('subcategory.category')
            ->whereNotNull('job_subcategory_id')
            ->latest()
            ->take(10)
            ->get();

        // Passport Status Monitor
        $statuses         = Status::withCount('passports')->get();
        $unstatusedCount  = Passport::whereNull('status_id')->count();
        $statusPassports  = Passport::with(['status', 'subcategory.category'])
            ->whereNotNull('status_id')
            ->latest()
            ->take(10)
            ->get();

        return view('client.dashboard', compact(
            'servicesCount',
            'categoriesCount',
            'subcategoriesCount',
            'passportsCount',
            'income',
            'expense',
            'balance',
            'recentAssignments',
            'statuses',
            'unstatusedCount',
            'statusPassports'
        ));
    }

    public function manageUsers()
    {
        $users = User::all();
        return view('client.manageusers', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:Admin,Employee,Guest'
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', "Role updated for {$user->name}");
    }
}
