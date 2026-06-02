<?php

namespace App\Models;
use App\Models\LawyerGroup;
use Illuminate\Database\Eloquent\Model;

class Lawyer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'status'
    ];

    public function lawyergroup()
    {
        return $this->hasMany(LawyerGroup::class, 'lawyer_id');
    }

}
