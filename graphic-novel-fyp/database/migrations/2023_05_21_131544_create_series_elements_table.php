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
        Schema::disableForeignKeyConstraints();

        Schema::create('series_elements', function (Blueprint $table) {
            $table->id('series_elements_id');
            $table->unsignedBigInteger('series_id');
            $table->foreign('series_id')->references('series_id')->on('series');
            $table->unsignedBigInteger('element_id');
            $table->foreign('element_id')->references('element_id')->on('element');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_elements');
    }
};
