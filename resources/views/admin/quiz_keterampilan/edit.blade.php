@extends('admin.layout')

@section('title', 'Edit Tugas Quiz Keterampilan ' . $task->kode)
@section('page-title', 'Edit Tugas Keterampilan')
@section('page-subtitle', 'Perbarui skenario studi kasus, judul, atau urutan langkah solusi flowchart.')

@section('header-actions')
    <a href="{{ route('admin.quiz-keterampilan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Daftar</span>
    </a>
@endsection

@section('content')
<div class="w-full">
    <form action="{{ route('admin.quiz-keterampilan.update', $task->id) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-6">
        @csrf
        @method('PUT')

        <!-- Metadata Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Kode Tugas -->
            <div>
                <label for="kode" class="block text-xs font-bold text-slate-700 mb-1.5">Kode Tugas *</label>
                <input type="text" name="kode" id="kode" value="{{ old('kode', $task->kode) }}" placeholder="Contoh: KS-1-5" required
                       class="w-full px-3.5 py-2.5 rounded-xl border @error('kode') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-mono font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                <span class="text-[10px] text-slate-400 mt-1 block">Format unik (e.g. KS-1-X, KS-2-X)</span>
                @error('kode')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Level -->
            <div>
                <label for="level" class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Level *</label>
                <select name="level" id="level" required
                        class="w-full px-3.5 py-2.5 rounded-xl border @error('level') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <option value="1" {{ old('level', $task->level) == 1 ? 'selected' : '' }}>Level 1 - Mudah (Sekuensial)</option>
                    <option value="2" {{ old('level', $task->level) == 2 ? 'selected' : '' }}>Level 2 - Sedang (Percabangan)</option>
                    <option value="3" {{ old('level', $task->level) == 3 ? 'selected' : '' }}>Level 3 - Sulit (Perulangan / Kompleks)</option>
                </select>
                @error('level')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Judul -->
            <div>
                <label for="judul" class="block text-xs font-bold text-slate-700 mb-1.5">Judul Kasus *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $task->judul) }}" placeholder="Contoh: Menghitung Keliling Persegi" required
                       class="w-full px-3.5 py-2.5 rounded-xl border @error('judul') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                @error('judul')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Soal / Instruksi Skenario -->
        <div>
            <label for="soal" class="block text-xs font-bold text-slate-700 mb-1.5">Skenario Masalah & Deskripsi Tugas *</label>
            <textarea name="soal" id="soal" rows="3" required placeholder="Tuliskan skenario masalah yang harus diselesaikan siswa..."
                      class="w-full px-3.5 py-2.5 rounded-xl border @error('soal') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white leading-relaxed">{{ old('soal', $task->soal) }}</textarea>
            @error('soal')
                <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Dynamic Steps Builder -->
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Urutan Kunci Langkah Flowchart (Solusi Benar)</h3>
                    <p class="text-[11px] text-slate-400">Susun urutan langkah dari awal sampai akhir.</p>
                </div>
                <button type="button" onclick="addStepRow()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Langkah</span>
                </button>
            </div>

            <!-- Steps Container -->
            <div id="steps-container" class="space-y-3">
                @php
                    $steps = old('steps', $task->langkah ?? []);
                @endphp
                @foreach($steps as $idx => $st)
                    <div class="step-row flex items-center gap-3 p-3 rounded-2xl border border-slate-200 bg-slate-50/50">
                        <div class="step-number w-8 h-8 rounded-xl bg-amber-400 text-slate-950 font-black text-xs flex items-center justify-center shrink-0">{{ $loop->iteration }}</div>
                        <input type="text" name="steps[{{ $idx }}][teks]" value="{{ $st['teks'] ?? '' }}" placeholder="Teks instruksi langkah..." required
                               class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <select name="steps[{{ $idx }}][simbol]" required class="w-36 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <option value="T" {{ ($st['simbol'] ?? '') === 'T' ? 'selected' : '' }}>Terminator (T)</option>
                            <option value="IO" {{ ($st['simbol'] ?? '') === 'IO' ? 'selected' : '' }}>Input/Output (IO)</option>
                            <option value="P" {{ ($st['simbol'] ?? '') === 'P' ? 'selected' : '' }}>Proses (P)</option>
                            <option value="D" {{ ($st['simbol'] ?? '') === 'D' ? 'selected' : '' }}>Keputusan (D)</option>
                        </select>
                        <button type="button" onclick="removeStepRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition" title="Hapus Langkah">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>

            @error('steps')
                <p class="text-rose-500 text-[11px] font-semibold">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Submit -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.quiz-keterampilan.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-extrabold shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<script>
    let stepCount = {{ count($steps) }};

    function addStepRow() {
        const container = document.getElementById('steps-container');
        const index = stepCount;
        stepCount++;

        const row = document.createElement('div');
        row.className = 'step-row flex items-center gap-3 p-3 rounded-2xl border border-slate-200 bg-slate-50/50';
        row.innerHTML = `
            <div class="step-number w-8 h-8 rounded-xl bg-amber-400 text-slate-950 font-black text-xs flex items-center justify-center shrink-0"></div>
            <input type="text" name="steps[${index}][teks]" placeholder="Teks instruksi langkah..." required
                   class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-amber-400">
            <select name="steps[${index}][simbol]" required class="w-36 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400">
                <option value="T">Terminator (T)</option>
                <option value="IO">Input/Output (IO)</option>
                <option value="P" selected>Proses (P)</option>
                <option value="D">Keputusan (D)</option>
            </select>
            <button type="button" onclick="removeStepRow(this)" class="p-2 text-slate-400 hover:text-rose-600 transition" title="Hapus Langkah">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        container.appendChild(row);
        renumberSteps();
    }

    function removeStepRow(btn) {
        const rows = document.querySelectorAll('.step-row');
        if (rows.length <= 2) {
            alert('Minimal harus memiliki 2 langkah flowchart.');
            return;
        }
        btn.closest('.step-row').remove();
        renumberSteps();
    }

    function renumberSteps() {
        const rows = document.querySelectorAll('.step-row');
        rows.forEach((row, idx) => {
            const numEl = row.querySelector('.step-number');
            if (numEl) numEl.innerText = idx + 1;
        });
    }
</script>
@endsection
