<?php

namespace App\Models;

use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Model;

class LawyerGroup extends Model
{
    protected $fillable = [
        'lawyer_id',
        'name',
        'description',
    ];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class, 'lawyer_id');
    }
}
