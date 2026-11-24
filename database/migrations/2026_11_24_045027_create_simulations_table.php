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
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('level_number')->unique();
            $table->string('title');
            $table->text('desc');
            $table->string('expected_symbol'); // decision, process, io, terminator
            $table->string('slot_prompt');
            $table->string('slot_condition_text');
            $table->string('slot_text_inside');
            $table->text('hint_text');
            $table->string('success_title');
            $table->text('success_desc');
            $table->string('layout_type')->default('branching'); // branching, sequential, loop
            $table->json('config_data')->nullable(); // additional custom node texts or data
            $table->unsignedInteger('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};
