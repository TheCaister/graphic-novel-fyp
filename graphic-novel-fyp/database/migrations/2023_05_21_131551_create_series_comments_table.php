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

        Schema::create('series_comments', function (Blueprint $table) {
            $table->id('series_comments_id');
            $table->bigInteger('series_id');
            $table->foreign('series_id')->references('series_id')->on('series');
            $table->bigInteger('comment_id');
            $table->foreign('comment_id')->references('comment_id')->on('comments');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_comments');
    }
};
