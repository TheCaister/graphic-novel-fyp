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
        Schema::create('elementables', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('element_id');
            $table->foreign('element_id')->references('element_id')->on('elements')->cascadeOnDelete();

            // Creating a polymorphic relationship between elements and pages, chapters, series and universes
            $table->unsignedBigInteger('elementable_id')->nullable();
            $table->string('elementable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elementables');
    }
};
