<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = ['name','category_description'];
    public function subcategories()
    {
        return $this->hasMany(JobSubcategory::class);
    }
}
