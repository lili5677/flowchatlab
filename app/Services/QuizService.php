<?php

namespace App\Services;

use App\Models\AttemptAnswer;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class QuizService
{
    /**
     * Get or create active quiz attempt for the user.
     */
    public function getOrCreateActiveAttempt(User $user): QuizAttempt
    {
        $existing = QuizAttempt::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existing) {
            return $existing;
        }

        return $this->createNewAttempt($user);
    }

    /**
     * Create a fresh attempt for user, randomly sampling from question bank:
     * - 8 questions from Mudah
     * - 7 questions from Sedang
     * - 5 questions from Sulit
     * Total: 20 questions in structured difficulty order.
     */
    public function createNewAttempt(User $user): QuizAttempt
    {
        // Cancel any existing in_progress attempt
        QuizAttempt::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->update([
                'status' => 'completed',
                'completed_at' => Carbon::now(),
            ]);

        // Sample random questions per difficulty tier
        $mudah = Question::where('level', 'Mudah')->inRandomOrder()->limit(8)->get();
        $sedang = Question::where('level', 'Sedang')->inRandomOrder()->limit(7)->get();
        $sulit = Question::where('level', 'Sulit')->inRandomOrder()->limit(5)->get();

        if ($mudah->count() < 8 || $sedang->count() < 7 || $sulit->count() < 5) {
            $selectedQuestions = Question::orderBy('question_number')->limit(20)->get();
        } else {
            $selectedQuestions = $mudah->concat($sedang)->concat($sulit);
        }

        $questionIds = $selectedQuestions->pluck('id')->all();
        $optionsOrder = [];

        foreach ($selectedQuestions as $q) {
            $keys = array_column($q->options, 'id');
            shuffle($keys);
            $optionsOrder[$q->id] = $keys;
        }

        return QuizAttempt::create([
            'user_id' => $user->id,
            'status' => 'in_progress',
            'current_question_index' => 1,
            'started_at' => Carbon::now(),
            'time_limit_seconds' => 0,
            'total_questions' => (int) config('quiz.total_questions', 20),
            'question_ids' => $questionIds,
            'options_order' => $optionsOrder,
        ]);
    }

    /**
     * Get ordered question IDs for an attempt.
     *
     * @return array<int>
     */
    public function getAttemptQuestionIds(QuizAttempt $attempt): array
    {
        if (! empty($attempt->question_ids) && is_array($attempt->question_ids) && count($attempt->question_ids) === 20) {
            return $attempt->question_ids;
        }

        return Question::orderBy('question_number')->limit(20)->pluck('id')->all();
    }

    /**
     * Get 1-based attempt question number for a specific question ID.
     */
    public function getQuestionAttemptNumber(QuizAttempt $attempt, int $questionId): int
    {
        $ids = $this->getAttemptQuestionIds($attempt);
        $index = array_search($questionId, $ids, true);

        return $index !== false ? $index + 1 : 1;
    }

    /**
     * Get questions for an attempt in their assigned sequence (1 to 20).
     *
     * @return Collection<int, Question>
     */
    public function getQuestionsForAttempt(QuizAttempt $attempt): Collection
    {
        $ids = $this->getAttemptQuestionIds($attempt);
        $questionsMap = Question::whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)->map(function ($id, $idx) use ($questionsMap) {
            $q = $questionsMap->get($id);
            if ($q) {
                $q->attempt_number = $idx + 1;
            }

            return $q;
        })->filter()->values();
    }

    /**
     * Check if a question is locked in the attempt based on 3 levels.
     * Level 1 (Soal 1-8): Always unlocked.
     * Level 2 (Soal 9-15): Locked until all questions 1-8 are answered.
     * Level 3 (Soal 16-20): Locked until all questions 1-15 are answered.
     */
    public function isQuestionLocked(QuizAttempt $attempt, int $questionNumber): bool
    {
        if ($questionNumber <= 8) {
            return false;
        }

        $orderedIds = $this->getAttemptQuestionIds($attempt);
        $answeredIds = $attempt->answers()->pluck('question_id')->all();

        if ($questionNumber <= 15) {
            for ($i = 0; $i < 8; $i++) {
                if (isset($orderedIds[$i]) && ! in_array($orderedIds[$i], $answeredIds)) {
                    return true;
                }
            }

            return false;
        }

        for ($i = 0; $i < 15; $i++) {
            if (isset($orderedIds[$i]) && ! in_array($orderedIds[$i], $answeredIds)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get first available/unlocked question number if preferred is locked.
     */
    public function getFirstAvailableQuestionNumber(QuizAttempt $attempt, int $preferredNumber = 1): int
    {
        if (! $this->isQuestionLocked($attempt, $preferredNumber)) {
            return $preferredNumber;
        }

        $orderedIds = $this->getAttemptQuestionIds($attempt);
        $answeredIds = $attempt->answers()->pluck('question_id')->all();

        // Check earliest unanswered unlocked question
        for ($i = 1; $i <= 20; $i++) {
            $qId = $orderedIds[$i - 1] ?? null;
            if (! $this->isQuestionLocked($attempt, $i) && (! $qId || ! in_array($qId, $answeredIds))) {
                return $i;
            }
        }

        // Return highest unlocked question
        for ($i = 20; $i >= 1; $i--) {
            if (! $this->isQuestionLocked($attempt, $i)) {
                return $i;
            }
        }

        return 1;
    }

    /**
     * Record an answer for a question in this attempt.
     *
     * @return array<string, mixed>
     */
    public function recordAnswer(QuizAttempt $attempt, Question $question, string $selectedOption): array
    {
        if ($attempt->status !== 'in_progress') {
            return [
                'error' => 'Quiz sudah selesai.',
            ];
        }

        $qAttemptNumber = $this->getQuestionAttemptNumber($attempt, $question->id);

        // Check if question is locked
        if ($this->isQuestionLocked($attempt, $qAttemptNumber)) {
            return [
                'error' => 'Tingkat ini masih terkunci. Selesaikan semua soal pada tingkat sebelumnya terlebih dahulu.',
            ];
        }

        $existing = AttemptAnswer::where('quiz_attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->first();

        if ($existing) {
            return [
                'already_answered' => true,
                'is_correct' => $existing->is_correct,
                'points_awarded' => $existing->points_awarded,
                'correct_key' => $question->correct_key,
                'explanation' => $question->explanation,
                'selected_option' => $existing->selected_option,
            ];
        }

        $isCorrect = strtoupper(trim($selectedOption)) === strtoupper(trim($question->correct_key));
        $pointsAwarded = $isCorrect ? 5 : 0; // Each question is worth 5 points

        AttemptAnswer::create([
            'quiz_attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'selected_option' => $selectedOption,
            'is_correct' => $isCorrect,
            'points_awarded' => $pointsAwarded,
            'answered_at' => Carbon::now(),
        ]);

        // Refresh answers and recalculate running metrics
        $allAnswers = $attempt->answers()->get();
        $attempt->correct_count = $allAnswers->where('is_correct', true)->count();
        $attempt->points_earned = $allAnswers->sum('points_awarded');

        // Determine next question number in attempt (1..20):
        $orderedIds = $this->getAttemptQuestionIds($attempt);
        $answeredIds = $allAnswers->pluck('question_id')->all();

        $nextNumber = null;
        for ($i = $qAttemptNumber + 1; $i <= $attempt->total_questions; $i++) {
            $qId = $orderedIds[$i - 1] ?? null;
            if (! $this->isQuestionLocked($attempt, $i) && (! $qId || ! in_array($qId, $answeredIds))) {
                $nextNumber = $i;
                break;
            }
        }
        if (! $nextNumber) {
            for ($i = 1; $i <= $attempt->total_questions; $i++) {
                $qId = $orderedIds[$i - 1] ?? null;
                if (! $this->isQuestionLocked($attempt, $i) && (! $qId || ! in_array($qId, $answeredIds))) {
                    $nextNumber = $i;
                    break;
                }
            }
        }

        if ($nextNumber) {
            $attempt->current_question_index = $nextNumber;
        }

        $attempt->save();

        $isCompleted = ($allAnswers->count() >= $attempt->total_questions);
        if ($isCompleted) {
            $this->finishAttempt($attempt);
        }

        // Get updated roadmap
        $roadmap = $this->getRoadmapData($attempt, $allAnswers);

        // List which question numbers are now unlocked
        $unlockedQuestions = [];
        for ($i = 1; $i <= 20; $i++) {
            if (! $this->isQuestionLocked($attempt, $i)) {
                $unlockedQuestions[] = $i;
            }
        }

        return [
            'is_correct' => $isCorrect,
            'points_awarded' => $pointsAwarded,
            'correct_key' => $question->correct_key,
            'explanation' => $question->explanation,
            'points_earned' => $attempt->points_earned,
            'correct_count' => $attempt->correct_count,
            'is_completed' => $isCompleted,
            'next_question_number' => $nextNumber ?? $qAttemptNumber,
            'roadmap' => $roadmap,
            'unlocked_questions' => $unlockedQuestions,
            'redirect' => $isCompleted ? route('quiz.result', $attempt->id) : null,
        ];
    }

    /**
     * Finish an attempt, calculate score, predicate, and credit user points.
     */
    public function finishAttempt(QuizAttempt $attempt): QuizAttempt
    {
        if ($attempt->status !== 'in_progress') {
            return $attempt;
        }

        $attempt->status = 'completed';
        $attempt->completed_at = Carbon::now();

        $elapsed = Carbon::now()->diffInSeconds($attempt->started_at, true);
        $attempt->duration_seconds = (int) $elapsed;

        $answers = $attempt->answers()->get();
        $correctCount = $answers->where('is_correct', true)->count();
        $pointsEarned = (int) $answers->sum('points_awarded'); // 20 * 5 = 100 max
        $totalQuestions = $attempt->total_questions ?: 20;

        $score = (int) round(($correctCount / $totalQuestions) * 100);
        $passingScore = (int) config('quiz.passing_score', 75);
        $isPassed = ($score >= $passingScore);

        $predicate = $this->calculatePredicate($score);

        $attempt->score = $score;
        $attempt->correct_count = $correctCount;
        $attempt->points_earned = $pointsEarned;
        $attempt->bonus_points = 0;
        $attempt->total_points = $pointsEarned;
        $attempt->is_passed = $isPassed;
        $attempt->predicate = $predicate['title'];
        $attempt->save();

        // Increment permanent user points
        if ($attempt->total_points > 0) {
            $user = $attempt->user;
            $user->increment('points', $attempt->total_points);
        }

        return $attempt;
    }

    /**
     * Determine predicate configuration based on score.
     *
     * @return array{title: string, badge: string, summary: string}
     */
    public function calculatePredicate(int $score): array
    {
        $predicates = config('quiz.predicates');

        if ($score >= 90) {
            return $predicates['sangat_baik'];
        }

        if ($score >= 75) {
            return $predicates['baik'];
        }

        return $predicates['remedial'];
    }

    /**
     * Get roadmap progress for 3 levels: Mudah (1-8), Sedang (9-15), Sulit (16-20).
     *
     * @return array<string, mixed>
     */
    public function getRoadmapData(QuizAttempt $attempt, Collection $answers): array
    {
        $orderedIds = $this->getAttemptQuestionIds($attempt);
        $answeredIds = $answers->pluck('question_id')->all();

        // Level 1: Mudah (1-8)
        $mudahCount = 8;
        $mudahAnswered = 0;
        for ($i = 0; $i < 8; $i++) {
            if (isset($orderedIds[$i]) && in_array($orderedIds[$i], $answeredIds)) {
                $mudahAnswered++;
            }
        }
        $mudahDone = ($mudahAnswered >= $mudahCount);

        // Level 2: Sedang (9-15)
        $sedangCount = 7;
        $sedangAnswered = 0;
        for ($i = 8; $i < 15; $i++) {
            if (isset($orderedIds[$i]) && in_array($orderedIds[$i], $answeredIds)) {
                $sedangAnswered++;
            }
        }
        $sedangDone = ($sedangAnswered >= $sedangCount);

        // Level 3: Sulit (16-20)
        $sulitCount = 5;
        $sulitAnswered = 0;
        for ($i = 15; $i < 20; $i++) {
            if (isset($orderedIds[$i]) && in_array($orderedIds[$i], $answeredIds)) {
                $sulitAnswered++;
            }
        }
        $sulitDone = ($sulitAnswered >= $sulitCount);

        // Determine Level States: completed, active, locked
        $level1State = $mudahDone ? 'completed' : 'active';
        $level2State = $mudahDone ? ($sedangDone ? 'completed' : 'active') : 'locked';
        $level3State = ($mudahDone && $sedangDone) ? ($sulitDone ? 'completed' : 'active') : 'locked';

        $states = [$level1State, $level2State, $level3State];
        $tuntasCount = count(array_filter($states, fn ($s) => $s === 'completed'));
        $aktifCount = count(array_filter($states, fn ($s) => $s === 'active'));
        $terkunciCount = count(array_filter($states, fn ($s) => $s === 'locked'));

        return [
            'levels' => [
                [
                    'name' => 'Tingkat 1: Mudah',
                    'range' => 'Soal 1–8',
                    'status' => $level1State,
                    'answered_count' => $mudahAnswered,
                    'total_count' => $mudahCount,
                ],
                [
                    'name' => 'Tingkat 2: Sedang',
                    'range' => 'Soal 9–15',
                    'status' => $level2State,
                    'answered_count' => $sedangAnswered,
                    'total_count' => $sedangCount,
                ],
                [
                    'name' => 'Tingkat 3: Sulit',
                    'range' => 'Soal 16–20',
                    'status' => $level3State,
                    'answered_count' => $sulitAnswered,
                    'total_count' => $sulitCount,
                ],
            ],
            'summary' => [
                'tuntas' => $tuntasCount,
                'aktif' => $aktifCount,
                'terkunci' => $terkunciCount,
            ],
        ];
    }
}
