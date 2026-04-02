<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Passport;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function create(Passport $passport)
    {
        return view('client.videos.create', compact('passport'));
    }
    
    public function store(Request $request, Passport $passport)
    {
        $request->validate([
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:20480', // 20MB
            'url' => 'nullable|url'
        ]);

        // ❗ Ensure at least one provided
        if (!$request->video && !$request->url) {
            return back()->withErrors(['error' => 'Upload video OR provide YouTube URL']);
        }

        $filePath = null;

        // Upload file if exists
        if ($request->hasFile('video')) {
            $filePath = $request->file('video')->store('videos', 'public');
        }

        Video::create([
            'passport_id' => $passport->id,
            'file' => $filePath,
            'url' => $request->url
        ]);

        return back()->with('success', 'Video added successfully');
    }

    public function destroy(Video $video)
    {
        // Delete file from storage
        if ($video->file && file_exists(storage_path('app/public/' . $video->file))) {
            unlink(storage_path('app/public/' . $video->file));
        }

        $video->delete();

        return back()->with('success', 'Video deleted successfully.');
    }
}
