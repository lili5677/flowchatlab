<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminProgressController extends Controller
{
    /**
     * Display student learning progress overview & statistics.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'siswa')
            ->with(['quizAttempts' => function ($q) {
                $q->orderByDesc('id');
            }]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('school', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter')) {
            $filter = $request->input('filter');
            if ($filter === 'praktik_selesai') {
                $query->whereNotNull('skor_praktik');
            } elseif ($filter === 'praktik_belum') {
                $query->whereNull('skor_praktik');
            } elseif ($filter === 'pengetahuan_lulus') {
                $query->whereHas('quizAttempts', function ($q) {
                    $q->where('status', 'completed')->where('is_passed', true);
                });
            } elseif ($filter === 'pengetahuan_remedial') {
                $query->whereHas('quizAttempts', function ($q) {
                    $q->where('status', 'completed')->where('is_passed', false);
                });
            }
        }

        $students = $query->latest('id')->paginate(12)->withQueryString();

        // Calculate summary statistics
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalCompletedPengetahuan = QuizAttempt::where('status', 'completed')->count();
        $passedPengetahuan = QuizAttempt::where('status', 'completed')->where('is_passed', true)->count();
        $avgPengetahuan = (float) (QuizAttempt::where('status', 'completed')->avg('score') ?? 0);

        $totalPraktikCompleted = User::where('role', 'siswa')->whereNotNull('skor_praktik')->count();
        $avgPraktik = (float) (User::where('role', 'siswa')->whereNotNull('skor_praktik')->avg('skor_praktik') ?? 0);

        return view('admin.progress.index', [
            'students' => $students,
            'stats' => [
                'totalSiswa' => $totalSiswa,
                'totalCompletedPengetahuan' => $totalCompletedPengetahuan,
                'passedPengetahuan' => $passedPengetahuan,
                'avgPengetahuan' => round($avgPengetahuan, 1),
                'totalPraktikCompleted' => $totalPraktikCompleted,
                'avgPraktik' => round($avgPraktik, 1),
            ],
            'currentSearch' => $request->input('search', ''),
            'currentFilter' => $request->input('filter', ''),
        ]);
    }

    /**
     * Show detailed answers and results for a specific student quiz attempt.
     */
    public function quizDetail(QuizAttempt $attempt): View
    {
        $attempt->load(['user', 'answers.question']);

        return view('admin.progress.quiz_detail', [
            'attempt' => $attempt,
            'user' => $attempt->user,
            'answers' => $attempt->answers,
        ]);
    }

    /**
     * Export student learning progress to CSV spreadsheet format.
     */
    public function export(Request $request): StreamedResponse
    {
        $students = User::where('role', 'siswa')
            ->with(['quizAttempts' => function ($q) {
                $q->where('status', 'completed')->orderByDesc('id');
            }])
            ->orderBy('name')
            ->get();

        $filename = 'rekap_nilai_siswa_flowchart_lab_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($students) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Header
            fputcsv($handle, [
                'No',
                'NIS',
                'Nama Siswa',
                'Kelas',
                'Sekolah',
                'Attempt Teori Selesai',
                'Skor Teori Tertinggi',
                'Skor Teori Terakhir',
                'Kelulusan Teori',
                'Skor Praktik',
                'Predikat Praktik',
                'Total Poin',
            ]);

            foreach ($students as $idx => $student) {
                $attempts = $student->quizAttempts;
                $maxScore = $attempts->max('score');
                $latest = $attempts->first();
                $isPassed = $maxScore !== null && $maxScore >= 75;

                $predikatPraktik = 'Belum Praktik';
                if ($student->skor_praktik !== null) {
                    if ($student->skor_praktik >= 85) {
                        $predikatPraktik = 'Sangat Terampil';
                    } elseif ($student->skor_praktik >= 75) {
                        $predikatPraktik = 'Kompeten';
                    } else {
                        $predikatPraktik = 'Perlu Latihan';
                    }
                }

                fputcsv($handle, [
                    $idx + 1,
                    $student->display_nis,
                    $student->name,
                    $student->display_kelas,
                    $student->school ?: '-',
                    $attempts->count(),
                    $maxScore !== null ? $maxScore : '-',
                    $latest ? $latest->score : '-',
                    $maxScore !== null ? ($isPassed ? 'LULUS' : 'REMEDIAL') : 'BELUM MENGERJAKAN',
                    $student->skor_praktik !== null ? $student->skor_praktik : '-',
                    $predikatPraktik,
                    $student->points,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Show clean printable student evaluation ledger report.
     */
    public function print(Request $request): View
    {
        $students = User::where('role', 'siswa')
            ->with(['quizAttempts' => function ($q) {
                $q->where('status', 'completed')->orderByDesc('id');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.progress.print', [
            'students' => $students,
            'generatedAt' => now(),
        ]);
    }
}
