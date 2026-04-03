<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passport;

class PassportController extends Controller
{
    public function create()
    {
        return view('client.create-passport');
    }

    public function store(Request $request)
    {
        $request->validate([
            'passport_number' => 'required|unique:passports',
            'familyname' => 'required',
            'givenname' => 'required',
            'date_of_birth' => 'required|date',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date',
        ]);

        Passport::create($request->only(['passport_number', 'familyname', 'givenname', 'date_of_birth', 'issue_date', 'expiry_date']));

        return redirect()->route('passports.index')->with('success', 'Passport created successfully.');
    }

    public function index(Request $request)
    {
        $query = Passport::query();

        // If search input exists, filter results
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('passport_number', 'like', "%{$search}%")
                ->orWhere('familyname', 'like', "%{$search}%")
                ->orWhere('givenname', 'like', "%{$search}%");
        }

        $passports = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('client.passport-list', compact('passports'));
    }

    public function show(Passport $passport)
    {
        return view('client.passport-show', compact('passport'));
    }

    public function destroy(Passport $passport)
    {
        if ($passport->job_subcategory_id) {
            return redirect()->back()->with('error', 'Cannot delete assigned passport.');
        }

        $passport->delete();

        return redirect()->back()->with('success', 'Passport deleted successfully.');
    }
}