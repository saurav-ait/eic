<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'passport_id',
        'file',
        'url'
    ];

    public function passport()
    {
        return $this->belongsTo(Passport::class);
    }
}
