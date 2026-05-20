<?php

namespace App\Http\Controllers;
use App\Models\Agent;

use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::all();
        return view('client.Agent.index', compact('agents'));
    }

    public function store(Request $request)
    {   
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        Agent::create($request->all());

        return redirect()->route('agent.index')->with('success', 'Agent created successfully.');    
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        $agent = Agent::findOrFail($id);
        $agent->update($request->all());

        return redirect()->route('agent.index')->with('success', 'Agent updated successfully.');
    }

    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();

        return redirect()->route('agent.index')->with('success', 'Agent deleted successfully.');
    }
}
