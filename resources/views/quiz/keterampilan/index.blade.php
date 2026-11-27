<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz Keterampilan - Flowchart Lab</title>
    <meta name="description" content="Quiz Keterampilan Praktik Alur Flowchart Algoritma Visual untuk Pelajar SMA/SMK/MA.">

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
            background-color: #F8FAFC;
            color: #0F172A;
        }
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
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-emerald-700 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Beranda
                </a>
                <a href="{{ route('materi') }}" class="text-slate-600 hover:text-emerald-700 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Materi
                </a>
                <a href="{{ route('simulasi') }}" class="text-slate-600 hover:text-emerald-700 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Simulasi
                </a>
                
                <!-- Quiz Dropdown -->
                <div class="relative" id="quiz-dropdown-wrapper">
                    <button type="button" 
                            id="quiz-dropdown-btn" 
                            onclick="toggleQuizDropdown(event)" 
                            class="bg-emerald-100 text-emerald-900 font-bold px-4 py-1.5 rounded-full text-[13px] shadow-2xs transition inline-flex items-center gap-1.5 cursor-pointer">
                        <span>Quiz</span>
                        <svg id="quiz-dropdown-arrow" class="w-3.5 h-3.5 text-emerald-700 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
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

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">

            <!-- Hero Intro Card -->
            <div class="bg-white rounded-[32px] sm:rounded-[36px] p-6 sm:p-10 shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <div class="bg-[#059669] text-white font-black text-[11px] sm:text-xs uppercase tracking-wider px-4 py-1.5 rounded-full inline-flex items-center gap-2 shadow-sm">
                        <span>🧩 QUIZ KETERAMPILAN BELAJAR ALGORITMA VISUAL</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="bg-[#FFF1F2] border border-[#FECDD3] text-[#E11D48] font-black text-[10px] sm:text-[11px] uppercase tracking-wider px-3.5 py-1 rounded-full">
                            PRAKTIK MANDIRI
                        </span>
                    </div>
                </div>

                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    Tantangan Praktik Merakit <span class="text-[#EF4444]">Flowchart</span>
                </h1>

                <p class="text-slate-600 font-medium text-sm sm:text-base leading-relaxed mt-4 max-w-2xl">
                    Uji pemahaman mendalammu dalam menyusun diagram alir algoritma standar ANSI/ISO. Kamu akan menyelesaikan 3 tantangan tugas langsung di kanvas interaktif.
                </p>

                <!-- Current Score Card (if authenticated) -->
                @auth
                    @if(Auth::user()->skor_praktik !== null)
                        @php
                            $sp = (int) Auth::user()->skor_praktik;
                            $spLulus = $sp >= 75;
                            $spCirc = 339.29;
                            $spOffset = $spCirc - (($sp / 100) * $spCirc);
                            $spGauge = $spLulus ? 'stroke-emerald-500' : 'stroke-rose-500';
                            if ($sp >= 85)     { $spBadge = '🌟 SANGAT TERAMPIL'; $spBadgeClass = 'bg-emerald-600'; }
                            elseif ($sp >= 75) { $spBadge = '✅ KOMPETEN'; $spBadgeClass = 'bg-emerald-600'; }
                            elseif ($sp >= 60) { $spBadge = '📈 CUKUP KOMPETEN'; $spBadgeClass = 'bg-amber-500'; }
                            else               { $spBadge = '📚 PERLU LATIHAN'; $spBadgeClass = 'bg-rose-600'; }
                        @endphp
                        <div class="mt-6 bg-slate-50 rounded-3xl p-6 border border-slate-200 shadow-sm space-y-5">
                            <!-- Predikat Badge -->
                            <div class="text-center">
                                <span class="inline-flex items-center gap-2 {{ $spBadgeClass }} text-white font-extrabold text-xs px-5 py-2 rounded-full uppercase tracking-wider shadow-md">
                                    {{ $spBadge }}
                                </span>
                            </div>

                            <!-- Score Display -->
                            <div class="flex items-center justify-center gap-6">
                                <div class="relative w-32 h-32 flex items-center justify-center shrink-0">
                                    <svg class="w-full h-full score-circle" viewBox="0 0 120 120" style="transform: rotate(-90deg)">
                                        <circle cx="60" cy="60" r="54" class="stroke-slate-200 fill-none" stroke-width="10" />
                                        <circle cx="60" cy="60" r="54"
                                                class="{{ $spGauge }} fill-none"
                                                stroke-width="10"
                                                stroke-linecap="round"
                                                stroke-dasharray="{{ $spCirc }}"
                                                stroke-dashoffset="{{ $spOffset }}" />
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                        <span class="text-3xl font-black text-slate-900 tracking-tight leading-none">{{ $sp }}</span>
                                        <span class="text-[9px] font-black tracking-widest text-slate-400 uppercase mt-1">SKOR PRAKTIK</span>
                                    </div>
                                </div>
                                <!-- Stats -->
                                <div class="space-y-3 flex-1">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 w-20 shrink-0">Skor Praktik</span>
                                        <span class="font-black text-slate-900 text-sm">{{ $sp }}<span class="text-slate-400 font-semibold text-xs"> / 100</span></span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 w-20 shrink-0">Status KKM</span>
                                        @if($spLulus)
                                            <span class="bg-emerald-100 text-emerald-800 font-black text-[10px] px-2.5 py-0.5 rounded-full">✓ Lulus (KKM 75)</span>
                                        @else
                                            <span class="bg-rose-100 text-rose-800 font-black text-[10px] px-2.5 py-0.5 rounded-full">✕ Belum Lulus (KKM 75)</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 w-20 shrink-0">Dikerjakan</span>
                                        <span class="font-semibold text-slate-600 text-xs">
                                            {{ isset($completedAt) && $completedAt ? $completedAt->locale('id')->diffForHumans() : '1 sesi yang lalu' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-slate-200"></div>

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <a href="{{ route('quiz.keterampilan.hasil') }}"
                                   class="w-full bg-slate-100 hover:bg-slate-200 text-slate-900 font-extrabold text-xs sm:text-[13px] px-6 py-3.5 rounded-full inline-flex items-center justify-center gap-2.5 transition shadow-2xs cursor-pointer">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>Lihat Hasil Sebelumnya</span>
                                </a>
                                <form action="{{ route('quiz.keterampilan.mulai') }}" method="POST" id="form-retry-kt">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wide px-6 py-3.5 rounded-full inline-flex items-center justify-center gap-2.5 shadow-lg shadow-emerald-500/30 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span>Kerjakan Ulang</span>
                                    </button>
                                </form>
                            </div>
                            <p class="text-center text-[11px] text-slate-400 font-medium">
                                ⚠️ Mengerjakan ulang akan memulai sesi baru. Skor praktik sebelumnya akan tertimpa.
                            </p>
                        </div>
                    @endif
                @endauth

                <!-- 3 Rule Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 font-black text-xs flex items-center justify-center mb-3">
                            01
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm mb-1">3 Tugas Berjenjang</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            1 tugas Level 1 (Mudah), 1 tugas Level 2 (Sedang), dan 1 tugas Level 3 (Sulit) diundi secara acak.
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 font-black text-xs flex items-center justify-center mb-3">
                            02
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm mb-1">Tanpa Hint & Legenda</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Tidak ada tombol cek sementara atau petunjuk simbol. Pilih bentuk simbol dan atur urutan langkah secara mandiri.
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-black text-xs flex items-center justify-center mb-3">
                            03
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm mb-1">Skor Muncul Usai Kirim</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Setelah menekan "Kirim jawaban", kanvas akan terkunci dan sistem server menampilkan koreksi serta skor perolehan.
                        </p>
                    </div>
                </div>

                <!-- Action Button -->
                @auth
                    @if(Auth::user()->skor_praktik === null)
                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                            <form action="{{ route('quiz.keterampilan.mulai') }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto bg-[#10B981] hover:bg-[#059669] text-white font-black text-sm py-4 px-10 rounded-full shadow-lg shadow-emerald-600/25 text-center transition cursor-pointer hover:scale-[1.02]">
                                    Mulai Kuis Keterampilan Sekarang →
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    {{-- Guest: tampilkan tombol yang membuka modal login --}}
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                        <button type="button" onclick="document.getElementById('modal-login-guard').classList.remove('hidden'); document.getElementById('modal-login-guard').classList.add('flex');"
                            class="w-full sm:w-auto bg-[#10B981] hover:bg-[#059669] text-white font-black text-sm py-4 px-10 rounded-full shadow-lg shadow-emerald-600/25 text-center transition cursor-pointer hover:scale-[1.02]">
                            Mulai Kuis Keterampilan Sekarang →
                        </button>
                    </div>
                @endauth
            </div>

        </div>
    </main>

    <!-- ================= FOOTER ================= -->
    <x-site-footer />

    {{-- Modal: Login Guard untuk Guest --}}
    <div id="modal-login-guard" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-sm rounded-3xl border border-slate-200 bg-white p-7 text-center shadow-2xl">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="mb-2 text-lg font-extrabold text-slate-800">Login Diperlukan</h3>
            <p class="mb-6 text-sm leading-relaxed text-slate-500">
                Kamu harus masuk ke akun terlebih dahulu untuk mengerjakan Quiz Keterampilan. Nilai dan histori pengerjaan kamu akan tersimpan otomatis.
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}"
                   class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-extrabold text-white shadow-md transition hover:bg-emerald-700">
                    Masuk ke Akun
                </a>
                <a href="{{ route('register') }}"
                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                    Daftar Akun Baru
                </a>
                <button type="button"
                    onclick="document.getElementById('modal-login-guard').classList.add('hidden'); document.getElementById('modal-login-guard').classList.remove('flex');"
                    class="text-xs font-semibold text-slate-400 hover:text-slate-600 mt-1 cursor-pointer">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- JS for Dropdown -->
    <script>        function toggleQuizDropdown(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('quiz-dropdown-menu');
            const arrow = document.getElementById('quiz-dropdown-arrow');
            const isCurrentlyOpen = !menu.classList.contains('hidden');
            if (isCurrentlyOpen) {
                closeQuizDropdown();
            } else {
                menu.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
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

        document.addEventListener('click', function(e) {
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
