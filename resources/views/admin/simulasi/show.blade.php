@extends('admin.layout')

@section('title', 'Detail Kasus Simulasi Level ' . $simulation->level_number)
@section('page-title', 'Detail Simulasi')
@section('page-subtitle', 'Pratinjau konfigurasi level ' . $simulation->level_number . ' simulasi flowchart.')

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.simulasi.edit', $simulation->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span>Edit Simulasi</span>
        </a>
        <a href="{{ route('admin.simulasi.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="w-full space-y-6">

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-xl bg-amber-400 text-slate-950 font-black text-xs flex items-center justify-center shrink-0">
                    {{ $simulation->level_number }}
                </span>
                <span class="text-sm font-extrabold text-slate-900">
                    {{ $simulation->title }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                @if($simulation->layout_type === 'branching')
                    <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800">Percabangan</span>
                @elseif($simulation->layout_type === 'sequential')
                    <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-sky-100 text-sky-800">Sekuensial</span>
                @else
                    <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800">Perulangan</span>
                @endif
                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold {{ $simulation->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                    {{ $simulation->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        </div>

        <!-- Skenario -->
        <div class="space-y-1.5">
            <div class="text-[11px] font-black uppercase tracking-wider text-slate-400">Deskripsi Masalah</div>
            <div class="text-xs text-slate-700 leading-relaxed p-4 rounded-2xl bg-slate-50 border border-slate-100">
                {!! $simulation->desc !!}
            </div>
        </div>

        <!-- Interactive Slot Setup -->
        <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
            <div class="text-[11px] font-black uppercase tracking-wider text-slate-500">Konfigurasi Blok Slot</div>
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 block text-[10px] font-bold uppercase">Simbol Jawaban Benar</span>
                    <span class="font-extrabold text-slate-900 uppercase">{{ $simulation->expected_symbol }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] font-bold uppercase">Teks Kondisi Slot</span>
                    <span class="font-mono font-bold text-amber-700">{{ $simulation->slot_condition_text }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] font-bold uppercase">Placeholder Slot</span>
                    <span class="font-medium text-slate-700">{{ $simulation->slot_prompt }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] font-bold uppercase">Teks Di Dalam Simbol</span>
                    <span class="font-mono font-bold text-slate-900">{{ $simulation->slot_text_inside }}</span>
                </div>
            </div>
        </div>

        <!-- Custom Node Texts -->
        @if(!empty($simulation->config_data))
            <div class="p-4 rounded-2xl border border-slate-200 bg-white space-y-2">
                <div class="text-[11px] font-black uppercase tracking-wider text-slate-500">Teks Node Diagram</div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    @foreach($simulation->config_data as $key => $val)
                        <div>
                            <span class="text-slate-400 block text-[10px] font-bold uppercase">{{ str_replace('_', ' ', $key) }}</span>
                            <span class="font-semibold text-slate-800">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Hint -->
        <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/70 space-y-2">
            <div class="text-[11px] font-black uppercase tracking-wider text-amber-800">Petunjuk Penyelesaian (Hint)</div>
            <div class="text-xs text-amber-950 leading-relaxed">
                {!! $simulation->hint_text !!}
            </div>
        </div>

        <!-- Success Message Preview -->
        <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/70 space-y-1">
            <div class="text-xs font-extrabold text-emerald-900">{{ $simulation->success_title }}</div>
            <div class="text-xs text-emerald-800">{{ $simulation->success_desc }}</div>
        </div>

    </div>

</div>
@endsection
