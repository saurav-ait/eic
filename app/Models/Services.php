<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobCategory;

class Services extends Model
{
    protected $fillable = ['name', 'description'];

    public function categories()
    {
        return $this->hasMany(JobCategory::class, 'service_id');
    }
}