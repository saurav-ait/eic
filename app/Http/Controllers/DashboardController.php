<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Only accessible if middleware passes
            return view('client.dashboard', [
            'totalUsers' => \App\Models\User::count(),
            'totalPassports' => \App\Models\Passport::count(),
            'assignedPassports' => \App\Models\Passport::whereNotNull('job_subcategory_id')->count(),
            'categories' => \App\Models\JobCategory::count(),
            'recentPassports' => \App\Models\Passport::latest()->take(5)->get(),
        ]);
    }
    public function manageUsers()
    {
        // Get all users except the logged-in Admin (optional)
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
