<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tugas {{ $taskNumber }} dari {{ $totalTasks }} - Quiz Keterampilan Flowchart Lab SMK</title>
    <meta name="description" content="Tantangan Kuis Keterampilan Praktik Alur Flowchart Algoritma Visual Siswa SMK.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #F8BA15;
            color: #0F172A;
        }

        /* Explicit Two-Column Layout for Desktop (Prevents 90px grid collapse) */
        .quiz-workspace-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            align-items: start;
            width: 100%;
        }

        @media (min-width: 1024px) {
            .quiz-workspace-grid {
                grid-template-columns: 380px minmax(0, 1fr) !important;
            }
        }

        /* Geometric Flowchart Block Shapes (Option 1: Bentuk Besar & Otentik seperti Gambar 2) */

        /* 1. Terminator (Mulai & Selesai): Kapsul / Oval Emas Tebal seperti Gambar 2 */
        .flow-node-T {
            border-radius: 9999px !important;
            background: linear-gradient(180deg, #FDC02F 0%, #F59E0B 100%) !important;
            border: 2.5px solid #D97706 !important;
            color: #0F172A !important;
            padding: 0.95rem 2.75rem !important;
            font-weight: 900 !important;
            box-shadow: 0 8px 20px -3px rgba(245, 158, 11, 0.45) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 220px !important;
            max-width: 92% !important;
            text-align: center !important;
            transition: all 0.2s ease !important;
        }
        .flow-node-T:hover {
            transform: translateY(-2px) scale(1.01) !important;
            box-shadow: 0 12px 24px -3px rgba(245, 158, 11, 0.55) !important;
        }

        /* 2. Input/Output: Jajar Genjang Gelap Elegan seperti Gambar 2 */
        .flow-node-IO {
            background-color: #0F172A !important;
            border: 2px solid #334155 !important;
            color: #F8FAFC !important;
            transform: skewX(-14deg) !important;
            border-radius: 6px !important;
            padding: 0.95rem 2.75rem !important;
            font-weight: 800 !important;
            box-shadow: 0 8px 22px -3px rgba(15, 23, 42, 0.4) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 260px !important;
            max-width: 92% !important;
            text-align: center !important;
            transition: all 0.2s ease !important;
        }
        .flow-node-IO .flow-unskew {
            transform: skewX(14deg) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.6rem !important;
            width: 100% !important;
        }
        .flow-node-IO:hover {
            transform: translateY(-2px) skewX(-14deg) scale(1.01) !important;
            box-shadow: 0 12px 26px -3px rgba(15, 23, 42, 0.5) !important;
        }

        /* 3. Keputusan (Decision): Belah Ketupat (Diamond / Rhombus) Standar ANSI/ISO */
        .flow-node-D {
            position: relative !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 320px !important;
            max-width: 520px !important;
            width: 100% !important;
            min-height: 130px !important;
            padding: 1.5rem 3.5rem !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            text-align: center !important;
            transition: transform 0.2s ease, filter 0.2s ease !important;
            filter: drop-shadow(0 8px 22px rgba(245, 158, 11, 0.32));
        }
        .flow-node-D:hover {
            transform: translateY(-2px) scale(1.01) !important;
            filter: drop-shadow(0 12px 28px rgba(245, 158, 11, 0.42));
        }
        .flow-diamond-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: visible;
        }
        .flow-diamond-polygon {
            fill: #FFFDF7;
            stroke: #F59E0B;
            stroke-width: 2.8;
            stroke-linejoin: round;
            vector-effect: non-scaling-stroke;
            transition: all 0.2s ease;
        }
        .flow-node-D.is-correct {
            filter: drop-shadow(0 8px 22px rgba(16, 185, 129, 0.35)) !important;
        }
        .flow-node-D.is-correct .flow-diamond-polygon {
            fill: #F0FDF4 !important;
            stroke: #10B981 !important;
            stroke-width: 3.2 !important;
        }
        .flow-node-D.is-wrong {
            filter: drop-shadow(0 8px 22px rgba(244, 63, 94, 0.35)) !important;
        }
        .flow-node-D.is-wrong .flow-diamond-polygon {
            fill: #FFF1F2 !important;
            stroke: #F43F5E !important;
            stroke-width: 3.2 !important;
        }
        .flow-node-D.is-placeholder .flow-diamond-polygon {
            fill: #FFFBEB !important;
            stroke: #FCD34D !important;
            stroke-dasharray: 6 4 !important;
        }
        .flow-diamond-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
            max-width: 82%;
        }

        /* 4. Proses: Persegi Panjang Biru Rapi ANSI/ISO */
        .flow-node-P {
            background: linear-gradient(180deg, #F0F9FF 0%, #E0F2FE 100%) !important;
            border: 2.5px solid #0284C7 !important;
            color: #0369A1 !important;
            border-radius: 10px !important;
            padding: 0.95rem 2.5rem !important;
            font-weight: 800 !important;
            box-shadow: 0 8px 22px -3px rgba(2, 132, 199, 0.25) !important;
            display: inline-flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 250px !important;
            max-width: 92% !important;
            text-align: center !important;
            transition: all 0.2s ease !important;
        }
        .flow-node-P:hover {
            transform: translateY(-2px) scale(1.01) !important;
            box-shadow: 0 12px 26px -3px rgba(2, 132, 199, 0.35) !important;
        }

        /* 5. Belum Dipilih: Kartu Pilihan Cepat 4 Simbol */
        .flow-node-empty {
            background-color: #FFFFFF !important;
            border: 2.5px dashed #CBD5E1 !important;
            border-radius: 20px !important;
            padding: 1.25rem 1.5rem !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 280px !important;
            max-width: 480px !important;
            width: 100% !important;
            text-align: center !important;
            box-shadow: 0 4px 12px -2px rgba(148, 163, 184, 0.15) !important;
            transition: all 0.2s ease !important;
        }
        .flow-node-empty:hover {
            border-color: #F59E0B !important;
            background-color: #FFFDF7 !important;
        }

        /* Vertical Flow Arrow Connector (Tanda Panah Tegas seperti Gambar 2) */
        .flow-connector-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 32px;
            position: relative;
            user-select: none;
        }
        .flow-connector-line {
            width: 2.5px;
            height: 20px;
            background-color: #334155;
        }
        .flow-connector-arrow {
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 8px solid #334155;
            margin-top: -1px;
        }

        /* Branching Flowchart Layout (Splitter & Merge seperti Gambar 2) */
        .branch-splitter-wrapper {
            width: 100%;
            max-width: 580px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            user-select: none;
            margin-top: 2px;
            margin-bottom: 8px;
        }

        .branch-crossbar-row {
            width: 100%;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0 12%;
        }

        .branch-crossbar-line {
            position: absolute;
            top: 0;
            left: 25%;
            right: 25%;
            height: 2.5px;
            background-color: #334155;
        }

        .branch-arm {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            top: -12px;
        }

        .branch-merge-wrapper {
            width: 100%;
            max-width: 580px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            user-select: none;
            margin-top: 8px;
            margin-bottom: 2px;
        }

        .branch-merge-row {
            width: 100%;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 12%;
            height: 20px;
        }

        .branch-merge-line {
            position: absolute;
            bottom: 0;
            left: 25%;
            right: 25%;
            height: 2.5px;
            background-color: #334155;
        }

        .branch-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
            width: 100%;
            max-width: 660px;
        }

        @media (min-width: 640px) {
            .branch-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-[#F8BA15] text-slate-900 flex flex-col antialiased selection:bg-amber-200 selection:text-amber-900">

    <!-- ================= 1. HEADER KUIS ================= -->
    <header class="w-full bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 shadow-xs transition-all">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Left: Logo & Brand Name -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-[#FFF9ED] border-2 border-[#FBD688] flex items-center justify-center text-amber-500 shadow-2xs group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="6" />
                        <path d="M7 12h10" />
                        <path d="M12 8v8" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-[17px] text-slate-900 tracking-tight leading-snug">Flowchart Lab</span>
                    <span class="font-bold text-[10px] tracking-wider text-[#EA580C] uppercase leading-none mt-0.5">SMK ALGORITMA VISUAL</span>
                </div>
            </a>

            <!-- Center Info: Progress indicator -->
            <div class="hidden sm:flex items-center gap-2 bg-slate-100 px-4 py-1.5 rounded-full border border-slate-200/80">
                <span class="text-xs font-bold text-slate-600">Progres Kuis:</span>
                <span class="text-xs font-black text-slate-900">Tugas {{ $taskNumber }} dari {{ $totalTasks }}</span>
                <div class="flex items-center gap-1.5 ml-2">
                    @for($i = 1; $i <= $totalTasks; $i++)
                        <span class="w-2.5 h-2.5 rounded-full {{ $i < $taskNumber ? 'bg-emerald-500' : ($i === $taskNumber ? 'bg-amber-500 ring-2 ring-amber-300' : 'bg-slate-300') }}"></span>
                    @endfor
                </div>
            </div>

            <!-- Right: Exit Button -->
            <form action="{{ route('quiz.keterampilan.reset') }}" method="POST" onsubmit="return confirm('Keluar dari kuis? Sesi kuis keterampilan yang sedang berjalan akan direset.')">
                @csrf
                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-4 py-2 rounded-full border border-rose-200/60 transition cursor-pointer">
                    Keluar Kuis
                </button>
            </form>
        </div>
    </header>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-6">

            <!-- ================= 2. KARTU JUDUL & STATUS ================= -->
            <div class="bg-white rounded-[28px] sm:rounded-[34px] p-6 sm:p-8 shadow-xl shadow-amber-950/10 border border-white">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    
                    <!-- Left: Titles & Badges -->
                    <div class="space-y-2.5">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="bg-[#059669] text-white font-black text-[10px] sm:text-[11px] uppercase tracking-wider px-3.5 py-1 rounded-full shadow-xs">
                                KUIS KETERAMPILAN
                            </span>
                            <span class="bg-[#FFF1F2] border border-[#FECDD3] text-[#E11D48] font-black text-[10px] sm:text-[11px] uppercase tracking-wider px-3.5 py-1 rounded-full">
                                PRAKTIK ALUR DIAGRAM
                            </span>
                            <span class="bg-amber-100 text-[#92400E] font-extrabold text-[10px] sm:text-[11px] px-3 py-1 rounded-full">
                                STANDAR ANSI / ISO 5807
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl lg:text-[34px] font-black text-slate-900 tracking-tight leading-tight">
                            {{ $currentTask['judul'] }}
                        </h1>

                        <p class="text-slate-600 font-medium text-xs sm:text-sm max-w-2xl leading-relaxed">
                            Rakit blok logika ke dalam kanvas alur secara terstruktur. Pilih bentuk simbol yang tepat untuk setiap instruksi algoritma.
                        </p>
                    </div>

                    <!-- Right: 3 Summary Status Boxes -->
                    <div class="grid grid-cols-3 gap-2.5 sm:gap-3 shrink-0">
                        <!-- Box 1: Tugas Ke- -->
                        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3 sm:p-3.5 text-center min-w-[90px]">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Tugas</span>
                            <span class="text-base sm:text-lg font-black text-slate-900 mt-0.5 block">
                                {{ sprintf('%02d', $taskNumber) }} <span class="text-xs text-slate-400 font-bold">/ {{ sprintf('%02d', $totalTasks) }}</span>
                            </span>
                        </div>

                        <!-- Box 2: Skor Sementara -->
                        <div class="bg-emerald-50/80 border border-emerald-200 rounded-2xl p-3 sm:p-3.5 text-center min-w-[90px]">
                            <span class="text-[10px] font-black text-emerald-800 uppercase tracking-wider block">Skor Smt.</span>
                            <span class="text-base sm:text-lg font-black text-emerald-600 mt-0.5 block">
                                {{ $skorSementara }} <span class="text-xs text-emerald-700/60 font-bold">Poin</span>
                            </span>
                        </div>

                        <!-- Box 3: Waktu Berjalan (UI Timer Display) -->
                        <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-3 sm:p-3.5 text-center min-w-[90px]">
                            <span class="text-[10px] font-black text-amber-800 uppercase tracking-wider block">Waktu</span>
                            <span id="quiz-timer-display" class="text-base sm:text-lg font-black text-amber-900 mt-0.5 block font-mono">
                                00:00
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ================= 3. PETA TUGAS (3 KARTU HORIZONTAL) ================= -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                @foreach($allTasks as $idx => $t)
                    @php
                        $isCompleted = ($allResults[$idx] ?? null) !== null;
                        $isActive = ($idx === $currentIndex);
                        $isLocked = (! $isCompleted && ! $isActive);
                        $tScore = $allResults[$idx]['skor'] ?? null;
                    @endphp

                    <div class="rounded-2xl p-4 transition-all {{ $isActive ? 'bg-white border-2 border-slate-900 shadow-lg shadow-slate-900/10' : ($isCompleted ? 'bg-white/95 border border-emerald-200 shadow-sm' : 'bg-white/60 border border-white/50 opacity-70') }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $isActive ? 'bg-slate-900 text-white' : ($isCompleted ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600') }}">
                                {{ $t['kode'] }} • Level {{ $t['level'] ?? ($idx + 1) }}
                            </span>

                            @if($isCompleted)
                                <span class="bg-emerald-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full flex items-center gap-1">
                                    ✓ {{ $tScore }} Poin
                                </span>
                            @elseif($isActive)
                                <span class="bg-amber-400 text-slate-950 text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    FOKUS AKTIF
                                </span>
                            @else
                                <span class="text-slate-400 text-xs font-bold flex items-center gap-1">
                                    🔒 Terkunci
                                </span>
                            @endif
                        </div>

                        <div class="text-xs sm:text-[13px] font-black text-slate-900 truncate">
                            {{ $t['judul'] }}
                        </div>
                        <div class="text-[11px] text-slate-500 mt-0.5">
                            {{ $t['nama_level'] ?? 'Tingkat ' . ($idx + 1) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- ================= 4. KARTU SOAL ================= -->
            <div class="bg-white rounded-[28px] p-6 shadow-xl shadow-amber-950/5 border border-white space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2.5 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="bg-[#059669] text-white font-black text-[10px] uppercase tracking-wider px-3 py-1 rounded-full">
                            LEVEL {{ $currentTask['level'] ?? 1 }} · {{ strtoupper($currentTask['nama_level'] ?? 'MUDAH') }}
                        </span>
                        <span class="bg-slate-100 text-slate-800 font-extrabold text-[10px] uppercase tracking-wider px-3 py-1 rounded-full">
                            KODE: {{ $currentTask['kode'] }}
                        </span>
                    </div>

                    <span class="bg-slate-100 text-slate-600 font-bold text-[11px] px-3 py-1 rounded-full">
                        Mode Uji Mandiri: Tanpa Bantuan
                    </span>
                </div>

                <div>
                    <h2 class="text-xl font-black text-slate-900">
                        {{ $currentTask['judul'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed mt-2">
                        {{ $currentTask['soal'] }}
                    </p>
                </div>

                <!-- Yellow notice box -->
                <div class="p-3.5 rounded-2xl bg-[#FFF9EC] border border-[#FDE68A] text-[#92400E] text-xs font-bold flex items-center gap-2.5">
                    <span class="text-base shrink-0">⚠️</span>
                    <span>Tantangan Praktik: tidak tersedia petunjuk otomatis atau hint pada kuis ini. Analisis logika algoritma dan pilih simbol yang tepat secara mandiri.</span>
                </div>
            </div>

            <!-- ================= 5. DUA KOLOM: DAFTAR LANGKAH & KANVAS ================= -->
            <div class="quiz-workspace-grid">

                <!-- ================= KOLOM KIRI: DAFTAR LANGKAH SOAL ================= -->
                <div class="bg-white rounded-[28px] p-5 sm:p-6 shadow-xl shadow-amber-950/5 border border-white space-y-4 min-w-0">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="font-black text-slate-900 text-sm">Daftar Langkah Soal</h3>
                            <p class="text-[11px] text-slate-500">Klik tombol (+) untuk memasang ke kanvas</p>
                        </div>
                        <span class="bg-slate-100 text-slate-800 text-[10px] font-black px-3 py-1 rounded-full">
                            {{ count($shuffledSteps) }} Langkah
                        </span>
                    </div>

                    <!-- Steps Pool List -->
                    <div id="step-pool-list" class="space-y-2.5">
                        @foreach($shuffledSteps as $index => $step)
                            @php
                                $teksStep = $step['teks'];
                                $cleanDisplayTeks = ucfirst(preg_replace('/^(ya|tidak)\s*:\s*/i', '', $teksStep));
                                $isInstalled = false;
                                if ($isSubmitted && $result) {
                                    foreach ($savedAnswer as $ans) {
                                        $ansClean = ucfirst(preg_replace('/^(ya|tidak)\s*:\s*/i', '', $ans['teks'] ?? ''));
                                        if (($ans['teks'] ?? '') === $teksStep || $ansClean === $cleanDisplayTeks) {
                                            $isInstalled = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            <div id="pool-item-{{ $index }}"
                                 data-step-index="{{ $index }}"
                                 data-step-text="{{ $teksStep }}"
                                 data-clean-text="{{ $cleanDisplayTeks }}"
                                 onclick="{{ $isSubmitted ? '' : 'installStep(' . $index . ')' }}"
                                 class="p-3.5 rounded-2xl border border-slate-200/90 bg-slate-50/70 transition select-none flex items-center justify-between gap-3 {{ $isSubmitted ? 'opacity-40 cursor-not-allowed' : 'hover:border-amber-400 hover:bg-amber-50/50 cursor-pointer group shadow-2xs hover:-translate-y-0.5' }} {{ $isInstalled ? 'opacity-35 pointer-events-none bg-slate-100 text-slate-400' : '' }}">
                                 
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <span class="w-6 h-6 rounded-lg bg-white border border-slate-200 text-slate-700 text-[11px] font-black flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ $loop->iteration }}
                                    </span>
                                    <span class="text-xs sm:text-[13px] font-bold text-slate-800 leading-snug break-words min-w-0">
                                        {{ $cleanDisplayTeks }}
                                    </span>
                                </div>

                                @if(! $isSubmitted)
                                    <button type="button" 
                                            aria-label="Pasang langkah: {{ $cleanDisplayTeks }}"
                                            class="w-7 h-7 rounded-full bg-emerald-100 hover:bg-emerald-200 text-emerald-700 flex items-center justify-center font-black text-sm shrink-0 transition group-hover:scale-105 shadow-2xs">
                                        +
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Kriteria Penilaian Card -->
                    <div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="text-xs font-black text-slate-900 flex items-center gap-1.5">
                            <span>📋</span>
                            <span>Kriteria Penilaian:</span>
                        </div>
                        <ul class="text-[11px] text-slate-600 space-y-1.5 leading-relaxed font-medium">
                            <li class="flex items-start gap-1.5">
                                <span class="text-emerald-600 font-bold">•</span>
                                <span><strong>Lengkap:</strong> Seluruh {{ count($shuffledSteps) }} langkah terpasang tanpa tertinggal.</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="text-emerald-600 font-bold">•</span>
                                <span><strong>Simbol Tepat:</strong> Pemilihan simbol sesuai standar ANSI/ISO (bobot 50%).</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="text-emerald-600 font-bold">•</span>
                                <span><strong>Urutan Tepat:</strong> Alur sekuensial tidak menurun (bobot 50%).</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- ================= KOLOM KANAN: KANVAS PERAKITAN ALUR ================= -->
                <div class="bg-white rounded-[28px] p-5 sm:p-6 shadow-xl shadow-amber-950/5 border border-white space-y-4 min-w-0">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="font-black text-slate-900 text-sm">Kanvas Perakitan Alur Diagram</h3>
                            <p class="text-[11px] text-slate-500">Pilih bentuk simbol dan tata urutan langkah dari atas ke bawah</p>
                        </div>

                        @if(! $isSubmitted)
                            <button type="button" onclick="clearCanvas()" aria-label="Reset Susunan Kanvas" class="text-[11px] font-bold text-slate-500 hover:text-rose-600 bg-slate-100 hover:bg-rose-50 px-3 py-1.5 rounded-full border border-slate-200 transition cursor-pointer flex items-center gap-1">
                                <span>🔄</span>
                                <span>Reset Susunan</span>
                            </button>
                        @endif
                    </div>

                    <!-- Canvas Area -->
                    <div id="canvas-container" class="min-h-[380px] p-4 sm:p-6 rounded-3xl bg-[#FFFDF7] border-2 border-dashed border-[#FBD688] flex flex-col items-center justify-start space-y-1.5">
                        
                        @if($isSubmitted && $result)
                            <!-- Submitted Render with Real Geometric Flowchart Shapes -->
                            @if(empty($result['evaluasi_langkah']))
                                <div class="py-16 text-center text-xs text-slate-400 font-medium">
                                    Tidak ada langkah yang terpasang pada kanvas saat dikirim.
                                </div>
                            @else
                                @php
                                    $renderEvalNode = function($eval, $idx) {
                                        $sym = $eval['simbol_siswa'];
                                        $isWrong = $eval['is_salah'] ?? false;
                                        $stepNumStr = sprintf('%02d', $idx + 1);
                                        $cleanText = html_entity_decode(html_entity_decode($eval['teks'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                        $displayClean = ucfirst(preg_replace('/^(ya|tidak)\s*:\s*/i', '', $cleanText));
                                        $escapedText = e($displayClean);
                                        $kunciName = e($eval['nama_simbol_kunci'] ?? '');

                                        $statusPill = $isWrong
                                            ? '<span class="px-3 py-1 rounded-full bg-rose-100 border border-rose-300 text-rose-900 text-[11px] font-black shadow-xs flex items-center gap-1.5"><span>✕</span><span>Keliru: Seharusnya <strong>'.$kunciName.'</strong></span></span>'
                                            : '<span class="px-3 py-1 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-900 text-[11px] font-black shadow-xs flex items-center gap-1.5"><span>✓</span><span>Simbol Tepat: <strong>'.$kunciName.'</strong></span></span>';

                                        $nodeHtml = '';
                                        if ($sym === 'T') {
                                            $nodeHtml = '<div class="flow-node-T '.($isWrong ? 'ring-4 ring-rose-400 !border-rose-600' : 'ring-4 ring-emerald-400 !border-emerald-600').'"><span class="text-sm sm:text-base font-black text-slate-950">'.$escapedText.'</span></div>';
                                        } elseif ($sym === 'IO') {
                                            $nodeHtml = '<div class="flow-node-IO '.($isWrong ? 'ring-4 ring-rose-400 !border-rose-500' : 'ring-4 ring-emerald-400 !border-emerald-500').'"><div class="flow-unskew"><span class="text-amber-400 text-sm font-black shrink-0">[→]</span><span class="text-sm sm:text-base font-black text-white">'.$escapedText.'</span></div></div>';
                                        } elseif ($sym === 'D') {
                                            $stateClass = $isWrong ? 'is-wrong' : 'is-correct';
                                            $nodeHtml = '
                                                <div class="flow-node-D '.$stateClass.'">
                                                    <svg class="flow-diamond-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                                        <polygon class="flow-diamond-polygon" points="50,3 97,50 50,97 3,50" />
                                                    </svg>
                                                    <div class="flow-diamond-content">
                                                        <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-[10px] font-black mb-1.5 shadow-2xs">
                                                            <span>◇</span>
                                                            <span>Simbol Keputusan (Kondisi Logika)</span>
                                                        </div>
                                                        <div class="text-xs sm:text-[14px] font-black text-slate-900 px-2 leading-snug">
                                                            '.$escapedText.'
                                                        </div>
                                                    </div>
                                                </div>
                                            ';
                                        } elseif ($sym === 'P') {
                                            $nodeHtml = '<div class="flow-node-P '.($isWrong ? 'ring-4 ring-rose-400 !border-rose-500' : 'ring-4 ring-emerald-400 !border-emerald-500').'"><div class="text-[10px] font-black text-sky-700 uppercase tracking-wider mb-1">▭ Proses Logika</div><span class="text-sm sm:text-base font-black text-sky-950">'.$escapedText.'</span></div>';
                                        } else {
                                            $nodeHtml = '<div class="flow-node-empty ring-4 ring-rose-400 !border-rose-400"><span class="text-xs font-black text-rose-600 mb-1">⚠️ Belum Pilih Simbol</span><span class="text-sm font-bold text-slate-800">'.$escapedText.'</span></div>';
                                        }

                                        return '
                                            <div class="flow-item-wrapper w-full flex flex-col items-center">
                                                <div class="flex items-center gap-2 mb-2 z-10">
                                                    <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-[11px] font-black flex items-center justify-center shadow-xs">'.$stepNumStr.'</span>
                                                    '.$statusPill.'
                                                </div>
                                                '.$nodeHtml.'
                                            </div>
                                        ';
                                    };

                                    $hasBranching = false;
                                    $dEval = null;
                                    $dIndex = null;
                                    $yaEval = null;
                                    $yaIndex = null;
                                    $tidakEval = null;
                                    $tidakIndex = null;
                                    $preEvals = [];
                                    $postEvals = [];

                                    foreach($result['evaluasi_langkah'] as $idx => $eval) {
                                        $t = strtolower($eval['teks'] ?? '');
                                        if (str_starts_with($t, 'ya:') || str_starts_with($t, 'ya :')) {
                                            $hasBranching = true;
                                            $yaEval = $eval;
                                            $yaIndex = $idx;
                                        } elseif (str_starts_with($t, 'tidak:') || str_starts_with($t, 'tidak :')) {
                                            $hasBranching = true;
                                            $tidakEval = $eval;
                                            $tidakIndex = $idx;
                                        } elseif (str_starts_with($t, 'apakah') || str_contains($t, '?') || ($eval['simbol_kunci'] ?? '') === 'D') {
                                            if ($dEval === null) {
                                                $dEval = $eval;
                                                $dIndex = $idx;
                                            }
                                        }
                                    }

                                    if ($hasBranching) {
                                        foreach($result['evaluasi_langkah'] as $idx => $eval) {
                                            if ($idx === $dIndex || $idx === $yaIndex || $idx === $tidakIndex) {
                                                continue;
                                            }
                                            if ($dIndex !== null && $idx < $dIndex) {
                                                $preEvals[] = ['eval' => $eval, 'idx' => $idx];
                                            } else {
                                                $postEvals[] = ['eval' => $eval, 'idx' => $idx];
                                            }
                                        }
                                    }
                                @endphp

                                @if($hasBranching)
                                    {{-- 1. Pre-decision Steps --}}
                                    @foreach($preEvals as $item)
                                        {!! $renderEvalNode($item['eval'], $item['idx']) !!}
                                        <div class="flow-connector-wrapper">
                                            <div class="flow-connector-line"></div>
                                            <div class="flow-connector-arrow"></div>
                                        </div>
                                    @endforeach

                                    {{-- 2. Decision Step --}}
                                    @if($dEval !== null)
                                        {!! $renderEvalNode($dEval, $dIndex) !!}
                                    @endif

                                    {{-- 3. Branch Splitter Connector --}}
                                    <div class="branch-splitter-wrapper">
                                        <div class="flow-connector-line" style="height: 14px;"></div>
                                        <div class="branch-crossbar-row">
                                            <div class="branch-crossbar-line"></div>
                                            <div class="branch-arm">
                                                <span class="px-3.5 py-1 rounded-full bg-emerald-500 text-white font-black text-xs shadow-md border-2 border-white ring-2 ring-emerald-400">
                                                    Ya (True)
                                                </span>
                                                <div class="flow-connector-line" style="height: 14px; margin-top: 4px;"></div>
                                                <div class="flow-connector-arrow"></div>
                                            </div>
                                            <div class="branch-arm">
                                                <span class="px-3.5 py-1 rounded-full bg-rose-500 text-white font-black text-xs shadow-md border-2 border-white ring-2 ring-rose-400">
                                                    Tidak (False)
                                                </span>
                                                <div class="flow-connector-line" style="height: 14px; margin-top: 4px;"></div>
                                                <div class="flow-connector-arrow"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 4. 2-Column Branch Grid (Side-by-Side!) --}}
                                    <div class="branch-grid my-2">
                                        <div class="flex flex-col items-center justify-start w-full">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 mb-2 rounded-full bg-emerald-100 text-emerald-900 border border-emerald-300 text-[10px] font-black uppercase tracking-wider shadow-2xs">
                                                <span>OUTPUT BENAR (YA)</span>
                                            </div>
                                            @if($yaEval !== null)
                                                {!! $renderEvalNode($yaEval, $yaIndex) !!}
                                            @else
                                                <div class="w-full p-4 rounded-2xl border-2 border-dashed border-rose-300 bg-rose-50 text-center text-xs font-bold text-rose-700">
                                                    Langkah cabang Ya tidak terpasang
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col items-center justify-start w-full">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 mb-2 rounded-full bg-rose-100 text-rose-900 border border-rose-300 text-[10px] font-black uppercase tracking-wider shadow-2xs">
                                                <span>OUTPUT LAIN (TIDAK)</span>
                                            </div>
                                            @if($tidakEval !== null)
                                                {!! $renderEvalNode($tidakEval, $tidakIndex) !!}
                                            @else
                                                <div class="w-full p-4 rounded-2xl border-2 border-dashed border-rose-300 bg-rose-50 text-center text-xs font-bold text-rose-700">
                                                    Langkah cabang Tidak tidak terpasang
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- 5. Branch Merge Connector --}}
                                    <div class="branch-merge-wrapper">
                                        <div class="branch-merge-row">
                                            <div class="flow-connector-line" style="height: 20px;"></div>
                                            <div class="flow-connector-line" style="height: 20px;"></div>
                                            <div class="branch-merge-line"></div>
                                        </div>
                                        <div class="flow-connector-line" style="height: 16px;"></div>
                                        <div class="flow-connector-arrow"></div>
                                    </div>

                                    {{-- 6. Post-branch Steps (e.g. Selesai) --}}
                                    @foreach($postEvals as $pIdx => $item)
                                        {!! $renderEvalNode($item['eval'], $item['idx']) !!}
                                        @if(! $loop->last)
                                            <div class="flow-connector-wrapper">
                                                <div class="flow-connector-line"></div>
                                                <div class="flow-connector-arrow"></div>
                                            </div>
                                        @endif
                                    @endforeach

                                @else
                                    {{-- Linear Flowchart (e.g. Level 1 Tasks) --}}
                                    @foreach($result['evaluasi_langkah'] as $idx => $eval)
                                        {!! $renderEvalNode($eval, $idx) !!}
                                        @if(! $loop->last)
                                            <div class="flow-connector-wrapper">
                                                <div class="flow-connector-line"></div>
                                                <div class="flow-connector-arrow"></div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endif

                        @else
                            <!-- Empty Canvas State -->
                            <div id="canvas-empty-state" class="py-20 text-center text-slate-400">
                                <div class="text-4xl mb-2">🎯</div>
                                <div class="text-sm font-extrabold text-slate-700">Kanvas Perakitan Alur Masih Kosong</div>
                                <div class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                                    Klik tombol <span class="font-bold text-emerald-700">(+)</span> pada daftar langkah di sebelah kiri untuk memasangnya ke diagram alir.
                                </div>
                            </div>

                            <!-- Dynamic Blocks Wrapper -->
                            <div id="canvas-blocks-wrapper" class="w-full flex flex-col items-center space-y-1.5 hidden"></div>
                        @endif

                    </div>

                    <!-- Hidden Form for Submission -->
                    @if(! $isSubmitted)
                        <form id="quiz-submit-form" action="{{ route('quiz.keterampilan.submit') }}" method="POST">
                            @csrf
                            <div id="hidden-inputs-wrapper"></div>
                        </form>
                    @endif
                </div>

            </div>

            <!-- ================= 6. KOTAK SOLUSI TEPAT & ANALISIS ALUR (PASCA-KIRIM) ================= -->
            @if($isSubmitted && $result)
                <div id="solusi-section" class="bg-white rounded-[28px] p-6 sm:p-8 shadow-xl shadow-amber-950/5 border border-white space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                        <div>
                            <span class="bg-[#059669] text-white font-black text-[10px] sm:text-[11px] uppercase tracking-wider px-3.5 py-1 rounded-full shadow-xs">
                                KOTAK SOLUSI TEPAT &amp; ANALISIS ALUR
                            </span>
                            <h2 class="text-2xl font-black text-slate-900 mt-2">
                                Pembahasan Tugas {{ $taskNumber }}: {{ $currentTask['judul'] }}
                            </h2>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 text-right">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Skor Tugas</span>
                            <span class="text-2xl font-black {{ $result['skor'] >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $result['skor'] }} <span class="text-xs text-slate-400 font-bold">/ 100</span>
                            </span>
                        </div>
                    </div>

                    <!-- Koreksi Evaluasi -->
                    <div class="p-4 rounded-2xl {{ $result['is_semua_benar'] ? 'bg-emerald-50/80 border border-emerald-200' : 'bg-rose-50/80 border border-rose-200' }} space-y-2">
                        <div class="text-xs font-black {{ $result['is_semua_benar'] ? 'text-emerald-800' : 'text-rose-800' }}">
                            {{ $result['is_semua_benar'] ? '🎉 Catatan Penilaian: Sempurna' : '⚠️ Catatan Evaluasi Jawaban:' }}
                        </div>
                        <ul class="space-y-1.5 text-xs sm:text-[13px] font-semibold {{ $result['is_semua_benar'] ? 'text-emerald-700' : 'text-rose-700' }}">
                            @foreach($result['pesan'] as $pesan)
                                <li class="flex items-start gap-2">
                                    <span>{{ $result['is_semua_benar'] ? '✓' : '•' }}</span>
                                    <span>{{ $pesan }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Kunci Urutan Alur Sebagai Rangkaian Chip Berpanah -->
                    <div class="space-y-3 pt-2">
                        <div class="text-xs font-extrabold text-slate-900">
                            Urutan Alur Standar Kunci Jawaban:
                        </div>

                        <div class="flex flex-wrap items-center gap-2.5 p-4 rounded-2xl bg-[#FFFDF7] border border-[#FDE6B7]">
                            @foreach($currentTask['langkah'] as $kIdx => $kLangkah)
                                @php
                                    $kSym = $kLangkah['simbol'];
                                    $kBadgeClass = 'bg-slate-800 text-white';
                                    if ($kSym === 'D') {
                                        $kBadgeClass = 'bg-amber-500 text-slate-950 font-black'; // Keputusan oranye mencolok
                                    } elseif ($kSym === 'IO') {
                                        $kBadgeClass = 'bg-sky-100 text-sky-800 font-bold border border-sky-300';
                                    } elseif ($kSym === 'T') {
                                        $kBadgeClass = 'bg-slate-900 text-white font-bold';
                                    } else {
                                        $kBadgeClass = 'bg-slate-200 text-slate-800 font-bold';
                                    }
                                @endphp

                                <div class="inline-flex items-center gap-2 p-2 px-3 rounded-xl bg-white border border-slate-200 shadow-2xs">
                                    <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-black flex items-center justify-center">
                                        {{ $kIdx + 1 }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-800">
                                        {{ $kLangkah['teks'] }}
                                    </span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-md {{ $kBadgeClass }}">
                                        {{ \App\Services\KeterampilanChecker::SIMBOL_NAMES[$kSym] ?? $kSym }}
                                    </span>
                                </div>

                                @if(! $loop->last)
                                    <span class="text-slate-400 font-black text-sm select-none">→</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- ================= 7. BILAH AKSI BAWAH ================= -->
            <div class="bg-white rounded-[24px] p-4 sm:p-5 shadow-xl shadow-amber-950/10 border border-white flex flex-col sm:flex-row items-center justify-between gap-4">
                
                @if(! $isSubmitted)
                    <!-- Pre-submission Actions -->
                    <div class="text-xs sm:text-sm font-bold text-slate-600">
                        <span id="canvas-count-label" class="text-slate-900 font-black text-base">0</span> dari {{ count($shuffledSteps) }} langkah terpasang
                    </div>

                    <button type="button" onclick="submitCanvasForm()" aria-label="Kumpulkan Jawaban Tugas Ini" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white font-black text-sm py-4 px-9 rounded-full shadow-lg shadow-slate-900/25 transition cursor-pointer hover:scale-[1.02]">
                        Kumpulkan Jawaban →
                    </button>
                @else
                    <!-- Post-submission Actions -->
                    <a href="#solusi-section" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs sm:text-sm py-3 px-6 rounded-full text-center transition">
                        🔍 Cek Evaluasi &amp; Pembahasan
                    </a>

                    <form action="{{ route('quiz.keterampilan.next') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-[#10B981] hover:bg-[#059669] text-white font-black text-sm py-3.5 px-8 rounded-full shadow-lg shadow-emerald-600/20 text-center transition cursor-pointer hover:scale-[1.02]">
                            {{ $isLastTask ? 'Lanjut ke Hasil Akhir Kuis →' : 'Tugas Berikutnya (' . ($taskNumber + 1) . '/' . $totalTasks . ') →' }}
                        </button>
                    </form>
                @endif

            </div>

        </div>
    </main>

    <!-- UI Scripts -->
    <script>
        // 1. Stopwatch Timer Display
        let secondsPassed = 0;
        const timerEl = document.getElementById('quiz-timer-display');
        if (timerEl) {
            setInterval(() => {
                secondsPassed++;
                const mins = String(Math.floor(secondsPassed / 60)).padStart(2, '0');
                const secs = String(secondsPassed % 60).padStart(2, '0');
                timerEl.innerText = `${mins}:${secs}`;
            }, 1000);
        }
    </script>

    @if(! $isSubmitted)
        <script>
            // Enriched pool steps from server (clean actions without giveaway prefixes)
            const rawPoolSteps = [
                @foreach($shuffledSteps as $sIdx => $s)
                    @php
                        $rawTeks = $s['teks'];
                        $cleanTeks = ucfirst(preg_replace('/^(ya|tidak)\s*:\s*/i', '', $rawTeks));
                        $actionTeks = preg_replace('/^(ya|tidak)\s*:\s*/i', '', $rawTeks);
                        $isBranchCandidate = (bool) preg_match('/^(ya|tidak)\s*:\s*/i', $rawTeks);
                    @endphp
                    {
                        poolIndex: {{ $sIdx }},
                        rawTeks: @json($rawTeks),
                        cleanTeks: @json($cleanTeks),
                        actionTeks: @json($actionTeks),
                        isBranch: {{ $isBranchCandidate ? 'true' : 'false' }}
                    },
                @endforeach
            ];

            const taskHasBranching = {{ (collect($currentTask['langkah'])->contains(fn($l) => str_starts_with(strtolower($l['teks']), 'ya:') || str_starts_with(strtolower($l['teks']), 'tidak:'))) ? 'true' : 'false' }};

            // Master canvas state
            let linearSteps = [];
            let branchYaStep = null;    // { poolIndex, cleanTeks, actionTeks, rawTeks, simbol }
            let branchTidakStep = null; // { poolIndex, cleanTeks, actionTeks, rawTeks, simbol }

            function dimPoolItem(poolIndex) {
                const el = document.getElementById('pool-item-' + poolIndex);
                if (el) el.classList.add('opacity-35', 'pointer-events-none', 'bg-slate-100', 'text-slate-400');
            }

            function unDimPoolItem(poolIndex) {
                const el = document.getElementById('pool-item-' + poolIndex);
                if (el) el.classList.remove('opacity-35', 'pointer-events-none', 'bg-slate-100', 'text-slate-400');
            }

            function isDecisionStep(step) {
                if (! step) return false;
                const t = (step.rawTeks || step.teks || '').toLowerCase();
                return step.simbol === 'D' || t.startsWith('apakah') || t.includes('?');
            }

            function installStep(poolIndex) {
                const poolItem = document.getElementById('pool-item-' + poolIndex);
                if (! poolItem || poolItem.classList.contains('pointer-events-none')) {
                    return;
                }

                const stepObj = rawPoolSteps[poolIndex];
                if (! stepObj) return;

                if (taskHasBranching && stepObj.isBranch) {
                    // Branch candidate step: student decides where to place it
                    if (branchYaStep === null) {
                        branchYaStep = {
                            poolIndex: poolIndex,
                            rawTeks: stepObj.rawTeks,
                            cleanTeks: stepObj.cleanTeks,
                            actionTeks: stepObj.actionTeks,
                            simbol: 'IO' // default for branch action
                        };
                    } else if (branchTidakStep === null) {
                        branchTidakStep = {
                            poolIndex: poolIndex,
                            rawTeks: stepObj.rawTeks,
                            cleanTeks: stepObj.cleanTeks,
                            actionTeks: stepObj.actionTeks,
                            simbol: 'IO'
                        };
                    } else {
                        // Both filled: replace Ya and return old Ya to pool
                        unDimPoolItem(branchYaStep.poolIndex);
                        branchYaStep = {
                            poolIndex: poolIndex,
                            rawTeks: stepObj.rawTeks,
                            cleanTeks: stepObj.cleanTeks,
                            actionTeks: stepObj.actionTeks,
                            simbol: 'IO'
                        };
                    }
                } else {
                    linearSteps.push({
                        poolIndex: poolIndex,
                        teks: stepObj.rawTeks,
                        cleanTeks: stepObj.cleanTeks,
                        simbol: ''
                    });
                }

                dimPoolItem(poolIndex);
                renderCanvas();
            }

            function installToBranch(targetBranch) {
                const available = rawPoolSteps.filter(s => {
                    if (! s.isBranch) return false;
                    const el = document.getElementById('pool-item-' + s.poolIndex);
                    return el && ! el.classList.contains('pointer-events-none');
                });

                if (available.length === 0) return;
                const chosen = available[0];

                dimPoolItem(chosen.poolIndex);

                if (targetBranch === 'ya') {
                    if (branchYaStep) unDimPoolItem(branchYaStep.poolIndex);
                    branchYaStep = {
                        poolIndex: chosen.poolIndex,
                        rawTeks: chosen.rawTeks,
                        cleanTeks: chosen.cleanTeks,
                        actionTeks: chosen.actionTeks,
                        simbol: 'IO'
                    };
                } else {
                    if (branchTidakStep) unDimPoolItem(branchTidakStep.poolIndex);
                    branchTidakStep = {
                        poolIndex: chosen.poolIndex,
                        rawTeks: chosen.rawTeks,
                        cleanTeks: chosen.cleanTeks,
                        actionTeks: chosen.actionTeks,
                        simbol: 'IO'
                    };
                }

                renderCanvas();
            }

            function swapBranches() {
                const temp = branchYaStep;
                branchYaStep = branchTidakStep;
                branchTidakStep = temp;
                renderCanvas();
            }

            function moveBranchStep(fromBranch) {
                if (fromBranch === 'ya' && branchYaStep) {
                    const temp = branchTidakStep;
                    branchTidakStep = branchYaStep;
                    branchYaStep = temp;
                } else if (fromBranch === 'tidak' && branchTidakStep) {
                    const temp = branchYaStep;
                    branchYaStep = branchTidakStep;
                    branchTidakStep = temp;
                }
                renderCanvas();
            }

            function removeBranchStep(branchName) {
                let removed = null;
                if (branchName === 'ya') {
                    removed = branchYaStep;
                    branchYaStep = null;
                } else {
                    removed = branchTidakStep;
                    branchTidakStep = null;
                }
                if (removed && removed.poolIndex !== undefined) {
                    unDimPoolItem(removed.poolIndex);
                }
                renderCanvas();
            }

            function updateBranchSymbol(branchName, symbolValue) {
                if (branchName === 'ya' && branchYaStep) {
                    branchYaStep.simbol = symbolValue;
                } else if (branchName === 'tidak' && branchTidakStep) {
                    branchTidakStep.simbol = symbolValue;
                }
                renderCanvas();
            }

            function removeLinearStep(idx) {
                if (idx < 0 || idx >= linearSteps.length) return;
                const removed = linearSteps.splice(idx, 1)[0];
                if (removed && removed.poolIndex !== undefined) {
                    unDimPoolItem(removed.poolIndex);
                }
                renderCanvas();
            }

            function moveLinearStepUp(idx) {
                if (idx <= 0) return;
                const temp = linearSteps[idx];
                linearSteps[idx] = linearSteps[idx - 1];
                linearSteps[idx - 1] = temp;
                renderCanvas();
            }

            function moveLinearStepDown(idx) {
                if (idx >= linearSteps.length - 1) return;
                const temp = linearSteps[idx];
                linearSteps[idx] = linearSteps[idx + 1];
                linearSteps[idx + 1] = temp;
                renderCanvas();
            }

            function updateLinearSymbol(idx, symbolValue) {
                if (idx < 0 || idx >= linearSteps.length) return;
                linearSteps[idx].simbol = symbolValue;
                renderCanvas();
            }

            function clearCanvas() {
                linearSteps.forEach(s => unDimPoolItem(s.poolIndex));
                if (branchYaStep) unDimPoolItem(branchYaStep.poolIndex);
                if (branchTidakStep) unDimPoolItem(branchTidakStep.poolIndex);
                linearSteps = [];
                branchYaStep = null;
                branchTidakStep = null;
                renderCanvas();
            }

            function renderLinearNodeHtml(step, idx) {
                const isFirst = (idx === 0);
                const isLast = (idx === linearSteps.length - 1);
                const stepNumStr = String(idx + 1).padStart(2, '0');
                const escapedText = escapeHtml(step.cleanTeks || step.teks);

                const upBtn = `<button type="button" onclick="moveLinearStepUp(${idx})" aria-label="Naikkan langkah ${stepNumStr}" ${isFirst ? 'disabled class="w-6 h-6 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center text-xs font-black opacity-40 cursor-not-allowed"' : 'class="w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-black transition cursor-pointer"'} title="Naikkan posisi">↑</button>`;
                const downBtn = `<button type="button" onclick="moveLinearStepDown(${idx})" aria-label="Turunkan langkah ${stepNumStr}" ${isLast ? 'disabled class="w-6 h-6 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center text-xs font-black opacity-40 cursor-not-allowed"' : 'class="w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-black transition cursor-pointer"'} title="Turunkan posisi">↓</button>`;
                const deleteBtn = `<button type="button" onclick="removeLinearStep(${idx})" aria-label="Hapus langkah ${stepNumStr}" class="w-6 h-6 rounded-full bg-rose-100 hover:bg-rose-200 text-rose-700 flex items-center justify-center text-xs font-black transition cursor-pointer shadow-2xs" title="Hapus dari kanvas">✕</button>`;

                const selectHtml = `
                    <div class="relative">
                        <select onchange="updateLinearSymbol(${idx}, this.value)" 
                                aria-label="Pilih simbol untuk langkah ${stepNumStr}"
                                class="font-black text-[11px] py-1 pl-2.5 pr-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer appearance-none">
                            <option value="" ${step.simbol === '' ? 'selected' : ''}>❓ Pilih Simbol...</option>
                            <option value="T" ${step.simbol === 'T' ? 'selected' : ''}>⬭ Terminator (Oval)</option>
                            <option value="IO" ${step.simbol === 'IO' ? 'selected' : ''}>▱ Input/Output (Jajar Genjang)</option>
                            <option value="P" ${step.simbol === 'P' ? 'selected' : ''}>▭ Proses (Persegi Panjang)</option>
                            <option value="D" ${step.simbol === 'D' ? 'selected' : ''}>◇ Keputusan (Percabangan)</option>
                        </select>
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-[9px] text-slate-500 font-bold">▼</span>
                    </div>
                `;

                const toolbarHtml = `
                    <div class="flex items-center gap-1.5 mb-2 bg-white/95 backdrop-blur-xs border border-slate-200/90 shadow-xs px-2.5 py-1 rounded-full text-xs z-10 transition">
                        <span class="w-5 h-5 rounded-full bg-slate-900 text-white text-[10px] font-black flex items-center justify-center shrink-0">
                            ${stepNumStr}
                        </span>
                        ${selectHtml}
                        <span class="w-px h-3.5 bg-slate-200"></span>
                        <div class="flex items-center gap-1">
                            ${upBtn}
                            ${downBtn}
                            ${deleteBtn}
                        </div>
                    </div>
                `;

                if (step.simbol === 'T') {
                    return `
                        <div class="flow-item-wrapper w-full flex flex-col items-center animate-in fade-in zoom-in-95 duration-150">
                            ${toolbarHtml}
                            <div class="flow-node-T">
                                <span class="text-sm sm:text-base font-black text-slate-950">${escapedText}</span>
                            </div>
                        </div>
                    `;
                } else if (step.simbol === 'IO') {
                    return `
                        <div class="flow-item-wrapper w-full flex flex-col items-center animate-in fade-in zoom-in-95 duration-150">
                            ${toolbarHtml}
                            <div class="flow-node-IO">
                                <div class="flow-unskew">
                                    <span class="text-amber-400 text-sm font-black shrink-0">[→]</span>
                                    <span class="text-sm sm:text-base font-black text-white">${escapedText}</span>
                                </div>
                            </div>
                        </div>
                    `;
                } else if (step.simbol === 'D') {
                    return `
                        <div class="flow-item-wrapper w-full flex flex-col items-center animate-in fade-in zoom-in-95 duration-150">
                            ${toolbarHtml}
                            <div class="flow-node-D">
                                <svg class="flow-diamond-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <polygon class="flow-diamond-polygon" points="50,3 97,50 50,97 3,50" />
                                </svg>
                                <div class="flow-diamond-content">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-[10px] font-black mb-1.5 shadow-2xs">
                                        <span>◇</span>
                                        <span>Simbol Keputusan (Kondisi Logika)</span>
                                    </div>
                                    <div class="text-xs sm:text-[14px] font-black text-slate-900 px-2 leading-snug">
                                        ${escapedText}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else if (step.simbol === 'P') {
                    return `
                        <div class="flow-item-wrapper w-full flex flex-col items-center animate-in fade-in zoom-in-95 duration-150">
                            ${toolbarHtml}
                            <div class="flow-node-P">
                                <div class="text-[10px] font-black text-sky-700 uppercase tracking-wider mb-1">▭ Proses Logika</div>
                                <span class="text-sm sm:text-base font-black text-sky-950">${escapedText}</span>
                            </div>
                        </div>
                    `;
                } else {
                    return `
                        <div class="flow-item-wrapper w-full flex flex-col items-center animate-in fade-in zoom-in-95 duration-150">
                            ${toolbarHtml}
                            <div class="flow-node-empty">
                                <span class="text-[11px] font-black text-amber-800 bg-amber-100 border border-amber-300 px-3 py-0.5 rounded-full mb-2">
                                    ⚠️ Pilih Bentuk Simbol:
                                </span>
                                <div class="text-xs sm:text-sm font-extrabold text-slate-800 mb-3 px-2">
                                    "${escapedText}"
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 w-full pt-2 border-t border-slate-100">
                                    <button type="button" onclick="updateLinearSymbol(${idx}, 'T')" class="px-2 py-1.5 rounded-xl bg-amber-100 hover:bg-amber-200 text-amber-900 text-[11px] font-black transition border border-amber-300 shadow-2xs cursor-pointer">
                                        ⬭ Terminator
                                    </button>
                                    <button type="button" onclick="updateLinearSymbol(${idx}, 'IO')" class="px-2 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-[11px] font-black transition border border-slate-700 shadow-2xs cursor-pointer">
                                        ▱ Input/Output
                                    </button>
                                    <button type="button" onclick="updateLinearSymbol(${idx}, 'P')" class="px-2 py-1.5 rounded-xl bg-sky-100 hover:bg-sky-200 text-sky-900 text-[11px] font-black transition border border-sky-300 shadow-2xs cursor-pointer">
                                        ▭ Proses
                                    </button>
                                    <button type="button" onclick="updateLinearSymbol(${idx}, 'D')" class="px-2 py-1.5 rounded-xl bg-orange-100 hover:bg-orange-200 text-orange-900 text-[11px] font-black transition border border-orange-300 shadow-2xs cursor-pointer">
                                        ◇ Keputusan
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }

            function renderBranchNodeHtml(step, branchName) {
                const isYa = (branchName === 'ya');
                const headerText = isYa ? 'OUTPUT BENAR' : 'OUTPUT LAIN';
                const headerColor = isYa ? 'text-emerald-800 bg-emerald-100 border-emerald-300' : 'text-rose-800 bg-rose-100 border-rose-300';
                const borderColor = isYa ? 'border-emerald-500 ring-4 ring-emerald-300/40' : 'border-rose-500 ring-4 ring-rose-300/40';
                const moveLabel = isYa ? '⇄ Pindah ke [ Tidak ]' : '⇄ Pindah ke [ Ya ]';
                const escapedText = escapeHtml(step.cleanTeks || step.rawTeks);

                const selectHtml = `
                    <div class="relative">
                        <select onchange="updateBranchSymbol('${branchName}', this.value)" 
                                aria-label="Pilih simbol untuk cabang ${branchName}"
                                class="font-black text-[11px] py-1 pl-2 pr-5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer appearance-none">
                            <option value="" ${step.simbol === '' ? 'selected' : ''}>❓ Simbol...</option>
                            <option value="IO" ${step.simbol === 'IO' ? 'selected' : ''}>▱ Input/Output</option>
                            <option value="P" ${step.simbol === 'P' ? 'selected' : ''}>▭ Proses</option>
                            <option value="T" ${step.simbol === 'T' ? 'selected' : ''}>⬭ Terminator</option>
                            <option value="D" ${step.simbol === 'D' ? 'selected' : ''}>◇ Keputusan</option>
                        </select>
                        <span class="absolute right-1.5 top-1/2 -translate-y-1/2 pointer-events-none text-[8px] text-slate-500 font-bold">▼</span>
                    </div>
                `;

                const toolbarHtml = `
                    <div class="flex items-center justify-between w-full max-w-[340px] px-2.5 py-1 mb-2 rounded-full bg-white/95 border border-slate-200 shadow-2xs text-xs z-10">
                        <div class="flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border ${headerColor}">${headerText}</span>
                            ${selectHtml}
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="moveBranchStep('${branchName}')" class="px-2 py-0.5 rounded-full bg-slate-100 hover:bg-amber-100 text-slate-700 hover:text-amber-900 border border-slate-200 text-[10px] font-black transition cursor-pointer" title="Pindahkan ke cabang lain">${moveLabel}</button>
                            <button type="button" onclick="removeBranchStep('${branchName}')" aria-label="Hapus dari cabang" class="w-5 h-5 rounded-full bg-rose-100 hover:bg-rose-200 text-rose-700 flex items-center justify-center text-[10px] font-black transition cursor-pointer" title="Hapus langkah">✕</button>
                        </div>
                    </div>
                `;

                let shapeHtml = '';
                if (step.simbol === 'IO') {
                    shapeHtml = `
                        <div class="flow-node-IO ${borderColor}">
                            <div class="flow-unskew">
                                <span class="text-amber-400 text-sm font-black shrink-0">[→]</span>
                                <span class="text-xs sm:text-sm font-black text-white">${escapedText}</span>
                            </div>
                        </div>
                    `;
                } else if (step.simbol === 'P') {
                    shapeHtml = `
                        <div class="flow-node-P ${borderColor}">
                            <div class="text-[10px] font-black text-sky-700 uppercase tracking-wider mb-0.5">▭ Proses Logika</div>
                            <span class="text-xs sm:text-sm font-black text-sky-950">${escapedText}</span>
                        </div>
                    `;
                } else if (step.simbol === 'T') {
                    shapeHtml = `
                        <div class="flow-node-T ${borderColor}">
                            <span class="text-xs sm:text-sm font-black text-slate-950">${escapedText}</span>
                        </div>
                    `;
                } else if (step.simbol === 'D') {
                    shapeHtml = `
                        <div class="flow-node-D ${borderColor}">
                            <svg class="flow-diamond-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                <polygon class="flow-diamond-polygon" points="50,3 97,50 50,97 3,50" />
                            </svg>
                            <div class="flow-diamond-content">
                                <span class="text-xs sm:text-sm font-black text-slate-900">${escapedText}</span>
                            </div>
                        </div>
                    `;
                } else {
                    shapeHtml = `
                        <div class="flow-node-empty ${borderColor}">
                            <span class="text-[10px] font-black text-amber-800 bg-amber-100 border border-amber-300 px-2.5 py-0.5 rounded-full mb-1">
                                ⚠️ Pilih Bentuk Simbol:
                            </span>
                            <div class="text-xs font-black text-slate-800 mb-2 px-1">
                                "${escapedText}"
                            </div>
                            <div class="grid grid-cols-2 gap-1.5 w-full pt-1.5 border-t border-slate-100">
                                <button type="button" onclick="updateBranchSymbol('${branchName}', 'IO')" class="px-2 py-1 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-[10px] font-black transition cursor-pointer">
                                    ▱ Input/Output
                                </button>
                                <button type="button" onclick="updateBranchSymbol('${branchName}', 'P')" class="px-2 py-1 rounded-lg bg-sky-100 hover:bg-sky-200 text-sky-900 text-[10px] font-black transition border border-sky-300 cursor-pointer">
                                    ▭ Proses
                                </button>
                            </div>
                        </div>
                    `;
                }

                return `
                    <div class="flow-item-wrapper w-full flex flex-col items-center animate-in fade-in zoom-in-95 duration-150">
                        ${toolbarHtml}
                        ${shapeHtml}
                    </div>
                `;
            }

            function renderConnectorArrowHtml() {
                return `
                    <div class="flow-connector-wrapper">
                        <div class="flow-connector-line"></div>
                        <div class="flow-connector-arrow"></div>
                    </div>
                `;
            }

            function renderBranchSplitterHtml() {
                return `
                    <div class="branch-splitter-wrapper">
                        <div class="flow-connector-line" style="height: 14px;"></div>
                        <div class="branch-crossbar-row">
                            <div class="branch-crossbar-line"></div>
                            <div class="branch-arm">
                                <span class="px-3.5 py-1 rounded-full bg-emerald-500 text-white font-black text-xs shadow-md border-2 border-white ring-2 ring-emerald-400">
                                    Ya (True)
                                </span>
                                <div class="flow-connector-line" style="height: 14px; margin-top: 4px;"></div>
                                <div class="flow-connector-arrow"></div>
                            </div>

                            <button type="button" onclick="swapBranches()" class="px-3 py-1 rounded-full bg-white hover:bg-amber-100 text-slate-800 hover:text-amber-900 border-2 border-slate-300 hover:border-amber-400 text-[11px] font-black shadow-xs transition cursor-pointer flex items-center gap-1 z-20 -mt-2.5" title="Tukar posisi langkah antara Cabang Ya dan Tidak">
                                <span>⇄</span>
                                <span>Tukar Ya ⇄ Tidak</span>
                            </button>

                            <div class="branch-arm">
                                <span class="px-3.5 py-1 rounded-full bg-rose-500 text-white font-black text-xs shadow-md border-2 border-white ring-2 ring-rose-400">
                                    Tidak (False)
                                </span>
                                <div class="flow-connector-line" style="height: 14px; margin-top: 4px;"></div>
                                <div class="flow-connector-arrow"></div>
                            </div>
                        </div>
                    </div>
                `;
            }

            function renderBranchMergeHtml() {
                return `
                    <div class="branch-merge-wrapper">
                        <div class="branch-merge-row">
                            <div class="flow-connector-line" style="height: 20px;"></div>
                            <div class="flow-connector-line" style="height: 20px;"></div>
                            <div class="branch-merge-line"></div>
                        </div>
                        <div class="flow-connector-line" style="height: 16px;"></div>
                        <div class="flow-connector-arrow"></div>
                    </div>
                `;
            }

            function renderCanvas() {
                const emptyState = document.getElementById('canvas-empty-state');
                const blocksWrapper = document.getElementById('canvas-blocks-wrapper');
                const countLabel = document.getElementById('canvas-count-label');

                const totalInstalled = linearSteps.length + (branchYaStep ? 1 : 0) + (branchTidakStep ? 1 : 0);

                if (countLabel) {
                    countLabel.innerText = totalInstalled;
                }

                if (totalInstalled === 0) {
                    if (emptyState) emptyState.classList.remove('hidden');
                    if (blocksWrapper) {
                        blocksWrapper.classList.add('hidden');
                        blocksWrapper.innerHTML = '';
                    }
                    return;
                }

                if (emptyState) emptyState.classList.add('hidden');
                if (blocksWrapper) blocksWrapper.classList.remove('hidden');

                let html = '';

                if (! taskHasBranching) {
                    linearSteps.forEach((step, idx) => {
                        const isLast = (idx === linearSteps.length - 1);
                        html += renderLinearNodeHtml(step, idx);
                        if (! isLast) {
                            html += renderConnectorArrowHtml();
                        }
                    });
                } else {
                    let decisionIdx = -1;
                    linearSteps.forEach((s, i) => {
                        if (isDecisionStep(s) && decisionIdx === -1) {
                            decisionIdx = i;
                        }
                    });

                    let preSteps = [];
                    let postSteps = [];

                    linearSteps.forEach((s, i) => {
                        if (i === decisionIdx) return;
                        if (decisionIdx !== -1 && i < decisionIdx) {
                            preSteps.push({ step: s, idx: i });
                        } else if (decisionIdx === -1) {
                            preSteps.push({ step: s, idx: i });
                        } else {
                            postSteps.push({ step: s, idx: i });
                        }
                    });

                    // 1. Pre-decision steps
                    preSteps.forEach(item => {
                        html += renderLinearNodeHtml(item.step, item.idx);
                        html += renderConnectorArrowHtml();
                    });

                    // 2. Decision step
                    if (decisionIdx !== -1) {
                        html += renderLinearNodeHtml(linearSteps[decisionIdx], decisionIdx);
                    } else {
                        html += `
                            <div class="flow-item-wrapper w-full flex flex-col items-center">
                                <div class="flow-node-D is-placeholder">
                                    <svg class="flow-diamond-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                        <polygon class="flow-diamond-polygon" points="50,3 97,50 50,97 3,50" />
                                    </svg>
                                    <div class="flow-diamond-content">
                                        <span class="text-xs font-black text-amber-800 mb-1">⏳ Menunggu Simbol Keputusan</span>
                                        <span class="text-[11px] text-slate-500">Pasang langkah pertanyaan (Apakah ...) dari daftar kiri</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    // 3. Branch Splitter
                    html += renderBranchSplitterHtml();

                    // 4. 2-Column Branch Grid (Side-by-Side!)
                    html += `<div class="branch-grid my-2">`;

                    // Left Column (Ya)
                    html += `<div class="flex flex-col items-center justify-start w-full">`;
                    if (branchYaStep !== null) {
                        html += renderBranchNodeHtml(branchYaStep, 'ya');
                    } else {
                        html += `
                            <div onclick="installToBranch('ya')" class="w-full min-h-[120px] p-4 rounded-2xl border-2 border-dashed border-emerald-400 bg-emerald-50/40 hover:bg-emerald-50/70 transition flex flex-col items-center justify-center text-center cursor-pointer group shadow-2xs">
                                <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-900 border border-emerald-300 text-[10px] font-black uppercase tracking-wider mb-1.5">
                                    <span>OUTPUT BENAR (YA)</span>
                                </div>
                                <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 text-lg font-black flex items-center justify-center mb-1 group-hover:scale-110 transition shadow-2xs">+</span>
                                <span class="text-xs font-black text-emerald-900">Pasang Aksi Cabang [ Ya ]</span>
                                <span class="text-[11px] text-emerald-700 mt-0.5 font-medium">Klik untuk menentukan aksi saat kondisi BENAR</span>
                            </div>
                        `;
                    }
                    html += `</div>`;

                    // Right Column (Tidak)
                    html += `<div class="flex flex-col items-center justify-start w-full">`;
                    if (branchTidakStep !== null) {
                        html += renderBranchNodeHtml(branchTidakStep, 'tidak');
                    } else {
                        html += `
                            <div onclick="installToBranch('tidak')" class="w-full min-h-[120px] p-4 rounded-2xl border-2 border-dashed border-rose-400 bg-rose-50/40 hover:bg-rose-50/70 transition flex flex-col items-center justify-center text-center cursor-pointer group shadow-2xs">
                                <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-900 border border-rose-300 text-[10px] font-black uppercase tracking-wider mb-1.5">
                                    <span>OUTPUT LAIN (TIDAK)</span>
                                </div>
                                <span class="w-8 h-8 rounded-full bg-rose-100 text-rose-700 text-lg font-black flex items-center justify-center mb-1 group-hover:scale-110 transition shadow-2xs">+</span>
                                <span class="text-xs font-black text-rose-900">Pasang Aksi Cabang [ Tidak ]</span>
                                <span class="text-[11px] text-rose-700 mt-0.5 font-medium">Klik untuk menentukan aksi saat kondisi SALAH</span>
                            </div>
                        `;
                    }
                    html += `</div>`;

                    html += `</div>`; // end branch-grid

                    // 5. Branch Merge Connector
                    html += renderBranchMergeHtml();

                    // 6. Post-branch steps (e.g. Selesai)
                    postSteps.forEach((item, pIdx) => {
                        html += renderLinearNodeHtml(item.step, item.idx);
                        if (pIdx < postSteps.length - 1) {
                            html += renderConnectorArrowHtml();
                        }
                    });
                }

                blocksWrapper.innerHTML = html;
            }

            function submitCanvasForm() {
                const hiddenWrapper = document.getElementById('hidden-inputs-wrapper');
                hiddenWrapper.innerHTML = '';

                let orderedSteps = [];

                if (! taskHasBranching) {
                    orderedSteps = linearSteps;
                } else {
                    let decisionIdx = -1;
                    linearSteps.forEach((s, i) => {
                        if (isDecisionStep(s) && decisionIdx === -1) {
                            decisionIdx = i;
                        }
                    });

                    let preSteps = [];
                    let postSteps = [];

                    linearSteps.forEach((s, i) => {
                        if (i === decisionIdx) return;
                        if (decisionIdx !== -1 && i < decisionIdx) {
                            preSteps.push(s);
                        } else if (decisionIdx === -1) {
                            preSteps.push(s);
                        } else {
                            postSteps.push(s);
                        }
                    });

                    orderedSteps = [...preSteps];
                    if (decisionIdx !== -1) {
                        orderedSteps.push(linearSteps[decisionIdx]);
                    }

                    // Whatever the user chose to put in branchYaStep is submitted with "Ya: " prefix!
                    if (branchYaStep) {
                        orderedSteps.push({
                            teks: "Ya: " + branchYaStep.actionTeks,
                            simbol: branchYaStep.simbol
                        });
                    }

                    // Whatever the user chose to put in branchTidakStep is submitted with "Tidak: " prefix!
                    if (branchTidakStep) {
                        orderedSteps.push({
                            teks: "Tidak: " + branchTidakStep.actionTeks,
                            simbol: branchTidakStep.simbol
                        });
                    }

                    orderedSteps = [...orderedSteps, ...postSteps];
                }

                orderedSteps.forEach((step, idx) => {
                    const inputTeks = document.createElement('input');
                    inputTeks.type = 'hidden';
                    inputTeks.name = `jawaban[${idx}][teks]`;
                    inputTeks.value = step.teks;
                    hiddenWrapper.appendChild(inputTeks);

                    const inputSimbol = document.createElement('input');
                    inputSimbol.type = 'hidden';
                    inputSimbol.name = `jawaban[${idx}][simbol]`;
                    inputSimbol.value = step.simbol;
                    hiddenWrapper.appendChild(inputSimbol);
                });

                document.getElementById('quiz-submit-form').submit();
            }

            function escapeHtml(string) {
                const div = document.createElement('div');
                div.appendChild(document.createTextNode(string || ''));
                return div.innerHTML;
            }
        </script>
    @endif

</body>
</html>
