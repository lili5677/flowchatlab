@extends('admin.layout')

@section('title', 'Edit Soal Quiz Pengetahuan #' . $question->question_number)
@section('page-title', 'Edit Soal Quiz')
@section('page-subtitle', 'Perbarui konten pertanyaan, pilihan ganda, kunci jawaban, atau bobot poin.')

@section('header-actions')
    <a href="{{ route('admin.quiz-pengetahuan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Daftar</span>
    </a>
@endsection

@section('content')
<div class="w-full">
    <form action="{{ route('admin.quiz-pengetahuan.update', $question->id) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-6">
        @csrf
        @method('PUT')

        @php
            $optMap = collect($question->options)->keyBy('id');
            $valA = old('option_a', $optMap['A']['text'] ?? '');
            $valB = old('option_b', $optMap['B']['text'] ?? '');
            $valC = old('option_c', $optMap['C']['text'] ?? '');
            $valD = old('option_d', $optMap['D']['text'] ?? '');
        @endphp

        <!-- Metadata Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
            <!-- Question Number -->
            <div>
                <label for="question_number" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Urut Soal *</label>
                <input type="number" name="question_number" id="question_number" value="{{ old('question_number', $question->question_number) }}" min="1" required
                       class="w-full px-3.5 py-2.5 rounded-xl border @error('question_number') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-extrabold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                @error('question_number')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Difficulty Level -->
            <div>
                <label for="level" class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kesulitan *</label>
                <select name="level" id="level" required
                        class="w-full px-3.5 py-2.5 rounded-xl border @error('level') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <option value="Mudah" {{ old('level', $question->level) === 'Mudah' ? 'selected' : '' }}>Mudah (Tingkat 1)</option>
                    <option value="Sedang" {{ old('level', $question->level) === 'Sedang' ? 'selected' : '' }}>Sedang (Tingkat 2)</option>
                    <option value="Sulit" {{ old('level', $question->level) === 'Sulit' ? 'selected' : '' }}>Sulit (Tingkat 3)</option>
                </select>
                @error('level')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Symbol Type Dropdown -->
            <div>
                <label for="symbol_type" class="block text-xs font-bold text-slate-700 mb-1.5">Tipe Simbol (Opsional)</label>
                <select name="symbol_type" id="symbol_type"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <option value="" {{ old('symbol_type', $question->symbol_type) === '' || is_null(old('symbol_type', $question->symbol_type)) ? 'selected' : '' }}>-- Tanpa Simbol (Umum) --</option>
                    <option value="terminator" {{ old('symbol_type', $question->symbol_type) === 'terminator' ? 'selected' : '' }}>Terminator (Mulai / Selesai)</option>
                    <option value="process" {{ old('symbol_type', $question->symbol_type) === 'process' ? 'selected' : '' }}>Proses (Perhitungan / Rumus)</option>
                    <option value="input_output" {{ old('symbol_type', $question->symbol_type) === 'input_output' ? 'selected' : '' }}>Input / Output (Jajar Genjang)</option>
                    <option value="decision" {{ old('symbol_type', $question->symbol_type) === 'decision' ? 'selected' : '' }}>Keputusan (Percabangan Decision)</option>
                    <option value="preparation" {{ old('symbol_type', $question->symbol_type) === 'preparation' ? 'selected' : '' }}>Preparation (Inisialisasi)</option>
                    <option value="connector" {{ old('symbol_type', $question->symbol_type) === 'connector' ? 'selected' : '' }}>Connector (Penghubung Bulat)</option>
                </select>
                <span class="text-[10px] text-slate-400 mt-1 block">Pilih tipe simbol jika relevan</span>
            </div>

            <!-- Points -->
            <div>
                <label for="points" class="block text-xs font-bold text-slate-700 mb-1.5">Bobot Poin *</label>
                <input type="number" name="points" id="points" value="{{ old('points', $question->points) }}" min="1" max="100" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                @error('points')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Question Text -->
        <div>
            <label for="question_text" class="block text-xs font-bold text-slate-700 mb-1.5">Teks Pertanyaan Soal *</label>
            <textarea name="question_text" id="question_text" rows="3" required placeholder="Tuliskan butir pertanyaan secara jelas..."
                      class="w-full px-3.5 py-2.5 rounded-xl border @error('question_text') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white leading-relaxed">{{ old('question_text', $question->question_text) }}</textarea>
            @error('question_text')
                <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Options A, B, C, D -->
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-700">Pilihan Jawaban & Kunci</h3>
                <span class="text-[11px] text-slate-400">Pilih salah satu sebagai kunci benar</span>
            </div>

            <div class="grid grid-cols-1 gap-3.5">
                <!-- Option A -->
                <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200 bg-slate-50/40">
                    <div class="w-8 h-8 rounded-xl bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">A</div>
                    <input type="text" name="option_a" value="{{ $valA }}" placeholder="Teks jawaban pilihan A..." required
                           class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <label class="flex items-center gap-1.5 cursor-pointer shrink-0 px-2">
                        <input type="radio" name="correct_key" value="A" {{ old('correct_key', $question->correct_key) === 'A' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-400">
                        <span class="text-[11px] font-bold text-slate-600">Kunci</span>
                    </label>
                </div>

                <!-- Option B -->
                <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200 bg-slate-50/40">
                    <div class="w-8 h-8 rounded-xl bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">B</div>
                    <input type="text" name="option_b" value="{{ $valB }}" placeholder="Teks jawaban pilihan B..." required
                           class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <label class="flex items-center gap-1.5 cursor-pointer shrink-0 px-2">
                        <input type="radio" name="correct_key" value="B" {{ old('correct_key', $question->correct_key) === 'B' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-400">
                        <span class="text-[11px] font-bold text-slate-600">Kunci</span>
                    </label>
                </div>

                <!-- Option C -->
                <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200 bg-slate-50/40">
                    <div class="w-8 h-8 rounded-xl bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">C</div>
                    <input type="text" name="option_c" value="{{ $valC }}" placeholder="Teks jawaban pilihan C..." required
                           class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <label class="flex items-center gap-1.5 cursor-pointer shrink-0 px-2">
                        <input type="radio" name="correct_key" value="C" {{ old('correct_key', $question->correct_key) === 'C' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-400">
                        <span class="text-[11px] font-bold text-slate-600">Kunci</span>
                    </label>
                </div>

                <!-- Option D -->
                <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200 bg-slate-50/40">
                    <div class="w-8 h-8 rounded-xl bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">D</div>
                    <input type="text" name="option_d" value="{{ $valD }}" placeholder="Teks jawaban pilihan D..." required
                           class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <label class="flex items-center gap-1.5 cursor-pointer shrink-0 px-2">
                        <input type="radio" name="correct_key" value="D" {{ old('correct_key', $question->correct_key) === 'D' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-400">
                        <span class="text-[11px] font-bold text-slate-600">Kunci</span>
                    </label>
                </div>
            </div>
            @error('correct_key')
                <p class="text-rose-500 text-[11px] font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Explanation -->
        <div class="pt-2 border-t border-slate-100">
            <label for="explanation" class="block text-xs font-bold text-slate-700 mb-1.5">Pembahasan & Penjelasan Soal *</label>
            <textarea name="explanation" id="explanation" rows="3" required placeholder="Jelaskan alasan kaidah logika jawaban yang benar berdasarkan standar..."
                      class="w-full px-3.5 py-2.5 rounded-xl border @error('explanation') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white leading-relaxed">{{ old('explanation', $question->explanation) }}</textarea>
            @error('explanation')
                <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Submit -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.quiz-pengetahuan.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-extrabold shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection
