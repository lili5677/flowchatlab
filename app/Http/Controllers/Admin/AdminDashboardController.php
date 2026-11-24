<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KeterampilanTask;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\Simulation;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Overview Dashboard & Statistics.
     */
    public function index(): View
    {
        // User statistics
        $totalUsers = User::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        // Quiz Pengetahuan statistics
        $totalAttempts = QuizAttempt::count();
        $completedAttempts = QuizAttempt::where('status', 'completed')->count();
        $passedAttempts = QuizAttempt::where('status', 'completed')->where('is_passed', true)->count();
        $avgScore = (float) (QuizAttempt::where('status', 'completed')->avg('score') ?? 0);
        $passingRate = $completedAttempts > 0 ? round(($passedAttempts / $completedAttempts) * 100, 1) : 0;

        // Predicate breakdown for Knowledge Quiz
        $predSangatBaik = QuizAttempt::where('status', 'completed')->where('score', '>=', 90)->count();
        $predBaik = QuizAttempt::where('status', 'completed')->whereBetween('score', [75, 89])->count();
        $predRemedial = QuizAttempt::where('status', 'completed')->where('score', '<', 75)->count();

        // Quiz Keterampilan statistics
        $praktikCompletedCount = User::whereNotNull('skor_praktik')->count();
        $avgPraktikScore = (float) (User::whereNotNull('skor_praktik')->avg('skor_praktik') ?? 0);
        $maxPraktikScore = (int) (User::whereNotNull('skor_praktik')->max('skor_praktik') ?? 0);

        // Top students (ordered by points or quiz scores)
        $topStudents = User::where('role', 'siswa')
            ->orderByDesc('points')
            ->orderByDesc('skor_praktik')
            ->limit(5)
            ->get();

        // Bank Soal & Content counts
        $totalQuestions = Question::count();
        $totalKeterampilanTasks = KeterampilanTask::count();
        $totalSimulations = Simulation::count();

        // Recent activity logs
        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->limit(8)
            ->get();

        // Recent users
        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => [
                'totalUsers' => $totalUsers,
                'totalSiswa' => $totalSiswa,
                'totalGuru' => $totalGuru,
                'totalAdmin' => $totalAdmin,
                'totalAttempts' => $totalAttempts,
                'completedAttempts' => $completedAttempts,
                'passedAttempts' => $passedAttempts,
                'avgScore' => round($avgScore, 1),
                'passingRate' => $passingRate,
                'praktikCount' => $praktikCompletedCount,
                'avgPraktikScore' => round($avgPraktikScore, 1),
                'maxPraktikScore' => $maxPraktikScore,
                'predicates' => [
                    'sangat_baik' => $predSangatBaik,
                    'baik' => $predBaik,
                    'remedial' => $predRemedial,
                ],
                'totalQuestions' => $totalQuestions,
                'totalKeterampilanTasks' => $totalKeterampilanTasks,
                'totalSimulations' => $totalSimulations,
            ],
            'topStudents' => $topStudents,
            'recentLogs' => $recentLogs,
            'recentUsers' => $recentUsers,
        ]);
    }
}
