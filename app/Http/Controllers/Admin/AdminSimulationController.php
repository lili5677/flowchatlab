<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Simulation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminSimulationController extends Controller
{
    /**
     * Display a listing of simulation cases.
     */
    public function index(Request $request): View
    {
        $query = Simulation::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('desc', 'like', "%{$search}%")
                    ->orWhere('slot_text_inside', 'like', "%{$search}%")
                    ->orWhere('level_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('layout_type')) {
            $type = $request->input('layout_type');
            if (in_array($type, ['branching', 'sequential', 'loop'], true)) {
                $query->where('layout_type', $type);
            }
        }

        $simulations = $query->orderBy('order')->orderBy('level_number')->paginate(10)->withQueryString();

        $typeCounts = [
            'all' => Simulation::count(),
            'branching' => Simulation::where('layout_type', 'branching')->count(),
            'sequential' => Simulation::where('layout_type', 'sequential')->count(),
            'loop' => Simulation::where('layout_type', 'loop')->count(),
        ];

        return view('admin.simulasi.index', [
            'simulations' => $simulations,
            'typeCounts' => $typeCounts,
            'currentSearch' => $request->input('search', ''),
            'currentType' => $request->input('layout_type', ''),
        ]);
    }

    /**
     * Show the form for creating a new simulation case.
     */
    public function create(): View
    {
        $nextLevel = ((int) Simulation::max('level_number')) + 1;

        return view('admin.simulasi.create', [
            'nextLevel' => $nextLevel,
        ]);
    }

    /**
     * Store a newly created simulation in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'level_number' => ['required', 'integer', 'min:1', 'unique:simulations,level_number'],
            'title' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string'],
            'layout_type' => ['required', 'string', Rule::in(['branching', 'sequential', 'loop'])],
            'expected_symbol' => ['required', 'string', Rule::in(['decision', 'process', 'io', 'terminator'])],
            'slot_text_inside' => ['required', 'string', 'max:255'],
            'slot_prompt' => ['nullable', 'string', 'max:255'],
            'slot_condition_text' => ['nullable', 'string', 'max:255'],
            'hint_rule' => ['nullable', 'string'],
            'hint_tip' => ['nullable', 'string'],
            'hint_text' => ['nullable', 'string'],
            'success_title' => ['required', 'string', 'max:255'],
            'success_desc' => ['required', 'string'],
            'branch_input_label' => ['nullable', 'string', 'max:255'],
            'branch_output_true' => ['nullable', 'string', 'max:255'],
            'branch_output_false' => ['nullable', 'string', 'max:255'],
            'seq_input_label' => ['nullable', 'string', 'max:255'],
            'seq_output_label' => ['nullable', 'string', 'max:255'],
            'loop_init_label' => ['nullable', 'string', 'max:255'],
            'loop_condition_label' => ['nullable', 'string', 'max:255'],
            'loop_body_label' => ['nullable', 'string', 'max:255'],
            'input_label' => ['nullable', 'string', 'max:255'],
            'output_true' => ['nullable', 'string', 'max:255'],
            'output_false' => ['nullable', 'string', 'max:255'],
            'output_label' => ['nullable', 'string', 'max:255'],
            'init_label' => ['nullable', 'string', 'max:255'],
            'condition_label' => ['nullable', 'string', 'max:255'],
            'body_label' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'level_number.required' => 'Nomor level simulasi wajib diisi.',
            'level_number.unique' => 'Nomor level ini sudah digunakan.',
            'title.required' => 'Judul tantangan simulasi wajib diisi.',
            'desc.required' => 'Deskripsi skenario simulasi wajib diisi.',
            'layout_type.required' => 'Pilih jenis struktur alur (layout).',
            'expected_symbol.required' => 'Pilih simbol yang menjadi jawaban benar.',
            'slot_text_inside.required' => 'Teks / rumus simbol yang diuji wajib diisi.',
            'success_title.required' => 'Judul pesan keberhasilan wajib diisi.',
            'success_desc.required' => 'Pesan edukasi saat berhasil wajib diisi.',
        ]);

        $hintText = $this->formatHintText(
            $validated['layout_type'],
            $request->input('hint_rule'),
            $request->input('hint_tip'),
            $request->input('hint_text')
        );

        if ($hintText === '') {
            return back()->withErrors(['hint_rule' => 'Isi petunjuk kaidah atau tips pengerjaan untuk siswa.'])->withInput();
        }

        $slotAttrs = $this->resolveSlotAttributes(
            $validated['expected_symbol'],
            $validated['slot_text_inside'],
            $request->input('slot_prompt'),
            $request->input('slot_condition_text')
        );

        $configData = $this->resolveConfigData($validated['layout_type'], $request);

        $simulation = Simulation::create([
            'level_number' => $validated['level_number'],
            'title' => trim($validated['title']),
            'desc' => trim($validated['desc']),
            'layout_type' => $validated['layout_type'],
            'expected_symbol' => $validated['expected_symbol'],
            'slot_prompt' => $slotAttrs['slot_prompt'],
            'slot_condition_text' => $slotAttrs['slot_condition_text'],
            'slot_text_inside' => $slotAttrs['slot_text_inside'],
            'hint_text' => $hintText,
            'success_title' => trim($validated['success_title']),
            'success_desc' => trim($validated['success_desc']),
            'config_data' => $configData,
            'order' => $validated['order'] ?? $validated['level_number'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (Auth::check()) {
            ActivityLog::record(
                'admin_simulation_created',
                'Admin '.Auth::user()->name." menambahkan kasus Simulasi Level {$simulation->level_number}: {$simulation->title}.",
                Auth::user(),
                ['simulation_id' => $simulation->id]
            );
        }

        return redirect()->route('admin.simulasi.index')
            ->with('success', "Kasus simulasi level {$simulation->level_number} berhasil ditambahkan.");
    }

    /**
     * Display the specified simulation.
     */
    public function show(Simulation $simulasi): View
    {
        return view('admin.simulasi.show', [
            'simulation' => $simulasi,
        ]);
    }

    /**
     * Show the form for editing the specified simulation.
     */
    public function edit(Simulation $simulasi): View
    {
        return view('admin.simulasi.edit', [
            'simulation' => $simulasi,
        ]);
    }

    /**
     * Update the specified simulation in storage.
     */
    public function update(Request $request, Simulation $simulasi): RedirectResponse
    {
        $validated = $request->validate([
            'level_number' => ['required', 'integer', 'min:1', Rule::unique('simulations', 'level_number')->ignore($simulasi->id)],
            'title' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string'],
            'layout_type' => ['required', 'string', Rule::in(['branching', 'sequential', 'loop'])],
            'expected_symbol' => ['required', 'string', Rule::in(['decision', 'process', 'io', 'terminator'])],
            'slot_text_inside' => ['required', 'string', 'max:255'],
            'slot_prompt' => ['nullable', 'string', 'max:255'],
            'slot_condition_text' => ['nullable', 'string', 'max:255'],
            'hint_rule' => ['nullable', 'string'],
            'hint_tip' => ['nullable', 'string'],
            'hint_text' => ['nullable', 'string'],
            'success_title' => ['required', 'string', 'max:255'],
            'success_desc' => ['required', 'string'],
            'branch_input_label' => ['nullable', 'string', 'max:255'],
            'branch_output_true' => ['nullable', 'string', 'max:255'],
            'branch_output_false' => ['nullable', 'string', 'max:255'],
            'seq_input_label' => ['nullable', 'string', 'max:255'],
            'seq_output_label' => ['nullable', 'string', 'max:255'],
            'loop_init_label' => ['nullable', 'string', 'max:255'],
            'loop_condition_label' => ['nullable', 'string', 'max:255'],
            'loop_body_label' => ['nullable', 'string', 'max:255'],
            'input_label' => ['nullable', 'string', 'max:255'],
            'output_true' => ['nullable', 'string', 'max:255'],
            'output_false' => ['nullable', 'string', 'max:255'],
            'output_label' => ['nullable', 'string', 'max:255'],
            'init_label' => ['nullable', 'string', 'max:255'],
            'condition_label' => ['nullable', 'string', 'max:255'],
            'body_label' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'level_number.required' => 'Nomor level simulasi wajib diisi.',
            'level_number.unique' => 'Nomor level ini sudah digunakan.',
            'title.required' => 'Judul tantangan simulasi wajib diisi.',
            'desc.required' => 'Deskripsi skenario simulasi wajib diisi.',
            'layout_type.required' => 'Pilih jenis struktur alur (layout).',
            'expected_symbol.required' => 'Pilih simbol yang menjadi jawaban benar.',
            'slot_text_inside.required' => 'Teks / rumus simbol yang diuji wajib diisi.',
            'success_title.required' => 'Judul pesan keberhasilan wajib diisi.',
            'success_desc.required' => 'Pesan edukasi saat berhasil wajib diisi.',
        ]);

        $hintText = $this->formatHintText(
            $validated['layout_type'],
            $request->input('hint_rule'),
            $request->input('hint_tip'),
            $request->input('hint_text') ?: $simulasi->hint_text
        );

        if ($hintText === '') {
            return back()->withErrors(['hint_rule' => 'Isi petunjuk kaidah atau tips pengerjaan untuk siswa.'])->withInput();
        }

        $slotAttrs = $this->resolveSlotAttributes(
            $validated['expected_symbol'],
            $validated['slot_text_inside'],
            $request->input('slot_prompt'),
            $request->input('slot_condition_text')
        );

        $configData = $this->resolveConfigData($validated['layout_type'], $request);

        $simulasi->update([
            'level_number' => $validated['level_number'],
            'title' => trim($validated['title']),
            'desc' => trim($validated['desc']),
            'layout_type' => $validated['layout_type'],
            'expected_symbol' => $validated['expected_symbol'],
            'slot_prompt' => $slotAttrs['slot_prompt'],
            'slot_condition_text' => $slotAttrs['slot_condition_text'],
            'slot_text_inside' => $slotAttrs['slot_text_inside'],
            'hint_text' => $hintText,
            'success_title' => trim($validated['success_title']),
            'success_desc' => trim($validated['success_desc']),
            'config_data' => $configData,
            'order' => $validated['order'] ?? $simulasi->order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (Auth::check()) {
            ActivityLog::record(
                'admin_simulation_updated',
                'Admin '.Auth::user()->name." memperbarui kasus Simulasi Level {$simulasi->level_number}: {$simulasi->title}.",
                Auth::user(),
                ['simulation_id' => $simulasi->id]
            );
        }

        return redirect()->route('admin.simulasi.index')
            ->with('success', "Kasus simulasi level {$simulasi->level_number} berhasil diperbarui.");
    }

    /**
     * Remove the specified simulation from storage.
     */
    public function destroy(Simulation $simulasi): RedirectResponse
    {
        $level = $simulasi->level_number;
        $title = $simulasi->title;
        $simulasi->delete();

        if (Auth::check()) {
            ActivityLog::record(
                'admin_simulation_deleted',
                'Admin '.Auth::user()->name." menghapus kasus Simulasi Level {$level}: {$title}.",
                Auth::user()
            );
        }

        return redirect()->route('admin.simulasi.index')
            ->with('success', "Kasus simulasi level {$level} berhasil dihapus.");
    }

    /**
     * Resolve slot text, prompt, and condition string cleanly without requiring code or syntax.
     *
     * @return array{slot_prompt: string, slot_condition_text: string, slot_text_inside: string}
     */
    protected function resolveSlotAttributes(string $expectedSymbol, string $slotTextInside, ?string $slotPrompt = null, ?string $slotConditionText = null): array
    {
        $slotTextInside = trim($slotTextInside);

        $defaultPrompts = [
            'decision' => 'Klik / Pasang Simbol Keputusan',
            'process' => 'Klik / Pasang Simbol Proses',
            'io' => 'Klik / Pasang Simbol Input/Output',
            'terminator' => 'Klik / Pasang Simbol Mulai / Selesai',
        ];

        $prompt = trim($slotPrompt ?? '');
        if ($prompt === '') {
            $prompt = $defaultPrompts[$expectedSymbol] ?? 'Klik / Pasang Simbol Terkait';
        }

        $conditionText = trim($slotConditionText ?? '');
        if ($conditionText === '') {
            $conditionText = "< {$slotTextInside} >";
        }

        return [
            'slot_prompt' => $prompt,
            'slot_condition_text' => $conditionText,
            'slot_text_inside' => $slotTextInside,
        ];
    }

    /**
     * Format plain text hint rule and hint tip into user-friendly standard card HTML without requiring admin to write code.
     */
    protected function formatHintText(string $layoutType, ?string $rule, ?string $tip, ?string $rawFallback = null): string
    {
        $rule = trim($rule ?? '');
        $tip = trim($tip ?? '');

        if ($rule === '' && $tip === '') {
            return trim($rawFallback ?? '');
        }

        $color = match ($layoutType) {
            'sequential' => ['bg' => 'bg-sky-50', 'border' => 'border-sky-200', 'text' => 'text-sky-900'],
            'loop' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-900'],
            default => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-900'],
        };

        $html = '';
        if ($rule !== '') {
            $html .= '<div class="p-3 '.$color['bg'].' rounded-2xl border '.$color['border'].' mb-2"><strong class="'.$color['text'].' block mb-1">Kaidah Standar ANSI/ISO 5807:</strong>'.e($rule).'</div>';
        }
        if ($tip !== '') {
            $html .= '<p>'.e($tip).'</p>';
        }

        return $html;
    }

    /**
     * Resolve configuration data array based on layout type.
     *
     * @return array<string, string>
     */
    protected function resolveConfigData(string $layoutType, Request $request): array
    {
        if ($layoutType === 'branching') {
            return [
                'input_label' => (string) ($request->input('branch_input_label') ?: ($request->input('input_label') ?: 'Input Nilai_Ujian = 80')),
                'output_true' => (string) ($request->input('branch_output_true') ?: ($request->input('output_true') ?: 'Cetak "Lulus"')),
                'output_false' => (string) ($request->input('branch_output_false') ?: ($request->input('output_false') ?: 'Cetak "Remedial"')),
            ];
        }

        if ($layoutType === 'sequential') {
            return [
                'input_label' => (string) ($request->input('seq_input_label') ?: ($request->input('input_label') ?: 'Input Data Awal')),
                'output_label' => (string) ($request->input('seq_output_label') ?: ($request->input('output_label') ?: 'Cetak Hasil Kalkulasi')),
            ];
        }

        if ($layoutType === 'loop') {
            return [
                'init_label' => (string) ($request->input('loop_init_label') ?: ($request->input('init_label') ?: 'Inisialisasi: i = 1')),
                'condition_label' => (string) ($request->input('loop_condition_label') ?: ($request->input('condition_label') ?: 'Apakah i <= 5 ?')),
                'body_label' => (string) ($request->input('loop_body_label') ?: ($request->input('body_label') ?: 'Cetak nilai i')),
            ];
        }

        return [];
    }
}
