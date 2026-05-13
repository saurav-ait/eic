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
            if (Schema::hasColumn('leads', 'name') && !Schema::hasColumn('leads', 'company_name')) {
                $table->renameColumn('name', 'company_name');
            }

            if (!Schema::hasColumn('leads', 'director')) {
                $table->string('director')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('leads', 'city')) {
                $table->string('city')->nullable()->after('email');
            }
            if (!Schema::hasColumn('leads', 'address')) {
                $table->text('address')->nullable()->after('city');
            }

            if (!Schema::hasColumn('leads', 'country_id')) {
                $table->foreignId('country_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('leads', 'activity_type_id')) {
                $table->foreignId('activity_type_id')->nullable()->after('country_id')->constrained()->nullOnDelete();
            }

            if (Schema::hasColumn('leads', 'status')) {
                $table->enum('status', ['New','Contacted','Converted','Lost'])->default('New')->change();
            }
        });

        Schema::table('leads', function (Blueprint $table) {
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
            if (Schema::hasColumn('leads', 'company_name') && !Schema::hasColumn('leads', 'name')) {
                $table->renameColumn('company_name', 'name');
            }

            if (Schema::hasColumn('leads', 'director')) {
                $table->dropColumn('director');
            }
            if (Schema::hasColumn('leads', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('leads', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('leads', 'activity_type_id')) {
                $table->dropForeign(['activity_type_id']);
                $table->dropColumn('activity_type_id');
            }
            if (Schema::hasColumn('leads', 'country_id')) {
                $table->dropForeign(['country_id']);
                $table->dropColumn('country_id');
            }

            if (!Schema::hasColumn('leads', 'service_id')) {
                $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('leads', 'note')) {
                $table->text('note')->nullable();
            }

            if (Schema::hasColumn('leads', 'status')) {
                $table->enum('status', ['new','contacted','converted','rejected'])->default('new')->change();
            }
        });
    }
};
