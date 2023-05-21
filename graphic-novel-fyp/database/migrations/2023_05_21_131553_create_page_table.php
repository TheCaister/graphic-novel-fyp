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

        Schema::create('page', function (Blueprint $table) {
            $table->id('page_id');
            $table->bigInteger('chapter_id');
            $table->foreign('chapter_id')->references('chapter_id')->on('chapter');
            $table->bigInteger('page_number');
            $table->string('page_image');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page');
    }
};
