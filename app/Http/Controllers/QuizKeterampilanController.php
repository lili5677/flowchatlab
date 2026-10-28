<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitKeterampilanRequest;
use App\Models\User;
use App\Services\KeterampilanBankService;
use App\Services\KeterampilanChecker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizKeterampilanController extends Controller
{
    public function __construct(
        protected KeterampilanBankService $bankService,
        protected KeterampilanChecker $checker
    ) {}

    /**
     * Display the Quiz Keterampilan introduction page.
     */
    public function index(Request $request): View
    {
        $levels = $this->bankService->getLevels();
        $user = Auth::user();
        $hasActiveSession = $request->session()->has('quiz_keterampilan.tasks');

        return view('quiz.keterampilan.index', [
            'levels' => $levels,
            'user' => $user,
            'hasActiveSession' => $hasActiveSession,
        ]);
    }

    /**
     * Start a new quiz session by selecting 3 random tasks (1 per level in order).
     */
    public function mulai(Request $request): RedirectResponse
    {
        $selectedTasks = $this->bankService->getRandomTasksForQuiz();

        if (count($selectedTasks) < 3) {
            return redirect()->route('quiz.keterampilan')
                ->with('error', 'Bank soal belum lengkap untuk memulai kuis.');
        }

        // Shuffle steps once per task and store in session
        foreach ($selectedTasks as $key => $task) {
            $steps = $task['langkah'] ?? [];
            shuffle($steps);
            $selectedTasks[$key]['shuffled_steps'] = $steps;
        }

        $request->session()->put('quiz_keterampilan', [
            'tasks' => $selectedTasks,
            'current_index' => 0,
            'results' => [0 => null, 1 => null, 2 => null],
            'user_answers' => [0 => null, 1 => null, 2 => null],
            'skor_akhir' => null,
            'status' => 'in_progress',
        ]);

        return redirect()->route('quiz.keterampilan.tugas');
    }

    /**
     * Display the task screen ("Tugas X dari 3").
     */
    public function tugas(Request $request): View|RedirectResponse
    {
        $quiz = $request->session()->get('quiz_keterampilan');

        if (! $quiz || empty($quiz['tasks'])) {
            return redirect()->route('quiz.keterampilan');
        }

        $currentIndex = $quiz['current_index'] ?? 0;
        $totalTasks = count($quiz['tasks']);

        if ($currentIndex >= $totalTasks) {
            return redirect()->route('quiz.keterampilan.hasil');
        }

        $currentTask = $quiz['tasks'][$currentIndex];
        $shuffledSteps = $currentTask['shuffled_steps'] ?? $currentTask['langkah'] ?? [];
        $result = $quiz['results'][$currentIndex] ?? null;
        $savedAnswer = $quiz['user_answers'][$currentIndex] ?? [];

        $skorSementara = 0;
        foreach ($quiz['results'] as $res) {
            if ($res !== null && isset($res['skor'])) {
                $skorSementara += (int) $res['skor'];
            }
        }

        return view('quiz.keterampilan.tugas', [
            'currentTask' => $currentTask,
            'taskNumber' => $currentIndex + 1,
            'totalTasks' => $totalTasks,
            'shuffledSteps' => $shuffledSteps,
            'result' => $result,
            'savedAnswer' => $savedAnswer,
            'isSubmitted' => $result !== null,
            'isLastTask' => ($currentIndex + 1) === $totalTasks,
            'allTasks' => $quiz['tasks'],
            'allResults' => $quiz['results'],
            'currentIndex' => $currentIndex,
            'skorSementara' => $skorSementara,
        ]);
    }

    /**
     * Submit and check student's answer for the current task.
     */
    public function submit(SubmitKeterampilanRequest $request): RedirectResponse
    {
        $quiz = $request->session()->get('quiz_keterampilan');

        if (! $quiz || empty($quiz['tasks'])) {
            return redirect()->route('quiz.keterampilan');
        }

        $currentIndex = $quiz['current_index'] ?? 0;
        $currentTask = $quiz['tasks'][$currentIndex] ?? null;

        if (! $currentTask) {
            return redirect()->route('quiz.keterampilan');
        }

        // If already submitted, prevent duplicate submissions
        if (isset($quiz['results'][$currentIndex]) && $quiz['results'][$currentIndex] !== null) {
            return redirect()->route('quiz.keterampilan.tugas');
        }

        $jawabanSiswa = $request->input('jawaban', []);
        if (! is_array($jawabanSiswa)) {
            $jawabanSiswa = [];
        }

        // Check and evaluate server-side
        $kunciLangkah = $currentTask['langkah'] ?? [];
        $evaluasi = $this->checker->check($kunciLangkah, $jawabanSiswa);

        // Save to session
        $quiz['results'][$currentIndex] = $evaluasi;
        $quiz['user_answers'][$currentIndex] = $jawabanSiswa;

        $request->session()->put('quiz_keterampilan', $quiz);

        return redirect()->route('quiz.keterampilan.tugas');
    }

    /**
     * Move to the next task or proceed to the final results screen.
     */
    public function next(Request $request): RedirectResponse
    {
        $quiz = $request->session()->get('quiz_keterampilan');

        if (! $quiz || empty($quiz['tasks'])) {
            return redirect()->route('quiz.keterampilan');
        }

        $currentIndex = $quiz['current_index'] ?? 0;
        $totalTasks = count($quiz['tasks']);

        // Only allow moving forward if current task has been submitted
        if (! isset($quiz['results'][$currentIndex]) || $quiz['results'][$currentIndex] === null) {
            return redirect()->route('quiz.keterampilan.tugas');
        }

        $nextIndex = $currentIndex + 1;

        if ($nextIndex < $totalTasks) {
            $quiz['current_index'] = $nextIndex;
            $request->session()->put('quiz_keterampilan', $quiz);

            return redirect()->route('quiz.keterampilan.tugas');
        }

        // Completed all 3 tasks: calculate final score
        $taskScores = [];
        foreach ($quiz['results'] as $res) {
            $taskScores[] = (int) ($res['skor'] ?? 0);
        }

        $skorAkhir = $this->checker->calculateFinalScore($taskScores);
        $quiz['skor_akhir'] = $skorAkhir;
        $quiz['status'] = 'completed';
        $request->session()->put('quiz_keterampilan', $quiz);

        // If user is authenticated, save skor_praktik to database
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $user->update(['skor_praktik' => $skorAkhir]);
        }

        return redirect()->route('quiz.keterampilan.hasil');
    }

    /**
     * Show the final results page.
     */
    public function hasil(Request $request): View|RedirectResponse
    {
        $quiz = $request->session()->get('quiz_keterampilan');

        if (! $quiz || empty($quiz['tasks']) || ! isset($quiz['skor_akhir'])) {
            return redirect()->route('quiz.keterampilan');
        }

        return view('quiz.keterampilan.hasil', [
            'tasks' => $quiz['tasks'],
            'results' => $quiz['results'],
            'skorAkhir' => $quiz['skor_akhir'],
            'user' => Auth::user(),
        ]);
    }

    /**
     * Reset the quiz and return to intro.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->session()->forget('quiz_keterampilan');

        return redirect()->route('quiz.keterampilan');
    }
}
