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
        Schema::create('hebergements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('long_title');
            $table->string('city');
            $table->text('description');
            $table->string('code');
            $table->integer('price');
            $table->integer('couchage');
        
            $table->string('pImage');
            $table->string('sImage');
            $table->string('tImage');

            $table->unsignedBigInteger('destination_id');
            $table->foreign('destination_id')->references('id')->on('destinations');

            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hebergements');
    }
};
