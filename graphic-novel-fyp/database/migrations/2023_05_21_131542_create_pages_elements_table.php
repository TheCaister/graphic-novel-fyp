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

        Schema::create('pages_elements', function (Blueprint $table) {
            $table->id('pages_elements_id');
            $table->unsignedBigInteger('page_id');
            $table->foreign('page_id')->references('page_id')->on('pages')->cascadeOnDelete();

            $table->unsignedBigInteger('element_id');
            $table->foreign('element_id')->references('element_id')->on('elements')->cascadeOnDelete();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages_elements');
    }
};
