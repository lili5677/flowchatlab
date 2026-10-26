<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quiz Pengetahuan Algoritma - Flowchart Lab SMK</title>
    <meta name="description" content="Uji pemahaman teoritis simbol baku diagram alir ISO 5807 dan logika komputasional untuk siswa SMK.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-amber-400 via-amber-200 to-amber-100 text-slate-900 flex flex-col antialiased selection:bg-amber-300 selection:text-amber-950">

    <!-- ================= NAVBAR ================= -->
    <header class="w-full bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b border-amber-200/60 shadow-xs transition-all">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Left: Logo & Brand Name -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 border-2 border-amber-300 flex items-center justify-center text-amber-600 shadow-2xs group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="6" />
                        <path d="M7 12h10" />
                        <path d="M12 8v8" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-[17px] text-slate-900 tracking-tight leading-snug">Flowchart Lab</span>
                    <span class="font-bold text-[10px] tracking-wider text-orange-600 uppercase leading-none mt-0.5">SMK ALGORITMA VISUAL</span>
                </div>
            </a>

            <!-- Center Navigation (Desktop) -->
            <nav class="hidden md:flex items-center gap-1.5 bg-slate-100/80 p-1.5 rounded-full border border-slate-200/80">
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Beranda
                </a>
                <a href="{{ route('materi') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Materi
                </a>
                <a href="{{ route('home') }}#simulasi" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Simulasi
                </a>
                
                <!-- Quiz Link (Active) -->
                <div class="relative" id="quiz-dropdown-wrapper">
                    <button type="button" 
                            id="quiz-dropdown-btn" 
                            onclick="toggleQuizDropdown(event)" 
                            class="bg-amber-100 text-amber-900 font-bold px-4 py-1.5 rounded-full text-[13px] shadow-2xs transition inline-flex items-center gap-1.5 cursor-pointer">
                        <span>Quiz</span>
                        <svg id="quiz-dropdown-arrow" class="w-3.5 h-3.5 text-amber-700 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="quiz-dropdown-menu" class="absolute left-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden transition-all z-50">
                        <a href="{{ route('quiz.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-amber-900 bg-amber-50/80 transition">
                            <span class="w-6 h-6 rounded-lg bg-amber-200 text-amber-800 flex items-center justify-center text-[11px]">📖</span>
                            <span>Quiz Pengetahuan</span>
                        </a>
                        <a href="{{ route('home') }}#simulasi" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition">
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
                            <div class="font-extrabold text-[13px] text-slate-900 leading-tight">{{ Auth::user()->name }}</div>
                            <div class="mt-0.5">
                                <span class="bg-amber-200 text-amber-900 font-black text-[9px] px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                                    {{ Auth::user()->role === 'guru' ? 'Guru SMK' : 'Siswa SMK' }}
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
                    <div class="font-extrabold text-sm text-slate-900">{{ Auth::user()->name }}</div>
                    <span class="bg-amber-200 text-amber-900 font-black text-[9px] px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                        {{ Auth::user()->role === 'guru' ? 'Guru SMK' : 'Siswa SMK' }}
                    </span>
                </div>
            @endauth
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Beranda</a>
            <a href="{{ route('materi') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Materi & Simbol</a>
            <a href="{{ route('home') }}#simulasi" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Simulasi Alur</a>
            <a href="{{ route('quiz.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold bg-amber-100 text-amber-900">Quiz Pengetahuan (Aktif)</a>
        </div>
    </header>

    <!-- ================= MAIN CONTENT WRAPPER ================= -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        <!-- Hero Section Header (Mengikuti Mockup 1) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div class="space-y-2.5 max-w-2xl">
                <!-- Badges Row -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="bg-emerald-600 text-white font-extrabold text-[11px] px-3.5 py-1 rounded-full uppercase tracking-wider shadow-2xs">
                        Modul Evaluasi Tahap 1
                    </span>
                    <span class="bg-white/80 backdrop-blur-xs text-amber-950 border border-amber-200 font-bold text-[11px] px-3 py-1 rounded-full uppercase tracking-wider shadow-2xs">
                        Standar Vokasi RPL / TKJ
                    </span>
                </div>

                <!-- Main Title -->
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    Quiz Pengetahuan Algoritma
                </h1>

                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">
                    Uji pemahaman teoritis simbol baku diagram alir ISO 5807 dan logika komputasional melalui 20 soal tantangan adaptif terstruktur.
                </p>
            </div>

            <!-- Top Right: Total Points Card (Mockup 1) -->
            <div class="shrink-0">
                <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-4 sm:p-5 border-2 border-amber-300 shadow-md flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-[10px] font-black tracking-wider text-slate-500 uppercase">Total Terkumpul</div>
                        <div class="text-xl sm:text-2xl font-black text-slate-900 leading-none mt-0.5">
                            <span id="hero-current-points" class="text-amber-600">{{ $pointsEarned }}</span>
                            <span class="text-slate-400 text-sm font-bold">/ 100 Poin</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-400 text-amber-950 flex items-center justify-center text-xl shadow-xs shrink-0">
                        🏆
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= 2-COLUMN MAIN QUIZ GRID ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- LEFT COLUMN: Question Card & Execution (Lg: 8 cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Question Header Bar (Pills & Progress) -->
                <div class="bg-white/90 backdrop-blur-xs rounded-2xl p-4 border border-amber-200/80 shadow-2xs space-y-3">
                    <div class="flex flex-wrap items-center justify-between gap-2.5">
                        <div class="flex items-center gap-2">
                            <!-- Soal X dari 20 -->
                            <span class="bg-slate-900 text-white font-black text-xs px-3.5 py-1 rounded-full shadow-2xs">
                                Soal <span id="label-current-question-num">{{ $activeQuestion->attempt_number ?? $targetNumber }}</span> dari {{ $totalQuestions }}
                            </span>
                            <!-- Level Tag -->
                            <span id="label-current-question-level" class="bg-amber-100 text-amber-900 font-extrabold text-xs px-3 py-1 rounded-full uppercase tracking-wider">
                                {{ $activeQuestion->level }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress Bar & Percentage -->
                    <div class="flex items-center gap-3">
                        <div class="flex-1 bg-slate-200/80 rounded-full h-2.5 overflow-hidden">
                            <div id="quiz-progress-bar" 
                                 class="bg-emerald-500 h-2.5 rounded-full transition-all duration-300"
                                 style="width: {{ round(($answeredCount / $totalQuestions) * 100) }}%">
                            </div>
                        </div>
                        <span id="quiz-progress-percent" class="text-xs font-black text-slate-700 shrink-0">
                            {{ round(($answeredCount / $totalQuestions) * 100) }}% Selesai
                        </span>
                    </div>
                </div>

                <!-- Main Question Card (White Rounded Card) -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/70 shadow-xl space-y-6 relative overflow-hidden">

                    <!-- Question Header & Diagram Symbol Box -->
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-2 flex-1">
                            <span class="inline-block bg-amber-100 text-amber-900 font-black text-[10px] tracking-wider uppercase px-3 py-1 rounded-lg">
                                PERTANYAAN <span id="q-number-padded">{{ sprintf('%02d', $activeQuestion->attempt_number ?? $targetNumber) }}</span>
                            </span>
                            <h2 id="q-text" class="text-lg sm:text-xl font-extrabold text-slate-900 leading-snug">
                                {{ $activeQuestion->question_text }}
                            </h2>
                        </div>

                        <!-- Top-Right Symbol Preview Box (for questions with flowchart symbol) -->
                        <div id="q-symbol-container" class="shrink-0 w-28 sm:w-32 rounded-2xl bg-amber-50/70 border-2 border-amber-200 p-2 shadow-2xs {{ $activeQuestion->symbol_type ? 'block' : 'hidden' }}">
                            <div id="q-symbol-slot">
                                @if ($activeQuestion->symbol_type)
                                    <x-flowchart-symbol :type="$activeQuestion->symbol_type" caption="ISO 5807" />
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Options List (A, B, C, D in Sequential Order) -->
                    <div id="options-container" class="space-y-3" role="radiogroup" aria-label="Pilihan Jawaban">
                        @php
                            $currentPrepared = $questions->firstWhere('number', $activeQuestion->attempt_number ?? $targetNumber) ?? $questions->firstWhere('id', $activeQuestion->id) ?? $questions->first();
                            $isAnswered = $currentPrepared['is_answered'] ?? false;
                            $userOption = $currentPrepared['selected_option'] ?? null;
                            $correctKey = $currentPrepared['correct_key'] ?? null;
                        @endphp

                        @foreach ($currentPrepared['options'] as $opt)
                            @php
                                $optId = $opt['id']; // original ID ('A', 'B', 'C', 'D' from bank)
                                $displayLabel = $opt['display_label']; // always A, B, C, D in order!
                                $isSelected = ($userOption === $optId);
                                $isRight = ($isAnswered && $optId === $correctKey);
                                $isWrong = ($isAnswered && $isSelected && ! $isRight);

                                $cardClass = 'bg-white border-slate-200 hover:border-amber-300 hover:bg-amber-50/30 text-slate-800';
                                $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';

                                if ($isSelected && ! $isAnswered) {
                                    $cardClass = 'bg-amber-50/70 border-amber-500 ring-2 ring-amber-400/40 text-slate-900 shadow-sm';
                                    $badgeClass = 'bg-amber-500 text-white border-amber-600';
                                } elseif ($isRight) {
                                    $cardClass = 'bg-emerald-50 border-emerald-500 ring-2 ring-emerald-400/30 text-emerald-950 font-bold';
                                    $badgeClass = 'bg-emerald-500 text-white border-emerald-600';
                                } elseif ($isWrong) {
                                    $cardClass = 'bg-rose-50 border-rose-400 ring-2 ring-rose-400/30 text-rose-950';
                                    $badgeClass = 'bg-rose-500 text-white border-rose-600';
                                }
                            @endphp

                            <div onclick="selectOption('{{ $optId }}')"
                                 id="opt-card-{{ $optId }}"
                                 role="radio"
                                 aria-checked="{{ $isSelected ? 'true' : 'false' }}"
                                 tabindex="0"
                                 onkeydown="if(event.key === ' ' || event.key === 'Enter') { selectOption('{{ $optId }}'); event.preventDefault(); }"
                                 class="p-4 sm:p-4.5 rounded-2xl border-2 {{ $cardClass }} transition-all duration-150 flex items-center justify-between gap-3.5 cursor-pointer select-none">
                                
                                <div class="flex items-center gap-3.5 flex-1">
                                    <!-- Option Letter Badge: ALWAYS A, B, C, D in order! -->
                                    <div id="opt-badge-{{ $optId }}" class="w-9 h-9 rounded-xl border {{ $badgeClass }} flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                                        {{ $displayLabel }}
                                    </div>
                                    <!-- Option Text -->
                                    <div class="text-xs sm:text-sm font-semibold leading-snug">
                                        {{ $opt['text'] }}
                                    </div>
                                </div>

                                <!-- Status / Indicator Icon -->
                                <div id="opt-status-{{ $optId }}" class="shrink-0 flex items-center">
                                    @if ($isRight)
                                        <span class="inline-flex items-center gap-1 bg-emerald-500 text-white font-black text-[11px] px-2.5 py-1 rounded-full shadow-2xs">
                                            ✓ Kunci Benar
                                        </span>
                                    @elseif ($isWrong)
                                        <span class="inline-flex items-center gap-1 bg-rose-500 text-white font-black text-[11px] px-2.5 py-1 rounded-full shadow-2xs">
                                            ✕ Jawaban Kamu
                                        </span>
                                    @elseif ($isSelected && ! $isAnswered)
                                        <span class="w-4 h-4 rounded-full bg-amber-500 ring-2 ring-amber-300"></span>
                                    @else
                                        <span class="w-4 h-4 rounded-full border-2 border-slate-300"></span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Direct Feedback Banner (Green: Benar / Red: Kurang Tepat) -->
                    <div id="feedback-panel" class="{{ $isAnswered ? 'block' : 'hidden' }} rounded-2xl p-4 sm:p-5 border transition-all">
                        <div class="flex items-start gap-3.5">
                            <div id="feedback-icon-box" class="w-9 h-9 rounded-xl flex items-center justify-center text-lg shrink-0 shadow-2xs">
                                <span id="feedback-icon">✓</span>
                            </div>
                            <div class="space-y-1 flex-1">
                                <div id="feedback-title" class="font-extrabold text-sm sm:text-base"></div>
                                <div id="feedback-explanation" class="text-xs leading-relaxed text-slate-700"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Controls Bar -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                        <!-- Review Soal Button -->
                        <button type="button" 
                                onclick="openReviewModal()"
                                class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-[13px] px-5 py-3 rounded-full inline-flex items-center justify-center gap-2 transition cursor-pointer shadow-2xs">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Review Soal (Daftar)</span>
                        </button>

                        <div class="w-full sm:w-auto flex items-center gap-3">
                            <!-- Answer Submit Button (Shown when not answered) -->
                            <button type="button" 
                                    id="btn-submit-answer"
                                    onclick="submitAnswer()"
                                    class="{{ $isAnswered ? 'hidden' : 'inline-flex' }} w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wide px-8 py-3.5 rounded-full items-center justify-center gap-2 shadow-lg shadow-emerald-600/25 transition cursor-pointer">
                                <span>Jawab Soal</span>
                                <span>→</span>
                            </button>

                            <!-- Next Question / View Result Button (Shown after answered) -->
                            <button type="button" 
                                    id="btn-next-question"
                                    onclick="goToNextQuestion()"
                                    class="{{ $isAnswered ? 'inline-flex' : 'hidden' }} w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wide px-8 py-3.5 rounded-full items-center justify-center gap-2 shadow-lg shadow-emerald-600/25 transition cursor-pointer">
                                <span id="btn-next-label">
                                    {{ $activeQuestion->question_number >= 20 ? 'Lihat Hasil Quiz' : 'Lanjut ke Soal Berikutnya' }}
                                </span>
                                <span>→</span>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- TIPS ANALISIS SOAL Box (Mockup 1) -->
                <div class="bg-amber-100/90 rounded-2xl p-4 sm:p-5 border border-amber-300 shadow-xs flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-400 text-amber-950 flex items-center justify-center text-sm font-black shrink-0">
                        💡
                    </div>
                    <div>
                        <div class="font-black text-xs uppercase tracking-wider text-amber-950">Tips Analisis Soal</div>
                        <p id="tip-text" class="text-xs text-amber-900 mt-0.5 leading-relaxed font-medium">
                            Identifikasi fungsi utama diagram: oval untuk titik mulai/selesai, jajar genjang untuk input/output, belah ketupat untuk pengujian logika, dan persegi panjang untuk pemrosesan.
                        </p>
                    </div>
                </div>

                <!-- 3 Feature Info Cards (Mockup 1 Bottom) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <!-- Info Card 1 -->
                    <div class="bg-white/95 rounded-2xl p-4 border border-amber-200/80 shadow-xs space-y-1.5">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">
                            ✓
                        </div>
                        <div class="font-extrabold text-xs text-slate-900">Validasi Instan</div>
                        <p class="text-[11px] text-slate-600 leading-snug">
                            Sistem memeriksa kecocokan semantik diagram alir secara otomatis sesuai acuan silabus Kurikulum Merdeka.
                        </p>
                    </div>

                    <!-- Info Card 2 -->
                    <div class="bg-white/95 rounded-2xl p-4 border border-amber-200/80 shadow-xs space-y-1.5">
                        <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">
                            ⚡
                        </div>
                        <div class="font-extrabold text-xs text-slate-900">Poin Akumulasi</div>
                        <p class="text-[11px] text-slate-600 leading-snug">
                            Kumpulkan nilai hingga 100 Poin untuk membuka mode simulasi kanvas interaktif tingkat lanjut.
                        </p>
                    </div>

                    <!-- Info Card 3 -->
                    <a href="{{ route('materi') }}" target="_blank" class="bg-white/95 hover:bg-amber-50/50 rounded-2xl p-4 border border-amber-200/80 shadow-xs space-y-1.5 transition block group">
                        <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center text-xs font-bold">
                            📖
                        </div>
                        <div class="font-extrabold text-xs text-slate-900 group-hover:text-amber-800 flex items-center justify-between">
                            <span>Buku Panduan Simbol</span>
                            <span class="text-slate-400 group-hover:text-amber-600">↗</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-snug">
                            Ragu dengan simbol lain? Buka glosarium diagram alir pada bilah navigasi materi kapan saja.
                        </p>
                    </a>
                </div>

            </div>

            <!-- RIGHT COLUMN: Level Roadmap Sidebar (Lg: 4 cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Roadmap Card (White Rounded Card) -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-amber-200/80 shadow-xl space-y-5">
                    
                    <!-- Roadmap Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-amber-500 font-bold text-base">🗺</span>
                            <h3 class="font-black text-slate-900 text-sm sm:text-base">Peta Jalur Tingkat</h3>
                        </div>
                        <span class="bg-amber-100 text-amber-900 font-extrabold text-[10px] px-2.5 py-1 rounded-full uppercase tracking-wider">
                            3 Tingkat
                        </span>
                    </div>

                    <!-- 3 Level Cards (Mudah, Sedang, Sulit) -->
                    <div class="space-y-3" id="roadmap-levels-container">
                        @foreach ($roadmap['levels'] as $idx => $lvl)
                            @php
                                $status = $lvl['status']; // completed, active, locked
                                $containerStyle = 'bg-slate-50 border-slate-200 text-slate-400';
                                $badgeStyle = 'bg-slate-200 text-slate-600';
                                $icon = '🔒';
                                $statusLabel = 'Terkunci';

                                if ($status === 'completed') {
                                    $containerStyle = 'bg-emerald-50/80 border-emerald-400 text-emerald-950 shadow-2xs';
                                    $badgeStyle = 'bg-emerald-600 text-white';
                                    $icon = '✓';
                                    $statusLabel = 'Tuntas';
                                } elseif ($status === 'active') {
                                    $containerStyle = 'bg-amber-50 border-amber-400 text-amber-950 ring-2 ring-amber-300/60 shadow-sm';
                                    $badgeStyle = 'bg-amber-500 text-white';
                                    $icon = '▶';
                                    $statusLabel = 'AKTIF';
                                }
                            @endphp

                            <div id="level-card-{{ $idx + 1 }}" class="p-3.5 rounded-2xl border-2 {{ $containerStyle }} transition-all">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <div id="level-icon-box-{{ $idx + 1 }}" class="w-7 h-7 rounded-xl {{ $badgeStyle }} flex items-center justify-center text-xs font-black shrink-0">
                                            {{ $icon }}
                                        </div>
                                        <div>
                                            <div class="font-black text-xs">{{ $lvl['name'] }}</div>
                                            <div class="text-[10px] opacity-75 font-medium">{{ $lvl['range'] }} • {{ $lvl['total_count'] }} Soal</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span id="level-badge-label-{{ $idx + 1 }}" class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded {{ $status === 'active' ? 'bg-amber-200 text-amber-900' : ($status === 'completed' ? 'bg-emerald-200 text-emerald-900' : 'bg-slate-200 text-slate-600') }}">
                                            {{ $statusLabel }}
                                        </span>
                                        <div id="level-answered-count-{{ $idx + 1 }}" class="text-[10px] opacity-75 mt-0.5 font-bold">
                                            {{ $lvl['answered_count'] }}/{{ $lvl['total_count'] }} Soal
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Question Numbers Quick Navigator (1 to 20) with Locked State Handling -->
                    <div class="pt-3 border-t border-slate-100">
                        <div class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-2.5 flex items-center justify-between">
                            <span>Indeks Soal (1–20)</span>
                            <span class="text-[10px] text-slate-400 font-medium">Klik untuk navigasi</span>
                        </div>
                        <div class="grid grid-cols-5 gap-1.5" id="nav-buttons-grid">
                            @foreach ($questions as $qItem)
                                @php
                                    $num = $qItem['number'];
                                    $isAns = $qItem['is_answered'];
                                    $isCur = ($num === ($activeQuestion->attempt_number ?? $targetNumber));
                                    $isCor = $qItem['is_correct'];
                                    $isLocked = $qItem['is_locked'];

                                    $btnClass = 'bg-slate-100 text-slate-500 hover:bg-slate-200 border border-slate-200';
                                    if ($isLocked) {
                                        $btnClass = 'bg-slate-100/70 text-slate-400 border border-slate-200/80 cursor-not-allowed opacity-60';
                                    } elseif ($isCur) {
                                        $btnClass = 'bg-amber-500 text-white font-black ring-2 ring-amber-300 border-amber-600';
                                    } elseif ($isAns && $isCor) {
                                        $btnClass = 'bg-emerald-500 text-white font-bold border-emerald-600';
                                    } elseif ($isAns && ! $isCor) {
                                        $btnClass = 'bg-rose-500 text-white font-bold border-rose-600';
                                    }
                                @endphp
                                <button type="button" 
                                        @if ($isLocked)
                                            disabled="true"
                                            title="Tingkat ini masih terkunci! Selesaikan semua soal tingkat sebelumnya terlebih dahulu."
                                        @else
                                            onclick="loadQuestion({{ $num }})"
                                        @endif
                                        id="nav-q-btn-{{ $num }}"
                                        data-locked="{{ $isLocked ? 'true' : 'false' }}"
                                        class="h-8 rounded-xl text-xs flex items-center justify-center gap-0.5 transition-all {{ $btnClass }}">
                                    <span>{{ $num }}</span>
                                    @if ($isLocked)
                                        <span class="text-[9px]">🔒</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Roadmap Bottom Summary Counts (Mockup 1) -->
                    <div class="pt-3 border-t border-slate-100 grid grid-cols-3 text-center gap-2">
                        <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-200/80">
                            <div id="summary-tuntas-val" class="font-black text-emerald-700 text-base leading-none">
                                {{ $roadmap['summary']['tuntas'] }}
                            </div>
                            <div class="text-[9px] font-black uppercase tracking-wider text-emerald-800 mt-1">Tuntas</div>
                        </div>
                        <div class="p-2 rounded-xl bg-amber-50 border border-amber-200/80">
                            <div id="summary-aktif-val" class="font-black text-amber-700 text-base leading-none">
                                {{ $roadmap['summary']['aktif'] }}
                            </div>
                            <div class="text-[9px] font-black uppercase tracking-wider text-amber-800 mt-1">Aktif</div>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-200">
                            <div id="summary-terkunci-val" class="font-black text-slate-600 text-base leading-none">
                                {{ $roadmap['summary']['terkunci'] }}
                            </div>
                            <div class="text-[9px] font-black uppercase tracking-wider text-slate-500 mt-1">Terkunci</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- ================= MODAL: REVIEW SOAL ================= -->
    <div id="modal-review" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full max-h-[85vh] flex flex-col shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between p-5 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-amber-500 text-lg">📋</span>
                    <h3 class="font-black text-slate-900 text-base">Daftar Review Soal</h3>
                </div>
                <button type="button" onclick="closeModal('modal-review')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm cursor-pointer">✕</button>
            </div>

            <div class="p-5 overflow-y-auto space-y-3 flex-1" id="review-list-container">
                <!-- Injected via JavaScript -->
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50 rounded-b-3xl text-right">
                <button type="button" onclick="closeModal('modal-review')" class="bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-6 py-2.5 rounded-full cursor-pointer">
                    Tutup Review
                </button>
            </div>
        </div>
    </div>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-zinc-900 text-slate-400 py-10 px-4 sm:px-6 lg:px-8 border-t border-zinc-800 mt-12">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            <div class="flex flex-col sm:flex-row items-center gap-3.5">
                <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-slate-900 font-black text-lg shadow-sm">
                    F
                </div>
                <div>
                    <div class="text-white font-extrabold text-base tracking-tight">Flowchart Lab SMK</div>
                    <div class="text-xs text-slate-400 mt-0.5">
                        &copy; 2025 Flowchart Lab. Media Pembelajaran Logika Algoritma Vokasi Indonesia.
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 text-xs sm:text-[13px] font-medium text-slate-400">
                <a href="#panduan" onclick="alert('Panduan terstruktur untuk guru mata pelajaran Pemrograman Dasar & Algoritma SMK.')" class="hover:text-white transition">Panduan Guru</a>
                <a href="{{ route('materi') }}" class="hover:text-white transition">Dokumentasi Simbol</a>
                <a href="#bantuan" onclick="alert('Pusat bantuan Flowchart Lab SMK. Buka panduan materi untuk mempelajari simbol flowchart.')" class="hover:text-white transition">Bantuan</a>
            </div>
        </div>
    </footer>

    <!-- ================= QUIZ JAVASCRIPT STATE ENGINE ================= -->
    <script>
        // Server state injected safely
        const attemptId = {{ $attempt->id }};
        const totalQuestions = {{ $totalQuestions }};
        let currentQuestionNumber = {{ $activeQuestion->attempt_number ?? $targetNumber }};
        let questionsData = @json($questions);
        let selectedOptionId = null;
        let isSubmitting = false;

        // Visual sequential letters for the 4 option rows
        const sequentialLetters = ['A', 'B', 'C', 'D'];

        // CSRF Token setup for AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Tips per difficulty level
        const levelTips = {
            'Mudah': 'Identifikasi bentuk dasar: Oval (Terminator) untuk Start/End, Jajar Genjang untuk Input/Output data, Belah Ketupat untuk Keputusan kondisi, dan Persegi Panjang untuk kalkulasi Proses.',
            'Sedang': 'Perhatikan keterurutan instruksi logika. Masukan (input) selalu dibutuhkan sebelum data diproses, dan hasil proses ditampilkan melalui simbol input/output.',
            'Sulit': 'Analisis teliti percabangan bertingkat. Urutan pengecekan IF-ELSE sangat krusial agar rentang nilai tinggi tidak tertelan kondisi yang lebih rendah.'
        };

        // --- Option Selection ---
        function selectOption(optId) {
            const currentQ = questionsData.find(q => q.number === currentQuestionNumber);
            if (!currentQ || currentQ.is_answered) {
                return; // cannot change if already answered
            }

            selectedOptionId = optId;

            // Update UI styles of cards
            currentQ.options.forEach((opt, idx) => {
                const card = document.getElementById(`opt-card-${opt.id}`);
                const badge = document.getElementById(`opt-badge-${opt.id}`);
                const status = document.getElementById(`opt-status-${opt.id}`);

                if (opt.id === optId) {
                    card.className = 'p-4 sm:p-4.5 rounded-2xl border-2 bg-amber-50/80 border-amber-500 ring-2 ring-amber-400/40 text-slate-900 shadow-sm transition-all duration-150 flex items-center justify-between gap-3.5 cursor-pointer select-none';
                    badge.className = 'w-9 h-9 rounded-xl border bg-amber-500 text-white border-amber-600 flex items-center justify-center font-black text-sm shrink-0 shadow-2xs';
                    status.innerHTML = '<span class="w-4 h-4 rounded-full bg-amber-500 ring-2 ring-amber-300"></span>';
                } else {
                    card.className = 'p-4 sm:p-4.5 rounded-2xl border-2 bg-white border-slate-200 hover:border-amber-300 hover:bg-amber-50/30 text-slate-800 transition-all duration-150 flex items-center justify-between gap-3.5 cursor-pointer select-none';
                    badge.className = 'w-9 h-9 rounded-xl border bg-slate-100 text-slate-700 border-slate-200 flex items-center justify-center font-black text-sm shrink-0 shadow-2xs';
                    status.innerHTML = '<span class="w-4 h-4 rounded-full border-2 border-slate-300"></span>';
                }
            });

            // Enable submit button
            const submitBtn = document.getElementById('btn-submit-answer');
            if (submitBtn) {
                submitBtn.removeAttribute('disabled');
            }
        }

        // --- Answer Submission via AJAX ---
        function submitAnswer() {
            if (!selectedOptionId || isSubmitting) {
                return;
            }

            const currentQ = questionsData.find(q => q.number === currentQuestionNumber);
            if (!currentQ || currentQ.is_answered) {
                return;
            }

            isSubmitting = true;
            const submitBtn = document.getElementById('btn-submit-answer');
            submitBtn.innerText = 'Memverifikasi...';
            submitBtn.setAttribute('disabled', 'true');

            fetch(`/quiz/${attemptId}/answer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    question_id: currentQ.id,
                    selected_option: selectedOptionId
                })
            })
            .then(res => res.json())
            .then(res => {
                isSubmitting = false;

                if (res.error) {
                    alert(res.error);
                    submitBtn.innerText = 'Jawab Soal';
                    submitBtn.removeAttribute('disabled');
                    return;
                }

                // Update local question data
                currentQ.is_answered = true;
                currentQ.selected_option = selectedOptionId;
                currentQ.is_correct = res.is_correct;
                currentQ.correct_key = res.correct_key;
                currentQ.explanation = res.explanation;

                // Update hero collected points
                if (res.points_earned !== undefined) {
                    const heroPts = document.getElementById('hero-current-points');
                    if (heroPts) heroPts.innerText = res.points_earned;
                }

                // Apply visual feedback to options & panel
                renderQuestionState(currentQ);

                // Update navigator unlocked states if provided
                if (res.unlocked_questions && Array.isArray(res.unlocked_questions)) {
                    questionsData.forEach(qItem => {
                        const isUnlockedNow = res.unlocked_questions.includes(qItem.number);
                        qItem.is_locked = !isUnlockedNow;

                        const navBtn = document.getElementById(`nav-q-btn-${qItem.number}`);
                        if (navBtn) {
                            if (!isUnlockedNow) {
                                navBtn.disabled = true;
                                navBtn.setAttribute('data-locked', 'true');
                                navBtn.title = 'Tingkat ini masih terkunci! Selesaikan semua soal tingkat sebelumnya terlebih dahulu.';
                                navBtn.className = 'h-8 rounded-xl text-xs flex items-center justify-center gap-0.5 transition-all bg-slate-100/70 text-slate-400 border border-slate-200/80 cursor-not-allowed opacity-60';
                                navBtn.innerHTML = `<span>${qItem.number}</span><span class="text-[9px]">🔒</span>`;
                                navBtn.onclick = null;
                            } else {
                                navBtn.disabled = false;
                                navBtn.setAttribute('data-locked', 'false');
                                navBtn.removeAttribute('title');
                                navBtn.onclick = () => loadQuestion(qItem.number);
                                navBtn.innerHTML = `<span>${qItem.number}</span>`;

                                if (qItem.number === currentQuestionNumber) {
                                    navBtn.className = 'h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer bg-amber-500 text-white font-black ring-2 ring-amber-300 border-amber-600';
                                } else if (qItem.is_answered && qItem.is_correct) {
                                    navBtn.className = 'h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer bg-emerald-500 text-white font-bold border-emerald-600';
                                } else if (qItem.is_answered && !qItem.is_correct) {
                                    navBtn.className = 'h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer bg-rose-500 text-white font-bold border-rose-600';
                                } else {
                                    navBtn.className = 'h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer bg-slate-100 text-slate-500 hover:bg-slate-200 border border-slate-200';
                                }
                            }
                        }
                    });
                }

                // Update roadmap level cards & summary counters
                if (res.roadmap) {
                    updateRoadmapUI(res.roadmap);
                }

                // Update overall progress bar
                updateOverallProgress();

                // Check if quiz finished
                if (res.is_completed) {
                    const nextBtn = document.getElementById('btn-next-question');
                    const nextLabel = document.getElementById('btn-next-label');
                    if (nextLabel) nextLabel.innerText = 'Lihat Hasil Quiz';
                    if (nextBtn) {
                        nextBtn.onclick = function() {
                            window.location.href = res.redirect || `/quiz/hasil/${attemptId}`;
                        };
                    }
                }
            })
            .catch(err => {
                isSubmitting = false;
                alert('Gagal mengirim jawaban. Silakan coba kembali.');
                submitBtn.innerText = 'Jawab Soal';
                submitBtn.removeAttribute('disabled');
            });
        }

        // --- Render UI State for Active Question ---
        function renderQuestionState(q) {
            // Update question meta
            document.getElementById('label-current-question-num').innerText = q.number;
            document.getElementById('q-number-padded').innerText = String(q.number).padStart(2, '0');
            document.getElementById('label-current-question-level').innerText = q.level;
            document.getElementById('q-text').innerText = q.question_text;

            // Symbol box
            const symbolBox = document.getElementById('q-symbol-container');
            const symbolSlot = document.getElementById('q-symbol-slot');
            if (q.symbol_type) {
                symbolBox.classList.remove('hidden');
                symbolBox.classList.add('block');
                symbolSlot.innerHTML = getSymbolSvgHtml(q.symbol_type);
            } else {
                symbolBox.classList.add('hidden');
                symbolBox.classList.remove('block');
                symbolSlot.innerHTML = '';
            }

            // Tip update
            const tipEl = document.getElementById('tip-text');
            if (tipEl) tipEl.innerText = levelTips[q.level] || levelTips['Mudah'];

            // Options rendering with strictly sequential visual letters (A, B, C, D in rows 0, 1, 2, 3)
            const container = document.getElementById('options-container');
            container.innerHTML = '';

            selectedOptionId = q.selected_option || null;

            let correctText = '';

            q.options.forEach((opt, idx) => {
                const optId = opt.id;
                const displayLetter = opt.display_label || sequentialLetters[idx] || 'A';
                const isSelected = (selectedOptionId === optId);
                const isRight = (q.is_answered && optId === q.correct_key);
                const isWrong = (q.is_answered && isSelected && !isRight);

                if (optId === q.correct_key) {
                    correctText = opt.text;
                }

                let cardClass = 'bg-white border-slate-200 hover:border-amber-300 hover:bg-amber-50/30 text-slate-800';
                let badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                let statusHtml = '<span class="w-4 h-4 rounded-full border-2 border-slate-300"></span>';

                if (isSelected && !q.is_answered) {
                    cardClass = 'bg-amber-50/80 border-amber-500 ring-2 ring-amber-400/40 text-slate-900 shadow-sm';
                    badgeClass = 'bg-amber-500 text-white border-amber-600';
                    statusHtml = '<span class="w-4 h-4 rounded-full bg-amber-500 ring-2 ring-amber-300"></span>';
                } else if (isRight) {
                    cardClass = 'bg-emerald-50 border-emerald-500 ring-2 ring-emerald-400/30 text-emerald-950 font-bold';
                    badgeClass = 'bg-emerald-500 text-white border-emerald-600';
                    statusHtml = '<span class="inline-flex items-center gap-1 bg-emerald-500 text-white font-black text-[11px] px-2.5 py-1 rounded-full shadow-2xs">✓ Kunci Benar</span>';
                } else if (isWrong) {
                    cardClass = 'bg-rose-50 border-rose-400 ring-2 ring-rose-400/30 text-rose-950';
                    badgeClass = 'bg-rose-500 text-white border-rose-600';
                    statusHtml = '<span class="inline-flex items-center gap-1 bg-rose-500 text-white font-black text-[11px] px-2.5 py-1 rounded-full shadow-2xs">✕ Jawaban Kamu</span>';
                }

                const cardEl = document.createElement('div');
                cardEl.id = `opt-card-${optId}`;
                cardEl.role = 'radio';
                cardEl.setAttribute('aria-checked', isSelected ? 'true' : 'false');
                cardEl.tabIndex = 0;
                cardEl.className = `p-4 sm:p-4.5 rounded-2xl border-2 ${cardClass} transition-all duration-150 flex items-center justify-between gap-3.5 ${q.is_answered ? 'cursor-default' : 'cursor-pointer'} select-none`;
                cardEl.onclick = () => selectOption(optId);
                cardEl.onkeydown = (e) => {
                    if (e.key === ' ' || e.key === 'Enter') {
                        selectOption(optId);
                        e.preventDefault();
                    }
                };

                // The badge always displays displayLetter ('A', 'B', 'C', 'D' in order!)
                cardEl.innerHTML = `
                    <div class="flex items-center gap-3.5 flex-1">
                        <div id="opt-badge-${optId}" class="w-9 h-9 rounded-xl border ${badgeClass} flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                            ${displayLetter}
                        </div>
                        <div class="text-xs sm:text-sm font-semibold leading-snug">
                            ${opt.text}
                        </div>
                    </div>
                    <div id="opt-status-${optId}" class="shrink-0 flex items-center">
                        ${statusHtml}
                    </div>
                `;

                container.appendChild(cardEl);
            });

            // Action Buttons & Feedback Panel
            const submitBtn = document.getElementById('btn-submit-answer');
            const nextBtn = document.getElementById('btn-next-question');
            const feedbackPanel = document.getElementById('feedback-panel');

            if (q.is_answered) {
                submitBtn.classList.add('hidden');
                submitBtn.classList.remove('inline-flex');

                nextBtn.classList.remove('hidden');
                nextBtn.classList.add('inline-flex');
                
                const nextLabel = document.getElementById('btn-next-label');
                const isAllAnswered = questionsData.every(item => item.is_answered);
                if (nextLabel) {
                    nextLabel.innerText = (q.number >= 20 || isAllAnswered) ? 'Lihat Hasil Quiz' : 'Lanjut ke Soal Berikutnya';
                }

                // Render Feedback Panel
                feedbackPanel.classList.remove('hidden');
                feedbackPanel.classList.add('block');

                const fbIconBox = document.getElementById('feedback-icon-box');
                const fbIcon = document.getElementById('feedback-icon');
                const fbTitle = document.getElementById('feedback-title');
                const fbDesc = document.getElementById('feedback-explanation');

                if (q.is_correct) {
                    feedbackPanel.className = 'rounded-2xl p-4 sm:p-5 border bg-emerald-50 border-emerald-300 text-emerald-950 transition-all';
                    fbIconBox.className = 'w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg shrink-0 shadow-2xs';
                    fbIcon.innerText = '✓';
                    fbTitle.innerText = 'Jawaban Kamu Benar!';
                    fbDesc.innerText = q.explanation || 'Pemahaman notasi flowchart Anda sangat tepat.';
                } else {
                    feedbackPanel.className = 'rounded-2xl p-4 sm:p-5 border bg-rose-50 border-rose-300 text-rose-950 transition-all';
                    fbIconBox.className = 'w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center text-lg shrink-0 shadow-2xs';
                    fbIcon.innerText = '✕';
                    fbTitle.innerText = `Jawaban Kurang Tepat`;
                    fbDesc.innerHTML = `<span class="font-bold text-rose-900 block mb-0.5">Kunci Jawaban Seharusnya: ${correctText || q.correct_key}</span><span>Pembahasan: ${q.explanation}</span>`;
                }

            } else {
                feedbackPanel.classList.add('hidden');
                feedbackPanel.classList.remove('block');

                submitBtn.classList.remove('hidden');
                submitBtn.classList.add('inline-flex');
                submitBtn.innerText = 'Jawab Soal';
                if (!selectedOptionId) {
                    submitBtn.setAttribute('disabled', 'true');
                } else {
                    submitBtn.removeAttribute('disabled');
                }

                nextBtn.classList.add('hidden');
                nextBtn.classList.remove('inline-flex');
            }
        }

        // --- Load Specific Question by Number ---
        function loadQuestion(num) {
            const target = questionsData.find(q => q.number === num);
            if (!target) return;

            // Block click if locked!
            if (target.is_locked) {
                alert('Tingkat ini masih terkunci! Selesaikan semua soal pada tingkat sebelumnya terlebih dahulu.');
                return;
            }

            // Highlight active in navigator
            questionsData.forEach(qItem => {
                const btn = document.getElementById(`nav-q-btn-${qItem.number}`);
                if (!btn) return;

                if (qItem.is_locked) {
                    btn.disabled = true;
                    btn.className = 'h-8 rounded-xl text-xs flex items-center justify-center gap-0.5 transition-all bg-slate-100/70 text-slate-400 border border-slate-200/80 cursor-not-allowed opacity-60';
                } else if (qItem.number === num) {
                    btn.className = 'h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer bg-amber-500 text-white font-black ring-2 ring-amber-300 border-amber-600';
                } else if (qItem.is_answered && qItem.is_correct) {
                    btn.className = 'h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer bg-emerald-500 text-white font-bold border-emerald-600';
                } else if (qItem.is_answered && !qItem.is_correct) {
                    btn.className = 'h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer bg-rose-500 text-white font-bold border-rose-600';
                } else {
                    btn.className = 'h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer bg-slate-100 text-slate-500 hover:bg-slate-200 border border-slate-200';
                }
            });

            currentQuestionNumber = num;
            renderQuestionState(target);
        }

        // --- Next Question Navigation ---
        function goToNextQuestion() {
            const isAllAnswered = questionsData.every(item => item.is_answered);
            if (isAllAnswered || currentQuestionNumber >= totalQuestions) {
                window.location.href = `/quiz/hasil/${attemptId}`;
                return;
            }

            // Find next unlocked question
            let nextQ = questionsData.find(q => q.number > currentQuestionNumber && !q.is_locked && !q.is_answered);
            if (!nextQ) {
                // Look for any unanswered unlocked question
                nextQ = questionsData.find(q => !q.is_locked && !q.is_answered);
            }
            if (!nextQ) {
                // If all unlocked are answered, check if next question is unlocked now
                nextQ = questionsData.find(q => q.number === currentQuestionNumber + 1 && !q.is_locked);
            }

            if (nextQ) {
                loadQuestion(nextQ.number);
            } else {
                window.location.href = `/quiz/hasil/${attemptId}`;
            }
        }

        // --- Update Progress Bar & Percentage ---
        function updateOverallProgress() {
            const answeredTotal = questionsData.filter(q => q.is_answered).length;
            const pct = Math.round((answeredTotal / totalQuestions) * 100);

            const bar = document.getElementById('quiz-progress-bar');
            const txt = document.getElementById('quiz-progress-percent');
            if (bar) bar.style.width = `${pct}%`;
            if (txt) txt.innerText = `${pct}% Selesai`;
        }

        // --- Update Roadmap Level Cards & Summary ---
        function updateRoadmapUI(roadmap) {
            if (!roadmap || !roadmap.levels) return;

            roadmap.levels.forEach((lvl, idx) => {
                const card = document.getElementById(`level-card-${idx + 1}`);
                const iconBox = document.getElementById(`level-icon-box-${idx + 1}`);
                const badgeLabel = document.getElementById(`level-badge-label-${idx + 1}`);
                const countLabel = document.getElementById(`level-answered-count-${idx + 1}`);

                if (!card) return;

                if (lvl.status === 'completed') {
                    card.className = 'p-3.5 rounded-2xl border-2 bg-emerald-50/80 border-emerald-400 text-emerald-950 shadow-2xs transition-all';
                    iconBox.className = 'w-7 h-7 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-xs font-black shrink-0';
                    iconBox.innerText = '✓';
                    badgeLabel.className = 'text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-200 text-emerald-900';
                    badgeLabel.innerText = 'Tuntas';
                } else if (lvl.status === 'active') {
                    card.className = 'p-3.5 rounded-2xl border-2 bg-amber-50 border-amber-400 text-amber-950 ring-2 ring-amber-300/60 shadow-sm transition-all';
                    iconBox.className = 'w-7 h-7 rounded-xl bg-amber-500 text-white flex items-center justify-center text-xs font-black shrink-0';
                    iconBox.innerText = '▶';
                    badgeLabel.className = 'text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-amber-200 text-amber-900';
                    badgeLabel.innerText = 'AKTIF';
                } else {
                    card.className = 'p-3.5 rounded-2xl border-2 bg-slate-50 border-slate-200 text-slate-400 transition-all';
                    iconBox.className = 'w-7 h-7 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-black shrink-0';
                    iconBox.innerText = '🔒';
                    badgeLabel.className = 'text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-slate-200 text-slate-600';
                    badgeLabel.innerText = 'Terkunci';
                }

                if (countLabel) {
                    countLabel.innerText = `${lvl.answered_count}/${lvl.total_count} Soal`;
                }
            });

            if (roadmap.summary) {
                const tuntas = document.getElementById('summary-tuntas-val');
                const aktif = document.getElementById('summary-aktif-val');
                const terkunci = document.getElementById('summary-terkunci-val');

                if (tuntas) tuntas.innerText = roadmap.summary.tuntas;
                if (aktif) aktif.innerText = roadmap.summary.aktif;
                if (terkunci) terkunci.innerText = roadmap.summary.terkunci;
            }
        }

        // --- Helper: SVG Symbols ---
        function getSymbolSvgHtml(type) {
            if (type === 'terminator') {
                return `
                    <div class="flex flex-col items-center justify-center text-center">
                        <svg class="w-full h-10" viewBox="0 0 100 48" fill="none">
                            <rect x="3" y="6" width="94" height="36" rx="18" class="fill-amber-50 stroke-amber-500" stroke-width="2.5" />
                            <text x="50" y="27" text-anchor="middle" class="fill-amber-900 text-[10px] font-black uppercase">START / END</text>
                        </svg>
                        <span class="mt-1 text-[9px] font-black tracking-widest text-slate-400 uppercase">ISO 5807</span>
                    </div>
                `;
            }
            if (type === 'input_output') {
                return `
                    <div class="flex flex-col items-center justify-center text-center">
                        <svg class="w-full h-10" viewBox="0 0 100 48" fill="none">
                            <polygon points="18,6 96,6 82,42 4,42" class="fill-sky-50 stroke-sky-500" stroke-width="2.5" stroke-linejoin="round" />
                            <text x="50" y="27" text-anchor="middle" class="fill-sky-900 text-[10px] font-black uppercase">INPUT / DATA</text>
                        </svg>
                        <span class="mt-1 text-[9px] font-black tracking-widest text-slate-400 uppercase">ISO 5807</span>
                    </div>
                `;
            }
            if (type === 'decision') {
                return `
                    <div class="flex flex-col items-center justify-center text-center">
                        <svg class="w-full h-10" viewBox="0 0 100 56" fill="none">
                            <polygon points="50,4 96,28 50,52 4,28" class="fill-amber-50 stroke-amber-500" stroke-width="2.5" stroke-linejoin="round" />
                            <text x="50" y="32" text-anchor="middle" class="fill-amber-950 text-[10px] font-black uppercase">IF / THEN</text>
                        </svg>
                        <span class="mt-1 text-[9px] font-black tracking-widest text-slate-400 uppercase">ISO 5807</span>
                    </div>
                `;
            }
            if (type === 'process') {
                return `
                    <div class="flex flex-col items-center justify-center text-center">
                        <svg class="w-full h-10" viewBox="0 0 100 48" fill="none">
                            <rect x="4" y="6" width="92" height="36" rx="4" class="fill-emerald-50 stroke-emerald-500" stroke-width="2.5" stroke-linejoin="round" />
                            <text x="50" y="27" text-anchor="middle" class="fill-emerald-900 text-[10px] font-black uppercase">PROSES / HITUNG</text>
                        </svg>
                        <span class="mt-1 text-[9px] font-black tracking-widest text-slate-400 uppercase">ISO 5807</span>
                    </div>
                `;
            }
            return '';
        }

        // --- Review Modal ---
        function openReviewModal() {
            const listEl = document.getElementById('review-list-container');
            listEl.innerHTML = '';

            questionsData.forEach(q => {
                if (q.is_locked) {
                    return; // Don't show locked questions in review
                }

                const item = document.createElement('div');
                item.className = 'p-3.5 rounded-2xl border transition-all flex items-center justify-between gap-3 ' +
                    (q.is_answered 
                        ? (q.is_correct ? 'bg-emerald-50/70 border-emerald-300' : 'bg-rose-50/70 border-rose-300') 
                        : 'bg-slate-50 border-slate-200');

                item.innerHTML = `
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black shrink-0 ${q.is_answered ? (q.is_correct ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white') : 'bg-slate-200 text-slate-600'}">
                            ${q.number}
                        </div>
                        <div class="truncate text-xs font-bold text-slate-800">
                            ${q.question_text}
                        </div>
                    </div>
                    <div class="shrink-0 flex items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded ${q.is_answered ? (q.is_correct ? 'bg-emerald-200 text-emerald-900' : 'bg-rose-200 text-rose-900') : 'bg-slate-200 text-slate-600'}">
                            ${q.is_answered ? (q.is_correct ? '✓ Tepat' : '✕ Koreksi') : 'Belum Dijawab'}
                        </span>
                        <button type="button" onclick="loadQuestion(${q.number}); closeModal('modal-review');" class="text-xs font-bold text-amber-700 hover:text-amber-900 px-2.5 py-1 rounded-lg hover:bg-amber-100 cursor-pointer">
                            Buka →
                        </button>
                    </div>
                `;
                listEl.appendChild(item);
            });

            document.getElementById('modal-review').classList.remove('hidden');
            document.getElementById('modal-review').classList.add('flex');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // --- Navbar Functions ---
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
