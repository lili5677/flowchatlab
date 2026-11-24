@extends('admin.layout')

@section('title', 'Detail Soal #' . $question->question_number)
@section('page-title', 'Detail Soal Quiz')
@section('page-subtitle', 'Pratinjau soal teori nomor ' . $question->question_number . ' Quiz Pengetahuan.')

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.quiz-pengetahuan.edit', $question->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span>Edit Soal</span>
        </a>
        <a href="{{ route('admin.quiz-pengetahuan.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="w-full space-y-6">

    <!-- Question Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-6">
        
        <!-- Header Info -->
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <span class="px-3 py-1 rounded-full text-xs font-black bg-slate-900 text-white">
                    Soal #{{ $question->question_number }}
                </span>
                @if($question->level === 'Mudah')
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800">Tingkat 1: Mudah</span>
                @elseif($question->level === 'Sedang')
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800">Tingkat 2: Sedang</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 text-rose-800">Tingkat 3: Sulit</span>
                @endif
                @if($question->symbol_type)
                    <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-600">
                        {{ $question->symbol_type }}
                    </span>
                @endif
            </div>
            <div class="text-xs font-extrabold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">
                {{ $question->points }} Poin
            </div>
        </div>

        <!-- Question Body -->
        <div class="space-y-3">
            <div class="text-[11px] font-black uppercase tracking-wider text-slate-400">Pertanyaan</div>
            <div class="text-base font-extrabold text-slate-900 leading-relaxed">
                {{ $question->question_text }}
            </div>
        </div>

        <!-- Options List -->
        <div class="space-y-3 pt-2">
            <div class="text-[11px] font-black uppercase tracking-wider text-slate-400">Pilihan Jawaban</div>
            <div class="space-y-2.5">
                @foreach($question->options as $opt)
                    @php
                        $isCorrect = ($opt['id'] ?? '') === $question->correct_key;
                    @endphp
                    <div class="flex items-center gap-3 p-3.5 rounded-2xl border transition {{ $isCorrect ? 'border-emerald-300 bg-emerald-50/70 text-emerald-950 font-bold' : 'border-slate-200 bg-white text-slate-700' }}">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-xs shrink-0 {{ $isCorrect ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600' }}">
                            {{ $opt['id'] ?? '-' }}
                        </div>
                        <div class="flex-1 text-xs leading-relaxed">
                            {{ $opt['text'] ?? '' }}
                        </div>
                        @if($isCorrect)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-200 text-emerald-900 shrink-0">
                                Kunci Benar
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Explanation -->
        <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 space-y-1.5">
            <div class="text-[10px] font-black uppercase tracking-wider text-amber-800 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Pembahasan & Penjelasan</span>
            </div>
            <p class="text-xs text-amber-950 font-medium leading-relaxed">
                {{ $question->explanation }}
            </p>
        </div>

    </div>

</div>
@endsection
