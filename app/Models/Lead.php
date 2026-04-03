<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'source',
        'service_id',
        'note',
        'status'
    ];
    public function service()
    {
        return $this->belongsTo(Services::class);
    }
}