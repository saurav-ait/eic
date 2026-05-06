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
        Schema::table('leads', function (Blueprint $table) {
            // Rename name to company_name
            $table->renameColumn('name', 'company_name');
            
            // Add new columns
            $table->string('director')->nullable()->after('company_name');
            $table->string('city')->nullable()->after('email');
            $table->text('address')->nullable()->after('city');
            
            // Add foreign keys
            $table->foreignId('country_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('activity_type_id')->nullable()->after('country_id')->constrained()->nullOnDelete();
            
            // Update status enum
            $table->enum('status', ['New','Contacted','Converted','Lost'])->default('New')->change();
            
            // Drop old columns if they exist
            if (Schema::hasColumn('leads', 'service_id')) {
                $table->dropForeign(['service_id']);
                $table->dropColumn('service_id');
            }
            if (Schema::hasColumn('leads', 'note')) {
                $table->dropColumn('note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('company_name', 'name');
            $table->dropColumn(['director', 'city', 'address']);
            $table->dropForeign(['country_id']);
            $table->dropForeign(['activity_type_id']);
            $table->dropColumn(['country_id', 'activity_type_id']);
            
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note')->nullable();
            $table->enum('status', ['new','contacted','converted','rejected'])->default('new')->change();
        });
    }
};
