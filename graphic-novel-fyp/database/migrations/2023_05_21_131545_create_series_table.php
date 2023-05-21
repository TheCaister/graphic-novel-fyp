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

        Schema::create('series', function (Blueprint $table) {
            $series_genres = array('ACTION', 'ADVENTURE', 'COMEDY', 'DRAMA', 'FANTASY', 'HORROR', 'MYSTERY', 'ROMANCE', 'THRILLER');

            // Create an array similar to series_genres but capitalised


            $table->id('series_id');
            $table->bigInteger('universe_id');
            $table->foreign('universe_id')->references('universe_id')->on('universes');
            $table->string('series_title');
            $table->enum('series_genre', $series_genres);
            $table->longText('series_summary');
            $table->string('series_thumbnail');
            $table->float('rating');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
