<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobCategory;
use Illuminate\Support\Str;

class Services extends Model
{
    protected $fillable = ['name', 'description', 'slug'];

    public function categories()
    {
        return $this->hasMany(JobCategory::class, 'service_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            $service->slug = Str::slug($service->name);
        });

        static::updating(function ($service) {
            if ($service->isDirty('name')) {
                $service->slug = Str::slug($service->name);
            }
        });
    }
}