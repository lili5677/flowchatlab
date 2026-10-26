@props([
    'type' => 'terminator',
    'class' => 'w-full h-full',
    'caption' => 'ISO 5807',
])

@php
    $type = strtolower((string) $type);
    $ariaLabels = [
        'terminator' => 'Simbol Terminator (Oval / Kapsul) - Menandai Titik Mulai dan Selesai',
        'input_output' => 'Simbol Input/Output (Jajar Genjang) - Menerima Input atau Menampilkan Output',
        'decision' => 'Simbol Keputusan (Belah Ketupat) - Percabangan Logika Ya atau Tidak',
        'process' => 'Simbol Proses (Persegi Panjang) - Pengolahan Data atau Perhitungan Aritmatika',
    ];
    $label = $ariaLabels[$type] ?? 'Simbol Flowchart Standar ISO 5807';
@endphp

<div class="flex flex-col items-center justify-center p-2 text-center" role="img" aria-label="{{ $label }}">
    <div class="{{ $class }} flex items-center justify-center">
        @if ($type === 'terminator')
            <!-- Oval / Capsule: Terminator (Start / Stop) -->
            <svg class="w-full h-auto max-h-12" viewBox="0 0 100 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="3" y="6" width="94" height="36" rx="18" class="fill-amber-50 stroke-amber-500" stroke-width="2.5" />
                <text x="50" y="27" text-anchor="middle" class="fill-amber-900 text-[10px] font-black uppercase tracking-wider">START / END</text>
            </svg>
        @elseif ($type === 'input_output')
            <!-- Parallelogram: Input / Output -->
            <svg class="w-full h-auto max-h-12" viewBox="0 0 100 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polygon points="18,6 96,6 82,42 4,42" class="fill-sky-50 stroke-sky-500" stroke-width="2.5" stroke-linejoin="round" />
                <text x="50" y="27" text-anchor="middle" class="fill-sky-900 text-[10px] font-black uppercase tracking-wider">INPUT / DATA</text>
            </svg>
        @elseif ($type === 'decision')
            <!-- Rhombus / Diamond: Decision (IF / THEN) -->
            <svg class="w-full h-auto max-h-12" viewBox="0 0 100 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polygon points="50,4 96,28 50,52 4,28" class="fill-amber-50 stroke-amber-500" stroke-width="2.5" stroke-linejoin="round" />
                <text x="50" y="32" text-anchor="middle" class="fill-amber-950 text-[10px] font-black uppercase tracking-wider">IF / THEN</text>
            </svg>
        @elseif ($type === 'process')
            <!-- Rectangle: Process -->
            <svg class="w-full h-auto max-h-12" viewBox="0 0 100 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="4" y="6" width="92" height="36" rx="4" class="fill-emerald-50 stroke-emerald-500" stroke-width="2.5" stroke-linejoin="round" />
                <text x="50" y="27" text-anchor="middle" class="fill-emerald-900 text-[10px] font-black uppercase tracking-wider">PROSES / HITUNG</text>
            </svg>
        @endif
    </div>
    @if ($caption)
        <span class="mt-1 text-[9px] font-black tracking-widest text-slate-400 uppercase leading-none">{{ $caption }}</span>
    @endif
</div>
