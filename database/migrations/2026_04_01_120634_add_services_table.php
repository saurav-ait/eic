<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Update categories table to reference service_id
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('service_id')->after('id')
                ->nullable()
                ->constrained('services')
                ->onDelete('restrict'); // prevents deletion if service has categories
        });

        // Update subcategories table to reference service_id
        Schema::table('subcategories', function (Blueprint $table) {
            $table->foreignId('service_id')->after('id')
                ->nullable()
                ->constrained('services')
                ->onDelete('restrict'); // prevents deletion if service has subcategories
        });
    }

    public function down(): void
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });

        Schema::dropIfExists('services');
    }
};