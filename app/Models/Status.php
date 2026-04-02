<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'name','description'
    ];
    public function passports()
    {
        return $this->hasMany(Passport::class);
    }
}
