<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Passport;

class JobSubcategory extends Model
{
    protected $fillable = [
        'name',
        'job_category_id',
        'subcategory_description',
    ];
    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function passports()
    {
        return $this->hasMany(Passport::class);
    }
}
