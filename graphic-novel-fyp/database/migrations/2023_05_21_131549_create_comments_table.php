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

        Schema::create('comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->unsignedBigInteger('commenter_id');
            $table->foreign('commenter_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('replying_to')->nullable();
            $table->foreign('replying_to')->references('comment_id')->on('comments')->cascadeOnDelete();
            $table->string('comment_content');
            $table->timestamp('created_at');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
