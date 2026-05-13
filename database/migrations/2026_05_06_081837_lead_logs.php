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
        Schema::create('lead_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
<<<<<<< HEAD
            $table->enum('type', ['email','text','note','call']);
            $table->text('content')->nullable();
=======

            $table->enum('type', ['email','text','note']);
            $table->text('content')->nullable();

>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD
        Schema::dropIfExists('lead_logs');
=======
        //
>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
    }
};
