<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'question_number',
    'level',
    'question_text',
    'symbol_type',
    'options',
    'correct_key',
    'explanation',
    'points',
])]
class Question extends Model
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
            'options' => 'array',
            'points' => 'integer',
            'question_number' => 'integer',
        ];
    }

    /**
     * Get all attempt answers for this question.
     *
     * @return HasMany<AttemptAnswer, $this>
     */
    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }
}
