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
        Schema::create('submissions', function (Blueprint $table) {

        $table->id();

        $table->foreignId('passport_id')->constrained()->cascadeOnDelete();

        $table->foreignId('lawyer_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->foreignId('lawyer_group_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->foreignId('country_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->date('submission_date');

        $table->string('submission_no')->nullable();

        $table->enum('status',[
            'Draft',
            'Submitted',
            'Processing',
            'Approved',
            'Rejected',
            'Returned',
            'Completed'
        ])->default('Draft');

        $table->text('remarks')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
