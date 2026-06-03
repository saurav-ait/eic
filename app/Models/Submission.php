<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'passport_id',
        'lawyer_id',
        'lawyer_group_id',
        'country_id',
        'submission_date',
        'decision_date',
        'submission_no',
        'application_number',
        'file_number',
        'embassy_name',
        'visa_type',
        'status',
        'remarks'
    ];

    protected $casts = [
        'submission_date' => 'date',
        'decision_date' => 'date',
    ];

    public function passport()
    {
        return $this->belongsTo(Passport::class);
    }

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function lawyerGroup()
    {
        return $this->belongsTo(LawyerGroup::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
