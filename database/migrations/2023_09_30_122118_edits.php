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

        Schema::create('elements', function (Blueprint $table) {
            $table->id('element_id');
            $table->string('element_name');
            $table->string('element_thumbnail');
            $table->string('derived_element_type');
            $table->unsignedBigInteger('derived_element_id');
            $table->longText('content');
            $table->boolean('hidden');
            
            $table->timestamps();
        });

        Schema::create('simple_text_elements', function (Blueprint $table) {
            $table->id('simple_text_element_id');
        });

        Schema::create('mindmap_elements', function (Blueprint $table) {
            $table->id('mindmap_element_id');
        });

        Schema::create('panel_planner_elements', function (Blueprint $table) {
            $table->id('panel_planner_element_id');
        });

        Schema::create('container_elements', function (Blueprint $table) {
            $table->id('container_element_id');

            $table->unsignedBigInteger('parent_element_id');
            $table->foreign('parent_element_id')->references('element_id')->on('elements');

            $table->unsignedBigInteger('child_element_id');
            $table->foreign('child_element_id')->references('element_id')->on('elements');

        });

        Schema::create('elementables', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('element_id');
            $table->foreign('element_id')->references('element_id')->on('elements');
            $table->unsignedBigInteger('elementable_id');
            $table->string('elementable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('container_elements');
        Schema::dropIfExists('elementables');
        Schema::dropIfExists('new_elements');
        Schema::dropIfExists('elements');
        Schema::dropIfExists('simple_text_elements');
        Schema::dropIfExists('mindmap_elements');
        Schema::dropIfExists('panel_planner_elements');
    }
};
