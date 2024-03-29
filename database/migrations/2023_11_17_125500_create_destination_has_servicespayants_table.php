<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('destination_has_servicespayants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('destination_id');
            $table->foreign('destination_id')->references('id')->on('destinations');

            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('servicespayants');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
