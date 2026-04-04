<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('client.userprofile', compact('user'));
    }

    public function update(Request $request)
    {
        // Get the authenticated user correctly
        $user = Auth::user(); // ← this must be an instance of App\Models\User

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update fields
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save to DB
        $user->save(); // ← this works only if $user is a User model instance

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}