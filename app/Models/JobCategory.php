<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobSubcategory;
use App\Models\Services;

class JobCategory extends Model
{
    protected $fillable = ['name','category_description', 'service_id'];

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function subcategories()
    {
        return $this->hasMany(JobSubcategory::class);
    }
}
