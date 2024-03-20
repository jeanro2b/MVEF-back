<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hebergements', function (Blueprint $table) {
            $table->boolean('is_display')->default(true)->nullable();
        });

        DB::table('hebergements')->update(['is_display' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
