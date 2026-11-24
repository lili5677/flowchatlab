@extends('admin.layout')

@section('title', 'Tambah Kasus Simulasi')
@section('page-title', 'Tambah Kasus Simulasi')
@section('page-subtitle', 'Buat level tantangan baru untuk simulasi alur visual algoritma tanpa perlu mengetik kode HTML.')

@section('header-actions')
    <a href="{{ route('admin.simulasi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Daftar</span>
    </a>
@endsection

@section('content')
<div class="w-full">
    <form action="{{ route('admin.simulasi.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-6">
        @csrf

        <!-- Grid 1: Basic Info -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label for="level_number" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Level *</label>
                <input type="number" name="level_number" id="level_number" value="{{ old('level_number', $nextLevel) }}" min="1" required
                       class="w-full px-3.5 py-2.5 rounded-xl border @error('level_number') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-black focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                @error('level_number')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="layout_type" class="block text-xs font-bold text-slate-700 mb-1.5">Struktur Alur (Layout) *</label>
                <select name="layout_type" id="layout_type" required onchange="toggleLayoutFields()"
                        class="w-full px-3.5 py-2.5 rounded-xl border @error('layout_type') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <option value="branching" {{ old('layout_type') === 'branching' ? 'selected' : '' }}>Percabangan (IF-ELSE Branching)</option>
                    <option value="sequential" {{ old('layout_type') === 'sequential' ? 'selected' : '' }}>Sekuensial (Perhitungan Linier)</option>
                    <option value="loop" {{ old('layout_type') === 'loop' ? 'selected' : '' }}>Perulangan (While / Iterasi Loop)</option>
                </select>
                @error('layout_type')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="expected_symbol" class="block text-xs font-bold text-slate-700 mb-1.5">Simbol Kunci Jawaban *</label>
                <select name="expected_symbol" id="expected_symbol" required
                        class="w-full px-3.5 py-2.5 rounded-xl border @error('expected_symbol') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <option value="decision" {{ old('expected_symbol') === 'decision' ? 'selected' : '' }}>Keputusan / Decision (Belah Ketupat)</option>
                    <option value="process" {{ old('expected_symbol') === 'process' ? 'selected' : '' }}>Proses / Process (Persegi Panjang)</option>
                    <option value="io" {{ old('expected_symbol') === 'io' ? 'selected' : '' }}>Input/Output (Jajar Genjang)</option>
                    <option value="terminator" {{ old('expected_symbol') === 'terminator' ? 'selected' : '' }}>Terminator (Kapsul / Mulai-Selesai)</option>
                </select>
                @error('expected_symbol')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Title & Desc -->
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 mb-1.5">Judul Kasus Simulasi *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Contoh: Menentukan Kelulusan Nilai Siswa (KKM ≥ 75)" required
                       class="w-full px-3.5 py-2.5 rounded-xl border @error('title') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                @error('title')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="desc" class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Kasus & Skenario Masalah *</label>
                <textarea name="desc" id="desc" rows="3" required placeholder="Jelaskan alur masalah atau logika kasus yang perlu diselesaikan siswa..."
                          class="w-full px-3.5 py-2.5 rounded-xl border @error('desc') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white leading-relaxed">{{ old('desc') }}</textarea>
                @error('desc')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Interactive Slot Setup -->
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <div>
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Simbol / Slot Interaktif yang Dikerjakan Siswa</h3>
                    <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">Bebas Coding</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-0.5">Cukup isi teks rumus di dalam simbol, sistem akan otomatis mengatur kondisi alur dan tombol slot.</p>
            </div>

            <div>
                <label for="slot_text_inside" class="block text-xs font-bold text-slate-700 mb-1.5">Teks / Rumus Di Dalam Simbol *</label>
                <input type="text" name="slot_text_inside" id="slot_text_inside" value="{{ old('slot_text_inside', 'Nilai >= 75 ?') }}" required
                       placeholder="Contoh: Nilai >= 75 ? atau Luas = p * l atau i = i + 1"
                       class="w-full px-3.5 py-2.5 rounded-xl border @error('slot_text_inside') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                <p class="text-[11px] text-slate-400 mt-1 font-medium">Teks atau rumus yang akan muncul di dalam bentuk simbol saat siswa meletakkannya dengan benar.</p>
                @error('slot_text_inside')
                    <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Optional Customization Toggle -->
            <details class="group bg-slate-50 rounded-2xl p-4 border border-slate-200/80">
                <summary class="text-xs font-bold text-slate-700 cursor-pointer list-none flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span>⚙️ Kustomisasi Placeholder & Label Slot (Opsional)</span>
                        <span class="text-[10px] bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md font-semibold">Bisa dikosongkan</span>
                    </span>
                    <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3 pt-3 border-t border-slate-200/60">
                    <div>
                        <label for="slot_prompt" class="block text-[11px] font-bold text-slate-600 mb-1">Teks Petunjuk Tombol Slot</label>
                        <input type="text" name="slot_prompt" id="slot_prompt" value="{{ old('slot_prompt') }}" placeholder="Otomatis terisi sesuai jenis simbol"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                    </div>
                    <div>
                        <label for="slot_condition_text" class="block text-[11px] font-bold text-slate-600 mb-1">Teks Badge Syarat Slot</label>
                        <input type="text" name="slot_condition_text" id="slot_condition_text" value="{{ old('slot_condition_text') }}" placeholder="Otomatis mengikuti teks rumus"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                    </div>
                </div>
            </details>
        </div>

        <!-- Dynamic Node Texts based on Layout -->
        <div id="layout-fields-branching" class="space-y-4 pt-2 border-t border-slate-100">
            <h3 class="text-xs font-black uppercase tracking-wider text-amber-700">Teks Node Diagram Percabangan (IF-ELSE)</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="branch_input_label" class="block text-xs font-bold text-slate-700 mb-1.5">Teks Input Awal</label>
                    <input type="text" name="branch_input_label" id="branch_input_label" value="{{ old('branch_input_label', 'Input Nilai_Ujian = 80') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label for="branch_output_true" class="block text-xs font-bold text-slate-700 mb-1.5">Output Cabang YA (True)</label>
                    <input type="text" name="branch_output_true" id="branch_output_true" value="{{ old('branch_output_true', 'Cetak "Lulus"') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label for="branch_output_false" class="block text-xs font-bold text-slate-700 mb-1.5">Output Cabang TIDAK (False)</label>
                    <input type="text" name="branch_output_false" id="branch_output_false" value="{{ old('branch_output_false', 'Cetak "Remedial"') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                </div>
            </div>
        </div>

        <div id="layout-fields-sequential" class="space-y-4 pt-2 border-t border-slate-100 hidden">
            <h3 class="text-xs font-black uppercase tracking-wider text-sky-700">Teks Node Diagram Sekuensial</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="seq_input_label" class="block text-xs font-bold text-slate-700 mb-1.5">Teks Input Data Awal</label>
                    <input type="text" name="seq_input_label" id="seq_input_label" value="{{ old('seq_input_label', 'Input Panjang (p)=10, Lebar (l)=6') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label for="seq_output_label" class="block text-xs font-bold text-slate-700 mb-1.5">Teks Output Hasil Perhitungan</label>
                    <input type="text" name="seq_output_label" id="seq_output_label" value="{{ old('seq_output_label', 'Cetak Luas = 60') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                </div>
            </div>
        </div>

        <div id="layout-fields-loop" class="space-y-4 pt-2 border-t border-slate-100 hidden">
            <h3 class="text-xs font-black uppercase tracking-wider text-purple-700">Teks Node Diagram Perulangan (Loop)</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="loop_init_label" class="block text-xs font-bold text-slate-700 mb-1.5">Teks Inisialisasi Counter</label>
                    <input type="text" name="loop_init_label" id="loop_init_label" value="{{ old('loop_init_label', 'Inisialisasi: i = 1') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label for="loop_condition_label" class="block text-xs font-bold text-slate-700 mb-1.5">Teks Kondisi Perulangan</label>
                    <input type="text" name="loop_condition_label" id="loop_condition_label" value="{{ old('loop_condition_label', 'Apakah i ≤ 5 ?') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label for="loop_body_label" class="block text-xs font-bold text-slate-700 mb-1.5">Teks Output Tubuh Perulangan</label>
                    <input type="text" name="loop_body_label" id="loop_body_label" value="{{ old('loop_body_label', 'Cetak nilai i') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-amber-400">
                </div>
            </div>
        </div>

        <!-- Hint Section: Pure Indonesian text, NO HTML -->
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Petunjuk Pengerjaan untuk Siswa (Hint)</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Tulis dalam teks biasa. Tampilan kartu kaidah dan tips akan diformat secara otomatis oleh sistem tanpa perlu kode HTML.</p>
                </div>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">✓ Bebas Tag HTML</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="hint_rule" class="block text-xs font-bold text-slate-700 mb-1.5">1. Kaidah Standar Algoritma (Alasan Pemilihan Simbol) *</label>
                    <textarea name="hint_rule" id="hint_rule" rows="4" required
                              placeholder="Contoh: Setiap titik dalam algoritma yang menghasilkan dua kemungkinan cabang (seperti True/False) wajib menggunakan Simbol Belah Ketupat (Decision)."
                              class="w-full px-3.5 py-2.5 rounded-xl border @error('hint_rule') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white leading-relaxed">{{ old('hint_rule') }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Mengapa simbol ini yang tepat menurut standar ANSI/ISO 5807.</p>
                    @error('hint_rule')
                        <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hint_tip" class="block text-xs font-bold text-slate-700 mb-1.5">2. Langkah Praktis / Tips Pengerjaan *</label>
                    <textarea name="hint_tip" id="hint_tip" rows="4" required
                              placeholder="Contoh: Periksa kondisi kelulusan nilai, pilih simbol Keputusan (Decision) pada palet di sebelah kiri lalu pasang ke kotak alur."
                              class="w-full px-3.5 py-2.5 rounded-xl border @error('hint_tip') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white leading-relaxed">{{ old('hint_tip') }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Langkah nyata yang mengarahkan siswa memilih palet dan menguji alur.</p>
                    @error('hint_tip')
                        <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Success Modal Content -->
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Pesan Edukasi Saat Berhasil</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="success_title" class="block text-xs font-bold text-slate-700 mb-1.5">Judul Pesan Sukses *</label>
                    <input type="text" name="success_title" id="success_title" value="{{ old('success_title', 'Luar Biasa! Tantangan Berhasil!') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                </div>

                <div>
                    <label for="success_desc" class="block text-xs font-bold text-slate-700 mb-1.5">Penjelasan Edukasi Saat Berhasil *</label>
                    <input type="text" name="success_desc" id="success_desc" value="{{ old('success_desc', 'Kamu berhasil memasang simbol yang tepat sesuai kaidah alur.') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                </div>
            </div>
        </div>

        <!-- Status & Order -->
        <div class="flex items-center gap-6 pt-2 border-t border-slate-100">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-amber-500 focus:ring-amber-400">
                <span class="text-xs font-bold text-slate-700">Aktifkan Kasus Simulasi Ini</span>
            </label>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.simulasi.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-extrabold shadow-sm transition">
                Simpan Kasus Baru
            </button>
        </div>

    </form>
</div>

<script>
    function toggleLayoutFields() {
        const type = document.getElementById('layout_type').value;
        const b = document.getElementById('layout-fields-branching');
        const s = document.getElementById('layout-fields-sequential');
        const l = document.getElementById('layout-fields-loop');

        b.classList.add('hidden');
        s.classList.add('hidden');
        l.classList.add('hidden');

        if (type === 'branching') b.classList.remove('hidden');
        else if (type === 'sequential') s.classList.remove('hidden');
        else if (type === 'loop') l.classList.remove('hidden');
    }

    toggleLayoutFields();
</script>
@endsection
