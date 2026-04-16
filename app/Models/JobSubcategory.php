<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Passport;
use Illuminate\Support\Str;

class JobSubcategory extends Model
{
    protected $fillable = [
        'name',
        'job_category_id',
        'subcategory_description',
        'slug',
    ];

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    public function passports()
    {
        return $this->hasMany(Passport::class, 'job_subcategory_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subcategory) {
            $subcategory->slug = Str::slug($subcategory->name);
        });

        static::updating(function ($subcategory) {
            if ($subcategory->isDirty('name')) {
                $subcategory->slug = Str::slug($subcategory->name);
            }
        });
    }
}
