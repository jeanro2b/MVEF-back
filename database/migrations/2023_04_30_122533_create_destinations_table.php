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
            $table->string('name');
            $table->string('city');
            $table->text('description');
            $table->text('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('phone');
            $table->string('languages');
            $table->string('mail');
            $table->string('reception');
            $table->string('arrival');
            $table->string('departure');
            $table->string('map');
            $table->string('pImage');
            $table->string('sImage');
            $table->string('tImage1');
            $table->string('tImage2');
            $table->string('vehicule');
            $table->string('parking');
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
