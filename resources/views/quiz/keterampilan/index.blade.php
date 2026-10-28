<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz Keterampilan - Flowchart Lab SMK</title>
    <meta name="description" content="Quiz Keterampilan Praktik Alur Flowchart Algoritma Visual untuk Siswa SMK.">

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
<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col antialiased selection:bg-amber-200 selection:text-amber-900">

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
                <a href="{{ route('simulasi') }}" class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white">
                    Simulasi
                </a>
                
                <!-- Quiz Dropdown -->
                <div class="relative" id="quiz-dropdown-wrapper">
                    <button type="button" 
                            id="quiz-dropdown-btn" 
                            onclick="toggleQuizDropdown(event)" 
                            class="bg-amber-100 text-amber-900 font-bold px-4 py-1.5 rounded-full text-[13px] shadow-2xs transition inline-flex items-center gap-1.5 cursor-pointer">
                        <span>Quiz</span>
                        <svg id="quiz-dropdown-arrow" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
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

                    <div class="w-9 h-9 rounded-full ring-2 ring-sky-300 ring-offset-2 overflow-hidden bg-sky-50 flex items-center justify-center shrink-0 shadow-2xs">
                        <svg class="w-full h-full text-sky-800" viewBox="0 0 40 40" fill="none">
                            <circle cx="20" cy="20" r="20" fill="#E0F2FE" />
                            <circle cx="20" cy="15" r="7" fill="#FBCFE8" />
                            <path d="M20 9C15 9 14 13 14 15C14 16 15 17 20 17C25 17 26 16 26 15C26 13 25 9 20 9Z" fill="#1E293B" />
                            <path d="M9 36C9 28 14 25 20 25C26 25 31 28 31 36" fill="#FFFFFF" />
                            <path d="M18 25L20 31L22 25" fill="#0284C7" />
                        </svg>
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
                        <span>🧩 QUIZ KETERAMPILAN ALGORITMA VISUAL</span>
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
                        <div class="mt-6 p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white font-black text-sm flex items-center justify-center shrink-0">
                                    ✓
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Rekam Skor Anda</div>
                                    <div class="text-sm font-extrabold text-slate-900">
                                        Skor Praktik Terakhir: <span class="text-emerald-600 text-base font-black">{{ Auth::user()->skor_praktik }}</span> / 100
                                    </div>
                                    <div class="text-[11px] text-slate-500">Skor Praktik tersimpan terpisah dari Quiz Pengetahuan. Percobaan ulang akan menimpa skor lama.</div>
                                </div>
                            </div>
                            <span class="bg-emerald-100 text-emerald-800 font-extrabold text-xs px-3 py-1 rounded-full text-center">
                                Skor Praktik
                            </span>
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
                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                    @if($hasActiveSession)
                        <a href="{{ route('quiz.keterampilan.tugas') }}" class="w-full sm:w-auto bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-sm py-3.5 px-8 rounded-full shadow-lg shadow-emerald-600/20 text-center transition">
                            Lanjutkan Kuis Berjalan →
                        </a>
                        <form action="{{ route('quiz.keterampilan.mulai') }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm py-3.5 px-6 rounded-full text-center transition cursor-pointer">
                                Mulai Kuis Baru
                            </button>
                        </form>
                    @else
                        <form action="{{ route('quiz.keterampilan.mulai') }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-[#10B981] hover:bg-[#059669] text-white font-black text-sm py-4 px-10 rounded-full shadow-lg shadow-emerald-600/25 text-center transition cursor-pointer hover:scale-[1.02]">
                                Mulai Kuis Keterampilan Sekarang →
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('home') }}" class="w-full sm:w-auto bg-transparent hover:bg-slate-100 text-slate-600 font-semibold text-sm py-3.5 px-6 rounded-full text-center transition">
                        Kembali ke Beranda
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

    <!-- JS for Dropdown -->
    <script>
        function toggleQuizDropdown(event) {
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
    </script>
</body>
</html>
