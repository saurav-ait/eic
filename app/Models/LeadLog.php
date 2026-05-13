<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadLog extends Model
{
<<<<<<< HEAD
    protected $fillable = [
        'lead_id',
        'type',
        'content'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
=======
    //
>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
}
