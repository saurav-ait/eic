<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JobSubcategory;
use App\Models\Status;

class Passport extends Model
{
    use HasFactory;

    // Add only real passport fields
    protected $fillable = [
        'familyname', 'givenname', 'father_name', 'mother_name',
        'date_of_birth', 'place_of_birth', 'gender', 'nationality',
        'passport_number', 'issue_date', 'expiry_date', 'place_of_issue',
        'address', 'phone', 'email',
        'job_subcategory_id', 'status_id',
    ];
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(JobSubcategory::class, 'job_subcategory_id');
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

}