<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadLog extends Model
{
    protected $fillable = [
        'lead_id',
        'type',
        'content'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
