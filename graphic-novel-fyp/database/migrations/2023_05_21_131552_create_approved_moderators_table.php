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

        Schema::create('approved_moderators', function (Blueprint $table) {
            $table->id('approved_moderators_id');
            // $table->unsignedBigInteger('approver_id');
            // $table->foreign('approver_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('moderator_id');
            $table->foreign('moderator_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('moderatable_id')->nullable();
            $table->string('moderatable_type');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approved_moderators');
    }
};
