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
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->dateTime('start');
            $table->dateTime('end');

            $table->string('name');
            $table->string('phone');
            $table->string('mail');
            $table->string('number');

            $table->unsignedBigInteger('planning_id');
            $table->foreign('planning_id')->references('id')->on('plannings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};
