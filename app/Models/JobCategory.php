<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobSubcategory;
use App\Models\Services;
use Illuminate\Support\Str;

class JobCategory extends Model
{
    protected $fillable = ['name','category_description', 'service_id', 'slug'];

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function subcategories()
    {
        return $this->hasMany(JobSubcategory::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
