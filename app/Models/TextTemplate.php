<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextTemplate extends Model
{
    protected $fillable = ['activity_type_id', 'body'];

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }
}
