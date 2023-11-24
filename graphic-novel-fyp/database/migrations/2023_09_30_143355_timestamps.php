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

        // Add timestamps to chapter table

        Schema::table('chapters', function (Blueprint $table) {
            $table->timestamps();
        });

        // Add timestamps to page table if it doesn't already exist
        if (!Schema::hasColumn('pages', 'created_at')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
