<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionHistory extends Model
{
    protected $fillable = [
        'submission_id',
        'status',
        'history_date',
        'remarks',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}