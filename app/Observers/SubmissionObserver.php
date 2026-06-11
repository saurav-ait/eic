<?php

namespace App\Observers;

use App\Models\Submission;
use App\Models\SubmissionHistory;

class SubmissionObserver
{
    /**
     * Handle the Submission "updated" event.
     */
    public function updated(Submission $submission): void
    {
        // Check if status has changed
        if ($submission->isDirty('status')) {
            $originalStatus = $submission->getOriginal('status');
            $newStatus = $submission->status;

            // Create a history record automatically
            SubmissionHistory::create([
                'submission_id' => $submission->id,
                'history_date' => now()->toDateString(),
                'status' => $newStatus,
                'remarks' => "Status changed from '{$originalStatus}' to '{$newStatus}'"
            ]);
        }
    }
}
