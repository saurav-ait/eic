<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityType;

class ActivityTypeController extends Controller
{
    public function index()
    {
        $activities = ActivityType::latest()->paginate(15);
        
        // Manually load leads count for each activity
        $activities->getCollection()->transform(function ($activity) {
            $activity->leads_count = $activity->leads()->count();
            return $activity;
        });
        
        return view('client.activities.index', compact('activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:activity_types,name'
        ]);

        ActivityType::create($request->all());

        return back()->with('success', 'Activity type added successfully');
    }

    public function update(Request $request, $id)
    {
        $activity = ActivityType::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:activity_types,name,' . $id
        ]);

        $activity->update($request->all());

        return back()->with('success', 'Activity type updated successfully');
    }

    public function destroy($id)
    {
        $activity = ActivityType::findOrFail($id);
        
        if ($activity->leads()->count() > 0) {
            return back()->with('error', 'Cannot delete activity type with associated leads');
        }

        $activity->delete();

        return back()->with('success', 'Activity type deleted successfully');
    }
}
