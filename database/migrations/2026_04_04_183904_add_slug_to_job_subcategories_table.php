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
        if (!Schema::hasColumn('job_subcategories', 'slug')) {
            Schema::table('job_subcategories', function (Blueprint $table) {
                $table->string('slug')->after('name');
            });

            // Populate slugs for existing subcategories
            $subcategories = DB::table('job_subcategories')->get();
            foreach ($subcategories as $subcategory) {
                DB::table('job_subcategories')->where('id', $subcategory->id)->update([
                    'slug' => Str::slug($subcategory->name)
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_subcategories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
