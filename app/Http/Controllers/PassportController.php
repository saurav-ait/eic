<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passport;
use App\Models\Agent;

class PassportController extends Controller
{
    public function create()
    {
        $agents = Agent::where('status', true)
        ->orderBy('name')
        ->get();
        return view('client.create-passport', compact('agents'));
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
            'marital_status' => 'required|in:Single,Married,Widow,Divorced',
            'spouse_name' => 'nullable|required_unless:marital_status,Single'
        ]);

        Passport::create($request->all());

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
        if ($request->filled('agent')) {

            $query->where('agent_id', $request->agent);
        }

        $passports = $query->orderBy('created_at', 'desc')->paginate(10);
        $agents = Agent::withCount('passports')->get();

        return view('client.passport-list', compact('passports', 'agents'));
    }

    public function show(Passport $passport)
    {
        $passport->load('agent');
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