<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextTemplate;
use App\Models\ActivityType;

class TextTemplateController extends Controller
{
    public function index()
    {
        $templates = TextTemplate::with('activityType')->latest()->paginate(15);
        $activities = ActivityType::all();
        
        return view('client.text-templates.index', compact('templates', 'activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'body' => 'required|string|max:1000'
        ]);

        TextTemplate::create($request->all());

        return back()->with('success', 'Text template added successfully');
    }

    public function update(Request $request, $id)
    {
        $template = TextTemplate::findOrFail($id);
        
        $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'body' => 'required|string|max:1000'
        ]);

        $template->update($request->all());

        return back()->with('success', 'Text template updated successfully');
    }

    public function destroy($id)
    {
        TextTemplate::findOrFail($id)->delete();

        return back()->with('success', 'Text template deleted successfully');
    }
}
