<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'date',
        'entry_type',
        'vendor_type',
        'vendor_name',
        'purpose',
        'details',
        'country',
        'last_status',
        'amount',
        'balance',
        'document'
    ];
}
