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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->date('date');

            $table->enum('entry_type', [
                'Received',
                'Receivable',
                'Payment',
                'Payable',
                'Purchase',
                'Salary',
                'Office costs'
            ]);

            $table->enum('vendor_type', [
                'Client',
                'Agent',
                'Others'
            ]);

            $table->string('vendor_name')->nullable();

            $table->enum('purpose', [
                'Advance File Opening',
                'Receive After Permit',
                'Receive during Processing',
                'Receive After Visa',
                'Return Against Received',
                'Others'
            ]);

            $table->text('details')->nullable();
            $table->string('country')->nullable();
            $table->string('last_status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
