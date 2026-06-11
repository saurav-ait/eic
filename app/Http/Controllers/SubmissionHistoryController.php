<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\SubmissionHistory;
use Illuminate\Http\Request;

class SubmissionHistoryController extends Controller
{
    public function index(Submission $submission)
    {
        $histories = $submission->histories()
            ->latest('history_date')
            ->paginate(20);

        return view(
            'client.submission_histories.index',
            compact('submission', 'histories')
        );
    }

    public function create(Submission $submission)
    {
        return view(
            'client.submission_histories.create',
            compact('submission')
        );
    }

    public function store(Request $request, Submission $submission)
    {
        $request->validate([
            'history_date' => 'required|date',
            'status' => 'required',
            'remarks' => 'nullable|string'
        ]);

        // Create a manual history entry with custom remarks
        SubmissionHistory::create([
            'submission_id' => $submission->id,
            'history_date' => $request->history_date,
            'status' => $request->status,
            'remarks' => $request->remarks
        ]);

        // If status is being changed, update the submission
        // This will trigger the SubmissionObserver to also create an automatic history
        if ($submission->status !== $request->status) {
            $submission->update([
                'status' => $request->status
            ]);
        }

        return redirect()
            ->route('submission.histories.index', $submission)
            ->with('success', 'History added successfully');
    }

    public function edit(SubmissionHistory $history)
    {
        $submission = $history->submission;
        return view(
            'client.submission_histories.edit',
            compact('history', 'submission')
        );
    }

    public function update(Request $request, SubmissionHistory $history)
    {
        $request->validate([
            'history_date' => 'required|date',
            'status' => 'required',
            'remarks' => 'nullable'
        ]);

        $history->update([
            'history_date' => $request->history_date,
            'status' => $request->status,
            'remarks' => $request->remarks
        ]);

        return back()
            ->with('success', 'History updated');
    }

    public function destroy(SubmissionHistory $history)
    {
        $submission = $history->submission;
        $history->delete();

        return redirect()
            ->route('submission.histories.index', $submission)
            ->with('success', 'History deleted');
    }
}