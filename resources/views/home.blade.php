<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flowchart Lab SMK - Media Belajar Logika Algoritma Visual Siswa SMK</title>
    <meta name="description" content="Media belajar interaktif logika dan algoritma alur kerja khusus siswa SMK. Standar ANSI/ISO 5807, gamifikasi terstruktur, dan Kurikulum Merdeka SMK.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #FFFFFF;
            color: #0F172A;
        }

        /* Custom soft animations */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }

        .animate-float-slow {
            animation: floatSlow 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 flex flex-col antialiased selection:bg-amber-200 selection:text-amber-900">

    <!-- ================= NAVBAR ================= -->
    <header class="w-full bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 transition-all">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Left: Logo & Brand Name -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-[#FFF9ED] border-2 border-[#FBD688] flex items-center justify-center text-amber-500 shadow-2xs group-hover:scale-105 transition-transform duration-200">
                    <!-- Flowchart squircle logo icon -->
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

            <!-- Center Navigation (Desktop) -->
            <nav class="hidden md:flex items-center gap-1.5 bg-slate-50/70 p-1.5 rounded-full border border-slate-100">
                <a href="{{ route('home') }}" class="bg-[#F4F1EA] text-slate-900 font-bold px-4 py-1.5 rounded-full text-[13px] shadow-2xs transition">
                    Beranda
                </a>
                <a href="{{ route('materi') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Materi
                </a>
                <a href="#simulasi" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
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
                        <a href="#quiz-pengetahuan" onclick="openQuizModal('pengetahuan'); closeQuizDropdown();" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-amber-50 hover:text-amber-800 transition">
                            <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-[11px]">📖</span>
                            <span>Quiz Pengetahuan</span>
                        </a>
                        <a href="{{ route('quiz.keterampilan') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition">
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
                                <span class="bg-[#FEE3A2] text-[#9A3412] font-black text-[9px] px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                                    {{ Auth::user()->role === 'guru' ? 'Guru SMK' : 'Siswa SMK' }}
                                </span>
                            </div>
                        </div>
                    @endauth

                    <!-- User Avatar -->
                    <div class="relative group cursor-pointer" onclick="openProfileModal()" title="Profil">
                        <div class="w-9 h-9 rounded-full ring-2 ring-sky-300 ring-offset-2 overflow-hidden bg-sky-50 flex items-center justify-center shrink-0 shadow-2xs hover:ring-sky-400 transition">
                            <!-- Clean SVG Avatar -->
                            <svg class="w-full h-full text-sky-800" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="20" fill="#E0F2FE" />
                                <circle cx="20" cy="15" r="7" fill="#FBCFE8" />
                                <path d="M20 9C15 9 14 13 14 15C14 16 15 17 20 17C25 17 26 16 26 15C26 13 25 9 20 9Z" fill="#1E293B" />
                                <!-- Uniform & Tie -->
                                <path d="M9 36C9 28 14 25 20 25C26 25 31 28 31 36" fill="#FFFFFF" />
                                <path d="M18 25L20 31L22 25" fill="#0284C7" />
                                <circle cx="18" cy="14" r="1" fill="#0F172A" />
                                <circle cx="22" cy="14" r="1" fill="#0F172A" />
                                <path d="M18.5 17C19 18 21 18 21.5 17" stroke="#0F172A" stroke-width="0.8" stroke-linecap="round" />
                            </svg>
                        </div>
                        @auth
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button type="button" class="md:hidden p-2 text-slate-600 hover:text-slate-900" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Nav Menu (collapsible) -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white px-4 pt-2 pb-4 space-y-2">
            @auth
                <div class="sm:hidden pb-2 mb-2 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="font-extrabold text-sm text-slate-900">{{ Auth::user()->name }}</div>
                        <span class="bg-[#FEE3A2] text-[#9A3412] font-black text-[9px] px-2 py-0.5 rounded uppercase tracking-wider inline-block">
                            {{ Auth::user()->role === 'guru' ? 'Guru SMK' : 'Siswa SMK' }}
                        </span>
                    </div>
                </div>
            @endauth
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-bold bg-[#F4F1EA] text-slate-900">Beranda</a>
            <a href="{{ route('materi') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Materi Standar Flowchart</a>
            <a href="#simulasi" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Simulasi Interaktif</a>
            <a href="#quiz" onclick="openQuizModal('all')" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Quiz Interaktif</a>
        </div>
    </header>

    <main class="flex-1">
        <!-- ================= HERO SECTION ================= -->
        <section class="pt-10 sm:pt-14 pb-8 sm:pb-12 bg-white relative">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">

                <!-- 3 Top Category Badges -->
                <div class="inline-flex flex-wrap items-center justify-center gap-2 sm:gap-2.5 mb-6">
                    <!-- Badge 1: Modul Interaktif -->
                    <div class="bg-[#10B981] text-white font-extrabold text-[11px] sm:text-xs uppercase tracking-wider px-3.5 py-1.5 rounded-full inline-flex items-center gap-1.5 shadow-sm shadow-emerald-500/20">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                        <span>Modul Interaktif</span>
                    </div>

                    <!-- Badge 2: Dasar Kejuruan SMK -->
                    <div class="bg-[#FDF3E3] border border-[#FDE3B8] text-[#C27803] font-extrabold text-[11px] sm:text-xs uppercase tracking-wider px-3.5 py-1.5 rounded-full">
                        <span>Dasar Kejuruan SMK</span>
                    </div>

                    <!-- Badge 3: Kurikulum Merdeka -->
                    <div class="bg-[#FCE8ED] border border-[#F8CBD6] text-[#D83A61] font-extrabold text-[11px] sm:text-xs uppercase tracking-wider px-3.5 py-1.5 rounded-full">
                        <span>Kurikulum Merdeka</span>
                    </div>
                </div>

                <!-- Main Hero Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-[54px] font-black text-slate-900 tracking-tight leading-[1.12]">
                    Kuasai <span class="text-[#EF4444]">Logika Pemrograman</span><br>
                    Lewat Flowchart
                </h1>

                <!-- Decorative Red Swoosh Wave -->
                <div class="w-full flex justify-center my-3.5">
                    <svg class="w-36 sm:w-44 h-3 text-[#EF4444]" viewBox="0 0 160 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 8.5C36 2.5 68 12.5 106 6C124 2.8 142 9.5 156 5.5" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" />
                    </svg>
                </div>

                <!-- Subtitle -->
                <p class="text-slate-600 font-normal text-base sm:text-[17px] max-w-xl mx-auto leading-relaxed mb-8">
                    Media belajar interaktif logika dan algoritma alur kerja khusus siswa SMK.
                </p>

                <!-- Hero CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 sm:gap-4 mb-9">
                    <!-- Button 1: Mulai Belajar -->
                    <a href="{{ route('materi') }}" class="w-full sm:w-auto bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-sm uppercase tracking-wide px-8 py-3.5 rounded-full inline-flex items-center justify-center gap-2.5 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                        <span>MULAI BELAJAR</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>

                    <!-- Button 2: Buka Simulasi Cepat -->
                    <button type="button" onclick="openSimulationQuickModal()" class="w-full sm:w-auto bg-[#FFF9EC] hover:bg-[#FFF3D6] border border-[#FDE6B7] text-slate-800 font-bold text-sm px-6 py-3.5 rounded-full inline-flex items-center justify-center gap-2.5 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 shadow-2xs cursor-pointer">
                        <span class="w-5 h-5 rounded-full bg-[#F59E0B] text-white flex items-center justify-center text-[10px] pl-0.5 shadow-2xs">
                            ▶
                        </span>
                        <span>Buka Simulasi Cepat</span>
                    </button>
                </div>

                <!-- Trust Badges / Checkpoints -->
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs sm:text-[13px] font-semibold text-slate-700">
                    <div class="inline-flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Standar ANSI/ISO 5807</span>
                    </div>

                    <span class="text-slate-300 hidden sm:inline">▪</span>

                    <div class="inline-flex items-center gap-2">
                        <span class="text-amber-500 text-sm">🏆</span>
                        <span>Gamifikasi Terstruktur</span>
                    </div>

                    <span class="text-slate-300 hidden sm:inline">▪</span>

                    <div class="inline-flex items-center gap-2">
                        <span class="text-rose-500 text-sm">✔</span>
                        <span>Kurikulum Merdeka SMK</span>
                    </div>
                </div>

            </div>
        </section>

        <!-- ================= CURVED WAVE DIVIDER ================= -->
        <!-- Smooth organic curve leading into the yellow section -->
        <div class="w-full overflow-hidden leading-none -mb-[1px]">
            <svg class="block w-full h-12 sm:h-20 lg:h-24 text-[#F8BA15]" viewBox="0 0 1440 90" fill="currentColor" preserveAspectRatio="none">
                <!-- Arch dipping down smoothly in center -->
                <path d="M0,25 C380,85 1060,85 1440,25 L1440,90 L0,90 Z"></path>
            </svg>
        </div>

        <!-- ================= YELLOW LEARNING TRACKS SECTION ================= -->
        <section class="bg-[#F8BA15] pt-4 sm:pt-6 pb-16 sm:pb-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">

                <!-- Section Header: Modul Pembelajaran -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7">
                    <!-- Left: Avatar Icon & Title -->
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-full bg-white flex items-center justify-center text-[#EF4444] font-black text-2xl shadow-sm shrink-0">
                            F
                        </div>
                        <div>
                            <div class="text-[11px] font-black uppercase tracking-wider text-amber-950/80">MODUL PEMBELAJARAN</div>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Pilih Jalur Belajar Kamu</h2>
                        </div>
                    </div>

                    <!-- Right: Interaktif Pill Badge -->
                    <div>
                        <div class="bg-[#FFF3CD]/90 backdrop-blur-xs text-slate-900 border border-[#FDE68A] font-bold text-xs px-4 py-2 rounded-full inline-flex items-center gap-1.5 shadow-2xs">
                            <span class="text-amber-600">⚡</span>
                            <span>Interaktif & Langsung Dinilai</span>
                        </div>
                    </div>
                </div>

                <!-- 3 Main Interactive Learning Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-7 mb-7">

                    <!-- CARD 1: MATERI -->
                    <div id="materi" class="bg-white rounded-3xl p-6 sm:p-7 shadow-xl shadow-amber-950/10 border border-white flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-2xl transition duration-300 group">
                        <div>
                            <!-- Header: Icon & Badge -->
                            <div class="flex items-center justify-between">
                                <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-center justify-center text-rose-500 shadow-2xs group-hover:scale-105 transition-transform">
                                    <!-- Flowchart node symbol -->
                                    <svg class="w-5 h-5 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                                        <path d="M10 6.5h4a2 2 0 0 1 2 2V14"/>
                                    </svg>
                                </div>
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200/70 font-bold text-[11px] px-3 py-1 rounded-full">
                                    Modul Teori
                                </span>
                            </div>

                            <!-- Title & Subtitle -->
                            <h3 class="text-xl font-black text-slate-900 mt-4 mb-2">Materi</h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed min-h-[38px]">
                                Pelajari 6 simbol standar flowchart dan contoh alur algoritma nyata.
                            </p>

                            <!-- Mini Interactive Visual Box: Start -> Proses -> Cabang -->
                            <div class="bg-[#FAF9F5] border border-slate-100/90 rounded-2xl p-4 my-6 flex items-center justify-between">
                                <!-- Start / Terminal -->
                                <div class="text-center flex-1">
                                    <div class="bg-[#EF4444] text-white text-[9px] font-black px-2 py-0.5 rounded-full inline-block tracking-wider shadow-2xs">
                                        I/O
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-bold block mt-1">Start</span>
                                </div>

                                <span class="text-slate-400 font-black text-xs">→</span>

                                <!-- Proses / Process -->
                                <div class="text-center flex-1">
                                    <div class="bg-[#10B981] text-white text-[9px] font-black px-2 py-0.5 rounded inline-block tracking-wider shadow-2xs">
                                        PRC
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-bold block mt-1">Proses</span>
                                </div>

                                <span class="text-slate-400 font-black text-xs">→</span>

                                <!-- Cabang / Decision -->
                                <div class="text-center flex-1">
                                    <div class="w-5 h-5 bg-[#F59E0B] text-white text-[9px] font-black flex items-center justify-center transform rotate-45 rounded-xs mx-auto shadow-2xs">
                                        <span class="-rotate-45">F</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-bold block mt-1.5">Cabang</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('materi') }}" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wider py-3.5 rounded-full flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                            <span>BUKA MATERI</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>

                    <!-- CARD 2: SIMULASI -->
                    <div id="simulasi" class="bg-white rounded-3xl p-6 sm:p-7 shadow-xl shadow-amber-950/10 border border-white flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-2xl transition duration-300 group">
                        <div>
                            <!-- Header: Icon & Badge -->
                            <div class="flex items-center justify-between">
                                <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-center text-amber-500 shadow-2xs group-hover:scale-105 transition-transform">
                                    <!-- 3x3 grid dots icon -->
                                    <svg class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="6" cy="6" r="2"/>
                                        <circle cx="12" cy="6" r="2"/>
                                        <circle cx="18" cy="6" r="2"/>
                                        <circle cx="6" cy="12" r="2"/>
                                        <circle cx="12" cy="12" r="2"/>
                                        <circle cx="18" cy="12" r="2"/>
                                        <circle cx="6" cy="18" r="2"/>
                                        <circle cx="12" cy="18" r="2"/>
                                        <circle cx="18" cy="18" r="2"/>
                                    </svg>
                                </div>
                                <span class="bg-rose-50 text-rose-600 border border-rose-200/70 font-bold text-[11px] px-3 py-1 rounded-full">
                                    3 Tahap Game
                                </span>
                            </div>

                            <!-- Title & Subtitle -->
                            <h3 class="text-xl font-black text-slate-900 mt-4 mb-2">Simulasi</h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed min-h-[38px]">
                                Latihan interaktif 3 tahap: kenali simbol, lengkapi slot, dan susun alur.
                            </p>

                            <!-- Mini Interactive Visual Box: 1 Kenal, 2 Slot, 3 Susun -->
                            <div class="bg-[#FAF9F5] border border-slate-100/90 rounded-2xl p-4 my-6 flex items-center justify-around">
                                <!-- Step 1 -->
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-[#10B981] text-white text-[10px] font-black inline-flex items-center justify-center shadow-2xs">1</span>
                                    <span class="text-xs font-bold text-slate-700">Kenal</span>
                                </div>

                                <!-- Step 2 -->
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-[#F59E0B] text-white text-[10px] font-black inline-flex items-center justify-center shadow-2xs">2</span>
                                    <span class="text-xs font-bold text-slate-700">Slot</span>
                                </div>

                                <!-- Step 3 -->
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-[#EF4444] text-white text-[10px] font-black inline-flex items-center justify-center shadow-2xs">3</span>
                                    <span class="text-xs font-bold text-slate-700">Susun</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button type="button" onclick="openSimulationQuickModal()" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wider py-3.5 rounded-full flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                            <span>MULAI SIMULASI</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>

                    <!-- CARD 3: QUIZ INTERAKTIF -->
                    <div id="quiz" class="bg-white rounded-3xl p-6 sm:p-7 shadow-xl shadow-amber-950/10 border border-white flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-2xl transition duration-300 group">
                        <div>
                            <!-- Header: Icon & Badge -->
                            <div class="flex items-center justify-between">
                                <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-center text-amber-600 shadow-2xs group-hover:scale-105 transition-transform">
                                    <!-- Question Box icon [?] -->
                                    <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="4"/>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                                    </svg>
                                </div>
                                <span class="bg-[#FFF8EA] text-[#B87A04] border border-[#FCE2A7] font-bold text-[11px] px-3 py-1 rounded-full flex items-center gap-1">
                                    <span>★</span>
                                    <span>+100 Poin</span>
                                </span>
                            </div>

                            <!-- Title & Subtitle -->
                            <h3 class="text-xl font-black text-slate-900 mt-4 mb-2">Quiz Interaktif</h3>
                            <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed min-h-[38px]">
                                Uji pemahaman teori di Quiz Pengetahuan & tantangan praktik di Quiz Keterampilan.
                            </p>

                            <!-- Mini Interactive Visual Box: Pengetahuan & Keterampilan -->
                            <div class="bg-[#FAF9F5] border border-slate-100/90 rounded-2xl p-4 my-6 flex items-center justify-around gap-2">
                                <!-- Mode 1: Pengetahuan -->
                                <button type="button" onclick="openQuizModal('pengetahuan')" class="bg-white hover:bg-slate-50 border border-slate-200/90 shadow-2xs px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 flex items-center gap-1.5 transition cursor-pointer">
                                    <span>Pengetahuan</span>
                                    <span class="text-amber-500 text-xs">📖</span>
                                </button>

                                <!-- Mode 2: Keterampilan -->
                                <button type="button" onclick="openQuizModal('keterampilan')" class="bg-white hover:bg-slate-50 border border-slate-200/90 shadow-2xs px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 flex items-center gap-1.5 transition cursor-pointer">
                                    <span>Keterampilan</span>
                                    <span class="text-emerald-500 text-xs">📋</span>
                                </button>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button type="button" onclick="openQuizModal('all')" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wider py-3.5 rounded-full flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                            <span>PILIH QUIZ</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>

                </div>

                <!-- ================= STANDAR KOMPETENSI ALGORITMA SMK BANNER ================= -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-lg shadow-amber-950/10 border border-white flex flex-col md:flex-row items-start md:items-center justify-between gap-5 transition hover:shadow-xl">
                    <!-- Left: Graduation Icon & Competency Info -->
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#FFF9EC] border border-[#FDE6B7] flex items-center justify-center text-[#D97706] shrink-0 shadow-2xs">
                            <!-- Graduation Cap / Mortarboard SVG -->
                            <svg class="w-6 h-6 text-[#D97706]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-base sm:text-lg">Standar Kompetensi Algoritma SMK</h4>
                            <p class="text-xs sm:text-[13px] text-slate-500 max-w-xl mt-0.5 leading-relaxed">
                                Memahami simbol terminasi, proses berulang (loop), seleksi kondisi IF-ELSE, dan integrasi pseudocode.
                            </p>
                        </div>
                    </div>

                    <!-- Right: Two Status Badges -->
                    <div class="flex flex-wrap items-center gap-2.5 shrink-0 self-end md:self-center">
                        <span class="bg-[#FFF8EA] text-[#B87A04] border border-[#FCE2A7] font-bold text-xs px-4 py-2 rounded-full shadow-2xs">
                            3 Modul Lengkap
                        </span>
                        <span class="bg-[#E8FAF0] text-[#0A9655] border border-[#BFF1D3] font-bold text-xs px-4 py-2 rounded-full shadow-2xs">
                            Autocheck Otomatis
                        </span>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-[#18181B] text-slate-400 py-10 px-4 sm:px-6 lg:px-8 border-t border-zinc-800">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            
            <!-- Left: Logo & Copyright -->
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

            <!-- Right: Navigation Links -->
            <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 text-xs sm:text-[13px] font-medium text-slate-400">
                <a href="#panduan" onclick="openInfoModal('Panduan Guru', 'Panduan terstruktur untuk guru mata pelajaran Pemrograman Dasar & Algoritma SMK dalam mendampingi siswa, melihat laporan autocheck, dan memantau tugas.')" class="hover:text-white transition">Panduan Guru</a>
                <a href="{{ route('materi') }}" class="hover:text-white transition">Dokumentasi Simbol</a>
                <a href="#bantuan" onclick="openInfoModal('Pusat Bantuan', 'Butuh bantuan seputar pengerjaan flowchart atau simulasi? Hubungi tim pendamping lab atau baca FAQ seputar aturan ANSI/ISO 5807.')" class="hover:text-white transition">Bantuan</a>
            </div>

        </div>
    </footer>

    <!-- ================= INTERACTIVE MODALS ================= -->

    <!-- Modal 1: Quick Simulation Modal (Buka Simulasi Cepat) -->
    <div id="modal-simulasi" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">▶</span>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Simulasi Cepat: Menentukan Kelulusan</h3>
                        <p class="text-xs text-slate-500">Uji logika IF-ELSE algoritma nilai siswa</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-simulasi')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm">✕</button>
            </div>

            <!-- Mini Interactive Flowchart Preview -->
            <div class="py-5 space-y-4">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-center">
                    <div class="inline-block bg-rose-500 text-white text-xs font-bold px-4 py-1 rounded-full mb-2">
                        Start: Masukkan Nilai (Contoh: <span id="sim-score-val">78</span>)
                    </div>
                    <div class="text-slate-400 font-bold text-sm">↓</div>
                    <div class="inline-block bg-amber-400 text-amber-950 text-xs font-bold px-4 py-1.5 rounded-lg my-1 rotate-0 border border-amber-300">
                        Apakah Nilai &ge; 75 ? (KKM)
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-2">
                        <div class="p-2.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-xs font-bold">
                            Ya → <span class="text-emerald-700 underline">Lulus (Tuntas)</span>
                        </div>
                        <div class="p-2.5 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 text-xs font-bold">
                            Tidak → <span class="text-rose-700 underline">Remedial</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex justify-between">
                        <span>Coba Ubah Nilai Siswa:</span>
                        <span id="slider-label" class="text-emerald-600 font-extrabold">78 (LULUS)</span>
                    </label>
                    <input type="range" min="40" max="100" value="78" id="sim-slider" oninput="updateSimulationScore(this.value)" class="w-full accent-emerald-500 cursor-pointer">
                </div>

                <div id="sim-status-box" class="p-3 rounded-xl bg-emerald-100 text-emerald-800 text-xs font-semibold text-center">
                    🎉 Nilai memenuhi KKM SMK. Alur branching mengambil cabang TRUE.
                </div>
            </div>

            <div class="flex gap-2.5 pt-2">
                <button type="button" onclick="closeModal('modal-simulasi')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-full text-xs">
                    Tutup
                </button>
                <button type="button" onclick="alert('Membuka kanvas simulasi penuh untuk 3 Tahap Game...'); closeModal('modal-simulasi');" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-full text-xs shadow-md">
                    Buka Kanvas Penuh →
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Materi Modal (6 Simbol Standar Flowchart) -->
    <div id="modal-materi" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-lg">6 Simbol Standar Flowchart ANSI/ISO</h3>
                    <p class="text-xs text-slate-500">Materi dasar logika alur algoritma SMK RPL & TKJ</p>
                </div>
                <button type="button" onclick="closeModal('modal-materi')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm">✕</button>
            </div>

            <div class="py-4 space-y-3">
                <!-- Simbol 1: Terminator -->
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="w-20 text-center shrink-0">
                        <span class="inline-block bg-rose-500 text-white text-[10px] font-black px-3 py-1 rounded-full">Terminator</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Oval / Kapsul (Terminator)</div>
                        <div class="text-[11px] text-slate-500">Menyatakan awal (START) atau akhir (STOP) suatu algoritma alur program.</div>
                    </div>
                </div>

                <!-- Simbol 2: Process -->
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="w-20 text-center shrink-0">
                        <span class="inline-block bg-emerald-500 text-white text-[10px] font-black px-3 py-1 rounded">Process</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Persegi Panjang (Proses)</div>
                        <div class="text-[11px] text-slate-500">Menyatakan kalkulasi aritmatika, penugasan variabel, atau pemrosesan data.</div>
                    </div>
                </div>

                <!-- Simbol 3: Decision -->
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="w-20 text-center shrink-0">
                        <div class="w-6 h-6 bg-amber-400 text-amber-950 font-black text-[9px] flex items-center justify-center rotate-45 rounded-xs mx-auto">
                            <span class="-rotate-45">IF</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Belah Ketupat (Decision / Cabang)</div>
                        <div class="text-[11px] text-slate-500">Percabangan logika dengan dua kemungkinan keluaran: Ya (True) / Tidak (False).</div>
                    </div>
                </div>

                <!-- Simbol 4: Input / Output -->
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="w-20 text-center shrink-0">
                        <span class="inline-block bg-sky-500 text-white text-[10px] font-black px-2.5 py-1 -skew-x-12 rounded">Input/Output</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Jajar Genjang (Data I/O)</div>
                        <div class="text-[11px] text-slate-500">Operasi menerima input dari pengguna atau menampilkan output/hasil program.</div>
                    </div>
                </div>

                <!-- Simbol 5: Predefined Process -->
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="w-20 text-center shrink-0">
                        <span class="inline-block bg-purple-500 text-white text-[10px] font-black px-2.5 py-1 rounded border-x-2 border-white">Subroutine</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Sub-Program / Function</div>
                        <div class="text-[11px] text-slate-500">Menjalankan blok modul, fungsi terpisah, atau prosedur lain.</div>
                    </div>
                </div>

                <!-- Simbol 6: On-page Connector -->
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="w-20 text-center shrink-0">
                        <span class="inline-block w-6 h-6 bg-slate-700 text-white text-[10px] font-black rounded-full leading-6">○</span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Konektor (Connector)</div>
                        <div class="text-[11px] text-slate-500">Menghubungkan alur flowchart dalam halaman yang sama tanpa garis bertumpuk.</div>
                    </div>
                </div>
            </div>

            <button type="button" onclick="closeModal('modal-materi')" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-xs uppercase py-3 rounded-full mt-2">
                Mengerti & Lanjut Belajar
            </button>
        </div>
    </div>

    <!-- Modal 3: Quiz Selection Modal -->
    <div id="modal-quiz" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Pilih Mode Quiz Interaktif</h3>
                    <p class="text-xs text-slate-500">Dapatkan +100 poin untuk setiap quiz yang diselesaikan</p>
                </div>
                <button type="button" onclick="closeModal('modal-quiz')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm">✕</button>
            </div>

            <div class="py-4 space-y-3">
                <!-- Option 1: Quiz Pengetahuan -->
                <div onclick="startQuiz('Quiz Pengetahuan Teori Simbol')" class="p-4 rounded-2xl border-2 border-amber-200 bg-amber-50/60 hover:bg-amber-100/70 transition cursor-pointer flex items-center gap-3.5 group">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-sm">
                        📖
                    </div>
                    <div class="flex-1">
                        <div class="font-extrabold text-slate-900 text-sm group-hover:text-amber-900">Quiz Pengetahuan</div>
                        <div class="text-xs text-slate-500">10 Soal pilihan ganda identifikasi simbol & pseudocode</div>
                    </div>
                    <span class="text-amber-600 font-extrabold text-xs">Mulai →</span>
                </div>

                <!-- Option 2: Quiz Keterampilan -->
                <a href="{{ route('quiz.keterampilan') }}" class="p-4 rounded-2xl border-2 border-emerald-200 bg-emerald-50/60 hover:bg-emerald-100/70 transition cursor-pointer flex items-center gap-3.5 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-sm">
                        📋
                    </div>
                    <div class="flex-1">
                        <div class="font-extrabold text-slate-900 text-sm group-hover:text-emerald-900">Quiz Keterampilan</div>
                        <div class="text-xs text-slate-500">Tantangan praktik menyusun flowchart algoritma visual</div>
                    </div>
                    <span class="text-emerald-600 font-extrabold text-xs">Mulai →</span>
                </a>
            </div>

            <button type="button" onclick="closeModal('modal-quiz')" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-full text-xs">
                Kembali ke Beranda
            </button>
        </div>
    </div>

    <!-- Modal 4: Student Profile Modal -->
    <div id="modal-profile" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center">
            <div class="w-16 h-16 rounded-full ring-4 ring-sky-200 bg-sky-50 mx-auto flex items-center justify-center overflow-hidden mb-3">
                <svg class="w-full h-full text-sky-800" viewBox="0 0 40 40" fill="none">
                    <circle cx="20" cy="20" r="20" fill="#E0F2FE" />
                    <circle cx="20" cy="15" r="7" fill="#FBCFE8" />
                    <path d="M20 9C15 9 14 13 14 15C14 16 15 17 20 17C25 17 26 16 26 15C26 13 25 9 20 9Z" fill="#1E293B" />
                    <path d="M9 36C9 28 14 25 20 25C26 25 31 28 31 36" fill="#FFFFFF" />
                    <path d="M18 25L20 31L22 25" fill="#0284C7" />
                </svg>
            </div>
            @auth
                <h3 class="font-extrabold text-slate-900 text-base">{{ Auth::user()->name }}</h3>
                <p class="text-xs text-slate-500 mb-1">{{ Auth::user()->email }}</p>
                <p class="text-xs text-slate-600 font-semibold mb-2">{{ Auth::user()->school ?? 'SMK Negeri' }}</p>
                <span class="bg-[#FEE3A2] text-[#9A3412] font-black text-[10px] px-2.5 py-1 rounded-full uppercase tracking-wider inline-block mb-4">
                    {{ Auth::user()->role === 'guru' ? 'Guru / Pembimbing' : 'Siswa SMK' }}
                </span>

                <div class="grid grid-cols-2 gap-3 text-left bg-slate-50 p-3.5 rounded-2xl mb-4 border border-slate-200/80">
                    <div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase block">Status Akun</span>
                        <span class="text-emerald-600 font-black text-sm">Aktif</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase block">Modul Tuntas</span>
                        <span class="text-amber-600 font-black text-sm">3 / 3 Modul</span>
                    </div>
                </div>
            @else
                <h3 class="font-extrabold text-slate-900 text-base">Profil Akun</h3>
                <p class="text-xs text-slate-500 mb-4">Anda belum masuk. Silakan masuk atau daftar untuk menyimpan progres pembelajaran Anda.</p>
            @endauth

            <div class="space-y-2">
                <button type="button" onclick="closeModal('modal-profile')" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-xs uppercase py-2.5 rounded-full transition">
                    Tutup Profil
                </button>

                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs uppercase py-2.5 rounded-full transition cursor-pointer">
                            Keluar (Logout)
                        </button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="{{ route('login') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs py-2 rounded-full block text-center transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs py-2 rounded-full block text-center transition">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Generic Info Modal -->
    <div id="modal-info" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100">
            <h3 id="info-title" class="font-black text-slate-900 text-base mb-2"></h3>
            <p id="info-desc" class="text-xs text-slate-600 leading-relaxed mb-4"></p>
            <button type="button" onclick="closeModal('modal-info')" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase py-2.5 rounded-full">
                Tutup
            </button>
        </div>
    </div>

    <!-- Simple Vanilla JS for Interactivity -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        function toggleQuizDropdown(event) {
            if (event) {
                event.stopPropagation();
            }
            const menu = document.getElementById('quiz-dropdown-menu');
            const arrow = document.getElementById('quiz-dropdown-arrow');
            const btn = document.getElementById('quiz-dropdown-btn');
            
            const isCurrentlyOpen = !menu.classList.contains('hidden');
            if (isCurrentlyOpen) {
                closeQuizDropdown();
            } else {
                menu.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
                if (btn) btn.classList.add('bg-white', 'text-slate-900', 'shadow-2xs');
            }
        }

        function closeQuizDropdown() {
            const menu = document.getElementById('quiz-dropdown-menu');
            const arrow = document.getElementById('quiz-dropdown-arrow');
            const btn = document.getElementById('quiz-dropdown-btn');
            if (menu) menu.classList.add('hidden');
            if (arrow) arrow.classList.remove('rotate-180');
            if (btn) btn.classList.remove('bg-white', 'text-slate-900', 'shadow-2xs');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('quiz-dropdown-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                closeQuizDropdown();
            }
        });

        function openSimulationQuickModal() {
            document.getElementById('modal-simulasi').classList.remove('hidden');
            document.getElementById('modal-simulasi').classList.add('flex');
        }

        function openMateriModal() {
            document.getElementById('modal-materi').classList.remove('hidden');
            document.getElementById('modal-materi').classList.add('flex');
        }

        function openQuizModal(type) {
            document.getElementById('modal-quiz').classList.remove('hidden');
            document.getElementById('modal-quiz').classList.add('flex');
        }

        function openProfileModal() {
            document.getElementById('modal-profile').classList.remove('hidden');
            document.getElementById('modal-profile').classList.add('flex');
        }

        function openInfoModal(title, text) {
            document.getElementById('info-title').innerText = title;
            document.getElementById('info-desc').innerText = text;
            document.getElementById('modal-info').classList.remove('hidden');
            document.getElementById('modal-info').classList.add('flex');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function updateSimulationScore(val) {
            document.getElementById('sim-score-val').innerText = val;
            const label = document.getElementById('slider-label');
            const statusBox = document.getElementById('sim-status-box');

            if (parseInt(val) >= 75) {
                label.innerText = val + ' (LULUS)';
                label.className = 'text-emerald-600 font-extrabold';
                statusBox.className = 'p-3 rounded-xl bg-emerald-100 text-emerald-800 text-xs font-semibold text-center';
                statusBox.innerText = '🎉 Nilai ' + val + ' >= 75. Alur percabangan mengambil jalur TRUE (Lulus).';
            } else {
                label.innerText = val + ' (REMEDIAL)';
                label.className = 'text-rose-600 font-extrabold';
                statusBox.className = 'p-3 rounded-xl bg-rose-100 text-rose-800 text-xs font-semibold text-center';
                statusBox.innerText = '⚠️ Nilai ' + val + ' < 75. Alur percabangan mengambil jalur FALSE (Remedial).';
            }
        }

        function startQuiz(quizName) {
            closeModal('modal-quiz');
            alert('Memulai ' + quizName + '! Soal 1 dari 10 telah disiapkan.');
        }

        // Close modal or dropdown on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQuizDropdown();
                ['modal-simulasi', 'modal-materi', 'modal-quiz', 'modal-profile', 'modal-info'].forEach(closeModal);
            }
        });
    </script>
</body>
</html>
