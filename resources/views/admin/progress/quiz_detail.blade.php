@extends('admin.layout')

@section('title', 'Detail Attempt Quiz #' . $attempt->id . ' - ' . ($user ? $user->name : 'Siswa'))
@section('page-title', 'Detail Evaluasi Quiz Pengetahuan')
@section('page-subtitle', 'Analisis jawaban dan hasil evaluasi individual 20 butir soal standar ANSI/ISO.')

@section('header-actions')
    <div class="flex items-center gap-2">
        @if($user)
            <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition">
                Profil Siswa
            </a>
        @endif
        <a href="{{ route('admin.progress.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition">
            &larr; Kembali ke Rekap
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Overview Result Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-700">
                        Attempt #{{ $attempt->id }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase {{ $attempt->is_passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $attempt->is_passed ? 'LULUS KOMPETENSI' : 'REMEDIAL' }}
                    </span>
                </div>
                <h2 class="text-xl font-black text-slate-900">{{ $user ? $user->name : 'Peserta' }}</h2>
                <div class="text-xs text-slate-500 mt-1">
                    NIS: {{ $user ? $user->display_nis : '-' }} &bull; {{ $user ? $user->display_kelas : '-' }} &bull; {{ $user ? $user->school : '-' }}
                </div>
            </div>

            <!-- Score Highlights -->
            <div class="flex flex-wrap items-center gap-4">
                <!-- Nilai -->
                <div class="px-5 py-3 rounded-2xl {{ $attempt->is_passed ? 'bg-emerald-50 border border-emerald-200' : 'bg-rose-50 border border-rose-200' }}">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Skor Pengetahuan</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-black {{ $attempt->is_passed ? 'text-emerald-700' : 'text-rose-700' }}">{{ $attempt->score }}</span>
                        <span class="text-xs font-bold text-slate-400">/ 100</span>
                    </div>
                </div>

                <!-- Benar -->
                <div class="px-5 py-3 rounded-2xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Jawaban Benar</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-black text-slate-800">{{ $attempt->correct_count }}</span>
                        <span class="text-xs font-bold text-slate-400">/ {{ $attempt->total_questions }} Soal</span>
                    </div>
                </div>

                <!-- Durasi -->
                <div class="px-5 py-3 rounded-2xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Waktu Pengerjaan</span>
                    <div class="text-xl font-black text-slate-800 mt-1">
                        {{ gmdate('i:s', $attempt->duration_seconds) }} <span class="text-xs font-semibold text-slate-500">menit</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-between text-xs text-slate-500">
            <span>Dikerjakan pada: <strong>{{ $attempt->started_at->format('d M Y, H:i:s') }}</strong></span>
            <span>Predikat: <strong class="{{ $attempt->is_passed ? 'text-emerald-700' : 'text-rose-700' }}">{{ $attempt->predicate ?: ($attempt->is_passed ? 'Baik' : 'Perlu Bimbingan') }}</strong></span>
        </div>
    </div>

    <!-- Questions Breakdown List -->
    <div class="space-y-4">
        <h3 class="text-base font-extrabold text-slate-900 px-1">Rincian 20 Soal & Jawaban Siswa</h3>

        @forelse($answers as $idx => $ans)
            @php
                $q = $ans->question;
                $options = $q ? $q->options : [];
                $selectedOpt = collect($options)->firstWhere('id', $ans->selected_option);
                $correctOpt = collect($options)->firstWhere('id', $q ? $q->correct_key : '');
            @endphp
            <div class="bg-white rounded-3xl p-5 sm:p-6 border {{ $ans->is_correct ? 'border-slate-200/80' : 'border-rose-200 bg-rose-50/10' }} shadow-2xs space-y-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black {{ $ans->is_correct ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $idx + 1 }}
                        </span>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                Tingkat: {{ $q ? $q->level : '-' }} &bull; Simbol: {{ $q ? $q->symbol_type : '-' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        @if($ans->is_correct)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>Benar (+{{ $ans->points_awarded }} Pts)</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Salah (0 Pts)</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Soal Text -->
                <p class="text-sm font-semibold text-slate-800 leading-relaxed">
                    {{ $q ? $q->question_text : 'Pertanyaan tidak ditemukan' }}
                </p>

                <!-- Comparison: Answered vs Key -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-1">
                    <div class="p-3.5 rounded-2xl {{ $ans->is_correct ? 'bg-emerald-50 border border-emerald-200' : 'bg-rose-50 border border-rose-200' }}">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">
                            Jawaban Siswa:
                        </span>
                        <div class="font-extrabold {{ $ans->is_correct ? 'text-emerald-900' : 'text-rose-900' }}">
                            {{ strtoupper($ans->selected_option) }}. {{ $selectedOpt['text'] ?? $ans->selected_option }}
                        </div>
                    </div>

                    @if(!$ans->is_correct && $q)
                        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block mb-1">
                                Kunci Jawaban Benar:
                            </span>
                            <div class="font-extrabold text-emerald-950">
                                {{ strtoupper($q->correct_key) }}. {{ $correctOpt['text'] ?? $q->correct_key }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pembahasan / Explanation -->
                @if($q && $q->explanation)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs">
                        <span class="font-bold text-slate-700 block mb-0.5">💡 Penjelasan / Pembahasan:</span>
                        <span class="text-slate-600 leading-relaxed">{{ $q->explanation }}</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-8 text-center text-xs text-slate-400 bg-white rounded-3xl border border-slate-200">
                Tidak ada data jawaban tersimpan untuk attempt ini.
            </div>
        @endforelse
    </div>

</div>
@endsection

