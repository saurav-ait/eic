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
        Schema::create('text_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_type_id')->constrained()->cascadeOnDelete();
<<<<<<< HEAD
=======
            $table->enum('channel', ['sms','whatsapp','viber','telegram']);
>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD
        Schema::dropIfExists('text_templates');
=======
        //
>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
    }
};
