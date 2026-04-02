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
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->string('familyname');
            $table->string('givenname');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('nationality');

            // Passport Details
            $table->string('passport_number')->unique();
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('place_of_issue');

            // Contact Information
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            
            $table->timestamps();
            $table->foreignId('status_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
