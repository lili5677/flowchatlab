<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Akhir Quiz Keterampilan - Flowchart Lab SMK</title>
    <meta name="description" content="Hasil evaluasi praktik penyusunan alur logika flowchart vokasi RPL.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            background: linear-gradient(155deg, #F8BA15 0%, #F59E0B 55%, #EA580C 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 340px; height: 340px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
            pointer-events: none;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }

        /* ===== CIRCULAR SCORE RING ===== */
        .score-ring-wrap {
            position: relative;
            width: 148px; height: 148px;
            flex-shrink: 0;
        }
        .score-ring-svg {
            transform: rotate(-90deg);
            width: 148px; height: 148px;
        }
        .score-ring-bg {
            fill: none;
            stroke: rgba(255,255,255,0.2);
            stroke-width: 10;
        }
        .score-ring-fill {
            fill: none;
            stroke: #ffffff;
            stroke-width: 10;
            stroke-linecap: round;
            stroke-dasharray: 400;
            stroke-dashoffset: 400;
            transition: stroke-dashoffset 1.4s cubic-bezier(.4,0,.2,1);
        }
        .score-ring-text {
            position: absolute; inset: 0;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
        }

        /* ===== SIMBOL SHAPES (compact, for rekap) ===== */
        .shape-T {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.28rem 0.9rem;
            border-radius: 9999px;
            background: linear-gradient(180deg, #FDC02F, #F59E0B);
            border: 2px solid #D97706;
            font-weight: 800; font-size: 0.68rem;
            color: #0F172A;
            box-shadow: 0 3px 8px -1px rgba(245,158,11,0.35);
            white-space: nowrap;
        }
        .shape-IO-wrap {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.28rem 0.9rem;
            background: #0F172A; border: 2px solid #334155;
            border-radius: 5px;
            transform: skewX(-12deg);
            font-weight: 800; font-size: 0.68rem;
            color: #F8FAFC;
            box-shadow: 0 3px 8px -1px rgba(15,23,42,0.35);
            white-space: nowrap;
        }
        .shape-IO-wrap span { transform: skewX(12deg); display: block; }
        .shape-P {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.28rem 0.9rem;
            background: linear-gradient(180deg, #EFF6FF, #DBEAFE);
            border: 2px solid #3B82F6;
            border-radius: 6px;
            font-weight: 800; font-size: 0.68rem;
            color: #1D4ED8;
            box-shadow: 0 3px 8px -1px rgba(59,130,246,0.25);
            white-space: nowrap;
        }
        .shape-D {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.5rem 1.4rem;
            background: #FFFDF7; border: 2px solid #F59E0B;
            font-weight: 800; font-size: 0.68rem;
            color: #92400E;
            clip-path: polygon(14px 50%, 50% 4px, calc(100% - 14px) 50%, 50% calc(100% - 4px));
            white-space: nowrap;
        }

        /* ===== STEP CARDS ===== */
        .step-card-correct {
            background: #F0FDF4;
            border: 1.5px solid #BBF7D0;
            border-radius: 16px;
        }
        .step-card-wrong {
            background: #FFF1F2;
            border: 1.5px solid #FECDD3;
            border-radius: 16px;
        }
        .badge-correct { background: #D1FAE5; color: #065F46; }
        .badge-wrong   { background: #FFE4E6; color: #9F1239; }

        /* ===== STAT CARD ===== */
        .stat-card {
            background: white;
            border-radius: 18px;
            padding: 0.9rem 1.1rem;
            box-shadow: 0 4px 16px -4px rgba(15,23,42,0.08);
            border: 1px solid #F1F5F9;
        }

        /* ===== PREDIKAT BADGE ===== */
        .badge-lulus   { background: #D1FAE5; color: #065F46; border: 1px solid #6EE7B7; }
        .badge-cukup   { background: #FEF9C3; color: #854D0E; border: 1px solid #FDE047; }
        .badge-kurang  { background: #FFE4E6; color: #9F1239; border: 1px solid #FCA5A5; }

        /* ===== TOGGLE / COLLAPSIBLE ===== */
        .recap-detail { display: none; }
        .recap-detail.open { display: block; }
        .chevron-icon { transition: transform 0.22s ease; }
        .chevron-icon.rotated { transform: rotate(180deg); }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim-1 { animation: fadeUp 0.5s ease 0.05s both; }
        .anim-2 { animation: fadeUp 0.5s ease 0.15s both; }
        .anim-3 { animation: fadeUp 0.5s ease 0.28s both; }
        .anim-4 { animation: fadeUp 0.5s ease 0.40s both; }
    </style>
</head>
<body class="min-h-screen bg-[#F8FAFC] text-slate-900 flex flex-col antialiased">

    <!-- ================= NAVBAR ================= -->
    <header class="w-full bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 transition-all">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Left: Logo & Brand Name -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9 group-hover:scale-105 transition-transform duration-200">
                <div class="flex flex-col">
                    <span class="font-extrabold text-[17px] text-slate-900 tracking-tight leading-snug">Flowchart Lab</span>
                    <span class="font-bold text-[10px] tracking-wider text-[#EA580C] uppercase leading-none mt-0.5">ALGORITMA VISUAL</span>
                </div>
            </a>

            <div class="flex items-center gap-2">
                <span class="hidden sm:block text-[10px] font-black uppercase tracking-wide px-3 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200">Quiz Keterampilan</span>
                @auth
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-7 h-7 rounded-full bg-amber-400 flex items-center justify-center text-white font-black text-[10px]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- =================== HERO =================== -->
    @php
        $namaUser    = $user ? $user->name : 'Peserta';
        $skorInt     = (int) $skorAkhir;

        if ($skorInt >= 85)      { $predikat = 'Sangat Terampil';        $predikatClass = 'badge-lulus';  $predikatEmoji = '🌟'; $lulus = true; }
        elseif ($skorInt >= 75)  { $predikat = 'Kompeten';               $predikatClass = 'badge-lulus';  $predikatEmoji = '✅'; $lulus = true; }
        elseif ($skorInt >= 60)  { $predikat = 'Cukup Kompeten';         $predikatClass = 'badge-cukup';  $predikatEmoji = '📈'; $lulus = false; }
        else                     { $predikat = 'Perlu Latihan Lanjutan'; $predikatClass = 'badge-kurang'; $predikatEmoji = '📚'; $lulus = false; }

        $totalBenar   = 0;
        $totalLangkah = 0;
        foreach ($results as $res) {
            $totalBenar   += (int) ($res['simbol_benar'] ?? 0);
            $totalLangkah += (int) ($res['total_langkah'] ?? 0);
        }
        $pctBenar  = $totalLangkah > 0 ? round($totalBenar / $totalLangkah * 100) : 0;

        // SVG ring: circumference ≈ 2π×63.66 ≈ 400
        $ringCirc   = 400;
        $ringOffset = $ringCirc - ($skorInt / 100 * $ringCirc);
        $ringColor  = $skorInt >= 75 ? '#34D399' : ($skorInt >= 60 ? '#FBBF24' : '#FB7185');
    @endphp

    <section class="hero-section py-12 sm:py-16 px-4">
        <div class="relative z-10 max-w-4xl mx-auto">

            <!-- Module tags -->
            <div class="flex flex-wrap gap-2 mb-6 anim-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-white/70 bg-white/15 px-3 py-1 rounded-full border border-white/20">Modul Dasar Flowchart</span>
                <span class="text-[10px] font-black uppercase tracking-widest text-white   bg-white/25 px-3 py-1 rounded-full border border-white/30">Evaluasi Akhir</span>
            </div>

            <!-- Headline -->
            <div class="anim-2 mb-8">
                <h1 class="text-3xl sm:text-4xl lg:text-[2.75rem] font-black text-white leading-tight tracking-tight drop-shadow">
                    Selamat, {{ $namaUser }}! Kamu<br>
                    Menyelesaikan Quiz Keterampilan!
                </h1>
                <p class="text-white/75 font-semibold text-sm mt-3 max-w-xl leading-relaxed">
                    Hasil evaluasi pemahaman teori dan keterampilan praktis penyusunan alur logika flowchart vokasi RPL.
                </p>
            </div>

            <!-- Score Card -->
            <div class="anim-3 bg-white/95 backdrop-blur rounded-3xl p-6 sm:p-8 shadow-2xl shadow-amber-900/20 max-w-2xl">

                <!-- Predikat -->
                <div class="mb-5">
                    <span class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wide px-3.5 py-1.5 rounded-full {{ $predikatClass }}">
                        {{ $predikatEmoji }} {{ $lulus ? 'LULUS DENGAN PREDIKAT ' : '' }}{{ strtoupper($predikat) }}
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                    <!-- Circular Ring -->
                    <div class="score-ring-wrap">
                        <svg class="score-ring-svg" viewBox="0 0 148 148">
                            <circle class="score-ring-bg" cx="74" cy="74" r="63.66"/>
                            <circle
                                class="score-ring-fill"
                                id="scoreRingFill"
                                cx="74" cy="74" r="63.66"
                                style="stroke: {{ $ringColor }};"
                            />
                        </svg>
                        <div class="score-ring-text">
                            <span class="text-4xl font-black text-slate-900" id="scoreCounter">0</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">SKOR AKHIR</span>
                        </div>
                    </div>

                    <!-- Right side details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-end gap-1.5 mb-1">
                            <span class="text-5xl font-black {{ $skorInt >= 75 ? 'text-emerald-600' : ($skorInt >= 60 ? 'text-amber-500' : 'text-rose-500') }}">{{ $skorInt }}</span>
                            <span class="text-2xl font-bold text-slate-400 mb-1">/ 100</span>
                        </div>
                        <div class="text-xs font-semibold text-slate-500 mb-3">
                            Standar Kelulusan: <strong class="text-slate-700">75</strong>
                        </div>
                        <p class="text-sm font-medium text-slate-600 leading-relaxed">
                            @if($skorInt >= 85)
                                Penguasaan logika algoritma terstruktur di atas rata-rata kompetensi kelas. Siap melanjutkan ke materi pengkondisian bertingkat.
                            @elseif($skorInt >= 75)
                                Kamu telah mencapai kompetensi dasar. Terus berlatih untuk meningkatkan akurasi simbol dan urutan.
                            @else
                                Jangan menyerah! Pelajari kembali materi simbol flowchart dan coba lagi untuk meningkatkan nilaimu.
                            @endif
                        </p>
                        @auth
                        <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-emerald-700">
                            <div class="w-4 h-4 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-2.5 h-2.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Skor Praktik tersimpan ke akun <strong>{{ $namaUser }}</strong></span>
                        </div>
                        @endauth
                    </div>
                </div>

                <!-- 3 stat chips -->
                <div class="grid grid-cols-3 gap-3 mt-6">
                    <div class="stat-card flex flex-col items-center text-center gap-1">
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
                        <div class="text-sm font-black text-slate-900">+{{ $skorInt * 2 }} Pts</div>
                        <div class="text-[10px] font-bold text-slate-400 leading-snug">Bonus poin<br>akumulasi mutu</div>
                    </div>
                    <div class="stat-card flex flex-col items-center text-center gap-1">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-sm font-black text-slate-900">{{ $pctBenar }}% Benar</div>
                        <div class="text-[10px] font-bold text-slate-400 leading-snug">{{ $totalBenar }} dari {{ $totalLangkah }}<br>simbol tepat</div>
                    </div>
                    <div class="stat-card flex flex-col items-center text-center gap-1">
                        <svg class="w-5 h-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <div class="text-sm font-black text-slate-900">3 Tugas</div>
                        <div class="text-[10px] font-bold text-slate-400 leading-snug">Berhasil<br>diselesaikan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =================== REKAP JAWABAN =================== -->
    <main class="flex-1 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-6">

            <div class="anim-4">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Rekap Jawaban Soal</h2>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Tugas hadir verifikasi logika untuk memperdalam analisis notasi diagram alir standar ISO/ANSI.</p>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-bold flex-shrink-0">
                        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Jawaban Tepat (+10 Pts)
                        </span>
                        <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span> Perlu Koreksi
                        </span>
                    </div>
                </div>

                <!-- Task Cards -->
                <div class="space-y-5">
                    @foreach($tasks as $idx => $task)
                    @php
                        $res             = $results[$idx] ?? [];
                        $skor            = (int) ($res['skor'] ?? 0);
                        $isOk            = $res['is_semua_benar'] ?? false;
                        $evaluasiLangkah = $res['evaluasi_langkah'] ?? [];
                        $pesanKoreksi    = $res['pesan'] ?? [];
                        $simbolBenar     = (int) ($res['simbol_benar'] ?? 0);
                        $totalLangkahT   = (int) ($res['total_langkah'] ?? 0);
                        $level           = $task['level'] ?? 1;
                        $namaLevel       = $task['nama_level'] ?? 'Mudah';
                        $levelColor      = match($level) {
                            1 => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            2 => 'bg-amber-100 text-amber-700 border-amber-200',
                            3 => 'bg-rose-100 text-rose-700 border-rose-200',
                            default => 'bg-slate-100 text-slate-600 border-slate-200',
                        };
                        // auto-open if has errors
                        $detailOpen = !$isOk;
                    @endphp
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

                        <!-- Task header row -->
                        <div class="flex items-center justify-between p-5">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-2xl font-black text-sm flex items-center justify-center shrink-0
                                    {{ $isOk ? 'bg-emerald-500 text-white' : 'bg-rose-100 text-rose-600 border-2 border-rose-200' }}">
                                    {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-extrabold text-slate-900 text-sm truncate">{{ $task['judul'] }}</div>
                                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $levelColor }}">Level {{ $level }} — {{ $namaLevel }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">Kode: {{ $task['kode'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <div class="text-right">
                                    <div class="text-xl font-black {{ $skor >= 75 ? 'text-emerald-600' : 'text-rose-500' }}">
                                        {{ $skor }}<span class="text-xs font-bold text-slate-400">/100</span>
                                    </div>
                                    <div class="text-[10px] font-bold {{ $isOk ? 'text-emerald-600' : 'text-rose-500' }}">
                                        {{ $isOk ? '✓ Jawaban Tepat (+10 Pts)' : '⚠ Perlu Koreksi (0 Pts)' }}
                                    </div>
                                </div>
                                <button
                                    class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors shrink-0"
                                    onclick="toggleRecap({{ $idx }})"
                                    id="recap-btn-{{ $idx }}"
                                    aria-label="Lihat detail tugas {{ $idx + 1 }}"
                                >
                                    <svg class="w-4 h-4 text-slate-500 chevron-icon {{ $detailOpen ? 'rotated' : '' }}" id="chevron-{{ $idx }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Collapsible detail -->
                        <div class="recap-detail {{ $detailOpen ? 'open' : '' }}" id="recap-{{ $idx }}">
                            <div class="border-t border-slate-100 px-5 pt-4 pb-5 space-y-3">

                                @forelse($evaluasiLangkah as $step)
                                @php
                                    $stepOk      = $step['is_simbol_benar'] ?? false;
                                    $teks        = $step['teks'] ?? '-';
                                    $simSiswa    = strtoupper($step['simbol_siswa'] ?? '');
                                    $simKunci    = strtoupper($step['simbol_kunci'] ?? '');
                                    $namaKunci   = $step['nama_simbol_kunci'] ?? $simKunci;

                                    $namaSiswa = match($simSiswa) {
                                        'T'  => 'Terminator (Oval)',
                                        'P'  => 'Persegi Panjang (Proses)',
                                        'IO' => 'Jajar Genjang (I/O)',
                                        'D'  => 'Belah Ketupat (Decision)',
                                        default => $simSiswa ?: '—',
                                    };
                                    $namaKunciDisplay = match($simKunci) {
                                        'T'  => 'Terminator (Oval)',
                                        'P'  => 'Persegi Panjang (Proses)',
                                        'IO' => 'Jajar Genjang (I/O)',
                                        'D'  => 'Belah Ketupat (Decision)',
                                        default => $namaKunci,
                                    };
                                @endphp
                                <div class="p-4 {{ $stepOk ? 'step-card-correct' : 'step-card-wrong' }}">
                                    <!-- Top row -->
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-6 h-6 rounded-full shrink-0 flex items-center justify-center text-[10px] font-black mt-0.5
                                                {{ $stepOk ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                                {{ $loop->iteration }}
                                            </div>
                                            <div class="font-bold text-slate-800 text-sm leading-snug">{{ $teks }}</div>
                                        </div>
                                        <span class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wide flex-shrink-0
                                            {{ $stepOk ? 'badge-correct' : 'badge-wrong' }}">
                                            {{ $stepOk ? '✓ Tepat' : '✗ Koreksi' }}
                                        </span>
                                    </div>

                                    <!-- Jawaban vs Kunci -->
                                    <div class="grid {{ $stepOk ? 'grid-cols-1' : 'grid-cols-2' }} gap-3">
                                        <!-- Jawaban Kamu -->
                                        <div>
                                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-1.5">
                                                {{ $stepOk ? 'Jawaban Kamu' : 'Jawaban Kamu (Kurang Tepat):' }}
                                            </div>
                                            @if($simSiswa === 'T')
                                                <div class="shape-T">{{ $namaSiswa }}</div>
                                            @elseif($simSiswa === 'IO')
                                                <div class="shape-IO-wrap"><span>{{ $namaSiswa }}</span></div>
                                            @elseif($simSiswa === 'P')
                                                <div class="shape-P">{{ $namaSiswa }}</div>
                                            @elseif($simSiswa === 'D')
                                                <div class="shape-D">{{ $namaSiswa }}</div>
                                            @else
                                                <span class="text-xs font-bold text-slate-400 italic">Tidak dipilih</span>
                                            @endif
                                        </div>

                                        <!-- Kunci Jawaban (hanya tampil jika salah) -->
                                        @if(!$stepOk)
                                        <div>
                                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-1.5">Kunci Jawaban Seharusnya:</div>
                                            @if($simKunci === 'T')
                                                <div class="shape-T">{{ $namaKunciDisplay }}</div>
                                            @elseif($simKunci === 'IO')
                                                <div class="shape-IO-wrap"><span>{{ $namaKunciDisplay }}</span></div>
                                            @elseif($simKunci === 'P')
                                                <div class="shape-P">{{ $namaKunciDisplay }}</div>
                                            @elseif($simKunci === 'D')
                                                <div class="shape-D">{{ $namaKunciDisplay }}</div>
                                            @else
                                                <span class="text-xs font-bold text-slate-700">{{ $namaKunciDisplay }}</span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <div class="text-xs text-slate-400 italic text-center py-4">Tidak ada data langkah untuk tugas ini.</div>
                                @endforelse

                                <!-- Pembahasan / pesan koreksi -->
                                @foreach($pesanKoreksi as $pesan)
                                @if($pesan !== 'Semua langkah sudah tepat.')
                                <div class="flex items-start gap-2 p-3.5 rounded-2xl bg-amber-50 border border-amber-200 mt-1">
                                    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div>
                                        <span class="text-[10px] font-black text-amber-800 uppercase tracking-wide">Pembahasan: </span>
                                        <span class="text-xs font-medium text-amber-700">{{ $pesan }}</span>
                                    </div>
                                </div>
                                @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- =================== ACTION BUTTONS =================== -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 pb-8">
                <a href="{{ route('home') }}"
                   class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3.5 rounded-full border-2 border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-extrabold text-sm transition hover:scale-[1.01] shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Beranda
                </a>
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <form action="{{ route('quiz.keterampilan.mulai') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3.5 rounded-full border-2 border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-extrabold text-sm transition hover:scale-[1.01] shadow-sm cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Ulangi Quiz
                        </button>
                    </form>
                    <a href="{{ route('materi') }}"
                       class="w-full sm:w-auto flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-amber-400 hover:bg-amber-500 text-slate-900 font-black text-sm transition hover:scale-[1.02] shadow-lg shadow-amber-400/30">
                        TINGKAT BERIKUTNYA
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-[#18181B] text-slate-400 py-10 px-4 sm:px-6 lg:px-8 border-t border-zinc-800">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            
            <!-- Left: Logo & Copyright -->
            <div class="flex flex-col sm:flex-row items-center gap-3.5"> 
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <div>
                    <div class="text-white font-extrabold text-base tracking-tight">Flowchart Lab</div>
                    <div class="text-xs text-slate-400 mt-0.5">
                        &copy; 2026 Flowchart Lab. Media Pembelajaran Logika Algoritma Vokasi Indonesia.
                    </div>
                </div>
            </div>

            <!-- Right: Navigation Links -->
            <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 text-xs sm:text-[13px] font-medium text-slate-400">
                <a href="{{ route('materi') }}" class="hover:text-white transition">Dokumentasi Simbol</a>
                <a href="#bantuan" onclick="openInfoModal('Pusat Bantuan', 'Butuh bantuan seputar pengerjaan flowchart atau simulasi? Hubungi tim pendamping lab atau baca FAQ seputar aturan ANSI/ISO 5807.')" class="hover:text-white transition">Bantuan</a>
            </div>
        </div>
    </footer>

    <!-- =================== SCRIPTS =================== -->
    <script>
        // Animated score counter
        (function () {
            const target = {{ $skorInt }};
            const el = document.getElementById('scoreCounter');
            if (!el) return;
            let cur = 0;
            const step = Math.max(1, Math.ceil(target / 60));
            const timer = setInterval(() => {
                cur = Math.min(cur + step, target);
                el.textContent = cur;
                if (cur >= target) clearInterval(timer);
            }, 18);
        })();

        // Score ring animation
        (function () {
            const ring   = document.getElementById('scoreRingFill');
            if (!ring) return;
            const circ   = {{ $ringCirc }};
            const offset = {{ $ringOffset }};
            ring.style.strokeDashoffset = circ;
            setTimeout(() => { ring.style.strokeDashoffset = offset; }, 250);
        })();

        // Toggle collapsible recap detail
        function toggleRecap(idx) {
            const panel   = document.getElementById('recap-' + idx);
            const chevron = document.getElementById('chevron-' + idx);
            if (!panel) return;
            panel.classList.toggle('open');
            chevron.classList.toggle('rotated');
        }
    </script>
</body>
</html>
