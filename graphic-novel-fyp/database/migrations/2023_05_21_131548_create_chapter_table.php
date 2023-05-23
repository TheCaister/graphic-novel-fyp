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

        Schema::create('chapters', function (Blueprint $table) {
            $table->id('chapter_id');
            $table->unsignedBigInteger('series_id');
            $table->foreign('series_id')->references('series_id')->on('series')->cascadeOnDelete();
            $table->integer('chapter_number');
            $table->string('chapter_title');
            $table->string('chapter_thumbnail');
            $table->longText('chapter_notes');
            $table->boolean('comments_enabled');
            $table->timestamp('scheduled_publish');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter');
    }
};
