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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('question_number')->unique();
            $table->string('level'); // Mudah, Sedang, Sulit
            $table->text('question_text');
            $table->string('symbol_type')->nullable(); // terminator, input_output, decision, process
            $table->json('options');
            $table->string('correct_key', 10);
            $table->text('explanation');
            $table->unsignedInteger('points')->default(5);
            $table->timestamps();
        });

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('in_progress'); // in_progress, completed, timed_out
            $table->unsignedInteger('current_question_index')->default(1);
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('time_limit_seconds')->default(900);
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('points_earned')->default(0);
            $table->unsignedInteger('bonus_points')->default(0);
            $table->unsignedInteger('total_points')->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('total_questions')->default(20);
            $table->boolean('is_passed')->default(false);
            $table->string('predicate')->nullable();
            $table->json('question_ids')->nullable();
            $table->json('options_order')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('selected_option', 10);
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('points_awarded')->default(0);
            $table->timestamp('answered_at');
            $table->timestamps();

            $table->unique(['quiz_attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('questions');
    }
};
