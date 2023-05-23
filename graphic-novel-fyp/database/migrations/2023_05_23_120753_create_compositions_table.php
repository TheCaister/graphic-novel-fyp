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

        Schema::create('compositions', function (Blueprint $table) {
            $table->id('composition_id');
            $table->unsignedBigInteger('universe_id');
            $table->foreign('universe_id')->references('universe_id')->on('universes');
            // Create a long text column
            $table->longText('composition_content');
            // Create a nullable column that represents a file path
            $table->string('composition_file_path')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compositions');
    }
};
