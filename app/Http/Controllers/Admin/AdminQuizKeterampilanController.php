<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KeterampilanTask;
use App\Services\KeterampilanBankService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminQuizKeterampilanController extends Controller
{
    public function __construct(
        protected KeterampilanBankService $bankService
    ) {}

    /**
     * Display a listing of Quiz Keterampilan tasks.
     */
    public function index(Request $request): View
    {
        $query = KeterampilanTask::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('soal', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $level = (int) $request->input('level');
            if (in_array($level, [1, 2, 3], true)) {
                $query->where('level', $level);
            }
        }

        $tasks = $query->orderBy('level')->orderBy('kode')->paginate(12)->withQueryString();

        $levelCounts = [
            'all' => KeterampilanTask::count(),
            '1' => KeterampilanTask::where('level', 1)->count(),
            '2' => KeterampilanTask::where('level', 2)->count(),
            '3' => KeterampilanTask::where('level', 3)->count(),
        ];

        return view('admin.quiz_keterampilan.index', [
            'tasks' => $tasks,
            'levelCounts' => $levelCounts,
            'currentSearch' => $request->input('search', ''),
            'currentLevel' => $request->input('level', ''),
        ]);
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        $levelNames = [
            1 => 'Mudah',
            2 => 'Sedang',
            3 => 'Sulit',
        ];

        return view('admin.quiz_keterampilan.create', [
            'levelNames' => $levelNames,
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:keterampilan_tasks,kode'],
            'level' => ['required', 'integer', Rule::in([1, 2, 3])],
            'judul' => ['required', 'string', 'max:255'],
            'soal' => ['required', 'string'],
            'steps' => ['required', 'array', 'min:2'],
            'steps.*.teks' => ['required', 'string'],
            'steps.*.simbol' => ['required', 'string', Rule::in(['T', 'P', 'IO', 'D'])],
        ], [
            'kode.required' => 'Kode tugas wajib diisi (contoh: KS-1-5).',
            'kode.unique' => 'Kode tugas ini sudah digunakan.',
            'level.required' => 'Pilih tingkatan level tugas.',
            'judul.required' => 'Judul tugas wajib diisi.',
            'soal.required' => 'Deskripsi soal / skenario instruksi wajib diisi.',
            'steps.required' => 'Minimal 2 langkah penyusunan flowchart wajib ditentukan.',
            'steps.min' => 'Minimal 2 langkah penyusunan flowchart wajib ditentukan.',
            'steps.*.teks.required' => 'Teks instruksi setiap langkah wajib diisi.',
            'steps.*.simbol.required' => 'Pilih simbol flowchart untuk setiap langkah.',
        ]);

        $levelMap = [
            1 => 'Mudah',
            2 => 'Sedang',
            3 => 'Sulit',
        ];

        $langkah = [];
        $order = 1;
        foreach ($validated['steps'] as $st) {
            $langkah[] = [
                'teks' => trim($st['teks']),
                'simbol' => strtoupper(trim($st['simbol'])),
                'urutan' => $order++,
            ];
        }

        $task = KeterampilanTask::create([
            'kode' => trim($validated['kode']),
            'level' => $validated['level'],
            'nama_level' => $levelMap[$validated['level']] ?? 'Mudah',
            'judul' => trim($validated['judul']),
            'soal' => trim($validated['soal']),
            'langkah' => $langkah,
        ]);

        $this->bankService->clearCache();

        if (Auth::check()) {
            ActivityLog::record(
                'admin_keterampilan_created',
                'Admin '.Auth::user()->name." menambahkan tugas Quiz Keterampilan [{$task->kode}] {$task->judul}.",
                Auth::user(),
                ['task_id' => $task->id]
            );
        }

        return redirect()->route('admin.quiz-keterampilan.index')
            ->with('success', "Tugas keterampilan [{$task->kode}] berhasil ditambahkan ke bank soal.");
    }

    /**
     * Display the specified task.
     */
    public function show(KeterampilanTask $quiz_keterampilan): View
    {
        return view('admin.quiz_keterampilan.show', [
            'task' => $quiz_keterampilan,
        ]);
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(KeterampilanTask $quiz_keterampilan): View
    {
        $levelNames = [
            1 => 'Mudah',
            2 => 'Sedang',
            3 => 'Sulit',
        ];

        return view('admin.quiz_keterampilan.edit', [
            'task' => $quiz_keterampilan,
            'levelNames' => $levelNames,
        ]);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, KeterampilanTask $quiz_keterampilan): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', Rule::unique('keterampilan_tasks', 'kode')->ignore($quiz_keterampilan->id)],
            'level' => ['required', 'integer', Rule::in([1, 2, 3])],
            'judul' => ['required', 'string', 'max:255'],
            'soal' => ['required', 'string'],
            'steps' => ['required', 'array', 'min:2'],
            'steps.*.teks' => ['required', 'string'],
            'steps.*.simbol' => ['required', 'string', Rule::in(['T', 'P', 'IO', 'D'])],
        ], [
            'kode.required' => 'Kode tugas wajib diisi.',
            'kode.unique' => 'Kode tugas ini sudah digunakan.',
            'level.required' => 'Pilih tingkatan level tugas.',
            'judul.required' => 'Judul tugas wajib diisi.',
            'soal.required' => 'Deskripsi soal / instruksi wajib diisi.',
            'steps.required' => 'Minimal 2 langkah penyusunan flowchart wajib ditentukan.',
            'steps.*.teks.required' => 'Teks instruksi setiap langkah wajib diisi.',
            'steps.*.simbol.required' => 'Pilih simbol flowchart untuk setiap langkah.',
        ]);

        $levelMap = [
            1 => 'Mudah',
            2 => 'Sedang',
            3 => 'Sulit',
        ];

        $langkah = [];
        $order = 1;
        foreach ($validated['steps'] as $st) {
            $langkah[] = [
                'teks' => trim($st['teks']),
                'simbol' => strtoupper(trim($st['simbol'])),
                'urutan' => $order++,
            ];
        }

        $quiz_keterampilan->update([
            'kode' => trim($validated['kode']),
            'level' => $validated['level'],
            'nama_level' => $levelMap[$validated['level']] ?? 'Mudah',
            'judul' => trim($validated['judul']),
            'soal' => trim($validated['soal']),
            'langkah' => $langkah,
        ]);

        $this->bankService->clearCache();

        if (Auth::check()) {
            ActivityLog::record(
                'admin_keterampilan_updated',
                'Admin '.Auth::user()->name." memperbarui tugas Quiz Keterampilan [{$quiz_keterampilan->kode}] {$quiz_keterampilan->judul}.",
                Auth::user(),
                ['task_id' => $quiz_keterampilan->id]
            );
        }

        return redirect()->route('admin.quiz-keterampilan.index')
            ->with('success', "Tugas keterampilan [{$quiz_keterampilan->kode}] berhasil diperbarui.");
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(KeterampilanTask $quiz_keterampilan): RedirectResponse
    {
        $kode = $quiz_keterampilan->kode;
        $quiz_keterampilan->delete();

        $this->bankService->clearCache();

        if (Auth::check()) {
            ActivityLog::record(
                'admin_keterampilan_deleted',
                'Admin '.Auth::user()->name." menghapus tugas Quiz Keterampilan [{$kode}].",
                Auth::user()
            );
        }

        return redirect()->route('admin.quiz-keterampilan.index')
            ->with('success', "Tugas keterampilan [{$kode}] berhasil dihapus dari bank soal.");
    }
}
