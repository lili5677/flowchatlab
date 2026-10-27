<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simbol Standar Flowchart - Flowchart Lab SMK</title>
    <meta name="description" content="Modul 01 Standarisasi ANSI/ISO Simbol Standar Flowchart dan Studi Kasus Algoritma Bilangan Ganjil Genap untuk Siswa SMK.">

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

        .rhombus-shape {
            width: 140px;
            height: 70px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="min-h-screen bg-[#F8BA15] text-slate-900 flex flex-col antialiased selection:bg-amber-200 selection:text-amber-900">

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
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Beranda
                </a>
                <a href="{{ route('materi') }}" class="bg-[#F4F1EA] text-slate-900 font-bold px-4 py-1.5 rounded-full text-[13px] shadow-2xs transition">
                    Materi
                </a>
                <a href="{{ route('home') }}#simulasi" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
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
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Beranda</a>
            <a href="{{ route('materi') }}" class="block px-3 py-2 rounded-xl text-sm font-bold bg-[#F4F1EA] text-slate-900">Materi Standar Flowchart</a>
            <a href="{{ route('home') }}#simulasi" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Simulasi Interaktif</a>
            <a href="#quiz" onclick="openQuizModal('all')" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Quiz Interaktif</a>
        </div>
    </header>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-8">

            <!-- ================= TOP HEADER CARD: MODUL 01 ================= -->
            <div class="bg-white rounded-[32px] sm:rounded-[38px] p-6 sm:p-9 lg:p-10 shadow-xl shadow-amber-950/10 border border-white">
                <!-- Badges Header Row -->
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <!-- Left: Modul 01 Badge -->
                    <div class="bg-[#059669] text-white font-black text-[11px] sm:text-xs uppercase tracking-wider px-4 py-1.5 rounded-full inline-flex items-center gap-2 shadow-sm">
                        <!-- Module icon -->
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                        <span>MODUL 01: STANDARISASI ANSI / ISO</span>
                    </div>

                    <!-- Right Badges -->
                    <div class="flex items-center gap-2 sm:gap-2.5">
                        <span class="bg-[#FFF1F2] border border-[#FECDD3] text-[#E11D48] font-black text-[10px] sm:text-[11px] uppercase tracking-wider px-3.5 py-1 rounded-full">
                            STANDAR KURIKULUM
                        </span>
                        <span class="bg-[#FFF9EC] border border-[#FDE68A] text-[#92400E] font-bold text-[10px] sm:text-[11px] px-3.5 py-1 rounded-full">
                            Dasar Pemrograman RPL
                        </span>
                    </div>
                </div>

                <!-- Headline -->
                <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-black text-slate-900 tracking-tight leading-tight">
                    Simbol Standar 
                    <span class="relative inline-block text-[#EF4444]">
                        Flowchart
                        <!-- Wavy Underline -->
                        <svg class="absolute -bottom-2 sm:-bottom-2.5 left-0 w-full h-3 text-[#EF4444]" viewBox="0 0 160 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 8.5C18 3 34 11 50 8C66 5 82 11 98 8C114 5 130 11 146 7C152 5.5 156 8 158 8" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                        </svg>
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="text-slate-600 font-medium text-sm sm:text-base max-w-3xl leading-relaxed mt-4">
                    Pahami fungsi setiap bentuk simbol untuk menyusun algoritma yang valid, terstruktur, dan siap dieksekusi mesin.
                </p>
            </div>

            <!-- ================= SECTION 1: KATALOG 6 SIMBOL UTAMA ================= -->
            <section>
                <!-- Section Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Katalog 6 Simbol Utama</h2>
                        <p class="text-xs sm:text-sm text-slate-800 font-medium mt-1">Kuasai bentuk dan semantik operasional sebelum merakit diagram</p>
                    </div>
                    <div>
                        <span class="bg-white/95 text-slate-800 font-bold text-xs px-4 py-2 rounded-full shadow-2xs border border-white/60 inline-block">
                            6 Komponen Wajib
                        </span>
                    </div>
                </div>

                <!-- 6 Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">

                    <!-- CARD 1: Terminator -->
                    <div class="bg-white rounded-2xl p-4 shadow-lg shadow-amber-950/5 border border-white flex flex-col justify-between hover:-translate-y-1 hover:shadow-xl transition-all duration-200">
                        <div>
                            <!-- Header Badges -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-[#059669] text-white font-black text-[10px] tracking-wider uppercase px-2.5 py-1 rounded-full">
                                    01 / MULAI-AKHIR
                                </span>
                                <span class="bg-[#FEF3C7] text-[#92400E] font-black text-xs px-2.5 py-0.5 rounded-full">
                                    #1
                                </span>
                            </div>

                            <!-- Symbol Visual Box -->
                            <div class="bg-[#FFFDF7] border border-[#FDE6B7]/60 rounded-xl h-24 flex items-center justify-center my-2.5 p-3">
                                <div class="border-2 border-rose-500 bg-rose-50/70 text-rose-600 font-black text-xs sm:text-[13px] px-7 py-2.5 rounded-full shadow-xs">
                                    Mulai / Selesai
                                </div>
                            </div>

                            <!-- Title & Description -->
                            <h3 class="font-black text-slate-900 text-sm sm:text-base mt-1.5">Terminator</h3>
                            <p class="text-[11px] text-slate-600 leading-relaxed mt-1 mb-3 min-h-[32px]">
                                Menandai titik awal (Mulai) atau titik akhir (Selesai) dari suatu alur program.
                            </p>
                        </div>

                        <!-- Example Box -->
                        <div class="bg-[#FFFDF7] border border-amber-200/70 rounded-xl p-3 text-left">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">CONTOH PENGGUNAAN:</span>
                            <span class="text-xs font-bold text-rose-600 mt-0.5 block">Mulai, Selesai, Start, End</span>
                        </div>
                    </div>

                    <!-- CARD 2: Proses (Process) -->
                    <div class="bg-white rounded-2xl p-4 shadow-lg shadow-amber-950/5 border border-white flex flex-col justify-between hover:-translate-y-1 hover:shadow-xl transition-all duration-200">
                        <div>
                            <!-- Header Badges -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-[#059669] text-white font-black text-[10px] tracking-wider uppercase px-2.5 py-1 rounded-full">
                                    02 / KALKULASI
                                </span>
                                <span class="bg-[#FEF3C7] text-[#92400E] font-black text-xs px-2.5 py-0.5 rounded-full">
                                    #2
                                </span>
                            </div>

                            <!-- Symbol Visual Box -->
                            <div class="bg-[#FFFDF7] border border-[#FDE6B7]/60 rounded-xl h-24 flex items-center justify-center my-2.5 p-3">
                                <div class="border-2 border-blue-500 bg-blue-50/70 text-blue-600 font-black text-xs sm:text-[13px] px-6 py-2.5 rounded-lg shadow-xs">
                                    Proses Aritmatika
                                </div>
                            </div>

                            <!-- Title & Description -->
                            <h3 class="font-black text-slate-900 text-sm sm:text-base mt-1.5">Proses (Process)</h3>
                            <p class="text-[11px] text-slate-600 leading-relaxed mt-1 mb-3 min-h-[32px]">
                                Menunjukkan operasi perhitungan matematika atau pengolahan data internal program.
                            </p>
                        </div>

                        <!-- Example Box -->
                        <div class="bg-[#FFFDF7] border border-amber-200/70 rounded-xl p-3 text-left">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">CONTOH PENGGUNAAN:</span>
                            <span class="text-xs font-bold text-blue-600 mt-0.5 block">Sisa_Bagi = Bilangan % 2</span>
                        </div>
                    </div>

                    <!-- CARD 3: Keputusan (Decision) -->
                    <div class="bg-white rounded-2xl p-4 shadow-lg shadow-amber-950/5 border border-white flex flex-col justify-between hover:-translate-y-1 hover:shadow-xl transition-all duration-200">
                        <div>
                            <!-- Header Badges -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-[#059669] text-white font-black text-[10px] tracking-wider uppercase px-2.5 py-1 rounded-full">
                                    03 / LOGIKA IF-ELSE
                                </span>
                                <span class="bg-[#FEF3C7] text-[#92400E] font-black text-xs px-2.5 py-0.5 rounded-full">
                                    #3
                                </span>
                            </div>

                            <!-- Symbol Visual Box -->
                            <div class="bg-[#FFFDF7] border border-[#FDE6B7]/60 rounded-xl h-24 flex items-center justify-center my-2.5 p-3">
                                <div class="relative w-40 h-16 flex items-center justify-center">
                                    <svg class="absolute inset-0 w-full h-full text-red-500" viewBox="0 0 160 64" fill="none">
                                        <polygon points="80,4 154,32 80,60 6,32" fill="#FEF2F2" stroke="#EF4444" stroke-width="2.2" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="relative z-10 text-red-700 font-extrabold text-[11px] sm:text-xs">
                                        Kondisi (Ya / Tidak)
                                    </span>
                                </div>
                            </div>

                            <!-- Title & Description -->
                            <h3 class="font-black text-slate-900 text-sm sm:text-base mt-1.5">Keputusan (Decision)</h3>
                            <p class="text-[11px] text-slate-600 leading-relaxed mt-1 mb-3 min-h-[32px]">
                                Memeriksa kondisi logika percabangan bernilai Ya (True) atau Tidak (False).
                            </p>
                        </div>

                        <!-- Example Box -->
                        <div class="bg-[#FFFDF7] border border-amber-200/70 rounded-xl p-3 text-left">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">CONTOH PENGGUNAAN:</span>
                            <span class="text-xs font-bold text-emerald-700 mt-0.5 block">Apakah Sisa_Bagi == 0 ?</span>
                        </div>
                    </div>

                    <!-- CARD 4: Input / Output (Data) -->
                    <div class="bg-white rounded-2xl p-4 shadow-lg shadow-amber-950/5 border border-white flex flex-col justify-between hover:-translate-y-1 hover:shadow-xl transition-all duration-200">
                        <div>
                            <!-- Header Badges -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-[#059669] text-white font-black text-[10px] tracking-wider uppercase px-2.5 py-1 rounded-full">
                                    04 / MASUKAN &amp; LUARAN
                                </span>
                                <span class="bg-[#FEF3C7] text-[#92400E] font-black text-xs px-2.5 py-0.5 rounded-full">
                                    #4
                                </span>
                            </div>

                            <!-- Symbol Visual Box -->
                            <div class="bg-[#FFFDF7] border border-[#FDE6B7]/60 rounded-xl h-24 flex items-center justify-center my-2.5 p-3">
                                <div class="border-2 border-cyan-500 bg-cyan-50/70 text-cyan-700 font-black text-xs sm:text-[13px] px-6 py-2.5 -skew-x-12 rounded-sm shadow-xs">
                                    <span class="skew-x-12 inline-block">Input / Output</span>
                                </div>
                            </div>

                            <!-- Title & Description -->
                            <h3 class="font-black text-slate-900 text-sm sm:text-base mt-1.5">Input / Output (Data)</h3>
                            <p class="text-[11px] text-slate-600 leading-relaxed mt-1 mb-3 min-h-[32px]">
                                Menerima masukan data dari pengguna atau menampilkan hasil keluaran ke layar.
                            </p>
                        </div>

                        <!-- Example Box -->
                        <div class="bg-[#FFFDF7] border border-amber-200/70 rounded-xl p-3 text-left">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">CONTOH PENGGUNAAN:</span>
                            <span class="text-xs font-bold text-blue-600 mt-0.5 block">Input angka; Cetak "Genap"</span>
                        </div>
                    </div>

                    <!-- CARD 5: Konektor (Connector) -->
                    <div class="bg-white rounded-2xl p-4 shadow-lg shadow-amber-950/5 border border-white flex flex-col justify-between hover:-translate-y-1 hover:shadow-xl transition-all duration-200">
                        <div>
                            <!-- Header Badges -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-[#059669] text-white font-black text-[10px] tracking-wider uppercase px-2.5 py-1 rounded-full">
                                    05 / SAMBUNGAN
                                </span>
                                <span class="bg-[#FEF3C7] text-[#92400E] font-black text-xs px-2.5 py-0.5 rounded-full">
                                    #5
                                </span>
                            </div>

                            <!-- Symbol Visual Box -->
                            <div class="bg-[#FFFDF7] border border-[#FDE6B7]/60 rounded-xl h-24 flex items-center justify-center my-2.5 p-3">
                                <div class="w-12 h-12 rounded-full border-2 border-purple-500 bg-purple-50/70 text-purple-700 font-black text-sm flex items-center justify-center shadow-xs">
                                    A
                                </div>
                            </div>

                            <!-- Title & Description -->
                            <h3 class="font-black text-slate-900 text-sm sm:text-base mt-1.5">Konektor (Connector)</h3>
                            <p class="text-[11px] text-slate-600 leading-relaxed mt-1 mb-3 min-h-[32px]">
                                Menghubungkan alur flowchart dalam halaman yang sama tanpa garis bertabrakan.
                            </p>
                        </div>

                        <!-- Example Box -->
                        <div class="bg-[#FFFDF7] border border-amber-200/70 rounded-xl p-3 text-left">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">CONTOH PENGGUNAAN:</span>
                            <span class="text-xs font-bold text-purple-700 mt-0.5 block">Label sambungan "A" atau "1"</span>
                        </div>
                    </div>

                    <!-- CARD 6: Arah Alur (Flowline) -->
                    <div class="bg-white rounded-2xl p-4 shadow-lg shadow-amber-950/5 border border-white flex flex-col justify-between hover:-translate-y-1 hover:shadow-xl transition-all duration-200">
                        <div>
                            <!-- Header Badges -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-[#059669] text-white font-black text-[10px] tracking-wider uppercase px-2.5 py-1 rounded-full">
                                    06 / NAVIGASI
                                </span>
                                <span class="bg-[#FEF3C7] text-[#92400E] font-black text-xs px-2.5 py-0.5 rounded-full">
                                    #6
                                </span>
                            </div>

                            <!-- Symbol Visual Box -->
                            <div class="bg-[#FFFDF7] border border-[#FDE6B7]/60 rounded-xl h-24 flex flex-col items-center justify-center my-2.5 p-3">
                                <span class="text-[10px] font-bold text-slate-600 mb-1">Arah Aliran</span>
                                <div class="flex items-center">
                                    <div class="h-0.5 w-24 bg-slate-900"></div>
                                    <div class="w-0 h-0 border-y-[5px] border-y-transparent border-l-[8px] border-l-slate-900"></div>
                                </div>
                            </div>

                            <!-- Title & Description -->
                            <h3 class="font-black text-slate-900 text-sm sm:text-base mt-1.5">Arah Alur (Flowline)</h3>
                            <p class="text-[11px] text-slate-600 leading-relaxed mt-1 mb-3 min-h-[32px]">
                                Menunjukkan arah jalannya instruksi algoritma dari satu langkah ke langkah berikutnya.
                            </p>
                        </div>

                        <!-- Example Box -->
                        <div class="bg-[#FFFDF7] border border-amber-200/70 rounded-xl p-3 text-left">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">CONTOH PENGGUNAAN:</span>
                            <span class="text-xs font-bold text-slate-700 mt-0.5 block">Panah dari Input ke Proses</span>
                        </div>
                    </div>

                </div>
            </section>

            <!-- ================= SECTION 2: STUDI KASUS VOKASI SMK ================= -->
            <section class="bg-white rounded-[32px] sm:rounded-[38px] p-6 sm:p-9 lg:p-10 shadow-2xl shadow-amber-950/10 border border-white">
                
                <!-- Section Badges Header -->
                <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                    <!-- Left: Studi Kasus Badge -->
                    <div class="bg-[#FFF5EA] text-[#C2410C] border border-[#FED7AA] font-black text-[10px] sm:text-[11px] uppercase tracking-wider px-3.5 py-1.5 rounded-full inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                        <span>STUDI KASUS VOKASI SMK</span>
                    </div>

                    <!-- Right: Tingkat Badge -->
                    <div class="bg-[#FFF1F2] text-[#E11D48] border border-[#FECDD3] font-bold text-[10px] sm:text-[11px] px-3.5 py-1.5 rounded-full inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M3 9h18M9 21V9"/>
                        </svg>
                        <span>Tingkat: Dasar (Percabangan 1 Jalur)</span>
                    </div>
                </div>

                <!-- Section Title & Subtitle -->
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-3">
                    Menentukan Bilangan Ganjil atau Genap
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mt-1 mb-8">
                    Penerapan seluruh 6 simbol di atas ke dalam diagram alir komputasi logika nyata.
                </p>

                <!-- Two Column Layout: Diagram on Left, Step Explanations on Right -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- LEFT COLUMN: DIAGRAM VISUAL -->
                    <div class="lg:col-span-6 bg-[#FFFDF7] border-2 border-[#FDE68A] rounded-[28px] p-6 sm:p-8 flex flex-col items-center shadow-xs">
                        
                        <!-- 1. Mulai (Terminator) -->
                        <div class="border-2 border-rose-400 bg-white text-rose-600 font-extrabold text-xs px-9 py-2 rounded-full shadow-2xs">
                            Mulai
                        </div>

                        <!-- Down Arrow -->
                        <div class="flex flex-col items-center my-0.5">
                            <div class="w-0.5 h-5 bg-slate-800"></div>
                            <div class="w-0 h-0 border-x-[4px] border-x-transparent border-t-[6px] border-t-slate-800"></div>
                        </div>

                        <!-- 2. Input Bilangan Bulat (Input/Output) -->
                        <div class="border-2 border-sky-400 bg-white text-sky-800 font-extrabold text-xs px-8 py-2.5 -skew-x-12 rounded shadow-2xs">
                            <span class="skew-x-12 inline-block">Input Bilangan Bulat</span>
                        </div>

                        <!-- Down Arrow -->
                        <div class="flex flex-col items-center my-0.5">
                            <div class="w-0.5 h-5 bg-slate-800"></div>
                            <div class="w-0 h-0 border-x-[4px] border-x-transparent border-t-[6px] border-t-slate-800"></div>
                        </div>

                        <!-- 3. Hitung: Sisa = Bilangan % 2 (Process) -->
                        <div class="border-2 border-sky-400 bg-white text-slate-800 font-bold text-xs px-6 py-2.5 rounded shadow-2xs">
                            Hitung: Sisa = Bilangan % 2
                        </div>

                        <!-- Down Arrow -->
                        <div class="flex flex-col items-center my-0.5">
                            <div class="w-0.5 h-5 bg-slate-800"></div>
                            <div class="w-0 h-0 border-x-[4px] border-x-transparent border-t-[6px] border-t-slate-800"></div>
                        </div>

                        <!-- 4. Decision Diamond -->
                        <div class="relative w-44 h-20 flex items-center justify-center my-1">
                            <svg class="absolute inset-0 w-full h-full text-red-500" viewBox="0 0 176 80" fill="none">
                                <polygon points="88,4 170,40 88,76 6,40" fill="#FFFFFF" stroke="#EF4444" stroke-width="2.2" stroke-linejoin="round"/>
                            </svg>
                            <span class="relative z-10 text-red-600 font-extrabold text-xs text-center leading-snug">
                                Apakah<br>Sisa == 0 ?
                            </span>
                        </div>

                        <!-- Branching Arrows (Left & Right) -->
                        <div class="w-full max-w-[280px] sm:max-w-[320px] relative mt-1">
                            
                            <!-- Branch connector lines -->
                            <div class="w-full flex justify-between items-center relative">
                                <!-- Left Branch (True / Ya) -->
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full flex justify-end">
                                        <div class="h-0.5 w-1/2 bg-slate-800"></div>
                                    </div>
                                    <div class="w-full flex items-center justify-start pl-2">
                                        <div class="w-0.5 h-4 bg-slate-800"></div>
                                    </div>
                                    <!-- Ya Badge -->
                                    <span class="bg-[#059669] text-white text-[9px] font-black px-2.5 py-0.5 rounded-full my-1 shadow-2xs">
                                        Ya (True)
                                    </span>
                                    <div class="w-0.5 h-3 bg-slate-800"></div>
                                    <div class="w-0 h-0 border-x-[4px] border-x-transparent border-t-[6px] border-t-slate-800 mb-1"></div>
                                    
                                    <!-- Cetak Genap -->
                                    <div class="border-2 border-slate-700 bg-white text-slate-900 font-bold text-xs px-4 py-2 rounded shadow-2xs text-center w-full max-w-[125px]">
                                        Cetak "Genap"
                                    </div>
                                    <div class="w-0.5 h-5 bg-slate-800 mt-1"></div>
                                </div>

                                <!-- Center Spacer from diamond point -->

                                <!-- Right Branch (False / Tidak) -->
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full flex justify-start">
                                        <div class="h-0.5 w-1/2 bg-slate-800"></div>
                                    </div>
                                    <div class="w-full flex items-center justify-end pr-2">
                                        <div class="w-0.5 h-4 bg-slate-800"></div>
                                    </div>
                                    <!-- Tidak Badge -->
                                    <span class="bg-[#DC2626] text-white text-[9px] font-black px-2.5 py-0.5 rounded-full my-1 shadow-2xs">
                                        Tidak (False)
                                    </span>
                                    <div class="w-0.5 h-3 bg-slate-800"></div>
                                    <div class="w-0 h-0 border-x-[4px] border-x-transparent border-t-[6px] border-t-slate-800 mb-1"></div>

                                    <!-- Cetak Ganjil -->
                                    <div class="border-2 border-slate-700 bg-white text-slate-900 font-bold text-xs px-4 py-2 rounded shadow-2xs text-center w-full max-w-[125px]">
                                        Cetak "Ganjil"
                                    </div>
                                    <div class="w-0.5 h-5 bg-slate-800 mt-1"></div>
                                </div>
                            </div>

                            <!-- Horizontal convergence line to Selesai -->
                            <div class="w-full flex justify-center relative">
                                <div class="w-[190px] sm:w-[220px] h-0.5 bg-slate-800"></div>
                            </div>
                        </div>

                        <!-- Down Arrow to Selesai -->
                        <div class="flex flex-col items-center my-0.5">
                            <div class="w-0.5 h-5 bg-slate-800"></div>
                            <div class="w-0 h-0 border-x-[4px] border-x-transparent border-t-[6px] border-t-slate-800"></div>
                        </div>

                        <!-- 5. Selesai (Terminator) -->
                        <div class="border-2 border-rose-400 bg-white text-rose-600 font-extrabold text-xs px-9 py-2 rounded-full shadow-2xs">
                            Selesai
                        </div>

                    </div>

                    <!-- RIGHT COLUMN: ALGORITMA LANGKAH BERTAHAP & CTA -->
                    <div class="lg:col-span-6 flex flex-col justify-between h-full space-y-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Algoritma Langkah Bertahap</h3>

                            <!-- 4 Step Cards -->
                            <div class="space-y-3.5">
                                
                                <!-- Step 1 -->
                                <div class="bg-[#FFFDF9] border border-amber-200/70 rounded-2xl p-4 flex items-start gap-3.5 shadow-2xs">
                                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0 mt-0.5">
                                        1
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs sm:text-[13px]">Input Nilai Angka</div>
                                        <p class="text-[11px] sm:text-xs text-slate-600 mt-0.5 leading-relaxed">
                                            Komputer menerima satu bilangan integer positif atau negatif dari pengguna.
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <div class="bg-[#FFFDF9] border border-amber-200/70 rounded-2xl p-4 flex items-start gap-3.5 shadow-2xs">
                                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0 mt-0.5">
                                        2
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs sm:text-[13px]">Operasi Modulo (Sisa Pembagian)</div>
                                        <p class="text-[11px] sm:text-xs text-slate-600 mt-0.5 leading-relaxed">
                                            Sistem menghitung sisa hasil bagi dengan membagi bilangan tersebut dengan angka 2.
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div class="bg-[#FFFDF9] border border-amber-200/70 rounded-2xl p-4 flex items-start gap-3.5 shadow-2xs">
                                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0 mt-0.5">
                                        3
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs sm:text-[13px]">Evaluasi Percabangan Logika</div>
                                        <p class="text-[11px] sm:text-xs text-slate-600 mt-0.5 leading-relaxed">
                                            Jika sisa bernilai 0 arahkan ke cabang <span class="bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded text-[10px]">Ya</span>, sebaliknya jika tidak 0 masuk ke cabang <span class="bg-rose-100 text-rose-800 font-bold px-1.5 py-0.5 rounded text-[10px]">Tidak</span>.
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 4 -->
                                <div class="bg-[#FFFDF9] border border-amber-200/70 rounded-2xl p-4 flex items-start gap-3.5 shadow-2xs">
                                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white font-black text-xs flex items-center justify-center shrink-0 mt-0.5">
                                        4
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs sm:text-[13px]">Tampilkan Output &amp; Terminasi</div>
                                        <p class="text-[11px] sm:text-xs text-slate-600 mt-0.5 leading-relaxed">
                                            Cetak teks hasil klasifikasi ke konsol, lalu eksekusi dihentikan secara resmi.
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Bottom Dark CTA Box -->
                        <div class="bg-[#18181B] text-white rounded-3xl p-5 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl shadow-zinc-950/20 mt-4 border border-zinc-800">
                            <div>
                                <div class="font-extrabold text-sm sm:text-base text-white">Sudah menguasai simbol?</div>
                                <div class="text-xs text-zinc-400 mt-0.5">Uji langsung dengan merakit Flowchart secara interaktif.</div>
                            </div>
                            <a href="{{ route('home') }}#simulasi" class="w-full sm:w-auto bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-xs uppercase tracking-wider px-6 py-3 rounded-full flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:scale-105 active:scale-95 shrink-0">
                                <span>Lanjut ke Simulasi</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>

                    </div>

                </div>

            </section>

        </div>
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-[#18181B] text-slate-400 py-10 px-4 sm:px-6 lg:px-8 border-t border-zinc-800 mt-auto">
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
                <a href="{{ route('home') }}#simulasi" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-full text-xs shadow-md text-center">
                    Buka Kanvas Penuh →
                </a>
            </div>
        </div>
    </div>

    <!-- Modal 2: Quiz Selection Modal -->
    <div id="modal-quiz" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Pilih Mode Quiz Interaktif</h3>
                    <p class="text-xs text-slate-500">Uji kemampuan pemahaman flowchart logika SMK</p>
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
                        <div class="text-xs text-slate-500">10 Soal pilihan ganda identifikasi simbol &amp; pseudocode</div>
                    </div>
                    <span class="text-amber-600 font-extrabold text-xs">Mulai →</span>
                </div>

                <!-- Option 2: Quiz Keterampilan -->
                <div onclick="startQuiz('Quiz Keterampilan Praktik Alur')" class="p-4 rounded-2xl border-2 border-emerald-200 bg-emerald-50/60 hover:bg-emerald-100/70 transition cursor-pointer flex items-center gap-3.5 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg shadow-sm">
                        📋
                    </div>
                    <div class="flex-1">
                        <div class="font-extrabold text-slate-900 text-sm group-hover:text-emerald-900">Quiz Keterampilan</div>
                        <div class="text-xs text-slate-500">Tantangan drag-and-drop menyusun algoritma perulangan</div>
                    </div>
                    <span class="text-emerald-600 font-extrabold text-xs">Mulai →</span>
                </div>
            </div>

            <button type="button" onclick="closeModal('modal-quiz')" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-full text-xs">
                Tutup
            </button>
        </div>
    </div>

    <!-- Modal 3: Profile Modal -->
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
                <button type="button" onclick="closeModal('modal-profile')" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-xs uppercase py-2.5 rounded-full transition cursor-pointer">
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
                        <a href="{{ route('login') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs py-2.5 rounded-full block text-center transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs py-2.5 rounded-full block text-center transition">
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

    <!-- Interactivity Scripts -->
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
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
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

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQuizDropdown();
                ['modal-simulasi', 'modal-quiz', 'modal-profile', 'modal-info'].forEach(closeModal);
            }
        });
    </script>
</body>
</html>
