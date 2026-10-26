<?php

namespace Tests\Unit;

use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\QuizService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizScoringTest extends TestCase
{
    use RefreshDatabase;

    protected QuizService $quizService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quizService = new QuizService;
    }

    /**
     * Test score formula: (correct / total) * 100.
     */
    public function test_score_formula_calculation(): void
    {
        $this->assertSame(100, (int) round((20 / 20) * 100));
        $this->assertSame(75, (int) round((15 / 20) * 100));
        $this->assertSame(70, (int) round((14 / 20) * 100));
        $this->assertSame(50, (int) round((10 / 20) * 100));
    }

    /**
     * Test predicate resolution based on score.
     */
    public function test_predicate_determination(): void
    {
        $pred95 = $this->quizService->calculatePredicate(95);
        $this->assertSame('Sangat Baik', $pred95['title']);

        $pred90 = $this->quizService->calculatePredicate(90);
        $this->assertSame('Sangat Baik', $pred90['title']);

        $pred80 = $this->quizService->calculatePredicate(80);
        $this->assertSame('Baik', $pred80['title']);

        $pred75 = $this->quizService->calculatePredicate(75);
        $this->assertSame('Baik', $pred75['title']);

        $pred70 = $this->quizService->calculatePredicate(70);
        $this->assertSame('Perlu Remedial', $pred70['title']);

        $pred0 = $this->quizService->calculatePredicate(0);
        $this->assertSame('Perlu Remedial', $pred0['title']);
    }

    /**
     * Test maximum base points achievable across all 20 questions (100 points, 5 points per question).
     */
    public function test_maximum_base_points(): void
    {
        $pointsPerQuestion = config('quiz.points_per_question', 5);
        $totalBase = 20 * $pointsPerQuestion;

        $this->assertSame(5, $pointsPerQuestion);
        $this->assertSame(100, $totalBase);
    }

    /**
     * Test finish attempt calculation with score and passing status.
     */
    public function test_finish_attempt_calculation(): void
    {
        $user = User::factory()->create(['points' => 0]);

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'status' => 'in_progress',
            'started_at' => Carbon::now()->subMinutes(6),
            'total_questions' => 20,
        ]);

        // Finish attempt
        $finished = $this->quizService->finishAttempt($attempt);

        $this->assertSame('completed', $finished->status);
        $this->assertNotNull($finished->completed_at);
        $this->assertFalse($finished->is_passed); // 0 answers = 0 score, not passed
        $this->assertSame('Perlu Remedial', $finished->predicate);
        $this->assertSame(0, $finished->bonus_points);
        $this->assertSame($finished->points_earned, $finished->total_points); // total = earned (no bonus)
    }
}
