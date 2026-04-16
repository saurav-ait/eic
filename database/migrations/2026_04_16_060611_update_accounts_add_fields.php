<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->default(0)->after('entry_type');
            $table->decimal('balance', 12, 2)->default(0)->after('amount');
            $table->string('document')->nullable()->after('details');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['amount', 'balance', 'document']);
        });
    }
};