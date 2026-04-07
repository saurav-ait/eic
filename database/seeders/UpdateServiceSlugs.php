<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Services;
use Illuminate\Support\Str;

class UpdateServiceSlugs extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Services::all()->each(function ($service) {
            $service->update(['slug' => Str::slug($service->name)]);
        });
    }
}
