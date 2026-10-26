<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'quiz_attempt_id',
    'question_id',
    'selected_option',
    'is_correct',
    'points_awarded',
    'answered_at',
])]
class AttemptAnswer extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'points_awarded' => 'integer',
            'answered_at' => 'datetime',
        ];
    }

    /**
     * Get the quiz attempt that owns this answer.
     *
     * @return BelongsTo<QuizAttempt, $this>
     */
    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    /**
     * Get the question answered.
     *
     * @return BelongsTo<Question, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
