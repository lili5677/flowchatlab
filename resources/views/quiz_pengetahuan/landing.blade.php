<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz Pengetahuan – Riwayat Pengerjaan - Flowchart Lab SMK</title>
    <meta name="description" content="Kamu telah mengerjakan Quiz Pengetahuan. Lihat hasil sebelumnya atau mulai pengerjaan ulang.">

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
            <nav class="hidden md:flex items-center gap-1.5 bg-slate-100/80 p-1.5 rounded-full border border-slate-200/80">
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">Beranda</a>
                <a href="{{ route('materi') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">Materi</a>
                <a href="{{ route('simulasi') }}#simulasi" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">Simulasi</a>
                <div class="relative" id="quiz-dropdown-wrapper">
                    <button type="button" id="quiz-dropdown-btn" onclick="toggleQuizDropdown(event)"
                            class="bg-amber-100 text-amber-900 font-bold px-4 py-1.5 rounded-full text-[13px] shadow-2xs transition inline-flex items-center gap-1.5 cursor-pointer">
                        <span>Quiz</span>
                        <svg id="quiz-dropdown-arrow" class="w-3.5 h-3.5 text-amber-700 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
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
                    <div class="w-9 h-9 rounded-full ring-2 ring-sky-300 ring-offset-2 overflow-hidden bg-sky-50 flex items-center justify-center shrink-0 shadow-2xs">
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
                </div>
                <!-- Mobile Menu Button -->
                <button type="button" class="md:hidden p-2 text-slate-700 hover:text-slate-950" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Nav -->
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
            <a href="{{ route('quiz.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold bg-amber-100 text-amber-900">Quiz Pengetahuan</a>
        </div>
    </header>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="w-full max-w-xl space-y-6">

            <!-- Badge Row -->
            <div class="flex items-center justify-center gap-2">
                <span class="bg-emerald-600 text-white font-extrabold text-[11px] px-3.5 py-1 rounded-full uppercase tracking-wider shadow-2xs">
                    Modul Dasar Flowchart
                </span>
                <span class="bg-white/80 backdrop-blur-xs text-amber-950 border border-amber-200 font-bold text-[11px] px-3.5 py-1 rounded-full uppercase tracking-wider shadow-2xs">
                    Riwayat Pengerjaan
                </span>
            </div>

            <!-- Title -->
            <div class="text-center space-y-1.5">
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    Kamu Sudah Pernah<br>Mengerjakan Quiz Ini!
                </h1>
                <p class="text-sm text-slate-700 font-medium">
                    Berikut hasil pengerjaan terakhir kamu. Pilih tindakan yang ingin dilakukan.
                </p>
            </div>

            <!-- Previous Score Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-2xl space-y-5">

                <!-- Predicate Badge -->
                <div class="text-center">
                    @if ($lastAttempt->is_passed)
                        <span class="inline-flex items-center gap-2 bg-emerald-600 text-white font-extrabold text-xs sm:text-sm px-5 py-2 rounded-full uppercase tracking-wider shadow-md shadow-emerald-600/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $predicateConfig['badge'] }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 bg-rose-600 text-white font-extrabold text-xs sm:text-sm px-5 py-2 rounded-full uppercase tracking-wider shadow-md shadow-rose-600/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ $predicateConfig['badge'] }}
                        </span>
                    @endif
                </div>

                <!-- Score Display -->
                <div class="flex items-center justify-center gap-6">
                    @php
                        $circumference = 339.29;
                        $offset = $circumference - (($lastAttempt->score / 100) * $circumference);
                        $gaugeColor = $lastAttempt->is_passed ? 'stroke-emerald-500' : 'stroke-rose-500';
                    @endphp
                    <div class="relative w-36 h-36 flex items-center justify-center shrink-0">
                        <svg class="w-full h-full score-circle" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="54" class="stroke-slate-100 fill-none" stroke-width="10" />
                            <circle cx="60" cy="60" r="54"
                                    class="{{ $gaugeColor }} fill-none"
                                    stroke-width="10"
                                    stroke-linecap="round"
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $offset }}" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="text-4xl font-black text-slate-900 tracking-tight leading-none">
                                {{ $lastAttempt->score }}
                            </span>
                            <span class="text-[9px] font-black tracking-widest text-slate-400 uppercase mt-1">SKOR AKHIR</span>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="space-y-3 flex-1">
                        <div class="flex items-center gap-2.5">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 w-20 shrink-0">Jawaban Benar</span>
                            <span class="font-black text-slate-900 text-sm">{{ $lastAttempt->correct_count }}<span class="text-slate-400 font-semibold text-xs"> / {{ $lastAttempt->total_questions }} soal</span></span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 w-20 shrink-0">Total Poin</span>
                            <span class="font-black text-slate-900 text-sm">{{ $lastAttempt->total_points }}<span class="text-slate-400 font-semibold text-xs"> / 100</span></span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 w-20 shrink-0">Status KKM</span>
                            @if ($lastAttempt->is_passed)
                                <span class="bg-emerald-100 text-emerald-800 font-black text-[10px] px-2.5 py-0.5 rounded-full">✓ Lulus (KKM {{ $passingScore }})</span>
                            @else
                                <span class="bg-rose-100 text-rose-800 font-black text-[10px] px-2.5 py-0.5 rounded-full">✕ Belum Lulus (KKM {{ $passingScore }})</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 w-20 shrink-0">Dikerjakan</span>
                            <span class="font-semibold text-slate-600 text-xs">{{ $lastAttempt->completed_at?->locale('id')->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-slate-100"></div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Review Hasil -->
                    <a href="{{ route('quiz.result', $lastAttempt->id) }}"
                       id="btn-lihat-hasil"
                       class="w-full bg-slate-100 hover:bg-slate-200 text-slate-900 font-extrabold text-xs sm:text-[13px] px-6 py-3.5 rounded-full inline-flex items-center justify-center gap-2.5 transition shadow-2xs cursor-pointer">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Lihat Hasil Sebelumnya</span>
                    </a>

                    <!-- Kerjakan Ulang -->
                    <form method="POST" action="{{ route('quiz.retry') }}" id="form-retry">
                        @csrf
                        <button type="submit"
                                id="btn-kerjakan-ulang"
                                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs sm:text-[13px] uppercase tracking-wide px-6 py-3.5 rounded-full inline-flex items-center justify-center gap-2.5 shadow-lg shadow-amber-500/30 transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Kerjakan Ulang dari Awal</span>
                        </button>
                    </form>
                </div>

                <!-- Info note -->
                <p class="text-center text-[11px] text-slate-400 font-medium">
                    ⚠️ Mengerjakan ulang akan memulai sesi baru. Riwayat pengerjaan sebelumnya tetap tersimpan.
                </p>
            </div>

            <!-- Sub info cards -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/80 rounded-2xl p-4 border border-amber-200/80 shadow-xs flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-lg shrink-0">💻</div>
                    <div>
                        <div class="font-extrabold text-xs text-slate-900">20 Soal Adaptif</div>
                        <p class="text-[10px] text-slate-600 mt-0.5">3 tingkat kesulitan: Mudah, Sedang, Sulit.</p>
                    </div>
                </div>
                <a href="{{ route('materi') }}" class="bg-white/80 hover:bg-amber-50/80 rounded-2xl p-4 border border-amber-200/80 shadow-xs flex items-center gap-3 transition group">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg shrink-0">📖</div>
                    <div>
                        <div class="font-extrabold text-xs text-slate-900 group-hover:text-amber-800 flex items-center gap-1">
                            <span>Pelajari Materi</span>
                            <span class="text-slate-400 group-hover:text-amber-600 text-[9px]">↗</span>
                        </div>
                        <p class="text-[10px] text-slate-600 mt-0.5">Baca materi sebelum mengerjakan ulang.</p>
                    </div>
                </a>
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

    <script>
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

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('quiz-dropdown-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                const menu = document.getElementById('quiz-dropdown-menu');
                const arrow = document.getElementById('quiz-dropdown-arrow');
                if (menu) menu.classList.add('hidden');
                if (arrow) arrow.classList.remove('rotate-180');
            }
        });
    </script>
</body>
</html>
