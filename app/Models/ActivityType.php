<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    protected $fillable = ['name', 'email_template', 'text_template'];

    public function leads() {
        return $this->hasMany(Lead::class, 'activity_type_id');
    }
}
