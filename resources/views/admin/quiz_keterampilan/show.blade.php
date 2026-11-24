@extends('admin.layout')

@section('title', 'Detail Tugas ' . $task->kode)
@section('page-title', 'Detail Tugas Keterampilan')
@section('page-subtitle', 'Pratinjau skenario masalah dan susunan kunci langkah flowchart.')

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.quiz-keterampilan.edit', $task->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span>Edit Tugas</span>
        </a>
        <a href="{{ route('admin.quiz-keterampilan.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="w-full space-y-6">

    <!-- Card Overview -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-6">
        
        <!-- Header Badges -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <span class="px-3 py-1 rounded-full text-xs font-mono font-black bg-slate-900 text-white">
                    {{ $task->kode }}
                </span>
                @if($task->level == 1)
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800">Tingkat 1 - Mudah</span>
                @elseif($task->level == 2)
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800">Tingkat 2 - Sedang</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 text-rose-800">Tingkat 3 - Sulit</span>
                @endif
            </div>
            <span class="text-xs font-bold text-slate-400">
                {{ count($task->langkah ?? []) }} Langkah Total
            </span>
        </div>

        <!-- Judul & Skenario -->
        <div class="space-y-2">
            <h2 class="text-lg font-black text-slate-900">
                {{ $task->judul }}
            </h2>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs text-slate-700 font-medium leading-relaxed">
                {{ $task->soal }}
            </div>
        </div>

        <!-- Flowchart Steps Timeline -->
        <div class="space-y-3 pt-2">
            <div class="text-[11px] font-black uppercase tracking-wider text-slate-400">
                Urutan Kunci Flowchart (Solusi Ideal)
            </div>

            @php
                $symbolLabels = [
                    'T' => ['name' => 'Terminator', 'badge' => 'bg-amber-100 text-amber-900 border-amber-300'],
                    'IO' => ['name' => 'Input / Output', 'badge' => 'bg-sky-100 text-sky-900 border-sky-300'],
                    'P' => ['name' => 'Proses', 'badge' => 'bg-indigo-100 text-indigo-900 border-indigo-300'],
                    'D' => ['name' => 'Keputusan (Decision)', 'badge' => 'bg-purple-100 text-purple-900 border-purple-300'],
                ];
            @endphp

            <div class="space-y-3 relative before:absolute before:left-4 before:top-4 before:bottom-4 before:w-0.5 before:bg-slate-200">
                @foreach($task->langkah ?? [] as $st)
                    @php
                        $symInfo = $symbolLabels[$st['simbol'] ?? ''] ?? ['name' => $st['simbol'], 'badge' => 'bg-slate-100 text-slate-700 border-slate-300'];
                    @endphp
                    <div class="relative flex items-center gap-4 pl-1">
                        <div class="w-7 h-7 rounded-full bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0 z-10 shadow-xs">
                            {{ $st['urutan'] ?? $loop->iteration }}
                        </div>
                        <div class="flex-1 p-3.5 rounded-2xl border border-slate-200 bg-white shadow-2xs flex items-center justify-between gap-3">
                            <span class="text-xs font-bold text-slate-800 leading-relaxed">
                                {{ $st['teks'] ?? '' }}
                            </span>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border shrink-0 {{ $symInfo['badge'] }}">
                                {{ $symInfo['name'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
