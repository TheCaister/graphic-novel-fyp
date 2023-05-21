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
            $table->bigInteger('approver_id');
            $table->foreign('approver_id')->references('id')->on('user');
            $table->bigInteger('approvee_id');
            $table->foreign('approvee_id')->references('id')->on('user');
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
