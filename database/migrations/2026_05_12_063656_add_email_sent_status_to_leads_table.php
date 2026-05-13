<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the status enum to include 'Email Sent'
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('New','Contacted','Email Sent','Converted','Lost') DEFAULT 'New'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('New','Contacted','Converted','Lost') DEFAULT 'New'");
    }
};
