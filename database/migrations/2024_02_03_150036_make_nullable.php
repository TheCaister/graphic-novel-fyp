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
        //
        // In series table, make series_thumbnail nullable
        Schema::table('series', function (Blueprint $table) {
            $table->string('series_thumbnail')->nullable()->change();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('page_image')->nullable()->change();
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
