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
        if (Schema::hasTable('lawyer_group')) {
            Schema::table('lawyer_group', function (Blueprint $table) {
                $table->dropForeign(['lawyer_id']);
            });
        }

        if (Schema::hasTable('lawyer')) {
            Schema::rename('lawyer', 'lawyers');
        }

        if (Schema::hasTable('lawyer_group')) {
            Schema::rename('lawyer_group', 'lawyer_groups');
        }

        if (Schema::hasTable('lawyer_groups')) {
            Schema::table('lawyer_groups', function (Blueprint $table) {
                $table->foreign('lawyer_id')->references('id')->on('lawyers')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lawyer_groups')) {
            Schema::table('lawyer_groups', function (Blueprint $table) {
                $table->dropForeign(['lawyer_id']);
            });

            Schema::rename('lawyer_groups', 'lawyer_group');
        }

        if (Schema::hasTable('lawyers')) {
            Schema::rename('lawyers', 'lawyer');
        }

        if (Schema::hasTable('lawyer_group')) {
            Schema::table('lawyer_group', function (Blueprint $table) {
                $table->foreign('lawyer_id')->references('id')->on('lawyer')->onDelete('cascade');
            });
        }
    }
};
