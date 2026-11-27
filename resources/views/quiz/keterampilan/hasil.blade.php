<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Akhir Quiz Keterampilan - Flowchart Lab</title>
    <meta name="description" content="Hasil evaluasi praktik penyusunan alur logika flowchart.">

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
<body class="min-h-screen bg-gradient-to-b from-emerald-500 via-emerald-300 to-emerald-200 text-slate-900 flex flex-col antialiased selection:bg-emerald-300 selection:text-emerald-950">

    <!-- ================= NAVBAR ================= -->
    <header class="w-full bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 transition-all">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">

            <!-- Left: Logo & Brand Name -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9 group-hover:scale-105 transition-transform duration-200">
                <div class="flex flex-col">
                    <span class="font-extrabold text-[17px] text-slate-900 tracking-tight leading-snug">Flowchart Lab</span>
                    <span class="font-bold text-[10px] tracking-wider text-[#EA580C] uppercase leading-none mt-0.5">BELAJAR ALGORITMA VISUAL</span>
                </div>
            </a>

            <!-- Center Navigation (Desktop) -->
            <nav class="hidden md:flex items-center gap-1.5 bg-slate-50/70 p-1.5 rounded-full border border-slate-100">
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-emerald-700 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">Beranda</a>
                <a href="{{ route('materi') }}" class="text-slate-600 hover:text-emerald-700 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">Materi</a>
                <a href="{{ route('simulasi') }}" class="text-slate-600 hover:text-emerald-700 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">Simulasi</a>
                <!-- Quiz Dropdown -->
                <div class="relative" id="quiz-dropdown-wrapper">
                    <button type="button" id="quiz-dropdown-btn" onclick="toggleQuizDropdown(event)"
                            class="bg-emerald-100 text-emerald-900 font-bold px-4 py-1.5 rounded-full text-[13px] shadow-2xs transition inline-flex items-center gap-1.5 cursor-pointer">
                        <span>Quiz</span>
                        <svg id="quiz-dropdown-arrow" class="w-3.5 h-3.5 text-emerald-700 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div id="quiz-dropdown-menu" class="absolute left-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden transition-all z-50">
                        <a href="{{ route('quiz.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-amber-50 hover:text-amber-800 transition">
                            <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-[11px]">📖</span>
                            <span>Quiz Pengetahuan</span>
                        </a>
                        <a href="{{ route('quiz.keterampilan') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-emerald-800 bg-emerald-50/70 transition">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-[11px]">🧩</span>
                            <span>Quiz Keterampilan</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Right: User Profile & Auth -->
            <div class="flex items-center gap-3 sm:gap-4">
                @guest
                    <!-- Guest: Quick Login Button -->
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-500 hover:bg-emerald-600 text-white transition shadow-2xs">
                        <span>Masuk</span>
                        <span>&rarr;</span>
                    </a>
                @endguest

                <!-- User Profile Container (Hover Dropdown + Click) -->
                <div class="relative group" id="profile-dropdown-wrapper" onmouseenter="openProfileDropdown()" onmouseleave="closeProfileDropdown()">
                    <div class="flex items-center gap-2.5 pl-1 sm:pl-2 cursor-pointer py-1" onclick="handleProfileClick(event)">
                        @auth
                            <div class="text-right hidden sm:block">
                                <div class="font-extrabold text-[13px] text-slate-900 leading-tight">{{ Auth::user()->name }}</div>
                                <div class="mt-0.5 flex items-center justify-end gap-1.5">
                                    @if(Auth::user()->isAdmin())
                                        <span class="bg-amber-100 text-amber-900 border border-amber-300 font-black text-[9px] px-2 py-0.5 rounded uppercase tracking-wider inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Admin
                                        </span>
                                    @else
                                        <span class="bg-[#FEE3A2] text-[#9A3412] font-black text-[9px] px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                                            {{ Auth::user()->role === 'guru' ? 'Guru' : 'Siswa' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endauth

                        <!-- User Avatar -->
                        <div class="relative cursor-pointer" onclick="event.stopPropagation(); @auth handleProfileClick(event) @else window.location.href='{{ route('login') }}' @endauth;" title="{{ Auth::check() ? Auth::user()->name : 'Klik untuk Masuk Akun' }}">
                            <div class="w-9 h-9 rounded-full ring-2 {{ Auth::check() && Auth::user()->isAdmin() ? 'ring-amber-400 bg-amber-50' : 'ring-emerald-300 bg-emerald-50' }} ring-offset-2 overflow-hidden flex items-center justify-center shrink-0 shadow-2xs hover:ring-emerald-400 transition">
                                <svg class="w-full h-full text-emerald-800" viewBox="0 0 40 40" fill="none">
                                    <circle cx="20" cy="20" r="20" fill="{{ Auth::check() && Auth::user()->isAdmin() ? '#FEF3C7' : '#E0F2FE' }}" />
                                    <circle cx="20" cy="15" r="7" fill="#FBCFE8" />
                                    <path d="M20 9C15 9 14 13 14 15C14 16 15 17 20 17C25 17 26 16 26 15C26 13 25 9 20 9Z" fill="#1E293B" />
                                    <path d="M9 36C9 28 14 25 20 25C26 25 31 28 31 36" fill="#FFFFFF" />
                                    <path d="M18 25L20 31L22 25" fill="{{ Auth::check() && Auth::user()->isAdmin() ? '#D97706' : '#059669' }}" />
                                    <circle cx="18" cy="14" r="1" fill="#0F172A" />
                                    <circle cx="22" cy="14" r="1" fill="#0F172A" />
                                    <path d="M18.5 17C19 18 21 18 21.5 17" stroke="#0F172A" stroke-width="0.8" stroke-linecap="round" />
                                </svg>
                            </div>
                            @auth
                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 {{ Auth::user()->isAdmin() ? 'bg-amber-500' : 'bg-emerald-500' }} border-2 border-white rounded-full"></span>
                            @endauth
                        </div>
                    </div>

                    <!-- Profile Dropdown (Hover & Click Menu) -->
                    <div id="profile-dropdown-menu" class="absolute right-0 top-full pt-1.5 w-72 hidden group-hover:block transition-all duration-150 z-50">
                        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/90 p-3.5 text-slate-800 space-y-3">
                            @auth
                                <!-- Header Identitas Akun -->
                                <div class="px-2 py-1 border-b border-slate-100 pb-2.5">
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Akun Masuk</div>
                                    <div class="font-black text-sm text-slate-900 truncate">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</div>
                                    <div class="mt-1.5">
                                        @if(Auth::user()->isAdmin())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-900 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Administrator Flowchart Lab
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                {{ Auth::user()->role === 'guru' ? 'Guru Pembimbing' : 'Siswa' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action: Tombol Masuk Panel Khusus Admin -->
                                @if(Auth::user()->isAdmin())
                                    <div class="p-0.5">
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between gap-3 w-full px-4 py-3 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 hover:from-slate-800 hover:to-slate-750 text-emerald-400 font-black text-xs rounded-xl shadow-md border border-slate-800 transition transform hover:-translate-y-0.5">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center text-sm font-bold">
                                                    ⚙️
                                                </div>
                                                <div class="text-left">
                                                    <span class="block text-white font-extrabold text-xs leading-tight">Panel Administrator</span>
                                                    <span class="block text-[10px] text-emerald-300 font-normal">Kelola Soal, Kasus & Siswa</span>
                                                </div>
                                            </div>
                                            <span class="text-emerald-400 font-extrabold text-sm">&rarr;</span>
                                        </a>
                                    </div>
                                @endif

                                <!-- Secondary Links -->
                                <div class="space-y-1 pt-1 border-t border-slate-100">
                                    <a href="{{ route('profile.show') }}" class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 flex items-center gap-2.5 transition">
                                        <span>👤</span>
                                        <span>Buka Detail Profil</span>
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2.5 transition cursor-pointer">
                                            <span>🚪</span>
                                            <span>Keluar (Logout)</span>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="p-2 text-center space-y-2.5">
                                    <p class="text-xs text-slate-600 font-medium">Anda belum masuk akun</p>
                                    <a href="{{ route('login') }}" class="block w-full py-2.5 bg-amber-400 hover:bg-amber-500 text-slate-950 font-extrabold text-xs rounded-xl transition shadow-sm">
                                        Masuk ke Akun
                                    </a>
                                    <a href="{{ route('register') }}" class="block w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                                        Daftar Akun Baru
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- =================== MAIN CONTENT =================== -->
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

        // SVG ring: circumference ≈ 2π×63.66 ≈ 400 (r=63.66, viewBox 148x148)
        // For the pengetahuan style: r=54 in viewBox 120x120, circumference=339.29
        $circumference = 339.29;
        $offset = $circumference - ($skorInt / 100 * $circumference);
        $gaugeColorClass = $lulus ? 'stroke-emerald-500' : 'stroke-rose-500';
    @endphp

    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">

        <!-- Top Badges & Hero Header -->
        <div class="text-center space-y-3">
            <div class="flex items-center justify-center gap-2">
                <span class="bg-emerald-600 text-white font-extrabold text-[11px] px-3.5 py-1 rounded-full uppercase tracking-wider shadow-2xs">
                    Modul Dasar Flowchart
                </span>
                <span class="bg-white/80 backdrop-blur-xs text-amber-950 border border-amber-200 font-bold text-[11px] px-3.5 py-1 rounded-full uppercase tracking-wider shadow-2xs">
                    Evaluasi Akhir
                </span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                Selamat, {{ $namaUser }}! Kamu Menyelesaikan Quiz Keterampilan!
            </h1>

            <p class="text-xs sm:text-sm text-slate-800 max-w-xl mx-auto font-medium leading-relaxed">
                Hasil evaluasi pemahaman teori dan keterampilan praktis penyusunan alur logika flowchart.
            </p>
        </div>

        <!-- ================= MAIN SCORE CARD ================= -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-amber-200/80 shadow-2xl relative overflow-hidden space-y-8">

            <!-- Top Predicate Badge -->
            <div class="text-center">
                @if ($lulus)
                    <span class="inline-flex items-center gap-2 bg-emerald-600 text-white font-extrabold text-xs sm:text-sm px-5 py-2 rounded-full uppercase tracking-wider shadow-md shadow-emerald-600/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ $predikatEmoji }} LULUS DENGAN PREDIKAT {{ strtoupper($predikat) }}</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 bg-rose-600 text-white font-extrabold text-xs sm:text-sm px-5 py-2 rounded-full uppercase tracking-wider shadow-md shadow-rose-600/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ $predikatEmoji }} {{ strtoupper($predikat) }}</span>
                    </span>
                @endif
            </div>

            <!-- Score Circular Gauge & Summary Row -->
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12 py-2">

                <!-- Circular Score Gauge -->
                <div class="relative w-44 h-44 sm:w-48 sm:h-48 flex items-center justify-center shrink-0">
                    <svg class="w-full h-full score-circle" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="54" class="stroke-slate-100 fill-none" stroke-width="10" />
                        <circle cx="60" cy="60" r="54"
                                class="{{ $gaugeColorClass }} fill-none"
                                stroke-width="10"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $offset }}" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight leading-none">
                            {{ $skorInt }}
                        </span>
                        <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase mt-1">
                            SKOR PRAKTIK
                        </span>
                    </div>
                </div>

                <!-- Score Details -->
                <div class="space-y-4 max-w-md text-center md:text-left">
                    <div>
                        <span class="bg-amber-100 text-amber-900 font-extrabold text-[11px] px-3 py-1 rounded-md uppercase tracking-wider inline-block">
                            STANDAR KELULUSAN: 75
                        </span>
                        <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-1">
                            {{ $skorInt }} <span class="text-slate-400 text-xl font-bold">/ 100</span>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                        @if($skorInt >= 85)
                            Penguasaan logika algoritma terstruktur di atas rata-rata. Siap melanjutkan ke materi pengkondisian bertingkat.
                        @elseif($skorInt >= 75)
                            Kamu telah mencapai kompetensi dasar. Terus berlatih untuk meningkatkan akurasi simbol dan urutan.
                        @else
                            Jangan menyerah! Pelajari kembali materi simbol flowchart dan coba lagi untuk meningkatkan nilaimu.
                        @endif
                    </p>

                    <!-- Student Identity Card -->
                    @auth
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3 text-left">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 border border-emerald-300 flex items-center justify-center text-emerald-800 font-black text-sm shrink-0">
                                🧩
                            </div>
                            <div class="min-w-0">
                                <div class="font-extrabold text-xs sm:text-sm text-slate-900 truncate">
                                    {{ Auth::user()->name }} <span class="text-slate-500 font-semibold text-xs">(NIS: {{ Auth::user()->nis ?? '-' }})</span>
                                </div>
                                <div class="text-[11px] text-slate-500 truncate font-medium">
                                    {{ Auth::user()->kelas ?? 'Kelas belum diisi' }}
                                </div>
                            </div>
                        </div>
                        @if(isset($completedAt) && $completedAt)
                            <div class="text-right shrink-0">
                                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Dikerjakan</div>
                                <div class="text-xs font-bold text-slate-600">{{ $completedAt->locale('id')->diffForHumans() }}</div>
                            </div>
                        @endif
                    </div>
                    @endauth
                </div>
            </div>

            <!-- 3 Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100">
                <!-- Stat 1: Bonus Poin -->
                <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/90 flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-amber-400 text-amber-950 flex items-center justify-center text-xl shrink-0 shadow-2xs">
                        ★
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-500">Bonus Poin</div>
                        <div class="text-lg sm:text-xl font-black text-slate-900 leading-none mt-0.5">
                            +{{ $skorInt * 2 }} Pts
                        </div>
                        <div class="text-[10px] text-amber-700 font-bold mt-1">Akumulasi mutu</div>
                    </div>
                </div>

                <!-- Stat 2: Akurasi Simbol -->
                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/90 flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-xl shrink-0 shadow-2xs">
                        ✓
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-500">Akurasi Simbol</div>
                        <div class="text-lg sm:text-xl font-black text-slate-900 leading-none mt-0.5">
                            {{ $pctBenar }}% Benar
                        </div>
                        <div class="text-[10px] text-emerald-700 font-bold mt-1">{{ $totalBenar }} dari {{ $totalLangkah }} simbol tepat</div>
                    </div>
                </div>

                <!-- Stat 3: Status -->
                <div class="p-4 rounded-2xl {{ $lulus ? 'bg-sky-50/70 border-sky-200/90' : 'bg-rose-50/70 border-rose-200/90' }} border flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl {{ $lulus ? 'bg-sky-500 text-white' : 'bg-rose-500 text-white' }} flex items-center justify-center text-xl shrink-0 shadow-2xs">
                        {{ $lulus ? '🎓' : '⚠️' }}
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-500">Status Kelulusan</div>
                        <div class="text-lg sm:text-xl font-black {{ $lulus ? 'text-sky-950' : 'text-rose-950' }} leading-none mt-0.5">
                            {{ $lulus ? 'Lulus KKM' : 'Perlu Remedial' }}
                        </div>
                        <div class="text-[10px] {{ $lulus ? 'text-sky-700' : 'text-rose-700' }} font-bold mt-1">Standar KKM: 75</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- =================== REKAP JAWABAN =================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-xl space-y-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Rekap Jawaban Soal</h2>
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
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4">
                <a href="{{ route('home') }}"
                   class="w-full sm:w-auto bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-extrabold text-xs sm:text-[13px] px-6 py-3.5 rounded-full inline-flex items-center justify-center gap-2 shadow-xs transition cursor-pointer">
                    <span>←</span>
                    <span>Kembali ke Beranda</span>
                </a>
                <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">
                    <form action="{{ route('quiz.keterampilan.mulai') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-extrabold text-xs sm:text-[13px] px-6 py-3.5 rounded-full inline-flex items-center justify-center gap-2 shadow-xs transition cursor-pointer">
                            <span>🔄</span>
                            <span>Ulangi Quiz</span>
                        </button>
                    </form>
                    <a href="{{ route('materi') }}"
                       class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wide px-8 py-3.5 rounded-full inline-flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/25 transition cursor-pointer">
                        <span>Pelajari Materi Lagi</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- ================= FOOTER ================= -->
    <x-site-footer />

    <!-- =================== SCRIPTS =================== -->
    <script>
        // Toggle collapsible recap detail
        function toggleRecap(idx) {
            const panel   = document.getElementById('recap-' + idx);
            const chevron = document.getElementById('chevron-' + idx);
            if (!panel) return;
            panel.classList.toggle('open');
            chevron.classList.toggle('rotated');
        }

        // Quiz dropdown
        function toggleQuizDropdown(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('quiz-dropdown-menu');
            const arrow = document.getElementById('quiz-dropdown-arrow');
            if (menu) {
                menu.classList.toggle('hidden');
                if (arrow) arrow.classList.toggle('rotate-180');
            }
        }
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('quiz-dropdown-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                const menu = document.getElementById('quiz-dropdown-menu');
                const arrow = document.getElementById('quiz-dropdown-arrow');
                if (menu) menu.classList.add('hidden');
                if (arrow) arrow.classList.remove('rotate-180');
            }
            const pWrapper = document.getElementById('profile-dropdown-wrapper');
            if (pWrapper && !pWrapper.contains(e.target)) {
                closeProfileDropdown();
            }
        });

        // Profile dropdown
        function openProfileDropdown() {
            const menu = document.getElementById('profile-dropdown-menu');
            if (menu) menu.classList.remove('hidden');
        }
        function closeProfileDropdown() {
            const menu = document.getElementById('profile-dropdown-menu');
            if (menu) menu.classList.add('hidden');
        }
        function handleProfileClick(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('profile-dropdown-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }
    </script>
</body>
</html>
