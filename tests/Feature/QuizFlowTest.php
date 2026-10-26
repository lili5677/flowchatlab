<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\QuizService;
use Database\Seeders\QuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(QuestionSeeder::class);
    }

    /**
     * Test guests are redirected to login.
     */
    public function test_guest_cannot_access_quiz(): void
    {
        $response = $this->get('/quiz');
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user starts or resumes quiz.
     */
    public function test_authenticated_user_can_access_quiz(): void
    {
        $user = User::factory()->create(['name' => 'Bayu Pratama']);

        $response = $this->actingAs($user)->get('/quiz');

        $response->assertRedirect();

        $attempt = QuizAttempt::where('user_id', $user->id)->first();
        $this->assertNotNull($attempt);
        $this->assertSame('in_progress', $attempt->status);

        $showResponse = $this->actingAs($user)->get(route('quiz.show', $attempt->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Quiz Pengetahuan Algoritma');
        $showResponse->assertSee('Total Terkumpul');
        $showResponse->assertSee('Peta Jalur Tingkat');
    }

    /**
     * Test that correct keys and explanations are not leaked before answering.
     */
    public function test_correct_keys_not_leaked_before_answering(): void
    {
        $user = User::factory()->create();
        $service = new QuizService;
        $attempt = $service->createNewAttempt($user);

        $response = $this->actingAs($user)->get(route('quiz.show', $attempt->id));

        $response->assertStatus(200);
        // Correct key and explanation for question 1 should NOT be in the data
        $response->assertDontSee('"correct_key":"A"');
        $response->assertDontSee('"explanation":"Terminator (oval) menandai mulai dan selesai."');
    }

    /**
     * Test answering a question via AJAX.
     */
    public function test_user_can_submit_answer_via_ajax(): void
    {
        $user = User::factory()->create(['points' => 0]);
        $service = new QuizService;
        $attempt = $service->createNewAttempt($user);

        $q1Id = $service->getAttemptQuestionIds($attempt)[0];
        $q1 = Question::findOrFail($q1Id);

        $response = $this->actingAs($user)->postJson(route('quiz.answer', $attempt->id), [
            'question_id' => $q1->id,
            'selected_option' => $q1->correct_key,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'is_correct' => true,
            'points_awarded' => 5,
            'correct_key' => $q1->correct_key,
        ]);

        $this->assertDatabaseHas('attempt_answers', [
            'quiz_attempt_id' => $attempt->id,
            'question_id' => $q1->id,
            'selected_option' => $q1->correct_key,
            'is_correct' => true,
        ]);
    }

    /**
     * Test viewing results and user permanent points credited.
     */
    public function test_quiz_results_and_user_points(): void
    {
        $user = User::factory()->create(['points' => 0]);
        $service = new QuizService;
        $attempt = $service->createNewAttempt($user);

        $q1Id = $service->getAttemptQuestionIds($attempt)[0];
        $q1 = Question::findOrFail($q1Id);
        $service->recordAnswer($attempt, $q1, $q1->correct_key);

        // Finish attempt
        $service->finishAttempt($attempt, false);

        $response = $this->actingAs($user)->get(route('quiz.result', $attempt->id));

        $response->assertStatus(200);
        $response->assertSee('Hasil Evaluasi');
        $response->assertSee('Rekap Jawaban Soal');
        $response->assertSee('SKOR AKHIR');

        // User points credited (5 points)
        $user->refresh();
        $this->assertSame(5, $user->points);
    }

    /**
     * Test retry quiz starts a new attempt without deleting previous history.
     */
    public function test_retry_creates_new_attempt(): void
    {
        $user = User::factory()->create();
        $service = new QuizService;
        $firstAttempt = $service->createNewAttempt($user);
        $service->finishAttempt($firstAttempt, false);

        $response = $this->actingAs($user)->post(route('quiz.retry'));

        $response->assertRedirect();

        // 2 attempts should exist in total
        $this->assertSame(2, QuizAttempt::where('user_id', $user->id)->count());
        $this->assertDatabaseHas('quiz_attempts', [
            'id' => $firstAttempt->id,
            'status' => 'completed',
        ]);
    }

    /**
     * Test Q9 (Sedang) is locked when Mudah (1-8) questions are unanswered.
     */
    public function test_sedang_is_locked_until_mudah_complete(): void
    {
        $user = User::factory()->create();
        $service = new QuizService;
        $attempt = $service->createNewAttempt($user);

        $q9Id = $service->getAttemptQuestionIds($attempt)[8];
        $q9 = Question::findOrFail($q9Id);

        // Q9 must be locked with no Mudah answers
        $this->assertTrue($service->isQuestionLocked($attempt, 9));

        // Attempting to answer Q9 while locked should return 403
        $response = $this->actingAs($user)->postJson(route('quiz.answer', $attempt->id), [
            'question_id' => $q9->id,
            'selected_option' => 'A',
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test Q9 (Sedang) unlocks after all Mudah questions (1-8) are answered.
     */
    public function test_sedang_unlocks_after_mudah_complete(): void
    {
        $user = User::factory()->create(['points' => 0]);
        $service = new QuizService;
        $attempt = $service->createNewAttempt($user);

        $attemptIds = $service->getAttemptQuestionIds($attempt);

        // Answer all 8 Mudah questions
        for ($i = 0; $i < 8; $i++) {
            $q = Question::findOrFail($attemptIds[$i]);
            $service->recordAnswer($attempt, $q, $q->correct_key);
        }

        // Q9 must now be unlocked
        $this->assertFalse($service->isQuestionLocked($attempt, 9));

        // Q16 (Sulit) must still be locked
        $this->assertTrue($service->isQuestionLocked($attempt, 16));
    }

    /**
     * Test questions are correctly selected and ordered by difficulty tier.
     */
    public function test_quiz_attempt_samples_by_difficulty_tiers(): void
    {
        $user = User::factory()->create();
        $service = new QuizService;
        $attempt = $service->createNewAttempt($user);

        $questions = $service->getQuestionsForAttempt($attempt);

        $this->assertCount(20, $questions);

        // First 8: Mudah
        for ($i = 0; $i < 8; $i++) {
            $this->assertSame('Mudah', $questions[$i]->level);
        }

        // Next 7: Sedang
        for ($i = 8; $i < 15; $i++) {
            $this->assertSame('Sedang', $questions[$i]->level);
        }

        // Last 5: Sulit
        for ($i = 15; $i < 20; $i++) {
            $this->assertSame('Sulit', $questions[$i]->level);
        }
    }

    /**
     * Test user with completed attempt is shown landing page with previous score, review, and retry options.
     */
    public function test_user_with_completed_attempt_sees_landing_page(): void
    {
        $user = User::factory()->create(['name' => 'Bayu Pratama']);
        $service = new QuizService;
        $attempt = $service->createNewAttempt($user);
        $service->finishAttempt($attempt, false);

        $response = $this->actingAs($user)->get(route('quiz.index'));

        $response->assertStatus(200);
        $response->assertViewIs('quiz_pengetahuan.landing');
        $response->assertSee('Kamu Sudah Pernah');
        $response->assertSee('Mengerjakan Quiz Ini');
        $response->assertSee('Lihat Hasil Sebelumnya');
        $response->assertSee('Kerjakan Ulang dari Awal');
    }
}
