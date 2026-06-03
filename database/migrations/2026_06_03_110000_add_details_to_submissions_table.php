<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('application_number')->nullable()->after('submission_no');
            $table->string('file_number')->nullable()->after('application_number');
            $table->string('embassy_name')->nullable()->after('file_number');
            $table->string('visa_type')->nullable()->after('embassy_name');
            $table->date('decision_date')->nullable()->after('submission_date');
        });

        DB::statement("ALTER TABLE submissions MODIFY status ENUM('Draft','Submitted','Processing','Document Requested','Approved','Rejected','Returned','Completed') DEFAULT 'Draft'");
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn([
                'application_number',
                'file_number',
                'embassy_name',
                'visa_type',
                'decision_date',
            ]);
        });

        DB::statement("ALTER TABLE submissions MODIFY status ENUM('Draft','Submitted','Processing','Approved','Rejected','Returned','Completed') DEFAULT 'Draft'");
    }
};
