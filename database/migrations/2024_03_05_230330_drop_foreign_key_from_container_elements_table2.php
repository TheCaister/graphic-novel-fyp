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
        Schema::table('container_elements', function (Blueprint $table) {
            $table->dropForeign(['child_element_id']); // Drop the existing foreign key

            // Then redefine it with ON DELETE CASCADE
            $table->foreign('child_element_id')
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
        Schema::table('container_elements', function (Blueprint $table) {
            //
        });
    }
};
