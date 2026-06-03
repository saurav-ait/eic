<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LawyerGroup;
use App\Models\Lawyer;

class LawyerController extends Controller
{
    public function lawyerindex()
    {
        $lawyers = Lawyer::with('lawyergroup')->latest()->paginate(10);
        return view('client.submissions.lawyers', compact('lawyers'));
    }
    public function storelawyer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:lawyers,name',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'status' => 'sometimes|boolean',
        ]);

        Lawyer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'status' => $request->boolean('status'),
        ]);

        return redirect()->route('lawyer.index')
            ->with('success', 'Lawyer created successfully.');
    }
    // Update lawyer
    public function updatelawyer(Request $request, Lawyer $lawyer)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:lawyers,name,' . $lawyer->id,
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'status' => 'sometimes|boolean',
        ]);

        $validated['status'] = $request->boolean('status');

        // Update lawyer
        $lawyer->update($validated);

        return redirect()->route('lawyer.index')
                         ->with('success', 'Lawyer updated successfully');
    }

    public function destroylawyer(Lawyer $lawyer)
    {
        $lawyer->delete();
        return redirect()->route('lawyer.index')->with('success', 'Lawyer deleted successfully.');
    }

    public function lawyergroupindex()
    {
        $groups = LawyerGroup::latest()->paginate(10);
        $lawyers = Lawyer::where('status', 1)
        ->latest()
        ->get();
        return view('client.submissions.lawyergroup', compact('groups', 'lawyers'));
    }
    public function storelawyergroup(Request $request)
    {
        $request->validate([
            'lawyer_id' => 'required|exists:lawyers,id',
            'name' => 'required|string|max:255|unique:lawyer_groups,name',
            'description' => 'nullable|string',
        ]);

        LawyerGroup::create([
            'lawyer_id' => $request->lawyer_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('lawyergroup.index')
            ->with('success', 'Lawyer group created successfully.');
    }
    // Update lawyer group
    public function updatelawyergroup(Request $request, LawyerGroup $group)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:lawyer_groups,name,' . $group->id,
            'description' => 'nullable|string',
        ]);

        // Update lawyer group
        $group->update($validated);

        return redirect()->route('lawyergroup.index')
                         ->with('success', 'Lawyer group updated successfully');
    }

    public function destroylawyergroup(LawyerGroup $group)
    {
        $group->delete();
        return redirect()->route('lawyergroup.index')->with('success', 'Lawyer group deleted successfully.');
    }
}
