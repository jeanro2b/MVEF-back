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
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('object');
            $table->string('code');
            $table->string('status');
            $table->boolean('lit');
            $table->boolean('toilette');
            $table->boolean('menage');

            $table->unsignedBigInteger('hebergement_id');
            $table->foreign('hebergement_id')->references('id')->on('hebergements');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
