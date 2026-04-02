<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->text('category_description')->nullable();
        });

        Schema::table('job_subcategories', function (Blueprint $table) {
            $table->text('subcategory_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories_and_subcategories', function (Blueprint $table) {
            //
        });
    }
};
