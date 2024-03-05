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
        Schema::table('elementables', function (Blueprint $table) {
            $table->dropForeign(['element_id']); // Drop the existing foreign key
        
            // Then redefine it with ON DELETE CASCADE
            $table->foreign('element_id')
                  ->references('element_id')
                  ->on('elements')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elementables', function (Blueprint $table) {
            //
        });
    }
};
