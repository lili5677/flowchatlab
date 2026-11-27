<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Baru - Flowchart Lab</title>
    <meta name="description" content="Daftar akun baru siswa di Flowchart Lab. Mulai kumpulkan poin dan kuasai logika algoritma visual.">

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

            <!-- Brand Header -->
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 group-hover:scale-105 transition-transform duration-200">
                    <div class="flex flex-col">
                        <span class="font-extrabold text-[17px] text-slate-900 tracking-tight leading-snug">Flowchart Lab</span>
                        <span class="font-bold text-[10px] tracking-wider text-[#EA580C] uppercase leading-none mt-0.5">BELAJAR ALGORITMA VISUAL</span>
                    </div>
                </a>
            </div>

            <!-- Header Titles -->
            <div class="mb-6">
                <h1 class="text-2xl sm:text-[28px] font-black text-slate-900 tracking-tight leading-tight flex items-center gap-2">
                    <span>Daftar Akun Baru</span>
                    <span class="text-2xl">✨</span>
                </h1>
                <p class="text-xs sm:text-[13px] text-slate-500 mt-2 leading-relaxed">
                    Silakan lengkapi data Anda untuk membuat akun siswa baru di Flowchart Lab.
                </p>
            </div>

            <!-- Status Alert -->
            @if (session('success'))
                <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                    <span class="text-emerald-600 font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Alerts (if any) -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    <div class="font-bold mb-0.5">Gagal Mendaftar:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="role" value="siswa">

                <!-- Field 1: NAMA LENGKAP -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="name" class="block text-[11px] font-extrabold tracking-wider text-slate-700 uppercase">
                            NAMA LENGKAP
                        </label>
                        <span class="text-[11px] text-slate-400 font-medium">Wajib diisi</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <!-- User Silhouette Icon -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            placeholder="Contoh: Bayu Pratama"
                            class="w-full bg-[#F8FAFC] border border-slate-200/90 hover:border-slate-300 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder:text-slate-400"
                        >
                    </div>
                </div>

                <!-- Field 2: ASAL SEKOLAH -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="school" class="block text-[11px] font-extrabold tracking-wider text-slate-700 uppercase">
                            ASAL SEKOLAH
                        </label>
                        <span class="text-[11px] text-slate-400 font-medium">Wajib diisi</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <!-- Graduation Cap Icon -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="school"
                            name="school"
                            value="{{ old('school') }}"
                            required
                            placeholder="Contoh: SMA / SMK / MA Negeri 1"
                            class="w-full bg-[#F8FAFC] border border-slate-200/90 hover:border-slate-300 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder:text-slate-400"
                        >
                    </div>
                </div>

                <!-- Field 3: EMAIL -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="email" class="block text-[11px] font-extrabold tracking-wider text-slate-700 uppercase">
                            EMAIL
                        </label>
                        <span class="text-[11px] text-slate-400 font-medium">Wajib diisi</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <!-- Mail Envelope Icon -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@email.com"
                            class="w-full bg-[#F8FAFC] border border-slate-200/90 hover:border-slate-300 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder:text-slate-400"
                        >
                    </div>
                </div>

                <!-- Field 4: KATA SANDI -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-[11px] font-extrabold tracking-wider text-slate-700 uppercase">
                            KATA SANDI
                        </label>
                        <span class="text-[11px] text-slate-400 font-medium">Min. 8 Karakter</span>
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
                            oninput="checkPasswordStrength(this.value)"
                            placeholder="Minimal 8 karakter"
                            class="w-full bg-[#F8FAFC] border border-slate-200/90 hover:border-slate-300 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 pr-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder:text-slate-400"
                        >
                        <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg id="eye-icon-1" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Field 5: KONFIRMASI SANDI -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password_confirmation" class="block text-[11px] font-extrabold tracking-wider text-slate-700 uppercase">
                            KONFIRMASI SANDI
                        </label>
                        <span class="text-[11px] text-slate-400 font-medium">Wajib cocok</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <!-- Shield Check Icon -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M9 12l2 2 4-4"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            placeholder="Ulangi kata sandi Anda"
                            class="w-full bg-[#F8FAFC] border border-slate-200/90 hover:border-slate-300 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 pr-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all placeholder:text-slate-400"
                        >
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg id="eye-icon-2" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Strength Indicator -->
                <div class="pt-1">
                    <div class="flex items-center justify-between text-[11px] mb-1.5">
                        <span class="font-extrabold text-slate-500 uppercase tracking-wider text-[10px]">Kekuatan Sandi</span>
                        <span id="strength-text" class="font-bold text-slate-400">Minimal 8 karakter</span>
                    </div>
                    <!-- 4 Segmented Bars -->
                    <div class="grid grid-cols-4 gap-2">
                        <div id="bar-1" class="h-1.5 rounded-full bg-slate-200 transition-all"></div>
                        <div id="bar-2" class="h-1.5 rounded-full bg-slate-200 transition-all"></div>
                        <div id="bar-3" class="h-1.5 rounded-full bg-slate-200 transition-all"></div>
                        <div id="bar-4" class="h-1.5 rounded-full bg-slate-200 transition-all"></div>
                    </div>
                </div>

                <!-- Terms & Privacy Agreement Checkbox -->
                <div class="flex items-start gap-2.5 pt-1">
                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        value="1"
                        required
                        class="mt-0.5 w-4 h-4 rounded text-emerald-600 accent-emerald-600 border-slate-300 focus:ring-emerald-500 cursor-pointer"
                    >
                    <label for="terms" class="text-xs text-slate-600 leading-snug cursor-pointer select-none">
                        Saya menyetujui <span class="font-bold text-slate-800">Ketentuan Pembelajaran</span> &amp; <span class="font-bold text-slate-800">Kebijakan Privasi</span> Flowchart Lab.
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-extrabold text-sm sm:text-base py-3.5 rounded-full shadow-lg shadow-emerald-500/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer mt-4"
                >
                    Daftar Akun Baru
                </button>
            </form>

            <!-- Card Bottom Navigation -->
            <div class="text-center mt-6 pt-5 border-t border-slate-100 text-xs text-slate-600 font-medium">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-extrabold text-[#B45309] hover:text-[#92400E] ml-1">
                    Masuk Sekarang
                </a>
            </div>

            <!-- Back to Home Link -->
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-[11px] font-semibold text-slate-400 hover:text-slate-600 transition inline-flex items-center gap-1">
                    &larr; Kembali ke Beranda
                </a>
            </div>

        </div>
    </main>

    <!-- Page Bottom Footer -->
    <x-auth-footer />

    <!-- Interactive Client Script -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }

        function checkPasswordStrength(val) {
            const b1 = document.getElementById('bar-1');
            const b2 = document.getElementById('bar-2');
            const b3 = document.getElementById('bar-3');
            const b4 = document.getElementById('bar-4');
            const text = document.getElementById('strength-text');

            if (!val || val.length === 0) {
                b1.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                b2.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                b3.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                b4.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                text.innerText = 'Minimal 8 karakter';
                text.className = 'font-bold text-slate-400';
                return;
            }

            if (val.length < 5) {
                b1.className = 'h-1.5 rounded-full bg-rose-500 transition-all';
                b2.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                b3.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                b4.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                text.innerText = 'Sangat Lemah';
                text.className = 'font-bold text-rose-500';
            } else if (val.length < 8) {
                b1.className = 'h-1.5 rounded-full bg-amber-500 transition-all';
                b2.className = 'h-1.5 rounded-full bg-amber-500 transition-all';
                b3.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                b4.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                text.innerText = 'Cukup';
                text.className = 'font-bold text-amber-500';
            } else if (val.length < 10) {
                b1.className = 'h-1.5 rounded-full bg-emerald-400 transition-all';
                b2.className = 'h-1.5 rounded-full bg-emerald-400 transition-all';
                b3.className = 'h-1.5 rounded-full bg-emerald-400 transition-all';
                b4.className = 'h-1.5 rounded-full bg-slate-200 transition-all';
                text.innerText = 'Kuat';
                text.className = 'font-bold text-emerald-500';
            } else {
                b1.className = 'h-1.5 rounded-full bg-emerald-500 transition-all';
                b2.className = 'h-1.5 rounded-full bg-emerald-500 transition-all';
                b3.className = 'h-1.5 rounded-full bg-emerald-500 transition-all';
                b4.className = 'h-1.5 rounded-full bg-emerald-500 transition-all';
                text.innerText = 'Aman • Siap Digunakan';
                text.className = 'font-bold text-emerald-600';
            }
        }
    </script>
</body>
</html>
