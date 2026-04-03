<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Carbon\Carbon;
use App\Models\Services;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(10);

        $todayLeads = Lead::whereDate('created_at', Carbon::today())->count();
        $totalLeads = Lead::count();
        $monthlyLeads = Lead::whereMonth('created_at', Carbon::now()->month)->count();

        $services = Services::all();

        return view('client.leads.index', compact(
            'leads',
            'todayLeads',
            'totalLeads',
            'monthlyLeads',
            'services'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'source' => 'required|in:Facebook,Email,WhatsApp,Agent,Management',
        ]);

        Lead::create($request->all());

        return back()->with('success', 'Lead added successfully');
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $lead->update($request->all());

        return back()->with('success', 'Lead updated');
    }

    public function destroy($id)
    {
        Lead::findOrFail($id)->delete();

        return back()->with('success', 'Lead deleted');
    }
}