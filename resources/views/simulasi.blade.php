<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulasi Interaktif Flowchart - Flowchart Lab SMK</title>
    <meta name="description" content="Simulasi interaktif logika algoritma alur kerja flowchart khusus siswa SMK. Bangun, pasang simbol, dan uji eksekusi alur program secara visual.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #F8BA15;
            color: #0F172A;
        }

        /* SVG & Node connector pulse */
        @keyframes pulseLine {
            0% { stroke-dashoffset: 24; }
            100% { stroke-dashoffset: 0; }
        }

        .line-active {
            stroke: #10B981 !important;
            stroke-width: 3.5 !important;
            stroke-dasharray: 6 3;
            animation: pulseLine 0.8s linear infinite;
        }

        /* Glowing shadow for running node */
        .node-highlight {
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.4), 0 10px 25px -5px rgba(16, 185, 129, 0.3) !important;
            transform: scale(1.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Parallelogram (Jajar Genjang) styling for Input / Output nodes */
        .shape-parallelogram {
            transform: skewX(-16deg);
            transform-origin: center;
        }

        .shape-parallelogram.node-highlight {
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.4), 0 10px 25px -5px rgba(16, 185, 129, 0.3) !important;
            transform: skewX(-16deg) scale(1.03) !important;
        }

        .shape-parallelogram-content {
            transform: skewX(16deg);
        }

        /* Shake animation for error */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }

        .shake-node {
            animation: shake 0.4s ease-in-out;
        }

        /* Pop animation on symbol place */
        @keyframes popIn {
            0% { transform: scale(0.9); opacity: 0; }
            70% { transform: scale(1.04); }
            100% { transform: scale(1); opacity: 1; }
        }

        .pop-in {
            animation: popIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        /* Confetti particle styling */
        .confetti-particle {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 2px;
            pointer-events: none;
            z-index: 100;
        }
    </style>
</head>
<body class="min-h-screen bg-[#F8BA15] text-slate-900 flex flex-col antialiased selection:bg-amber-200 selection:text-amber-900">

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
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Beranda
                </a>
                <a href="{{ route('materi') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Materi
                </a>
                <a href="{{ route('simulasi') }}" class="bg-amber-100 text-amber-900 font-bold px-4 py-1.5 rounded-full text-[13px] shadow-2xs transition">
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
                        <a href="{{ route('quiz.keterampilan') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-[11px]">🧩</span>
                            <span>Quiz Keterampilan</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Right: User Profile -->
            <div class="flex items-center gap-3 sm:gap-4">
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
                            <svg class="w-full h-full text-sky-800" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="20" fill="#E0F2FE" />
                                <circle cx="20" cy="15" r="7" fill="#FBCFE8" />
                                <path d="M20 9C15 9 14 13 14 15C14 16 15 17 20 17C25 17 26 16 26 15C26 13 25 9 20 9Z" fill="#1E293B" />
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

        <!-- Mobile Nav Menu -->
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
            <a href="{{ route('materi') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Materi Standar Flowchart</a>
            <a href="{{ route('simulasi') }}" class="block px-3 py-2 rounded-xl text-sm font-bold bg-[#F4F1EA] text-slate-900">Simulasi Interaktif</a>
            <a href="#quiz" onclick="openQuizModal('all')" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Quiz Interaktif</a>
        </div>
    </header>

    <!-- ================= MAIN SIMULATION SECTION ================= -->
    <main class="flex-1 py-6 sm:py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- ================= TOP STATUS BAR WITH LEVEL SELECTOR (1 BOX GABUNG) ================= -->
            <div id="top-status-card" class="bg-white rounded-2xl shadow-sm px-6 py-4 border border-amber-200/60 mb-6 transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Left: Status Eksekusi Alur -->
                <div class="flex-1 min-w-0">
                    <div class="text-[11px] font-black tracking-wider text-slate-900 uppercase">STATUS EKSEKUSI ALUR</div>
                    <div id="status-message" class="text-xs sm:text-sm text-slate-600 font-medium mt-0.5 flex items-center gap-2">
                        <span id="status-icon" class="inline-block text-amber-500 font-bold">●</span>
                        <span id="status-text">Slot keputusan belum terpasang. Klik kartu simbol pada panel kiri untuk mengisi.</span>
                    </div>
                </div>

                <!-- Right: Pilih Level Simulasi (1 Box dengan Status Eksekusi) -->
                <div class="flex items-center gap-3 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                    <span class="text-xs font-extrabold text-slate-700 whitespace-nowrap">Pilih Simulasi:</span>
                    <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-2xl border border-slate-200/80">
                        <button type="button" onclick="loadSimulation(1)" id="level-btn-1" class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center font-extrabold shadow-xs transition cursor-pointer">1</button>
                        <button type="button" onclick="loadSimulation(2)" id="level-btn-2" class="w-8 h-8 rounded-xl bg-transparent text-slate-600 hover:bg-white hover:shadow-2xs flex items-center justify-center font-extrabold transition cursor-pointer">2</button>
                        <button type="button" onclick="loadSimulation(3)" id="level-btn-3" class="w-8 h-8 rounded-xl bg-transparent text-slate-600 hover:bg-white hover:shadow-2xs flex items-center justify-center font-extrabold transition cursor-pointer">3</button>
                    </div>
                </div>
            </div>

            <!-- ================= WORKSPACE GRID ================= -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- ================= LEFT PANEL ================= -->
                <div class="lg:col-span-4 space-y-5">
                    
                    <!-- Card 1: Tantangan Kasus -->
                    <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-xl shadow-amber-950/5 border border-white">
                        <div class="flex items-center justify-between mb-4">
                            <span class="bg-[#F8BA15] text-[#78350F] font-black text-[11px] px-3.5 py-1 rounded-full uppercase tracking-wider">
                                TANTANGAN KASUS
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span id="challenge-step-badge" class="bg-amber-100 text-amber-900 font-black text-xs px-3 py-1 rounded-full border border-amber-200">
                                    1/3
                                </span>
                            </div>
                        </div>

                        <!-- Level Title -->
                        <h2 id="challenge-title" class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-snug mb-3">
                            Menentukan Kelulusan Nilai Siswa (KKM &ge; 75)
                        </h2>

                        <!-- Level Description -->
                        <p id="challenge-desc" class="text-xs sm:text-[13px] text-slate-600 leading-relaxed mb-6">
                            Sebuah sistem harus mengevaluasi nilai siswa. Jika nilai ujian minimal 75, cetak status <span class="bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded text-xs">"Lulus"</span>. Lengkapi blok keputusan yang belum terpasang dan periksa kesesuaian alurnya.
                        </p>

                        <!-- Action Button: Hanya Petunjuk (Tombol Reset telah dihapus) -->
                        <div>
                            <button type="button" 
                                    onclick="openHintModal()" 
                                    class="w-full bg-[#F8BA15] hover:bg-[#eab308] text-amber-950 font-extrabold text-xs py-3.5 rounded-full flex items-center justify-center gap-2 shadow-2xs transition active:scale-95 cursor-pointer">
                                <span>💡 Petunjuk Pengerjaan</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card 2: Palet Simbol Logika -->
                    <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-xl shadow-amber-950/5 border border-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-extrabold text-slate-900 text-base">Palet Simbol Logika</h3>
                            <span class="text-[11px] text-slate-400 font-semibold">Pilih Simbol</span>
                        </div>

                        <!-- 4 Symbol Choices (Default line box abu-abu, saat diklik berubah jadi oren) -->
                        <div class="space-y-3" id="palette-container">
                            
                            <!-- Choice 1: KEPUTUSAN (DECISION) -->
                            <div onclick="selectSymbolFromPalette('decision')" 
                                 id="palette-item-decision" 
                                 class="flex items-center justify-between p-3 sm:p-3.5 rounded-2xl border-2 border-slate-200 bg-white hover:border-slate-300 transition duration-200 cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div id="palette-icon-decision" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 flex items-center justify-center shadow-2xs shrink-0 transition-transform">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <polygon points="12 3 21 12 12 21 3 12" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-black text-slate-800 tracking-tight leading-tight">KEPUTUSAN (DECISION)</div>
                                        <div class="text-[10px] text-slate-500 mt-0.5">Percabangan logika Ya / Tidak</div>
                                    </div>
                                </div>
                                <button type="button" id="palette-btn-decision" class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center font-black text-sm transition">
                                    +
                                </button>
                            </div>

                            <!-- Choice 2: PROSES (PROCESS) -->
                            <div onclick="selectSymbolFromPalette('process')" 
                                 id="palette-item-process" 
                                 class="flex items-center justify-between p-3 sm:p-3.5 rounded-2xl border-2 border-slate-200 bg-white hover:border-slate-300 transition duration-200 cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div id="palette-icon-process" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 flex items-center justify-center shadow-2xs shrink-0 transition-transform">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <rect x="3" y="6" width="18" height="12" rx="2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-black text-slate-800 tracking-tight leading-tight">PROSES (PROCESS)</div>
                                        <div class="text-[10px] text-slate-500 mt-0.5">Operasi kalkulasi & rumus</div>
                                    </div>
                                </div>
                                <button type="button" id="palette-btn-process" class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center font-black text-sm transition">
                                    +
                                </button>
                            </div>

                            <!-- Choice 3: OUTPUT / I/O -->
                            <div onclick="selectSymbolFromPalette('io')" 
                                 id="palette-item-io" 
                                 class="flex items-center justify-between p-3 sm:p-3.5 rounded-2xl border-2 border-slate-200 bg-white hover:border-slate-300 transition duration-200 cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div id="palette-icon-io" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 flex items-center justify-center shadow-2xs shrink-0 transition-transform">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <polygon points="6 6 21 6 18 18 3 18" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-black text-slate-800 tracking-tight leading-tight">OUTPUT / I/O</div>
                                        <div class="text-[10px] text-slate-500 mt-0.5">Menerima data / Cetak hasil</div>
                                    </div>
                                </div>
                                <button type="button" id="palette-btn-io" class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center font-black text-sm transition">
                                    +
                                </button>
                            </div>

                            <!-- Choice 4: TERMINATOR -->
                            <div onclick="selectSymbolFromPalette('terminator')" 
                                 id="palette-item-terminator" 
                                 class="flex items-center justify-between p-3 sm:p-3.5 rounded-2xl border-2 border-slate-200 bg-white hover:border-slate-300 transition duration-200 cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div id="palette-icon-terminator" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 flex items-center justify-center shadow-2xs shrink-0 transition-transform">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <rect x="3" y="7" width="18" height="10" rx="5" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-black text-slate-800 tracking-tight leading-tight">TERMINATOR</div>
                                        <div class="text-[10px] text-slate-500 mt-0.5">Titik Awal (Start) & Selesai (Stop)</div>
                                    </div>
                                </div>
                                <button type="button" id="palette-btn-terminator" class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center font-black text-sm transition">
                                    +
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ================= RIGHT PANEL (FLOWCHART CANVAS) ================= -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-3xl shadow-xl shadow-amber-950/10 p-6 sm:p-8 min-h-[640px] relative flex flex-col border border-white overflow-hidden">
                        
                        <!-- Ambient Glow on Top Right -->
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-amber-300/40 rounded-full blur-3xl pointer-events-none"></div>

                        <!-- Canvas Top Controls Bar -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 relative z-10">
                            
                            <!-- Left Controls: Zoom & View -->
                            <div class="flex items-center gap-2">
                                <div class="bg-slate-50 border border-slate-200/80 rounded-full px-3 py-1 flex items-center gap-2 text-xs font-bold text-slate-600">
                                    <span>Zoom: <span id="zoom-label">100%</span></span>
                                    <button type="button" onclick="adjustZoom(-10)" class="hover:text-slate-900 cursor-pointer font-black px-0.5" title="Zoom Out">−</button>
                                    <button type="button" onclick="adjustZoom(10)" class="hover:text-slate-900 cursor-pointer font-black px-0.5" title="Zoom In">+</button>
                                </div>
                                <button type="button" onclick="resetZoom()" class="w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 border border-slate-200/80 text-slate-600 flex items-center justify-center font-bold text-xs cursor-pointer" title="Reset Zoom">
                                    ⛶
                                </button>
                            </div>

                            <!-- Right Control: Run Simulation Button -->
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        id="btn-run" 
                                        onclick="runSimulation()" 
                                        class="bg-[#10B981] hover:bg-[#059669] text-white w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30 transition transform hover:scale-105 active:scale-95 cursor-pointer relative group" 
                                        title="Jalankan Simulasi Alur">
                                    <svg class="w-5 h-5 fill-current ml-0.5" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <span class="sr-only">Jalankan Alur</span>
                                    <!-- Tooltip -->
                                    <span class="absolute -bottom-8 right-0 bg-slate-900 text-white text-[10px] font-bold px-2.5 py-1 rounded-md opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none shadow-md">
                                        Uji Alur (Play)
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Flowchart Board Canvas -->
                        <div id="flowchart-canvas" class="flex-1 flex flex-col items-center justify-center py-8 relative transition-transform duration-200 origin-top">

                            <!-- Dynamic Flowchart Content (Level 1, 2, or 3) -->
                            <div id="flowchart-render-area" class="w-full max-w-lg mx-auto flex flex-col items-center">
                                <!-- Populated dynamically by JavaScript -->
                            </div>

                        </div>

                        <!-- Real-time Variable Execution Console Log -->
                        <div id="execution-console" class="mt-4 bg-slate-900 text-slate-200 rounded-2xl p-3.5 font-mono text-xs hidden transition-all">
                            <div class="flex items-center justify-between text-[11px] text-slate-400 pb-1.5 border-b border-slate-800 font-sans font-bold">
                                <span>TERMINAL LOG EKSEKUSI ALUR</span>
                                <span id="console-badge" class="bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded text-[10px]">READY</span>
                            </div>
                            <div id="console-logs" class="pt-2 space-y-1 text-[11px] leading-relaxed max-h-24 overflow-y-auto">
                                <div>[0.00s] Sistem siap. Menunggu perintah eksekusi.</div>
                            </div>
                        </div>

                    </div>
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

    <!-- ================= MODALS ================= -->

    <!-- Modal: Success / Level Complete Modal -->
    <div id="modal-success" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 text-center animate-in fade-in zoom-in-95 duration-200">
            <div id="modal-success-icon" class="w-16 h-16 rounded-3xl bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center text-3xl mb-4 shadow-sm">
                🎉
            </div>
            
            <span id="modal-success-badge" class="bg-emerald-100 text-emerald-800 font-extrabold text-[10px] px-3 py-1 rounded-full uppercase tracking-wider inline-block mb-2">
                Tantangan Selesai
            </span>

            <h3 id="modal-success-title" class="font-black text-slate-900 text-xl tracking-tight mb-2">
                Luar Biasa! Tantangan 1/3 Berhasil!
            </h3>

            <p id="modal-success-desc" class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-5">
                Kamu berhasil memasang simbol Keputusan (Decision) dan logika percabangan IF-ELSE berjalan sesuai standar ANSI/ISO 5807.
            </p>

            <!-- Test Summary Card -->
            <div id="modal-success-summary" class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 mb-6 text-left text-xs space-y-1.5 font-medium text-slate-700">
                <!-- Injected via JS -->
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2.5">
                <button type="button" 
                        id="btn-modal-next" 
                        onclick="goToNextSimulation()" 
                        class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-xs uppercase tracking-wider py-3.5 rounded-full flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition active:scale-95 cursor-pointer">
                    <span id="btn-modal-next-text">Lanjut ke Simulasi 2: Menghitung Luas →</span>
                </button>
                <button type="button" 
                        onclick="closeModal('modal-success')" 
                        class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-2.5 rounded-full transition cursor-pointer">
                    Tutup & Lihat Kembali Kanvas
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Feedback Salah (Incorrect Feedback Modal) -->
    <div id="modal-error" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 text-center animate-in fade-in zoom-in-95 duration-200">
            <div class="w-16 h-16 rounded-3xl bg-rose-100 text-rose-600 mx-auto flex items-center justify-center text-3xl mb-4 shadow-sm">
                ⚠️
            </div>
            
            <span class="bg-rose-100 text-rose-800 font-extrabold text-[10px] px-3 py-1 rounded-full uppercase tracking-wider inline-block mb-2">
                Jawaban Belum Tepat
            </span>

            <h3 id="modal-error-title" class="font-black text-slate-900 text-lg tracking-tight mb-2">
                Simbol Belum Sesuai Kaidah
            </h3>

            <p id="modal-error-desc" class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">
                Simbol yang kamu pasang belum sesuai dengan aturan flowchart standar ANSI/ISO 5807.
            </p>

            <div class="flex gap-3">
                <button type="button" 
                        onclick="openHintModal(); closeModal('modal-error');" 
                        class="flex-1 bg-amber-400 hover:bg-amber-500 text-amber-950 font-extrabold text-xs py-3 rounded-full transition cursor-pointer shadow-sm">
                    💡 Baca Petunjuk
                </button>
                <button type="button" 
                        onclick="closeModal('modal-error')" 
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-3 rounded-full transition cursor-pointer">
                    Coba Ganti Simbol
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Petunjuk (Hint Modal) -->
    <div id="modal-hint" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-base">💡</span>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Petunjuk Penyelesaian Kasus</h3>
                        <p class="text-xs text-slate-500" id="hint-level-sub">Tantangan 1 dari 3</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-hint')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm">✕</button>
            </div>

            <div id="hint-content" class="text-xs sm:text-[13px] text-slate-600 space-y-3 leading-relaxed mb-6">
                <!-- Injected via JS -->
            </div>

            <button type="button" onclick="closeModal('modal-hint')" class="w-full bg-amber-400 hover:bg-amber-500 text-amber-950 font-extrabold text-xs uppercase py-3 rounded-full transition cursor-pointer">
                Saya Mengerti, Lanjutkan!
            </button>
        </div>
    </div>

    <!-- Modal: Profile Modal -->
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

    <!-- Modal: Quiz Selection Modal -->
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
                <div onclick="alert('Memulai Quiz Pengetahuan!'); closeModal('modal-quiz');" class="p-4 rounded-2xl border-2 border-amber-200 bg-amber-50/60 hover:bg-amber-100/70 transition cursor-pointer flex items-center gap-3.5 group">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-sm">
                        📖
                    </div>
                    <div class="flex-1">
                        <div class="font-extrabold text-slate-900 text-sm group-hover:text-amber-900">Quiz Pengetahuan</div>
                        <div class="text-xs text-slate-500">10 Soal pilihan ganda identifikasi simbol &amp; pseudocode</div>
                    </div>
                    <span class="text-amber-600 font-extrabold text-xs">Mulai →</span>
                </div>

                <div onclick="alert('Memulai Quiz Keterampilan!'); closeModal('modal-quiz');" class="p-4 rounded-2xl border-2 border-emerald-200 bg-emerald-50/60 hover:bg-emerald-100/70 transition cursor-pointer flex items-center gap-3.5 group">
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

    <!-- Modal: Generic Info Modal -->
    <div id="modal-info" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100">
            <h3 id="info-title" class="font-black text-slate-900 text-base mb-2"></h3>
            <p id="info-desc" class="text-xs text-slate-600 leading-relaxed mb-4"></p>
            <button type="button" onclick="closeModal('modal-info')" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase py-2.5 rounded-full">
                Tutup
            </button>
        </div>
    </div>

    <!-- ================= JAVASCRIPT LOGIC ================= -->
    <script>
        /* ================= SIMULATION CONFIGURATION ================= */
        const SIMULATION_CONFIG = {
            1: {
                stepBadge: '1/3',
                title: 'Menentukan Kelulusan Nilai Siswa (KKM ≥ 75)',
                desc: 'Sebuah sistem harus mengevaluasi nilai siswa. Jika nilai ujian minimal 75, cetak status <span class="bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded text-xs">"Lulus"</span>. Lengkapi blok keputusan yang belum terpasang dan periksa kesesuaian alurnya.',
                expectedSymbol: 'decision',
                slotPrompt: 'Klik / Pasang Simbol Keputusan',
                slotConditionText: '< Nilai >= 75 ? >',
                slotTextInside: 'Nilai >= 75 ?',
                hintText: `
                    <div class="p-3 bg-amber-50 rounded-2xl border border-amber-200 mb-2">
                        <strong class="text-amber-900 block mb-1">Kaidah Standar ANSI/ISO 5807:</strong>
                        Setiap titik dalam algoritma yang menghasilkan dua kemungkinan cabang (seperti <em>True/False</em> atau <em>Ya/Tidak</em>) WAJIB menggunakan <strong>Simbol Belah Ketupat (Decision)</strong>.
                    </div>
                    <p>Pada kasus kelulusan ini, sistem mengevaluasi ekspresi kondisi perbandingan. Pilih simbol <strong>KEPUTUSAN (DECISION)</strong> pada palet di sebelah kiri lalu klik tombol Putar (▶).</p>
                `,
                successTitle: 'Luar Biasa! Tantangan 1/3 Berhasil!',
                successDesc: 'Kamu berhasil memasang simbol Keputusan (Decision) dan logika percabangan IF-ELSE berjalan sesuai standar ANSI/ISO 5807.',
                nextLevel: 2,
                nextBtnText: 'Lanjut ke Simulasi 2: Menghitung Luas →'
            },
            2: {
                stepBadge: '2/3',
                title: 'Menghitung Luas Persegi Panjang (L = p × l)',
                desc: 'Sebuah sistem kalkulasi menerima input panjang dan lebar. Hitung luas dengan rumus <span class="bg-sky-100 text-sky-800 font-bold px-1.5 py-0.5 rounded text-xs">"Luas = p × l"</span>. Lengkapi blok proses perhitungan yang hilang agar program menghasilkan output yang akurat.',
                expectedSymbol: 'process',
                slotPrompt: 'Klik / Pasang Simbol Proses',
                slotConditionText: '< Luas = Panjang * Lebar >',
                slotTextInside: 'Luas = Panjang × Lebar',
                hintText: `
                    <div class="p-3 bg-sky-50 rounded-2xl border border-sky-200 mb-2">
                        <strong class="text-sky-900 block mb-1">Kaidah Standar ANSI/ISO 5807:</strong>
                        Setiap operasi kalkulasi aritmatika, rumus, atau penugasan variabel (assignment) WAJIB menggunakan <strong>Simbol Persegi Panjang (Process)</strong>.
                    </div>
                    <p>Pada kasus ini, algoritma melakukan perkalian antara <code>Panjang &times; Lebar</code>. Pilih simbol <strong>PROSES (PROCESS)</strong> pada palet lalu uji kembali alurnya.</p>
                `,
                successTitle: 'Mantap! Tantangan 2/3 Selesai!',
                successDesc: 'Simbol Proses (Process) berhasil mengeksekusi rumus perhitungan luas dengan akurat sebelum hasil dicetak.',
                nextLevel: 3,
                nextBtnText: 'Lanjut ke Simulasi 3: Perulangan (Looping) →'
            },
            3: {
                stepBadge: '3/3',
                title: 'Perulangan Menampilkan Angka (Loop While i ≤ 5)',
                desc: 'Algoritma harus menampilkan angka 1 sampai 5 secara berurutan. Pasang blok penambahan counter <span class="bg-purple-100 text-purple-800 font-bold px-1.5 py-0.5 rounded text-xs">"i = i + 1"</span> agar loop berjalan teratur dan tidak menimbulkan perulangan tanpa henti (infinite loop).',
                expectedSymbol: 'process',
                slotPrompt: 'Klik / Pasang Simbol Proses Counter',
                slotConditionText: '< i = i + 1 (Increment) >',
                slotTextInside: 'i = i + 1',
                hintText: `
                    <div class="p-3 bg-purple-50 rounded-2xl border border-purple-200 mb-2">
                        <strong class="text-purple-900 block mb-1">Kaidah Standar ANSI/ISO 5807:</strong>
                        Dalam struktur perulangan (looping), penambahan counter <code>i = i + 1</code> merupakan operasi aritmatika yang memerlukan <strong>Simbol Persegi Panjang (Process)</strong>.
                    </div>
                    <p>Tanpa penambahan nilai <code>i</code>, kondisi <code>i &le; 5</code> akan selalu bernilai Benar sehingga terjadi <em>infinite loop</em>. Pasang simbol Proses pada slot perulangan.</p>
                `,
                successTitle: '🏆 Selamat! Seluruh 3 Tantangan Tuntas!',
                successDesc: 'Kamu telah berhasil menuntaskan ketiga pondasi utama algoritma: Percabangan (Branching), Kalkulasi Sekuensial, dan Perulangan (Looping)!',
                nextLevel: 1,
                nextBtnText: '🔄 Ulangi Dari Awal (Simulasi 1)'
            }
        };

        let currentLevel = 1;
        let placedSymbol = null; // null | 'decision' | 'process' | 'io' | 'terminator'
        let currentZoom = 100;
        let isRunning = false;

        /* ================= INITIALIZATION ================= */
        document.addEventListener('DOMContentLoaded', function() {
            loadSimulation(1);
        });

        /* ================= LOAD SIMULATION ================= */
        function loadSimulation(levelNumber) {
            currentLevel = levelNumber;
            placedSymbol = null;
            isRunning = false;

            const config = SIMULATION_CONFIG[levelNumber];

            // Update Left Panel Badges & Text
            document.getElementById('challenge-step-badge').innerText = config.stepBadge;
            document.getElementById('challenge-title').innerText = config.title;
            document.getElementById('challenge-desc').innerHTML = config.desc;

            // Reset palette borders to grey
            updatePaletteVisuals(null);

            // Update Level Tabs Buttons (1 Box dengan Status Eksekusi)
            [1, 2, 3].forEach(lvl => {
                const btn = document.getElementById('level-btn-' + lvl);
                if (btn) {
                    if (lvl === currentLevel) {
                        btn.className = 'w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center font-extrabold shadow-xs transition cursor-pointer';
                    } else {
                        btn.className = 'w-8 h-8 rounded-xl bg-transparent text-slate-600 hover:bg-white hover:shadow-2xs flex items-center justify-center font-extrabold transition cursor-pointer';
                    }
                }
            });

            // Update Top Status Bar (Neutral message without spoilers)
            updateStatusBar(
                'Slot belum terpasang. Klik kartu simbol pada panel kiri untuk mengisi.',
                'neutral'
            );

            // Render Flowchart on Canvas
            renderFlowchartArea();

            // Reset execution console
            document.getElementById('console-logs').innerHTML = `<div>[0.00s] Simulasi ${levelNumber}/3 siap. Menunggu pemasangan simbol.</div>`;
            document.getElementById('console-badge').innerText = 'READY';
            document.getElementById('console-badge').className = 'bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded text-[10px]';
        }

        /* ================= RENDER FLOWCHART PER LEVEL ================= */
        function renderFlowchartArea() {
            const container = document.getElementById('flowchart-render-area');
            if (currentLevel === 1) {
                renderLevel1(container);
            } else if (currentLevel === 2) {
                renderLevel2(container);
            } else if (currentLevel === 3) {
                renderLevel3(container);
            }
        }

        /* --- Level 1: Percabangan Nilai Siswa (KKM >= 75) --- */
        function renderLevel1(container) {
            container.innerHTML = `
                <!-- Node 1: Start (Terminator) -->
                <div id="node-start" class="w-40 py-2.5 rounded-full bg-[#F8BA15] text-amber-950 font-black text-center text-xs sm:text-sm shadow-xs border border-amber-400 transition-all">
                    Mulai
                </div>

                <!-- Down Arrow 1 -->
                <div class="h-8 flex flex-col items-center justify-center">
                    <svg class="w-4 h-8 text-slate-700" viewBox="0 0 16 32" fill="none">
                        <line id="line-1" x1="8" y1="0" x2="8" y2="24" stroke="currentColor" stroke-width="2.2" />
                        <polygon points="4,24 8,32 12,24" fill="currentColor" />
                    </svg>
                </div>

                <!-- Node 2: Input Nilai_Ujian (Jajar Genjang / Parallelogram) -->
                <div id="node-input" class="shape-parallelogram px-8 py-2.5 rounded-lg bg-[#131F33] text-white shadow-sm border border-slate-700 transition-all">
                    <div class="shape-parallelogram-content flex items-center justify-center gap-2.5 font-mono text-xs">
                        <svg class="w-3.5 h-3.5 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        <span>Input Nilai_Ujian = <strong class="text-amber-300 font-bold">80</strong></span>
                    </div>
                </div>

                <!-- Down Arrow 2 -->
                <div class="h-8 flex flex-col items-center justify-center">
                    <svg class="w-4 h-8 text-slate-700" viewBox="0 0 16 32" fill="none">
                        <line id="line-2" x1="8" y1="0" x2="8" y2="24" stroke="currentColor" stroke-width="2.2" />
                        <polygon points="4,24 8,32 12,24" fill="currentColor" />
                    </svg>
                </div>

                <!-- Node 3: THE INTERACTIVE SLOT (Bentuk menyesuaikan simbol yang dipilih pengguna) -->
                <div id="interactive-slot-wrapper" class="w-full flex justify-center my-1">
                    ${renderSlotHTML()}
                </div>

                <!-- Branching Connectors & Badges -->
                <div class="w-full max-w-md relative mt-2">
                    <!-- SVG Branching Lines -->
                    <svg class="w-full h-12" viewBox="0 0 360 48" fill="none">
                        <line id="branch-line-horiz" x1="90" y1="10" x2="270" y2="10" stroke="#334155" stroke-width="2.2" />
                        <line id="branch-line-stem" x1="180" y1="0" x2="180" y2="10" stroke="#334155" stroke-width="2.2" />
                        <line id="branch-line-left" x1="90" y1="10" x2="90" y2="40" stroke="#334155" stroke-width="2.2" />
                        <polygon points="86,40 90,48 94,40" fill="#334155" />
                        <line id="branch-line-right" x1="270" y1="10" x2="270" y2="40" stroke="#334155" stroke-width="2.2" />
                        <polygon points="266,40 270,48 274,40" fill="#334155" />
                    </svg>

                    <!-- Branch Badges: Ya & Tidak -->
                    <div class="absolute top-0 inset-x-0 flex justify-between px-16 pointer-events-none -mt-2.5">
                        <span class="bg-emerald-500 text-white font-extrabold text-[10px] px-3 py-0.5 rounded-full shadow-2xs">Ya</span>
                        <span class="bg-rose-500 text-white font-extrabold text-[10px] px-2.5 py-0.5 rounded-full shadow-2xs">Tidak</span>
                    </div>
                </div>

                <!-- Branch Result Cards (Side by side) - Input / Output uses Parallelogram (Jajar Genjang) -->
                <div class="w-full max-w-md grid grid-cols-2 gap-5 px-2 mt-1">
                    <!-- Left: True Output -->
                    <div id="node-true" class="shape-parallelogram border-2 border-emerald-500 bg-white rounded-lg px-4 py-2.5 text-center shadow-xs transition-all">
                        <div class="shape-parallelogram-content">
                            <span class="text-[9px] font-black text-emerald-600 tracking-wider uppercase block">OUTPUT BENAR</span>
                            <span class="font-extrabold text-xs text-slate-800 mt-0.5 block">[ Cetak "Lulus" ]</span>
                        </div>
                    </div>

                    <!-- Right: False Output -->
                    <div id="node-false" class="shape-parallelogram border-2 border-rose-400 bg-white rounded-lg px-4 py-2.5 text-center shadow-xs transition-all">
                        <div class="shape-parallelogram-content">
                            <span class="text-[9px] font-black text-rose-600 tracking-wider uppercase block">OUTPUT LAIN</span>
                            <span class="font-extrabold text-xs text-slate-800 mt-0.5 block">[ Cetak "Remedial" ]</span>
                        </div>
                    </div>
                </div>

                <!-- Re-join Connectors -->
                <div class="w-full max-w-md relative">
                    <svg class="w-full h-12" viewBox="0 0 360 48" fill="none">
                        <line id="join-line-left" x1="90" y1="0" x2="90" y2="30" stroke="#334155" stroke-width="2.2" />
                        <line id="join-line-right" x1="270" y1="0" x2="270" y2="30" stroke="#334155" stroke-width="2.2" />
                        <line id="join-line-horiz" x1="90" y1="30" x2="270" y2="30" stroke="#334155" stroke-width="2.2" />
                        <line id="join-line-stem" x1="180" y1="30" x2="180" y2="40" stroke="#334155" stroke-width="2.2" />
                        <polygon points="176,40 180,48 184,40" fill="#334155" />
                    </svg>
                </div>

                <!-- Node End: Selesai -->
                <div id="node-end" class="w-40 py-2.5 rounded-full bg-[#F8BA15] text-amber-950 font-black text-center text-xs sm:text-sm shadow-xs border border-amber-400 transition-all mt-1">
                    Selesai
                </div>
            `;
        }

        /* --- Level 2: Proses Kalkulasi Luas Persegi Panjang --- */
        function renderLevel2(container) {
            container.innerHTML = `
                <!-- Node 1: Start -->
                <div id="node-start" class="w-40 py-2.5 rounded-full bg-[#F8BA15] text-amber-950 font-black text-center text-xs sm:text-sm shadow-xs border border-amber-400 transition-all">
                    Mulai
                </div>

                <!-- Down Arrow -->
                <div class="h-8 flex flex-col items-center justify-center">
                    <svg class="w-4 h-8 text-slate-700" viewBox="0 0 16 32" fill="none">
                        <line id="line-1" x1="8" y1="0" x2="8" y2="24" stroke="currentColor" stroke-width="2.2" />
                        <polygon points="4,24 8,32 12,24" fill="currentColor" />
                    </svg>
                </div>

                <!-- Node 2: Input Panjang & Lebar (Jajar Genjang / Parallelogram) -->
                <div id="node-input" class="shape-parallelogram px-8 py-2.5 rounded-lg bg-[#131F33] text-white shadow-sm border border-slate-700 transition-all">
                    <div class="shape-parallelogram-content flex items-center justify-center gap-2.5 font-mono text-xs">
                        <svg class="w-3.5 h-3.5 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        <span>Input Panjang (p)=<strong class="text-amber-300 font-bold">10</strong>, Lebar (l)=<strong class="text-sky-300 font-bold">6</strong></span>
                    </div>
                </div>

                <!-- Down Arrow -->
                <div class="h-8 flex flex-col items-center justify-center">
                    <svg class="w-4 h-8 text-slate-700" viewBox="0 0 16 32" fill="none">
                        <line id="line-2" x1="8" y1="0" x2="8" y2="24" stroke="currentColor" stroke-width="2.2" />
                        <polygon points="4,24 8,32 12,24" fill="currentColor" />
                    </svg>
                </div>

                <!-- Node 3: THE INTERACTIVE SLOT (Bentuk menyesuaikan simbol yang dipilih pengguna) -->
                <div id="interactive-slot-wrapper" class="w-full flex justify-center my-1">
                    ${renderSlotHTML()}
                </div>

                <!-- Down Arrow -->
                <div class="h-8 flex flex-col items-center justify-center">
                    <svg class="w-4 h-8 text-slate-700" viewBox="0 0 16 32" fill="none">
                        <line id="line-3" x1="8" y1="0" x2="8" y2="24" stroke="currentColor" stroke-width="2.2" />
                        <polygon points="4,24 8,32 12,24" fill="currentColor" />
                    </svg>
                </div>

                <!-- Node 4: Output Cetak Hasil (Jajar Genjang / Parallelogram) -->
                <div id="node-output" class="shape-parallelogram px-8 py-3 rounded-lg bg-white border-2 border-emerald-500 text-center shadow-xs transition-all max-w-xs w-full">
                    <div class="shape-parallelogram-content">
                        <span class="text-[9px] font-black text-emerald-600 tracking-wider uppercase block">OUTPUT HASIL</span>
                        <span class="font-extrabold text-xs text-slate-800 mt-0.5 block">
                            [ Cetak Luas = <strong class="text-emerald-700 font-black">60</strong> ]
                        </span>
                    </div>
                </div>

                <!-- Down Arrow -->
                <div class="h-8 flex flex-col items-center justify-center">
                    <svg class="w-4 h-8 text-slate-700" viewBox="0 0 16 32" fill="none">
                        <line id="line-4" x1="8" y1="0" x2="8" y2="24" stroke="currentColor" stroke-width="2.2" />
                        <polygon points="4,24 8,32 12,24" fill="currentColor" />
                    </svg>
                </div>

                <!-- Node 5: Selesai -->
                <div id="node-end" class="w-40 py-2.5 rounded-full bg-[#F8BA15] text-amber-950 font-black text-center text-xs sm:text-sm shadow-xs border border-amber-400 transition-all">
                    Selesai
                </div>
            `;
        }

        /* --- Level 3: Perulangan (Loop While i <= N) --- */
        function renderLevel3(container) {
            container.innerHTML = `
                <!-- Node 1: Mulai (Terminator oval, kuning) -->
                <div id="node-start" class="w-40 py-2.5 rounded-full bg-[#F8BA15] text-amber-950 font-black text-center text-xs sm:text-sm shadow-xs border border-amber-400 transition-all">
                    Mulai
                </div>

                <!-- Arrow down: Mulai -> Inisialisasi -->
                <div class="h-7 flex flex-col items-center justify-center">
                    <svg class="w-4 h-7 text-slate-700" viewBox="0 0 16 28" fill="none">
                        <line x1="8" y1="0" x2="8" y2="20" stroke="currentColor" stroke-width="2.2" />
                        <polygon points="4,20 8,28 12,20" fill="currentColor" />
                    </svg>
                </div>

                <!-- Node 2: Inisialisasi i = 1 (Process rect biru) -->
                <div id="node-init" class="w-[220px] py-2 rounded-xl bg-sky-50 border-2 border-sky-400 font-mono text-xs font-bold text-slate-800 text-center shadow-2xs transition-all">
                    Inisialisasi: i = 1
                </div>

                <!-- Arrow down: Inisialisasi -> Decision -->
                <div class="h-7 flex flex-col items-center justify-center">
                    <svg class="w-4 h-7 text-slate-700" viewBox="0 0 16 28" fill="none">
                        <line x1="8" y1="0" x2="8" y2="20" stroke="currentColor" stroke-width="2.2" />
                        <polygon points="4,20 8,28 12,20" fill="currentColor" />
                    </svg>
                </div>

                <!-- Loop Container (Decision down to Slot with continuous connected arrows) -->
                <div class="relative w-[440px] h-[264px] flex flex-col items-center mx-auto">
                    <!-- SVG Connecting Lines & Arrows -->
                    <svg class="absolute inset-0 w-[440px] h-[264px] pointer-events-none" viewBox="0 0 440 264" fill="none">
                        <!-- Tidak branch: arrow right from Decision right tip (310, 41) to Selesai (344, 41) -->
                        <line x1="310" y1="41" x2="336" y2="41" stroke="#334155" stroke-width="2.2" />
                        <polygon points="334,37 342,41 334,45" fill="#334155" />

                        <!-- Ya branch: arrow down from Decision bottom tip (220, 80) to Cetak i (220, 112) -->
                        <line x1="220" y1="80" x2="220" y2="104" stroke="#334155" stroke-width="2.2" />
                        <polygon points="216,104 220,112 224,104" fill="#334155" />

                        <!-- Arrow down from Cetak i (bottom at 150) to Slot (top at 182) -->
                        <line x1="220" y1="150" x2="220" y2="174" stroke="#334155" stroke-width="2.2" />
                        <polygon points="216,174 220,182 224,174" fill="#334155" />

                        <!-- LOOP-BACK ARROW: Dari sisi kiri Slot (100, 219) naik dan mengarah kembali ke sisi kiri Decision (130, 41) -->
                        <path id="line-loop-back" d="M 100 219 L 36 219 L 36 41 L 122 41" stroke="#334155" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                        <polygon points="122,37 130,41 122,45" fill="#334155" />
                    </svg>

                    <!-- Decision Row: Diamond (centered at 220, w=180, h=82) + Selesai pill on right -->
                    <div class="w-full flex items-center justify-center relative" style="height: 82px;">
                        <div id="node-decision-loop" class="relative z-10 transition-all flex-shrink-0 cursor-pointer">
                            <svg width="180" height="82" viewBox="0 0 180 82">
                                <polygon points="90,4 176,41 90,78 4,41" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2.5" />
                                <text x="90" y="37" text-anchor="middle" font-size="11" font-weight="800" font-family="'Plus Jakarta Sans',sans-serif" fill="#78350F">
                                    Apakah i &le; 5 ?
                                </text>
                            </svg>
                        </div>

                        <!-- Tidak label -->
                        <div class="absolute" style="left: 312px; top: 18px;">
                            <span class="text-[10px] font-extrabold text-rose-600 bg-white px-1 rounded">Tidak</span>
                        </div>

                        <!-- Selesai pill -->
                        <div class="absolute" style="left: 344px; top: 22px;">
                            <div id="node-end" class="w-[84px] py-2 rounded-full bg-rose-100 text-rose-800 font-black text-center text-xs shadow-xs border-2 border-rose-300 transition-all">
                                Selesai
                            </div>
                        </div>
                    </div>

                    <!-- Ya label spacer -->
                    <div class="w-full flex justify-center relative z-10" style="height: 30px;">
                        <span class="text-[10px] font-extrabold text-emerald-600 bg-white border border-emerald-300 px-2 py-0.5 rounded-full self-center">Ya</span>
                    </div>

                    <!-- Cetak i (Jajar Genjang hijau) -->
                    <div style="height: 38px;" class="w-full flex items-center justify-center z-10">
                        <div id="node-output-loop" class="shape-parallelogram px-8 py-2 rounded-lg bg-emerald-50/80 border-2 border-emerald-500 text-center text-xs font-bold text-emerald-950 shadow-xs transition-all w-[180px]">
                            <div class="shape-parallelogram-content font-extrabold">
                                Cetak i
                                <span id="loop-current-print" class="hidden">1</span>
                            </div>
                        </div>
                    </div>

                    <!-- Spacer between Cetak i and Slot for the down arrow -->
                    <div style="height: 32px;" class="w-full"></div>

                    <!-- Interactive Slot: i = i + 1 (Proses Counter) -->
                    <div id="interactive-slot-wrapper" class="w-full flex justify-center z-10">
                        ${renderSlotHTML()}
                    </div>
                </div>
            `;
        }

        /* --- Render Slot HTML: Bentuk menyesuaikan simbol yang dipilih pengguna tanpa label teks bentuk --- */
        function renderSlotHTML() {
            const config = SIMULATION_CONFIG[currentLevel];
            const slotText = config.slotTextInside;

            // State A: Belum ada simbol yang dipilih (Slot Kosong Dashed)
            if (!placedSymbol) {
                return `
                    <div onclick="openSymbolQuickPicker()" 
                         id="flow-slot" 
                         class="bg-[#FFFDF5] border-2 border-dashed border-amber-300 hover:border-amber-400 hover:bg-amber-100/60 rounded-xl px-4 py-2.5 text-center cursor-pointer transition shadow-xs w-[240px] h-[74px] flex flex-col items-center justify-center group">
                        <div class="flex items-center gap-1.5 text-xs font-extrabold text-slate-800">
                            <span class="w-5 h-5 rounded-full bg-amber-400 text-amber-950 flex items-center justify-center font-black text-xs group-hover:scale-110 transition-transform">+</span>
                            <span class="truncate">${config.slotPrompt}</span>
                        </div>
                        <div class="text-[11px] font-mono font-bold text-amber-700 mt-0.5">
                            ${config.slotConditionText}
                        </div>
                    </div>
                `;
            }

            // State B: Simbol Keputusan (Belah Ketupat)
            if (placedSymbol === 'decision') {
                return `
                    <div id="flow-slot" class="pop-in flex flex-col items-center justify-center transition-all my-1 cursor-pointer group" onclick="openSymbolQuickPicker()">
                        <div class="relative flex items-center justify-center">
                            <svg width="240" height="96" viewBox="0 0 240 96" class="drop-shadow-sm transition-all duration-300">
                                <polygon id="slot-shape" points="120,4 236,48 120,92 4,48" fill="#FFFFFF" stroke="#334155" stroke-width="2.5" />
                                <text id="slot-text-el" x="120" y="53" text-anchor="middle" font-size="12" font-weight="800" font-family="'Plus Jakarta Sans', sans-serif" fill="#0F172A">
                                    ${slotText}
                                </text>
                            </svg>
                        </div>
                    </div>
                `;
            }

            // State C: Simbol Proses (Persegi Panjang)
            if (placedSymbol === 'process') {
                return `
                    <div id="flow-slot" class="pop-in flex flex-col items-center justify-center transition-all my-1 cursor-pointer group" onclick="openSymbolQuickPicker()">
                        <div class="relative flex items-center justify-center">
                            <svg width="240" height="74" viewBox="0 0 240 74" class="drop-shadow-sm transition-all duration-300">
                                <rect id="slot-shape" x="4" y="4" width="232" height="66" rx="4" fill="#FFFFFF" stroke="#334155" stroke-width="2.5" />
                                <text id="slot-text-el" x="120" y="42" text-anchor="middle" font-size="12" font-weight="800" font-family="'Plus Jakarta Sans', sans-serif" fill="#0F172A">
                                    ${slotText}
                                </text>
                            </svg>
                        </div>
                    </div>
                `;
            }

            // State D: Simbol Output / I/O (Jajar Genjang)
            if (placedSymbol === 'io') {
                return `
                    <div id="flow-slot" class="pop-in flex flex-col items-center justify-center transition-all my-1 cursor-pointer group" onclick="openSymbolQuickPicker()">
                        <div class="relative flex items-center justify-center">
                            <svg width="240" height="74" viewBox="0 0 240 74" class="drop-shadow-sm transition-all duration-300">
                                <polygon id="slot-shape" points="36,4 236,4 204,70 4,70" fill="#FFFFFF" stroke="#334155" stroke-width="2.5" />
                                <text id="slot-text-el" x="120" y="42" text-anchor="middle" font-size="12" font-weight="800" font-family="'Plus Jakarta Sans', sans-serif" fill="#0F172A">
                                    ${slotText}
                                </text>
                            </svg>
                        </div>
                    </div>
                `;
            }

            // State E: Simbol Terminator (Kapsul / Oval)
            if (placedSymbol === 'terminator') {
                return `
                    <div id="flow-slot" class="pop-in flex flex-col items-center justify-center transition-all my-1 cursor-pointer group" onclick="openSymbolQuickPicker()">
                        <div class="relative flex items-center justify-center">
                            <svg width="240" height="70" viewBox="0 0 240 70" class="drop-shadow-sm transition-all duration-300">
                                <rect id="slot-shape" x="4" y="4" width="232" height="62" rx="31" fill="#FFFFFF" stroke="#334155" stroke-width="2.5" />
                                <text id="slot-text-el" x="120" y="40" text-anchor="middle" font-size="12" font-weight="800" font-family="'Plus Jakarta Sans', sans-serif" fill="#0F172A">
                                    ${slotText}
                                </text>
                            </svg>
                        </div>
                    </div>
                `;
            }

            return '';
        }

        /* ================= PALETTE VISUALS & SELECTION ================= */
        function updatePaletteVisuals(activeSymbol) {
            const symbols = ['decision', 'process', 'io', 'terminator'];
            symbols.forEach(sym => {
                const item = document.getElementById('palette-item-' + sym);
                const iconContainer = document.getElementById('palette-icon-' + sym);
                const plusBtn = document.getElementById('palette-btn-' + sym);
                if (!item) return;

                if (sym === activeSymbol) {
                    // Item yang diklik: Border berubah jadi OREN
                    item.className = 'flex items-center justify-between p-3 sm:p-3.5 rounded-2xl border-2 border-[#EA580C] bg-[#FFF7ED] shadow-sm transition duration-200 cursor-pointer group';
                    if (iconContainer) {
                        iconContainer.className = 'w-10 h-10 rounded-xl bg-white border border-orange-300 text-[#EA580C] flex items-center justify-center shadow-2xs shrink-0 transition-transform';
                    }
                    if (plusBtn) {
                        plusBtn.className = 'w-7 h-7 rounded-full bg-[#EA580C] text-white flex items-center justify-center font-black text-sm transition';
                        plusBtn.innerText = '✓';
                    }
                } else {
                    // Item yang belum diklik: Line box ABU-ABU
                    item.className = 'flex items-center justify-between p-3 sm:p-3.5 rounded-2xl border-2 border-slate-200 bg-white hover:border-slate-300 transition duration-200 cursor-pointer group';
                    if (iconContainer) {
                        iconContainer.className = 'w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 flex items-center justify-center shadow-2xs shrink-0 transition-transform';
                    }
                    if (plusBtn) {
                        plusBtn.className = 'w-7 h-7 rounded-full bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center font-black text-sm transition';
                        plusBtn.innerText = '+';
                    }
                }
            });
        }

        function selectSymbolFromPalette(symbolType) {
            placedSymbol = symbolType;

            // Update warna palet: kotak yang diklik jadi oren, yang lain jadi abu-abu
            updatePaletteVisuals(symbolType);

            // Re-render bentuk slot di kanvas sesuai simbol yang dipilih pengguna
            const slotArea = document.getElementById('interactive-slot-wrapper');
            if (slotArea) {
                slotArea.innerHTML = renderSlotHTML();
            }

            // Pesan status netral tanpa spoiler hasil
            updateStatusBar(
                'Simbol telah dipasang pada slot. Klik tombol Putar (▶) di sudut kanan atas untuk mengeksekusi dan menguji alur.',
                'neutral'
            );
            logToConsole(`[PASANG] Simbol '${symbolType}' dipasang ke slot.`);
        }

        /* ================= RUN SIMULATION ENGINE & VALIDATION ================= */
        async function runSimulation() {
            if (isRunning) return;

            const config = SIMULATION_CONFIG[currentLevel];
            const slot = document.getElementById('flow-slot');

            // 1. Validasi jika slot belum terisi
            if (!placedSymbol) {
                if (slot) {
                    slot.classList.add('shake-node');
                    setTimeout(() => slot.classList.remove('shake-node'), 500);
                }
                updateStatusBar(
                    '⚠️ Slot belum terpasang! Pilih salah satu simbol dari palet di sebelah kiri.',
                    'rose'
                );
                return;
            }

            const symbolNameMap = {
                decision: 'Keputusan (Belah Ketupat)',
                process: 'Proses (Persegi Panjang)',
                io: 'Output / I/O (Jajar Genjang)',
                terminator: 'Terminator (Kapsul/Oval)'
            };

            const expectedNameMap = {
                decision: 'Keputusan (Belah Ketupat)',
                process: 'Proses (Persegi Panjang)',
                io: 'Output / I/O (Jajar Genjang)',
                terminator: 'Terminator (Kapsul/Oval)'
            };

            // 2. Evaluasi Feedback Saat Tombol Play Diklik: Apakah Benar atau Salah!
            if (placedSymbol !== config.expectedSymbol) {
                // FEEDBACK SALAH: Berikan highlight merah pada bentuk slot & animasi goyang
                const slotShape = document.getElementById('slot-shape');
                if (slotShape) {
                    slotShape.setAttribute('stroke', '#EF4444');
                    slotShape.setAttribute('fill', '#FEF2F2');
                    slotShape.setAttribute('stroke-width', '3');
                }
                if (slot) {
                    slot.classList.add('shake-node');
                    setTimeout(() => slot.classList.remove('shake-node'), 500);
                }

                updateStatusBar(
                    `❌ Jawaban Belum Tepat! Simbol yang kamu pasang (${symbolNameMap[placedSymbol]}) tidak sesuai kaidah alur.`,
                    'rose'
                );
                logToConsole(`[ERROR] Jawaban salah: Simbol '${symbolNameMap[placedSymbol]}' tidak valid untuk blok ini.`);

                showErrorModal(
                    'Simbol Belum Tepat!',
                    `Simbol <strong>${symbolNameMap[placedSymbol]}</strong> yang kamu pasang belum sesuai aturan ANSI/ISO 5807 untuk langkah ini.<br><br>Untuk langkah ini, fungsi blok membutuhkan simbol <strong>${expectedNameMap[config.expectedSymbol]}</strong>. Silakan coba pilih simbol lain pada palet kiri.`
                );
                return;
            }

            // FEEDBACK BENAR: Berikan highlight hijau pada bentuk slot & jalankan animasi alur
            const slotShape = document.getElementById('slot-shape');
            if (slotShape) {
                slotShape.setAttribute('stroke', '#10B981');
                slotShape.setAttribute('fill', '#F0FDF4');
                slotShape.setAttribute('stroke-width', '3');
            }

            isRunning = true;
            const runBtn = document.getElementById('btn-run');
            runBtn.disabled = true;
            runBtn.classList.add('opacity-50', 'cursor-not-allowed');

            // Aktifkan terminal console
            const consoleCard = document.getElementById('execution-console');
            consoleCard.classList.remove('hidden');
            document.getElementById('console-badge').innerText = 'RUNNING';
            document.getElementById('console-badge').className = 'bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded text-[10px] animate-pulse';

            if (currentLevel === 1) {
                await executeLevel1Sequence();
            } else if (currentLevel === 2) {
                await executeLevel2Sequence();
            } else if (currentLevel === 3) {
                await executeLevel3Sequence();
            }

            runBtn.disabled = false;
            runBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            isRunning = false;
        }

        /* --- Sequence Eksekusi Level 1 --- */
        async function executeLevel1Sequence() {
            updateStatusBar('▶ Mengeksekusi: Memulai alur dari Terminator Mulai...', 'sky');
            logToConsole('[0.1s] MULAI: Inisialisasi evaluasi kelulusan nilai');
            highlightNode('node-start', true);
            await sleep(600);
            highlightNode('node-start', false);

            updateStatusBar('▶ Mengeksekusi: Membaca input nilai ujian = 80...', 'sky');
            logToConsole('[0.5s] INPUT: Nilai_Ujian = 80');
            highlightNode('node-input', true);
            await sleep(700);
            highlightNode('node-input', false);

            updateStatusBar('▶ Mengeksekusi: Mengevaluasi (80 >= 75) => TRUE (Lulus)...', 'sky');
            logToConsole('[1.0s] KEPUTUSAN: Evaluasi kondisi (80 >= 75) => TRUE');
            highlightNode('flow-slot', true);
            await sleep(800);
            highlightNode('flow-slot', false);

            updateStatusBar('▶ Mengambil cabang Ya (True): Mencetak status Lulus...', 'emerald');
            logToConsole('[1.5s] OUTPUT: Mencetak status "Lulus"');
            highlightNode('node-true', true);
            await sleep(700);
            highlightNode('node-true', false);

            updateStatusBar('✔ Alur berhasil mencapai Terminator Selesai. Pengujian sukses!', 'emerald');
            logToConsole('[2.0s] SELESAI: Eksekusi selesai tanpa error.');
            highlightNode('node-end', true);
            await sleep(500);
            highlightNode('node-end', false);

            document.getElementById('console-badge').innerText = 'SUCCESS';
            document.getElementById('console-badge').className = 'bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded text-[10px]';

            triggerConfetti();

            showSuccessModal({
                title: 'Luar Biasa! Tantangan 1/3 Berhasil!',
                desc: 'Kamu berhasil memasang simbol Keputusan (Decision) dan logika percabangan IF-ELSE berjalan sesuai standar ANSI/ISO 5807.',
                summaryHTML: `
                    <div class="flex justify-between"><span>Input Nilai:</span><strong>80</strong></div>
                    <div class="flex justify-between"><span>Evaluasi (80 &ge; 75):</span><strong class="text-emerald-600">TRUE (Lulus)</strong></div>
                    <div class="flex justify-between"><span>Simbol Terpasang:</span><strong class="text-amber-600">Decision (Belah Ketupat)</strong></div>
                `,
                nextLevel: 2,
                nextBtnText: 'Lanjut ke Tantangan 2: Menghitung Luas →'
            });
        }

        /* --- Sequence Eksekusi Level 2 --- */
        async function executeLevel2Sequence() {
            updateStatusBar('▶ Mengeksekusi: Inisialisasi program kalkulasi luas...', 'sky');
            logToConsole('[0.1s] MULAI: Menyiapkan alur perhitungan luas');
            highlightNode('node-start', true);
            await sleep(600);
            highlightNode('node-start', false);

            updateStatusBar('▶ Mengeksekusi: Menerima masukan panjang=10, lebar=6...', 'sky');
            logToConsole('[0.5s] INPUT: panjang = 10, lebar = 6');
            highlightNode('node-input', true);
            await sleep(700);
            highlightNode('node-input', false);

            updateStatusBar('▶ Mengeksekusi Proses: Luas = 10 * 6 = 60...', 'sky');
            logToConsole('[1.0s] PROSES: Luas = 10 * 6 = 60');
            highlightNode('flow-slot', true);
            await sleep(800);
            highlightNode('flow-slot', false);

            updateStatusBar('▶ Mengeksekusi: Menampilkan output Cetak Luas = 60...', 'emerald');
            logToConsole('[1.5s] OUTPUT: Menampilkan hasil luas 60 ke layar');
            highlightNode('node-output', true);
            await sleep(700);
            highlightNode('node-output', false);

            updateStatusBar('✔ Perhitungan selesai dengan tepat!', 'emerald');
            logToConsole('[2.0s] SELESAI: Program berhenti dengan sukses.');
            highlightNode('node-end', true);
            await sleep(500);
            highlightNode('node-end', false);

            document.getElementById('console-badge').innerText = 'SUCCESS';
            document.getElementById('console-badge').className = 'bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded text-[10px]';

            triggerConfetti();

            showSuccessModal({
                title: '⚡ Keren! Tantangan 2/3 Selesai!',
                desc: 'Simbol Proses (Persegi Panjang) berhasil mengeksekusi perhitungan aritmatika Luas = Panjang × Lebar dengan tepat.',
                summaryHTML: `
                    <div class="flex justify-between"><span>Panjang (p):</span><strong>10</strong></div>
                    <div class="flex justify-between"><span>Lebar (l):</span><strong>6</strong></div>
                    <div class="flex justify-between"><span>Hasil Luas:</span><strong class="text-emerald-600">60</strong></div>
                    <div class="flex justify-between"><span>Simbol Terpasang:</span><strong class="text-sky-600">Process (Persegi Panjang)</strong></div>
                `,
                nextLevel: 3,
                nextBtnText: 'Lanjut ke Tantangan 3: Perulangan (Looping) →'
            });
        }

        /* --- Sequence Eksekusi Level 3 --- */
        async function executeLevel3Sequence() {
            const maxN = 5;

            updateStatusBar('▶ Mengeksekusi: Memulai alur perulangan...', 'sky');
            logToConsole('[0.1s] MULAI: Menyiapkan struktur looping counter');
            highlightNode('node-start', true);
            await sleep(500);
            highlightNode('node-start', false);

            logToConsole('[0.3s] PROSES: Inisialisasi counter i = 1');
            highlightNode('node-init', true);
            await sleep(500);
            highlightNode('node-init', false);

            let printedNums = [];
            for (let i = 1; i <= maxN; i++) {
                updateStatusBar(`▶ Loop Iterasi #${i}: Evaluasi (${i} <= ${maxN}) => TRUE`, 'sky');
                logToConsole(`[${(0.5 + i * 0.4).toFixed(1)}s] DECISION: (${i} <= ${maxN}) => BENAR`);
                highlightNode('node-decision-loop', true);
                await sleep(400);
                highlightNode('node-decision-loop', false);

                const printEl = document.getElementById('loop-current-print');
                if (printEl) printEl.innerText = i;
                printedNums.push(i);

                logToConsole(`[${(0.7 + i * 0.4).toFixed(1)}s] OUTPUT: Cetak i = ${i}`);
                highlightNode('node-output-loop', true);
                await sleep(350);
                highlightNode('node-output-loop', false);

                logToConsole(`[${(0.9 + i * 0.4).toFixed(1)}s] PROSES: Counter dinaikkan i = ${i} + 1 = ${i + 1}`);
                highlightNode('flow-slot', true);
                await sleep(350);
                highlightNode('flow-slot', false);
            }

            const exitI = maxN + 1;
            updateStatusBar(`▶ Evaluasi akhir: (${exitI} <= ${maxN}) => FALSE. Keluar dari loop!`, 'amber');
            logToConsole(`[${(1.2 + maxN * 0.4).toFixed(1)}s] DECISION: (${exitI} <= ${maxN}) => SALAH. Keluar dari badan perulangan.`);
            highlightNode('node-decision-loop', true);
            await sleep(500);
            highlightNode('node-decision-loop', false);

            updateStatusBar('✔ Seluruh deret angka berhasil dicetak dan perulangan berakhir sesuai kaidah!', 'emerald');
            logToConsole(`[${(1.6 + maxN * 0.4).toFixed(1)}s] SELESAI: Output deret: ${printedNums.join(', ')}.`);
            highlightNode('node-end', true);
            await sleep(500);
            highlightNode('node-end', false);

            document.getElementById('console-badge').innerText = 'SUCCESS';
            document.getElementById('console-badge').className = 'bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded text-[10px]';

            triggerConfetti();

            showSuccessModal({
                title: '🏆 Selamat! Seluruh 3 Tantangan Selesai!',
                desc: 'Kamu telah menguasai tiga pondasi utama algoritma: Percabangan (Branching), Kalkulasi Sekuensial, dan Perulangan (Looping) sesuai standar ANSI/ISO 5807!',
                summaryHTML: `
                    <div class="flex justify-between"><span>Angka Dicetak:</span><strong>${printedNums.join(', ')}</strong></div>
                    <div class="flex justify-between"><span>Jumlah Iterasi:</span><strong>5 Kali Loop</strong></div>
                    <div class="flex justify-between"><span>Kondisi Berhenti:</span><strong class="text-rose-600">i = ${exitI} (&gt; 5)</strong></div>
                    <div class="flex justify-between"><span>Simbol Terpasang:</span><strong class="text-purple-600">Process Increment (Persegi Panjang)</strong></div>
                `,
                nextLevel: 1,
                nextBtnText: '🔄 Ulangi Dari Awal (Simulasi 1)'
            });
        }

        /* ================= MODAL HELPERS ================= */
        function showSuccessModal({ title, desc, summaryHTML, nextLevel, nextBtnText }) {
            document.getElementById('modal-success-title').innerText = title;
            document.getElementById('modal-success-desc').innerText = desc;
            document.getElementById('modal-success-summary').innerHTML = summaryHTML;
            document.getElementById('btn-modal-next-text').innerText = nextBtnText;
            
            const btn = document.getElementById('btn-modal-next');
            btn.onclick = function() {
                closeModal('modal-success');
                loadSimulation(nextLevel);
            };

            const modal = document.getElementById('modal-success');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function showErrorModal(title, desc) {
            document.getElementById('modal-error-title').innerHTML = title;
            document.getElementById('modal-error-desc').innerHTML = desc;
            const modal = document.getElementById('modal-error');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function goToNextSimulation() {
            closeModal('modal-success');
            const nextLvl = currentLevel < 3 ? currentLevel + 1 : 1;
            loadSimulation(nextLvl);
        }

        function openHintModal() {
            const config = SIMULATION_CONFIG[currentLevel];
            document.getElementById('hint-level-sub').innerText = `Tantangan ${currentLevel} dari 3`;
            document.getElementById('hint-content').innerHTML = config.hintText;

            const modal = document.getElementById('modal-hint');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openSymbolQuickPicker() {
            updateStatusBar('Silakan klik salah satu simbol pada Palet Simbol Logika (panel kiri) untuk memasangnya.', 'neutral');
            const palette = document.getElementById('palette-container');
            if (palette) {
                palette.classList.add('ring-2', 'ring-amber-400', 'rounded-2xl');
                setTimeout(() => palette.classList.remove('ring-2', 'ring-amber-400'), 800);
            }
        }

        /* ================= STATUS BAR HELPER ================= */
        function updateStatusBar(text, type) {
            const statusText = document.getElementById('status-text');
            const statusIcon = document.getElementById('status-icon');
            const statusCard = document.getElementById('top-status-card');

            statusText.innerText = text;

            if (type === 'emerald') {
                statusIcon.className = 'inline-block text-emerald-500 font-bold';
                statusCard.className = 'bg-white rounded-2xl shadow-sm px-6 py-4 border-2 border-emerald-400 mb-6 transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4';
            } else if (type === 'rose') {
                statusIcon.className = 'inline-block text-rose-500 font-bold';
                statusCard.className = 'bg-white rounded-2xl shadow-sm px-6 py-4 border-2 border-rose-400 mb-6 transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4';
            } else if (type === 'sky') {
                statusIcon.className = 'inline-block text-sky-500 font-bold animate-spin';
                statusCard.className = 'bg-white rounded-2xl shadow-sm px-6 py-4 border-2 border-sky-400 mb-6 transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4';
            } else {
                statusIcon.className = 'inline-block text-amber-500 font-bold';
                statusCard.className = 'bg-white rounded-2xl shadow-sm px-6 py-4 border border-amber-200/60 mb-6 transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4';
            }
        }

        function logToConsole(message) {
            const logsContainer = document.getElementById('console-logs');
            if (logsContainer) {
                const row = document.createElement('div');
                row.innerText = message;
                logsContainer.appendChild(row);
                logsContainer.scrollTop = logsContainer.scrollHeight;
            }
        }

        function highlightNode(nodeId, enable) {
            const el = document.getElementById(nodeId);
            if (!el) return;
            if (enable) {
                el.classList.add('node-highlight');
            } else {
                el.classList.remove('node-highlight');
            }
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        /* ================= ZOOM CONTROLS ================= */
        function adjustZoom(delta) {
            currentZoom = Math.min(Math.max(currentZoom + delta, 70), 140);
            applyZoom();
        }

        function resetZoom() {
            currentZoom = 100;
            applyZoom();
        }

        function applyZoom() {
            document.getElementById('zoom-label').innerText = currentZoom + '%';
            const canvas = document.getElementById('flowchart-canvas');
            canvas.style.transform = `scale(${currentZoom / 100})`;
        }

        /* ================= CONFETTI EFFECT ================= */
        function triggerConfetti() {
            const colors = ['#F8BA15', '#10B981', '#38BDF8', '#F43F5E', '#A855F7'];
            for (let i = 0; i < 40; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti-particle';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = (Math.random() * 80 + 10) + '%';
                confetti.style.top = '10%';
                confetti.style.opacity = '1';
                confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                document.body.appendChild(confetti);

                const fallDuration = Math.random() * 1.5 + 1;
                confetti.animate([
                    { transform: `translateY(0) rotate(0deg)`, opacity: 1 },
                    { transform: `translateY(${Math.random() * 300 + 200}px) rotate(${Math.random() * 720}deg)`, opacity: 0 }
                ], {
                    duration: fallDuration * 1000,
                    easing: 'cubic-bezier(0.25, 1, 0.5, 1)'
                }).onfinish = () => confetti.remove();
            }
        }

        /* ================= GENERAL UI CONTROLS ================= */
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        function toggleQuizDropdown(event) {
            if (event) event.stopPropagation();
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

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQuizDropdown();
                ['modal-success', 'modal-error', 'modal-hint', 'modal-quiz', 'modal-profile', 'modal-info'].forEach(closeModal);
            }
        });
    </script>
</body>
</html>
