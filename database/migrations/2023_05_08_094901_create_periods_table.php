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

            $table->date('start');
            $table->date('end');

            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('mail')->nullable();
            $table->string('number')->nullable();

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
