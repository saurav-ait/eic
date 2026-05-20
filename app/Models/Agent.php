<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'status'
    ];

    public function passports()
    {
        return $this->hasMany(Passport::class);
    }
}
