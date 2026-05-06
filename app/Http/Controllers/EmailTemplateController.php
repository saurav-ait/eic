<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\ActivityType;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::with('activityType')->latest()->paginate(15);
        $activities = ActivityType::all();
        
        return view('client.email-templates.index', compact('templates', 'activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        EmailTemplate::create($request->all());

        return back()->with('success', 'Email template added successfully');
    }

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);
        
        $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        $template->update($request->all());

        return back()->with('success', 'Email template updated successfully');
    }

    public function destroy($id)
    {
        EmailTemplate::findOrFail($id)->delete();

        return back()->with('success', 'Email template deleted successfully');
    }
}
