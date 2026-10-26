<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'status',
    'current_question_index',
    'started_at',
    'completed_at',
    'time_limit_seconds',
    'duration_seconds',
    'score',
    'points_earned',
    'bonus_points',
    'total_points',
    'correct_count',
    'total_questions',
    'is_passed',
    'predicate',
    'question_ids',
    'options_order',
])]
class QuizAttempt extends Model
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
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'is_passed' => 'boolean',
            'question_ids' => 'array',
            'options_order' => 'array',
            'current_question_index' => 'integer',
            'time_limit_seconds' => 'integer',
            'duration_seconds' => 'integer',
            'score' => 'integer',
            'points_earned' => 'integer',
            'bonus_points' => 'integer',
            'total_points' => 'integer',
            'correct_count' => 'integer',
            'total_questions' => 'integer',
        ];
    }

    /**
     * Get the user who owns this attempt.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all answers given in this attempt.
     *
     * @return HasMany<AttemptAnswer, $this>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    /**
     * Calculate remaining seconds for the attempt.
     */
    public function remainingSeconds(): int
    {
        if ($this->status !== 'in_progress') {
            return 0;
        }

        $elapsed = Carbon::now()->diffInSeconds($this->started_at, true);
        $remaining = (int) $this->time_limit_seconds - (int) $elapsed;

        return max(0, $remaining);
    }

    /**
     * Check if attempt time limit has expired.
     */
    public function isExpired(): bool
    {
        return $this->remainingSeconds() <= 0;
    }

    /**
     * Format duration into MM:SS.
     */
    public function formattedDuration(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
