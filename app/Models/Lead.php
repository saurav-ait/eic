<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'country_id',
        'activity_type_id',
        'company_name',
        'director',
        'phone',
        'email',
        'address',
        'city',
        'status'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function activity()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function logs()
    {
        return $this->hasMany(LeadLog::class, 'lead_id');
    }
}