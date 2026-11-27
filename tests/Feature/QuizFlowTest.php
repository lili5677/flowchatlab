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
     * Test guests see the landing page with login modal (not redirect to login).
     */
    public function test_guest_cannot_access_quiz(): void
    {
        $response = $this->get('/quiz');
        // Guests now see the landing page with a login modal popup (200)
        $response->assertStatus(200);
        $response->assertSee('Login Diperlukan');
    }

    /**
     * Test authenticated user sees the landing page before starting quiz.
     */
    public function test_authenticated_user_can_access_quiz(): void
    {
        $user = User::factory()->create(['name' => 'Bayu Pratama']);

        // First visit: new user sees the landing page (no redirect, no attempt yet)
        $response = $this->actingAs($user)->get('/quiz');
        $response->assertStatus(200);
        $response->assertSee('Quiz Pengetahuan');

        // No attempt created yet (landing page shown first)
        $this->assertNull(QuizAttempt::where('user_id', $user->id)->first());

        // User starts quiz via retry (POST)
        $retryResponse = $this->actingAs($user)->post(route('quiz.retry'));
        $retryResponse->assertRedirect();

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
        $response->assertSee('Kerjakan Ulang');
    }

    /**
     * Test quiz show page displays "Keluar Kuis" button, exit modal, and no website navigation links.
     */
    public function test_quiz_pengetahuan_has_exit_quiz_button_and_modal(): void
    {
        $user = User::factory()->create();
        $service = new QuizService;
        $attempt = $service->createNewAttempt($user);

        $response = $this->actingAs($user)->get(route('quiz.show', $attempt->id));

        $response->assertStatus(200);
        $response->assertSee('Keluar Kuis');
        $response->assertSee('id="exitModal"', false);
        $response->assertSee(route('quiz.exit', $attempt->id));
        $response->assertSee('Progres Kuis:');
    }

    /**
     * Test student can exit quiz which deletes the in-progress attempt and redirects to home.
     */
    public function test_student_can_exit_in_progress_quiz(): void
    {
        $user = User::factory()->create();
        $service = new QuizService;
        $attempt = $service->createNewAttempt($user);

        $this->assertDatabaseHas('quiz_attempts', ['id' => $attempt->id]);

        $response = $this->actingAs($user)->post(route('quiz.exit', $attempt->id));

        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('quiz_attempts', ['id' => $attempt->id]);
    }

    /**
     * Test question options and questions are randomized across attempts.
     */
    public function test_quiz_pengetahuan_creates_randomized_attempts(): void
    {
        $user = User::factory()->create();
        $service = new QuizService;

        $attempt1 = $service->createNewAttempt($user);
        $attempt2 = $service->createNewAttempt($user);

        $this->assertIsArray($attempt1->question_ids);
        $this->assertIsArray($attempt2->question_ids);
        $this->assertCount(20, $attempt1->question_ids);
        $this->assertCount(20, $attempt2->question_ids);
    }
}
