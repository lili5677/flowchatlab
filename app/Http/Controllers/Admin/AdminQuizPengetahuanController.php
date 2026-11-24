<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminQuizPengetahuanController extends Controller
{
    /**
     * Display a listing of quiz questions.
     */
    public function index(Request $request): View
    {
        $query = Question::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('question_text', 'like', "%{$search}%")
                    ->orWhere('explanation', 'like', "%{$search}%")
                    ->orWhere('symbol_type', 'like', "%{$search}%")
                    ->orWhere('question_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $level = $request->input('level');
            if (in_array($level, ['Mudah', 'Sedang', 'Sulit'], true)) {
                $query->where('level', $level);
            }
        }

        $questions = $query->orderBy('question_number')->paginate(15)->withQueryString();

        $levelCounts = [
            'all' => Question::count(),
            'Mudah' => Question::where('level', 'Mudah')->count(),
            'Sedang' => Question::where('level', 'Sedang')->count(),
            'Sulit' => Question::where('level', 'Sulit')->count(),
        ];

        return view('admin.quiz_pengetahuan.index', [
            'questions' => $questions,
            'levelCounts' => $levelCounts,
            'currentSearch' => $request->input('search', ''),
            'currentLevel' => $request->input('level', ''),
        ]);
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(): View
    {
        $nextNumber = ((int) Question::max('question_number')) + 1;

        return view('admin.quiz_pengetahuan.create', [
            'nextNumber' => $nextNumber,
        ]);
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question_number' => ['required', 'integer', 'min:1', 'unique:questions,question_number'],
            'level' => ['required', 'string', Rule::in(['Mudah', 'Sedang', 'Sulit'])],
            'symbol_type' => ['nullable', 'string', 'max:50'],
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:500'],
            'option_b' => ['required', 'string', 'max:500'],
            'option_c' => ['required', 'string', 'max:500'],
            'option_d' => ['required', 'string', 'max:500'],
            'correct_key' => ['required', 'string', Rule::in(['A', 'B', 'C', 'D'])],
            'explanation' => ['required', 'string'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'question_number.required' => 'Nomor urut soal wajib diisi.',
            'question_number.unique' => 'Nomor urut soal ini sudah digunakan.',
            'level.required' => 'Pilih tingkat kesulitan soal.',
            'question_text.required' => 'Teks pertanyaan wajib diisi.',
            'option_a.required' => 'Pilihan jawaban A wajib diisi.',
            'option_b.required' => 'Pilihan jawaban B wajib diisi.',
            'option_c.required' => 'Pilihan jawaban C wajib diisi.',
            'option_d.required' => 'Pilihan jawaban D wajib diisi.',
            'correct_key.required' => 'Pilih kunci jawaban yang benar.',
            'explanation.required' => 'Pembahasan / penjelasan soal wajib diisi.',
            'points.required' => 'Bobot poin wajib diisi.',
        ]);

        $options = [
            ['id' => 'A', 'text' => $validated['option_a']],
            ['id' => 'B', 'text' => $validated['option_b']],
            ['id' => 'C', 'text' => $validated['option_c']],
            ['id' => 'D', 'text' => $validated['option_d']],
        ];

        $question = Question::create([
            'question_number' => $validated['question_number'],
            'level' => $validated['level'],
            'symbol_type' => ! empty($validated['symbol_type']) ? $validated['symbol_type'] : null,
            'question_text' => $validated['question_text'],
            'options' => $options,
            'correct_key' => $validated['correct_key'],
            'explanation' => $validated['explanation'],
            'points' => $validated['points'],
        ]);

        if (Auth::check()) {
            ActivityLog::record(
                'admin_question_created',
                'Admin '.Auth::user()->name." menambahkan soal Quiz Pengetahuan No. {$question->question_number}.",
                Auth::user(),
                ['question_id' => $question->id]
            );
        }

        return redirect()->route('admin.quiz-pengetahuan.index')
            ->with('success', "Soal nomor {$question->question_number} berhasil ditambahkan ke bank soal.");
    }

    /**
     * Display the specified question.
     */
    public function show(Question $quiz_pengetahuan): View
    {
        return view('admin.quiz_pengetahuan.show', [
            'question' => $quiz_pengetahuan,
        ]);
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $quiz_pengetahuan): View
    {
        return view('admin.quiz_pengetahuan.edit', [
            'question' => $quiz_pengetahuan,
        ]);
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Question $quiz_pengetahuan): RedirectResponse
    {
        $validated = $request->validate([
            'question_number' => ['required', 'integer', 'min:1', Rule::unique('questions', 'question_number')->ignore($quiz_pengetahuan->id)],
            'level' => ['required', 'string', Rule::in(['Mudah', 'Sedang', 'Sulit'])],
            'symbol_type' => ['nullable', 'string', 'max:50'],
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string', 'max:500'],
            'option_b' => ['required', 'string', 'max:500'],
            'option_c' => ['required', 'string', 'max:500'],
            'option_d' => ['required', 'string', 'max:500'],
            'correct_key' => ['required', 'string', Rule::in(['A', 'B', 'C', 'D'])],
            'explanation' => ['required', 'string'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'question_number.required' => 'Nomor urut soal wajib diisi.',
            'question_number.unique' => 'Nomor urut soal ini sudah digunakan.',
            'level.required' => 'Pilih tingkat kesulitan soal.',
            'question_text.required' => 'Teks pertanyaan wajib diisi.',
            'option_a.required' => 'Pilihan jawaban A wajib diisi.',
            'option_b.required' => 'Pilihan jawaban B wajib diisi.',
            'option_c.required' => 'Pilihan jawaban C wajib diisi.',
            'option_d.required' => 'Pilihan jawaban D wajib diisi.',
            'correct_key.required' => 'Pilih kunci jawaban yang benar.',
            'explanation.required' => 'Pembahasan / penjelasan soal wajib diisi.',
            'points.required' => 'Bobot poin wajib diisi.',
        ]);

        $options = [
            ['id' => 'A', 'text' => $validated['option_a']],
            ['id' => 'B', 'text' => $validated['option_b']],
            ['id' => 'C', 'text' => $validated['option_c']],
            ['id' => 'D', 'text' => $validated['option_d']],
        ];

        $quiz_pengetahuan->update([
            'question_number' => $validated['question_number'],
            'level' => $validated['level'],
            'symbol_type' => ! empty($validated['symbol_type']) ? $validated['symbol_type'] : null,
            'question_text' => $validated['question_text'],
            'options' => $options,
            'correct_key' => $validated['correct_key'],
            'explanation' => $validated['explanation'],
            'points' => $validated['points'],
        ]);

        if (Auth::check()) {
            ActivityLog::record(
                'admin_question_updated',
                'Admin '.Auth::user()->name." memperbarui soal Quiz Pengetahuan No. {$quiz_pengetahuan->question_number}.",
                Auth::user(),
                ['question_id' => $quiz_pengetahuan->id]
            );
        }

        return redirect()->route('admin.quiz-pengetahuan.index')
            ->with('success', "Soal nomor {$quiz_pengetahuan->question_number} berhasil diperbarui.");
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(Question $quiz_pengetahuan): RedirectResponse
    {
        $number = $quiz_pengetahuan->question_number;
        $quiz_pengetahuan->delete();

        if (Auth::check()) {
            ActivityLog::record(
                'admin_question_deleted',
                'Admin '.Auth::user()->name." menghapus soal Quiz Pengetahuan No. {$number}.",
                Auth::user()
            );
        }

        return redirect()->route('admin.quiz-pengetahuan.index')
            ->with('success', "Soal nomor {$number} berhasil dihapus dari bank soal.");
    }
}
