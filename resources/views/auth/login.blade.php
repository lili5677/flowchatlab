<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Flowchart Lab SMK</title>
    <meta name="description" content="Masuk ke Flowchart Lab SMK untuk melanjutkan sesi belajar logika pemrograman dan simulasi algoritma visual.">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#E89E06] via-[#F8B715] to-[#F29F05] flex flex-col justify-between p-4 sm:p-6 lg:p-8 relative overflow-x-hidden">

    <!-- Subtle Decorative Flowchart Background Lines & Nodes -->
    <div class="fixed inset-0 pointer-events-none opacity-25 overflow-hidden">
        <!-- Dashed flowchart curve 1 -->
        <svg class="absolute -top-12 -left-20 w-[600px] h-[500px]" viewBox="0 0 600 500" fill="none">
            <path d="M 50 60 C 250 120, 380 250, 560 420" stroke="#78350F" stroke-width="2.5" stroke-dasharray="8 8"/>
            <rect x="240" y="100" width="16" height="16" rx="4" fill="#78350F" transform="rotate(15 248 108)"/>
        </svg>

        <!-- Dashed flowchart curve 2 -->
        <svg class="absolute top-1/4 -right-16 w-[700px] h-[600px]" viewBox="0 0 700 600" fill="none">
            <path d="M 50 200 C 320 80, 480 380, 680 320" stroke="#78350F" stroke-width="2.5" stroke-dasharray="8 8"/>
            <circle cx="340" cy="140" r="8" fill="#78350F"/>
            <rect x="440" y="300" width="16" height="16" rx="4" fill="#78350F" transform="rotate(-15 448 308)"/>
        </svg>

        <div class="absolute bottom-20 left-1/4 w-3.5 h-3.5 bg-amber-900 rounded-sm transform rotate-45"></div>
        <div class="absolute top-28 right-1/4 w-3.5 h-3.5 bg-amber-900 rounded-sm transform rotate-12"></div>
    </div>

    <!-- Main Container -->
    <main class="w-full flex-1 flex items-center justify-center relative z-10 py-6 sm:py-10">
        <div class="bg-white rounded-[38px] sm:rounded-[42px] max-w-md w-full p-7 sm:p-9 shadow-2xl shadow-amber-950/25 border border-white/60">

            <!-- Role Switcher Segmented Control -->
            <div class="mb-6">
                <div class="bg-[#F1F3F6] p-1 rounded-2xl flex items-center justify-between gap-1 shadow-2xs">
                    <button type="button" id="tab-login-siswa" onclick="setLoginRole('siswa')" class="w-1/2 py-2.5 rounded-xl bg-white shadow-2xs text-center font-bold text-xs sm:text-[13px] text-slate-800 flex items-center justify-center gap-2 cursor-pointer transition-all">
                        <span class="text-sm">🎓</span>
                        <span>Siswa SMK</span>
                    </button>
                    <button type="button" id="tab-login-guru" onclick="setLoginRole('guru')" class="w-1/2 py-2.5 rounded-xl text-center font-semibold text-xs sm:text-[13px] text-slate-500 hover:text-slate-700 flex items-center justify-center gap-2 cursor-pointer transition-all">
                        <span class="text-sm">👨‍🏫</span>
                        <span>Guru / Pembimbing</span>
                    </button>
                </div>
            </div>

            <!-- Header Titles -->
            <div class="mb-6">
                <h1 class="text-2xl sm:text-[28px] font-black text-slate-900 tracking-tight leading-tight flex items-center gap-2">
                    <span>Selamat Datang Kembali!</span>
                    <span class="text-2xl">👋</span>
                </h1>
                <p id="login-subtitle" class="text-xs sm:text-[13px] text-slate-500 mt-2 leading-relaxed">
                    Silakan masukkan data akun Anda untuk melanjutkan sesi pembelajaran.
                </p>
            </div>

            <!-- Status Alert (e.g. registration success or logout) -->
            @if (session('success'))
                <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                    <span class="text-emerald-600 font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Alerts (if any) -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    <div class="font-bold mb-0.5">Gagal Masuk:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="login_role" id="login-role-input" value="siswa">

                <!-- Field 1: EMAIL -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="email" class="block text-[11px] font-extrabold tracking-wider text-slate-700 uppercase">
                            EMAIL
                        </label>
                        <span class="text-[11px] text-slate-400 font-medium">Wajib diisi</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <!-- User Circle Icon -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="pengguna@gmail.com"
                            class="w-full bg-[#F8FAFC] border border-slate-200/90 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                        >
                    </div>
                </div>

                <!-- Field 2: KATA SANDI -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-[11px] font-extrabold tracking-wider text-slate-700 uppercase">
                            KATA SANDI
                        </label>
                        <span class="text-[11px] text-slate-400 font-medium">Min. 6 Karakter</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <!-- Lock Icon -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Masukkan kata sandi akun Anda"
                            class="w-full bg-[#F8FAFC] border border-slate-200/90 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 pr-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                        >
                        <button type="button" onclick="toggleLoginPassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg id="login-eye-icon" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password Row -->
                <div class="flex items-center justify-between pt-1 text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600 font-medium select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="w-4 h-4 rounded text-emerald-600 accent-emerald-600 border-slate-300 focus:ring-emerald-500 cursor-pointer"
                        >
                        <span>Ingat Saya di Perangkat Ini</span>
                    </label>
                    <a href="#lupa-sandi" onclick="alert('Fitur reset kata sandi dapat dilakukan melalui email guru atau admin lab SMK.')" class="font-bold text-[#B45309] hover:text-[#92400E] transition">
                        Lupa Kata Sandi?
                    </a>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-sm sm:text-base py-3.5 rounded-full shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer mt-4"
                >
                    Masuk
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6 text-center">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-100"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white px-3 text-[11px] text-slate-400 font-medium">
                        atau masuk dengan
                    </span>
                </div>
            </div>

            <!-- Single Click Demo Account for Easy Testing -->
            <div class="grid grid-cols-2 gap-2.5 mb-2">
                <button type="button" onclick="fillDemoAccount('siswa')" class="p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-[11px] font-bold text-slate-700 flex items-center justify-center gap-1.5 transition cursor-pointer">
                    <span>🎓</span>
                    <span>Demo Siswa</span>
                </button>
                <button type="button" onclick="fillDemoAccount('guru')" class="p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-[11px] font-bold text-slate-700 flex items-center justify-center gap-1.5 transition cursor-pointer">
                    <span>👨‍🏫</span>
                    <span>Demo Guru</span>
                </button>
            </div>

            <!-- Card Bottom Navigation -->
            <div class="text-center mt-6 text-xs text-slate-600 font-medium">
                Belum punya akun siswa atau guru? 
                <a href="{{ route('register') }}" class="font-extrabold text-[#B45309] hover:text-[#92400E] ml-1">
                    Daftar Sekarang
                </a>
            </div>

        </div>
    </main>

    <!-- Page Bottom Footer -->
    <footer class="w-full max-w-4xl mx-auto text-center text-[11px] sm:text-xs text-amber-950/80 font-medium py-3 relative z-10">
        &copy; 2025 Flowchart Lab SMK. Dikembangkan untuk kemajuan pendidikan vokasi vokasi teknologi di Indonesia.
    </footer>

    <!-- Interactive Client Script -->
    <script>
        function setLoginRole(role) {
            const input = document.getElementById('login-role-input');
            const tabSiswa = document.getElementById('tab-login-siswa');
            const tabGuru = document.getElementById('tab-login-guru');
            const subtitle = document.getElementById('login-subtitle');

            input.value = role;

            if (role === 'siswa') {
                tabSiswa.className = "w-1/2 py-2.5 rounded-xl bg-white shadow-2xs text-center font-bold text-xs sm:text-[13px] text-slate-800 flex items-center justify-center gap-2 cursor-pointer transition-all";
                tabGuru.className = "w-1/2 py-2.5 rounded-xl text-center font-semibold text-xs sm:text-[13px] text-slate-500 hover:text-slate-700 flex items-center justify-center gap-2 cursor-pointer transition-all";
                subtitle.innerText = "Silakan masukkan data akun Anda untuk melanjutkan sesi pembelajaran.";
            } else {
                tabGuru.className = "w-1/2 py-2.5 rounded-xl bg-white shadow-2xs text-center font-bold text-xs sm:text-[13px] text-slate-800 flex items-center justify-center gap-2 cursor-pointer transition-all";
                tabSiswa.className = "w-1/2 py-2.5 rounded-xl text-center font-semibold text-xs sm:text-[13px] text-slate-500 hover:text-slate-700 flex items-center justify-center gap-2 cursor-pointer transition-all";
                subtitle.innerText = "Masuk sebagai Guru / Pembimbing untuk mengelola modul dan penilaian.";
            }
        }

        function toggleLoginPassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('login-eye-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }

        function fillDemoAccount(type) {
            if (type === 'siswa') {
                setLoginRole('siswa');
                document.getElementById('email').value = 'bayu.pratama@smk.belajar.id';
                document.getElementById('password').value = 'password123';
            } else {
                setLoginRole('guru');
                document.getElementById('email').value = 'guru.algoritma@smk.belajar.id';
                document.getElementById('password').value = 'password123';
            }
        }
    </script>
</body>
</html>
