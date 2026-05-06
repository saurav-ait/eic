<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = ['activity_type_id', 'subject', 'body'];

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }
}
