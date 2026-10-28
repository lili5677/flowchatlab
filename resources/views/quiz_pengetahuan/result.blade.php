<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Evaluasi Quiz Pengetahuan - Flowchart Lab SMK</title>
    <meta name="description" content="Hasil evaluasi pemahaman teori dan keterampilan praktis logika flowchart vokasi RPL.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-amber-400 via-amber-200 to-amber-100 text-slate-900 flex flex-col antialiased selection:bg-amber-300 selection:text-amber-950">

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

            <!-- Center Navigation (Desktop) -->
            <nav class="hidden md:flex items-center gap-1.5 bg-slate-50/70 p-1.5 rounded-full border border-slate-100">
                <a href="{{ route('home') }}" class="bg-[#F4F1EA] text-slate-900 font-bold px-4 py-1.5 rounded-full text-[13px] shadow-2xs transition">
                    Beranda
                </a>
                <a href="{{ route('materi') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Materi
                </a>
                <a href="{{ route('simulasi') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Simulasi
                </a>
                
                <!-- Quiz Dropdown -->
                <div class="relative" id="quiz-dropdown-wrapper">
                    <button type="button" 
                            id="quiz-dropdown-btn" 
                            onclick="toggleQuizDropdown(event)" 
                            class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white inline-flex items-center gap-1.5 cursor-pointer">
                        <span>Quiz</span>
                        <svg id="quiz-dropdown-arrow" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Dropdown Menu (Stays open when clicked) -->
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

            <!-- Right: User Profile -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- User Profile Info -->
                <div class="flex items-center gap-2.5 pl-1 sm:pl-2">
                    @auth
                        <div class="text-right hidden sm:block">
                            <div class="font-extrabold text-[13px] text-slate-900 leading-tight">{{ $user->name }}</div>
                            <div class="mt-0.5">
                                <span class="bg-amber-200 text-amber-900 font-black text-[9px] px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                                    {{ $user->role === 'guru' ? 'Guru SMK' : 'Siswa SMK' }}
                                </span>
                            </div>
                        </div>
                    @endauth

                    <!-- User Avatar -->
                    <div class="relative group cursor-pointer" onclick="openProfileModal()" title="Profil">
                        <div class="w-9 h-9 rounded-full ring-2 ring-sky-300 ring-offset-2 overflow-hidden bg-sky-50 flex items-center justify-center shrink-0 shadow-2xs hover:ring-sky-400 transition">
                            <svg class="w-full h-full text-sky-800" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="20" class="fill-sky-100" />
                                <circle cx="20" cy="15" r="7" class="fill-rose-200" />
                                <path d="M20 9C15 9 14 13 14 15C14 16 15 17 20 17C25 17 26 16 26 15C26 13 25 9 20 9Z" class="fill-slate-800" />
                                <path d="M9 36C9 28 14 25 20 25C26 25 31 28 31 36" class="fill-white" />
                                <path d="M18 25L20 31L22 25" class="fill-sky-600" />
                                <circle cx="18" cy="14" r="1" class="fill-slate-900" />
                                <circle cx="22" cy="14" r="1" class="fill-slate-900" />
                                <path d="M18.5 17C19 18 21 18 21.5 17" class="stroke-slate-900" stroke-width="0.8" stroke-linecap="round" />
                            </svg>
                        </div>
                        @auth
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button type="button" class="md:hidden p-2 text-slate-700 hover:text-slate-950" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Nav Menu (collapsible) -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-amber-200 bg-white px-4 pt-2 pb-4 space-y-2">
            @auth
                <div class="sm:hidden pb-2 mb-2 border-b border-slate-100">
                    <div class="font-extrabold text-sm text-slate-900">{{ $user->name }}</div>
                    <span class="bg-amber-200 text-amber-900 font-black text-[9px] px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                        {{ $user->role === 'guru' ? 'Guru SMK' : 'Siswa SMK' }}
                    </span>
                </div>
            @endauth
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Beranda</a>
            <a href="{{ route('materi') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Materi & Simbol</a>
            <a href="{{ route('home') }}#simulasi" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Simulasi Alur</a>
            <a href="{{ route('quiz.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold bg-amber-100 text-amber-900">Quiz Pengetahuan</a>
        </div>
    </header>

    <!-- ================= MAIN CONTENT WRAPPER ================= -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8">

        <!-- Top Badges & Hero Header (Mengikuti Mockup 2) -->
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
                Selamat, {{ $user->name }}! Kamu Menyelesaikan Quiz Logika!
            </h1>

            <p class="text-xs sm:text-sm text-slate-800 max-w-xl mx-auto font-medium leading-relaxed">
                Hasil evaluasi pemahaman teori dan keterampilan praktis penyusunan alur logika flowchart vokasi RPL.
            </p>
        </div>

        <!-- ================= MAIN SCORE CARD (Mockup 2) ================= -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-amber-200/80 shadow-2xl relative overflow-hidden space-y-8">

            <!-- Top Predicate Badge -->
            <div class="text-center">
                @if ($attempt->is_passed)
                    <span class="inline-flex items-center gap-2 bg-emerald-600 text-white font-extrabold text-xs sm:text-sm px-5 py-2 rounded-full uppercase tracking-wider shadow-md shadow-emerald-600/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ $predicateConfig['badge'] }}</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 bg-rose-600 text-white font-extrabold text-xs sm:text-sm px-5 py-2 rounded-full uppercase tracking-wider shadow-md shadow-rose-600/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ $predicateConfig['badge'] }}</span>
                    </span>
                @endif
            </div>

            <!-- Score Circular Gauge & Summary Row -->
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12 py-2">
                
                <!-- Circular Score Gauge (Hijau jika Lulus >= 75, Merah jika Gagal) -->
                @php
                    $circumference = 339.29; // 2 * pi * 54
                    $offset = $circumference - (($attempt->score / 100) * $circumference);
                    $gaugeColorClass = $attempt->is_passed ? 'stroke-emerald-500' : 'stroke-rose-500';
                @endphp
                <div class="relative w-44 h-44 sm:w-48 sm:h-48 flex items-center justify-center shrink-0">
                    <svg class="w-full h-full score-circle" viewBox="0 0 120 120">
                        <!-- Track Circle -->
                        <circle cx="60" cy="60" r="54" class="stroke-slate-100 fill-none" stroke-width="10" />
                        <!-- Progress Circle -->
                        <circle cx="60" cy="60" r="54" 
                                class="{{ $gaugeColorClass }} fill-none" 
                                stroke-width="10" 
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}" 
                                stroke-dashoffset="{{ $offset }}" />
                    </svg>

                    <!-- Center Text inside Circle -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight leading-none">
                            {{ $attempt->score }}
                        </span>
                        <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase mt-1">
                            SKOR AKHIR
                        </span>
                    </div>
                </div>

                <!-- Score Details & Description -->
                <div class="space-y-4 max-w-md text-center md:text-left">
                    <div>
                        <span class="bg-amber-100 text-amber-900 font-extrabold text-[11px] px-3 py-1 rounded-md uppercase tracking-wider inline-block">
                            STANDAR KELULUSAN: {{ $passingScore }}
                        </span>
                        <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mt-1">
                            {{ $attempt->score }} <span class="text-slate-400 text-xl font-bold">/ 100</span>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                        {{ $predicateConfig['summary'] }}
                    </p>

                    <!-- Student Identity Card (Nama, NIS, Kelas) -->
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center gap-3 text-left">
                        <div class="w-10 h-10 rounded-full bg-sky-100 border border-sky-300 flex items-center justify-center text-sky-800 font-black text-sm shrink-0">
                            👨‍💻
                        </div>
                        <div class="min-w-0">
                            <div class="font-extrabold text-xs sm:text-sm text-slate-900 truncate">
                                {{ $user->name }} <span class="text-slate-500 font-semibold text-xs">(NIS: {{ $user->display_nis }})</span>
                            </div>
                            <div class="text-[11px] text-slate-500 truncate font-medium">
                                {{ $user->display_kelas }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 3 Stat Cards (Mockup 2) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100">
                <!-- Stat 1: Total Poin -->
                <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/90 flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-amber-400 text-amber-950 flex items-center justify-center text-xl shrink-0 shadow-2xs">
                        ★
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-500">Total Poin</div>
                        <div class="text-lg sm:text-xl font-black text-slate-900 leading-none mt-0.5">
                            {{ $attempt->total_points }} Poin
                        </div>
                        <div class="text-[10px] text-amber-700 font-bold mt-1">
                            {{ $attempt->points_earned }} dari 100 total poin
                        </div>
                    </div>
                </div>

                <!-- Stat 2: Akurasi Jawaban -->
                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/90 flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-xl shrink-0 shadow-2xs">
                        ✓
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-500">Akurasi Jawaban</div>
                        <div class="text-lg sm:text-xl font-black text-slate-900 leading-none mt-0.5">
                            {{ round(($attempt->correct_count / $attempt->total_questions) * 100) }}% Benar
                        </div>
                        <div class="text-[10px] text-emerald-700 font-bold mt-1">
                            {{ $attempt->correct_count }} dari {{ $attempt->total_questions }} soal tepat
                        </div>
                    </div>
                </div>

                <!-- Stat 3: Status Kelulusan -->
                <div class="p-4 rounded-2xl {{ $attempt->is_passed ? 'bg-sky-50/70 border-sky-200/90' : 'bg-rose-50/70 border-rose-200/90' }} border flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl {{ $attempt->is_passed ? 'bg-sky-500 text-white' : 'bg-rose-500 text-white' }} flex items-center justify-center text-xl shrink-0 shadow-2xs">
                        {{ $attempt->is_passed ? '🎓' : '⚠️' }}
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-500">Status Kelulusan</div>
                        <div class="text-lg sm:text-xl font-black {{ $attempt->is_passed ? 'text-sky-950' : 'text-rose-950' }} leading-none mt-0.5">
                            {{ $attempt->is_passed ? 'Lulus KKM' : 'Perlu Remedial' }}
                        </div>
                        <div class="text-[10px] {{ $attempt->is_passed ? 'text-sky-700' : 'text-rose-700' }} font-bold mt-1">
                            Standar KKM: {{ $passingScore }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Decorative Info Cards (Replaces photo lab images cleanly) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white/90 rounded-2xl p-5 border border-amber-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center text-2xl shrink-0">
                    💻
                </div>
                <div>
                    <div class="font-extrabold text-sm text-slate-900">Laboratorium RPL SMK • Standar Algoritma Visual</div>
                    <div class="text-xs text-slate-600 mt-0.5">Standar kompetensi Kurikulum Merdeka kejuruan Rekayasa Perangkat Lunak.</div>
                </div>
            </div>

            <div class="bg-white/90 rounded-2xl p-5 border border-amber-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl shrink-0">
                    📐
                </div>
                <div>
                    <div class="font-extrabold text-sm text-slate-900">Verifikasi Otomatis Notasi Alur ISO/ANSI 5807</div>
                    <div class="text-xs text-slate-600 mt-0.5">Evaluasi pemahaman semantik diagram alir terstruktur secara otomatis.</div>
                </div>
            </div>
        </div>

        <!-- ================= REKAP JAWABAN SOAL (20 Soal) ================= -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-xl space-y-6">

            <!-- Rekap Header with Filters -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Rekap Jawaban Soal
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">
                        Telusuri hasil verifikasi logika untuk memperdalam analisis notasi diagram alir standar ISO/ANSI.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Badges Count -->
                    <span class="bg-emerald-100 text-emerald-800 font-extrabold text-xs px-3 py-1.5 rounded-full">
                        {{ $attempt->total_questions }} Soal Total
                    </span>
                    @php
                        $wrongCount = $attempt->total_questions - $attempt->correct_count;
                    @endphp
                    <span class="bg-rose-100 text-rose-800 font-extrabold text-xs px-3 py-1.5 rounded-full">
                        {{ $wrongCount }} Perlu Koreksi
                    </span>

                    <!-- Filter Tabs -->
                    <div class="bg-slate-100 p-1 rounded-full inline-flex items-center gap-1 border border-slate-200">
                        <button type="button" 
                                onclick="filterRecap('all')" 
                                id="filter-btn-all"
                                class="bg-white text-slate-900 font-extrabold text-xs px-3 py-1 rounded-full shadow-2xs transition cursor-pointer">
                            Semua
                        </button>
                        <button type="button" 
                                onclick="filterRecap('correct')" 
                                id="filter-btn-correct"
                                class="text-slate-600 hover:text-slate-900 font-semibold text-xs px-3 py-1 rounded-full transition cursor-pointer">
                            Benar
                        </button>
                        <button type="button" 
                                onclick="filterRecap('wrong')" 
                                id="filter-btn-wrong"
                                class="text-slate-600 hover:text-slate-900 font-semibold text-xs px-3 py-1 rounded-full transition cursor-pointer">
                            Salah
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recap Items List (1 to 20) -->
            <div class="space-y-4" id="recap-list">
                @foreach ($questions as $q)
                    @php
                        $ans = $answers->get($q->id);
                        $isCorrect = $ans?->is_correct ?? false;
                        $userOptionKey = $ans?->selected_option;
                        
                        $userOptionText = 'Tidak dijawab (Waktu habis)';
                        $correctOptionText = '';

                        foreach ($q->options as $opt) {
                            if ($opt['id'] === $userOptionKey) {
                                $userOptionText = $opt['text'];
                            }
                            if ($opt['id'] === $q->correct_key) {
                                $correctOptionText = $opt['text'];
                            }
                        }

                        $cardBg = $isCorrect ? 'bg-emerald-50/50 border-emerald-300' : 'bg-rose-50/50 border-rose-300';
                        $statusBadge = $isCorrect 
                            ? '<span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 font-extrabold text-xs px-3 py-1 rounded-full">✓ Jawaban Tepat (+5 Poin)</span>' 
                            : '<span class="inline-flex items-center gap-1 bg-rose-100 text-rose-800 font-extrabold text-xs px-3 py-1 rounded-full">⚠ Perlu Koreksi (0 Poin)</span>';
                    @endphp

                    <div class="recap-item p-4 sm:p-5 rounded-2xl border-2 {{ $cardBg }} space-y-3.5 transition" data-status="{{ $isCorrect ? 'correct' : 'wrong' }}">
                        
                        <!-- Item Header -->
                        <div class="flex flex-wrap items-center justify-between gap-2.5">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl {{ $isCorrect ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }} flex items-center justify-center font-black text-xs shrink-0 shadow-2xs">
                                    {{ sprintf('%02d', $q->attempt_number ?? $loop->iteration) }}
                                </span>
                                <span class="font-extrabold text-sm text-slate-900">
                                    {{ $q->question_text }}
                                </span>
                            </div>
                            <div>
                                {!! $statusBadge !!}
                            </div>
                        </div>

                        <!-- Answer Comparison Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                            <!-- Jawaban Siswa -->
                            <div class="p-3 rounded-xl bg-white border border-slate-200">
                                <div class="text-[10px] font-black uppercase tracking-wider {{ $isCorrect ? 'text-slate-500' : 'text-rose-600' }}">
                                    Jawaban Kamu {{ $isCorrect ? '' : '(Kurang Tepat)' }}:
                                </div>
                                <div class="text-xs sm:text-sm font-bold text-slate-800 mt-1 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-md flex items-center justify-center text-xs font-black {{ $isCorrect ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $userOptionKey ?: '—' }}
                                    </span>
                                    <span>{{ $userOptionText }}</span>
                                </div>
                            </div>

                            <!-- Kunci Jawaban -->
                            <div class="p-3 rounded-xl bg-white border border-slate-200">
                                <div class="text-[10px] font-black uppercase tracking-wider text-emerald-700">
                                    Kunci Jawaban Seharusnya:
                                </div>
                                <div class="text-xs sm:text-sm font-bold text-slate-800 mt-1 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-md bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-black">
                                        {{ $q->correct_key }}
                                    </span>
                                    <span>{{ $correctOptionText }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Explanation Box (Pembahasan) -->
                        <div class="p-3 rounded-xl bg-white/80 border border-slate-200/90 text-xs text-slate-700 flex items-start gap-2.5">
                            <span class="text-amber-500 text-sm font-bold shrink-0">💡</span>
                            <div>
                                <strong class="text-slate-900">Pembahasan:</strong>
                                <span>{{ $q->explanation }}</span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>

        <!-- ================= BOTTOM ACTION BUTTONS (Mockup 2) ================= -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4">
            <!-- Left: Kembali ke Beranda -->
            <a href="{{ route('home') }}" class="w-full sm:w-auto bg-white hover:bg-slate-50 border border-amber-300 text-slate-800 font-extrabold text-xs sm:text-[13px] px-6 py-3.5 rounded-full inline-flex items-center justify-center gap-2 shadow-xs transition cursor-pointer">
                <span>←</span>
                <span>Kembali ke Beranda</span>
            </a>

            <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">
                <!-- Center: Ulangi Quiz (Creates new attempt) -->
                <form action="{{ route('quiz.retry') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto bg-white hover:bg-slate-50 border border-amber-300 text-slate-800 font-extrabold text-xs sm:text-[13px] px-6 py-3.5 rounded-full inline-flex items-center justify-center gap-2 shadow-xs transition cursor-pointer">
                        <span>🔄</span>
                        <span>Ulangi Quiz</span>
                    </button>
                </form>

                <!-- Right: Lanjut ke Simulasi / Pelajari Materi Lagi -->
                @if ($attempt->is_passed)
                    <a href="{{ route('home') }}#simulasi" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wide px-8 py-3.5 rounded-full inline-flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/25 transition cursor-pointer">
                        <span>Lanjut ke Simulasi</span>
                        <span>→</span>
                    </a>
                @else
                    <a href="{{ route('materi') }}" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wide px-8 py-3.5 rounded-full inline-flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/25 transition cursor-pointer">
                        <span>Pelajari Materi Lagi</span>
                        <span>→</span>
                    </a>
                @endif
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

    <!-- Simple Filter Logic & Dropdown JS -->
    <script>
        function filterRecap(type) {
            const items = document.querySelectorAll('.recap-item');
            const btnAll = document.getElementById('filter-btn-all');
            const btnCorrect = document.getElementById('filter-btn-correct');
            const btnWrong = document.getElementById('filter-btn-wrong');

            [btnAll, btnCorrect, btnWrong].forEach(b => {
                b.className = 'text-slate-600 hover:text-slate-900 font-semibold text-xs px-3 py-1 rounded-full transition cursor-pointer';
            });

            if (type === 'all') {
                btnAll.className = 'bg-white text-slate-900 font-extrabold text-xs px-3 py-1 rounded-full shadow-2xs transition cursor-pointer';
                items.forEach(el => el.classList.remove('hidden'));
            } else if (type === 'correct') {
                btnCorrect.className = 'bg-white text-slate-900 font-extrabold text-xs px-3 py-1 rounded-full shadow-2xs transition cursor-pointer';
                items.forEach(el => {
                    if (el.getAttribute('data-status') === 'correct') {
                        el.classList.remove('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                });
            } else if (type === 'wrong') {
                btnWrong.className = 'bg-white text-slate-900 font-extrabold text-xs px-3 py-1 rounded-full shadow-2xs transition cursor-pointer';
                items.forEach(el => {
                    if (el.getAttribute('data-status') === 'wrong') {
                        el.classList.remove('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                });
            }
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu) menu.classList.toggle('hidden');
        }

        function toggleQuizDropdown(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('quiz-dropdown-menu');
            const arrow = document.getElementById('quiz-dropdown-arrow');
            if (menu) {
                menu.classList.toggle('hidden');
                if (arrow) arrow.classList.toggle('rotate-180');
            }
        }

        function closeQuizDropdown() {
            const menu = document.getElementById('quiz-dropdown-menu');
            const arrow = document.getElementById('quiz-dropdown-arrow');
            if (menu) menu.classList.add('hidden');
            if (arrow) arrow.classList.remove('rotate-180');
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('quiz-dropdown-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                closeQuizDropdown();
            }
        });
    </script>
</body>
</html>
