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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('phone')->nullable();
            $table->string('languages')->nullable();
            $table->string('mail')->nullable();
            $table->text('reception')->nullable();
            $table->string('arrival')->nullable();
            $table->string('departure')->nullable();
            $table->text('carte')->nullable();
            $table->string('pImage')->nullable();
            $table->string('sImage')->nullable();
            $table->string('tImage1')->nullable();
            $table->string('tImage2')->nullable();
            $table->string('tImage3')->nullable();
            $table->string('tImage4')->nullable();
            $table->string('vehicule')->nullable();
            $table->string('parking')->nullable();
            $table->string('favorite')->nullable();
            $table->string('location')->nullable();
            $table->text('renseignement')->nullable();
            $table->text('site')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
