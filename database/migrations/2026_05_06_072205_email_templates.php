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
        Schema::create('email_templates', function (Blueprint $table) {
<<<<<<< HEAD
            $table->id();
            $table->foreignId('activity_type_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->longText('body');
            $table->timestamps();
        });
=======
        $table->id();
        $table->foreignId('activity_type_id')->constrained()->cascadeOnDelete();
        $table->string('subject');
        $table->longText('body');
        $table->timestamps();
    });
>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<< HEAD
        Schema::dropIfExists('email_templates');
=======
        //
>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
    }
};
