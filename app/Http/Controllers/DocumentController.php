<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Passport;
use App\Models\Document;

class DocumentController extends Controller
{
    public function create(Passport $passport)
    {
        return view('client.documents.create', compact('passport'));
    }
    public function store(Request $request, Passport $passport)
    {
        $request->validate([
            'type' => 'required|in:Photo,Passport,National ID,Birth Certificate,Educational Certificate (SSC),Educational Certificate (HSC),Educational Certificate (Diploma),CV,Work Permit,Police Clearance,Driving License,Others',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ]);

        $filePath = $request->file('file')->store('documents', 'public');

        $passport->documents()->create([
            'type' => $request->type,
            'file' => $filePath
        ]);

        return redirect()->route('passports.show', $passport)
            ->with('success', 'Document uploaded');
    }

    public function destroy(Document $document)
    {
        if ($document->file && Storage::disk('public')->exists($document->file)) {
            Storage::disk('public')->delete($document->file);
        }

        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}
