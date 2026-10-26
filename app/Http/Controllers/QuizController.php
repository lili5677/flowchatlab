<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuizAttempt;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    /**
     * Landing page: show previous result or start/resume quiz.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // If there is an active in-progress attempt, go straight to it
        $inProgress = QuizAttempt::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if ($inProgress) {
            return redirect()->route('quiz.show', $inProgress->id);
        }

        // Check for the latest completed attempt
        $lastCompleted = QuizAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->first();

        if ($lastCompleted) {
            // Show landing page with previous score and options
            $predicateConfig = $this->quizService->calculatePredicate($lastCompleted->score);

            return view('quiz_pengetahuan.landing', [
                'user' => $user,
                'lastAttempt' => $lastCompleted,
                'predicateConfig' => $predicateConfig,
                'passingScore' => (int) config('quiz.passing_score', 75),
            ]);
        }

        // No attempt at all – start fresh immediately
        $attempt = $this->quizService->createNewAttempt($user);

        return redirect()->route('quiz.show', $attempt->id);
    }

    /**
     * Display the quiz execution screen.
     */
    public function show(QuizAttempt $attempt, Request $request): View|RedirectResponse
    {
        if ($attempt->user_id !== $request->user()->id) {
            abort(403, 'Akses ke attempt quiz ini tidak diizinkan.');
        }

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('quiz.result', $attempt->id);
        }

        $questions = $this->quizService->getQuestionsForAttempt($attempt);
        $answers = $attempt->answers()->get()->keyBy('question_id');

        // Target question: query param ?soal=X or attempt current_question_index
        $requestedNumber = (int) $request->query('soal', $attempt->current_question_index);
        $requestedNumber = max(1, min(20, $requestedNumber));

        // Prevent opening locked question numbers
        $targetNumber = $this->quizService->getFirstAvailableQuestionNumber($attempt, $requestedNumber);

        // If requested number was locked and redirected internally, update url query if needed or proceed
        $activeQuestion = $questions->firstWhere('attempt_number', $targetNumber) ?? $questions->first();

        // Prepare client safe question list
        $optionsOrder = $attempt->options_order ?? [];
        $displayLabels = ['A', 'B', 'C', 'D'];

        $preparedQuestions = $questions->map(function (Question $q) use ($attempt, $answers, $optionsOrder, $displayLabels) {
            $ans = $answers->get($q->id);
            $order = $optionsOrder[$q->id] ?? null;

            $opts = $q->options;
            if (is_array($order)) {
                // Reorder options according to shuffled order
                $map = collect($opts)->keyBy('id');
                $opts = [];
                foreach ($order as $optId) {
                    if (isset($map[$optId])) {
                        $opts[] = $map[$optId];
                    }
                }
            }

            // Assign clean sequential visual labels A, B, C, D to options based on their shuffled position!
            $indexedOpts = [];
            foreach ($opts as $idx => $opt) {
                $indexedOpts[] = [
                    'id' => $opt['id'], // immutable key from bank
                    'display_label' => $displayLabels[$idx] ?? 'A', // always A, B, C, D in order!
                    'text' => $opt['text'],
                ];
            }

            return [
                'id' => $q->id,
                'number' => $q->attempt_number,
                'level' => $q->level,
                'question_text' => $q->question_text,
                'symbol_type' => $q->symbol_type,
                'options' => $indexedOpts,
                'points' => 5,
                'is_locked' => $this->quizService->isQuestionLocked($attempt, $q->attempt_number),
                'is_answered' => ! is_null($ans),
                'selected_option' => $ans?->selected_option,
                'is_correct' => $ans?->is_correct,
                // NEVER leak correct_key and explanation if not answered!
                'correct_key' => $ans ? $q->correct_key : null,
                'explanation' => $ans ? $q->explanation : null,
            ];
        });

        $roadmap = $this->quizService->getRoadmapData($attempt, $answers);

        return view('quiz_pengetahuan.show', [
            'attempt' => $attempt,
            'activeQuestion' => $activeQuestion,
            'targetNumber' => $targetNumber,
            'questions' => $preparedQuestions,
            'answers' => $answers,
            'roadmap' => $roadmap,
            'answeredCount' => $answers->count(),
            'totalQuestions' => $attempt->total_questions,
            'pointsEarned' => $attempt->points_earned,
        ]);
    }

    /**
     * Submit an answer via AJAX.
     */
    public function answer(QuizAttempt $attempt, Request $request): JsonResponse
    {
        if ($attempt->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Akses tidak diizinkan.'], 403);
        }

        $validated = $request->validate([
            'question_id' => ['required', 'exists:questions,id'],
            'selected_option' => ['required', 'string', 'in:A,B,C,D,a,b,c,d'],
        ]);

        $question = Question::findOrFail($validated['question_id']);
        $qAttemptNumber = $this->quizService->getQuestionAttemptNumber($attempt, $question->id);

        if ($this->quizService->isQuestionLocked($attempt, $qAttemptNumber)) {
            return response()->json([
                'error' => 'Tingkat ini masih terkunci. Selesaikan semua soal pada tingkat sebelumnya terlebih dahulu.',
            ], 403);
        }

        $result = $this->quizService->recordAnswer($attempt, $question, strtoupper($validated['selected_option']));

        return response()->json($result);
    }

    /**
     * Finish attempt manually.
     */
    public function finish(QuizAttempt $attempt, Request $request): JsonResponse|RedirectResponse
    {
        if ($attempt->user_id !== $request->user()->id) {
            abort(403);
        }

        $this->quizService->finishAttempt($attempt);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'completed',
                'redirect' => route('quiz.result', $attempt->id),
            ]);
        }

        return redirect()->route('quiz.result', $attempt->id);
    }

    /**
     * Display the quiz result screen.
     */
    public function result(QuizAttempt $attempt, Request $request): View|RedirectResponse
    {
        if ($attempt->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat hasil quiz ini.');
        }

        if ($attempt->status === 'in_progress') {
            $this->quizService->finishAttempt($attempt);
        }

        $questions = $this->quizService->getQuestionsForAttempt($attempt);
        $answers = $attempt->answers()->get()->keyBy('question_id');
        $predicateConfig = $this->quizService->calculatePredicate($attempt->score);

        return view('quiz_pengetahuan.result', [
            'attempt' => $attempt,
            'user' => $request->user(),
            'questions' => $questions,
            'answers' => $answers,
            'predicateConfig' => $predicateConfig,
            'passingScore' => (int) config('quiz.passing_score', 75),
        ]);
    }

    /**
     * Retry quiz by starting a new attempt.
     */
    public function retry(Request $request): RedirectResponse
    {
        $newAttempt = $this->quizService->createNewAttempt($request->user());

        return redirect()->route('quiz.show', $newAttempt->id);
    }
}
