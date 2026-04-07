<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('job_categories', 'slug')) {
            Schema::table('job_categories', function (Blueprint $table) {
                $table->string('slug')->after('name');
            });

            // Populate slugs for existing categories
            $categories = DB::table('job_categories')->get();
            foreach ($categories as $category) {
                DB::table('job_categories')->where('id', $category->id)->update([
                    'slug' => Str::slug($category->name)
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
