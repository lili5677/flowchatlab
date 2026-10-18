<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Baru - Flowchart Lab SMK</title>
    <meta name="description" content="Daftar akun baru siswa atau guru di Flowchart Lab SMK. Mulai kumpulkan poin dan kuasai logika algoritma visual.">

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
        <svg class="absolute -top-10 -left-20 w-[600px] h-[500px]" viewBox="0 0 600 500" fill="none">
            <path d="M 50 50 C 250 80, 400 300, 550 450" stroke="#78350F" stroke-width="2.5" stroke-dasharray="8 8"/>
            <rect x="230" y="70" width="16" height="16" rx="4" fill="#78350F" transform="rotate(15 238 78)"/>
        </svg>

        <!-- Dashed flowchart curve 2 -->
        <svg class="absolute top-1/3 -right-20 w-[700px] h-[600px]" viewBox="0 0 700 600" fill="none">
            <path d="M 50 150 C 300 100, 450 400, 650 350" stroke="#78350F" stroke-width="2.5" stroke-dasharray="8 8"/>
            <rect x="420" y="320" width="18" height="18" rx="4" fill="#78350F" transform="rotate(-20 429 329)"/>
            <circle cx="280" cy="115" r="8" fill="#78350F"/>
        </svg>

        <!-- Floating subtle squares -->
        <div class="absolute bottom-16 left-1/4 w-4 h-4 bg-amber-900 rounded-sm transform rotate-45"></div>
        <div class="absolute top-24 right-1/3 w-3 h-3 bg-amber-900 rounded-sm transform rotate-12"></div>
    </div>

    <!-- Main Container -->
    <main class="w-full flex-1 flex items-center justify-center relative z-10 py-6 sm:py-10">
        <div class="bg-white rounded-[38px] sm:rounded-[42px] max-w-lg w-full p-6 sm:p-9 shadow-2xl shadow-amber-950/25 border border-white/60">

            <!-- Role Switcher Segmented Control -->
            <div class="mb-6 flex justify-start">
                <div class="bg-[#EEF2F6] p-1.5 rounded-full inline-flex items-center gap-1 shadow-2xs">
                    <button type="button" id="tab-siswa" onclick="setRole('siswa')" class="bg-white text-slate-800 font-bold text-xs sm:text-[13px] px-4 py-2 rounded-full shadow-2xs flex items-center gap-1.5 transition-all cursor-pointer">
                        <span id="dot-siswa" class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span id="label-siswa">Siswa SMK (Aktif)</span>
                    </button>
                    <button type="button" id="tab-guru" onclick="setRole('guru')" class="text-slate-500 font-semibold text-xs sm:text-[13px] px-4 py-2 rounded-full hover:text-slate-700 transition-all cursor-pointer">
                        <span id="label-guru">Guru / Pembimbing</span>
                    </button>
                </div>
            </div>

            <!-- Header Titles -->
            <div class="mb-6">
                <h1 class="text-2xl sm:text-[28px] font-black text-slate-900 tracking-tight leading-tight">
                    Daftar Akun Baru
                </h1>
                <p id="form-subtitle" class="text-xs sm:text-[13px] text-slate-500 mt-1 leading-relaxed">
                    Lengkapi data dirimu untuk mulai mengumpulkan poin dan modul logika.
                </p>
            </div>

            <!-- Error Alerts (if any) -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    <div class="font-bold mb-1">Mohon periksa data berikut:</div>
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
                <input type="hidden" name="role" id="role-input" value="{{ old('role', 'siswa') }}">

                <!-- Field 1: Nama Lengkap -->
                <div>
                    <label for="name" id="name-label" class="block text-xs sm:text-[13px] font-bold text-slate-800 mb-1.5">
                        Nama Lengkap Siswa
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <!-- User Silhouette Icon -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Contoh: Bayu Pratama"
                            class="w-full bg-[#F1F4F9] border-0 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                        >
                    </div>
                </div>

                <!-- Field 2: Asal Sekolah -->
                <div>
                    <label for="school" id="school-label" class="block text-xs sm:text-[13px] font-bold text-slate-800 mb-1.5">
                        Asal Sekolah (SMK)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <!-- Graduation / School Icon -->
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
                            placeholder="Contoh: SMKN 1 Jakarta"
                            class="w-full bg-[#F1F4F9] border-0 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                        >
                    </div>
                </div>

                <!-- Field 3: Email Siswa / Akun Belajar.id -->
                <div>
                    <label for="email" id="email-label" class="block text-xs sm:text-[13px] font-bold text-slate-800 mb-1.5">
                        Email Siswa / Akun Belajar.id
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">
                            @
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@smk.belajar.id atau email aktif"
                            class="w-full bg-[#F1F4F9] border-0 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                        >
                    </div>
                </div>

                <!-- Field 4: Kata Sandi -->
                <div>
                    <label for="password" class="block text-xs sm:text-[13px] font-bold text-slate-800 mb-1.5">
                        Kata Sandi
                    </label>
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
                            class="w-full bg-[#F1F4F9] border-0 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 pr-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
                        >
                        <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg id="eye-icon-1" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Field 5: Ulangi Sandi -->
                <div>
                    <label for="password_confirmation" class="block text-xs sm:text-[13px] font-bold text-slate-800 mb-1.5">
                        Ulangi Sandi
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <!-- Repeat / Reload Icon -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            placeholder="Ketik ulang sandi"
                            class="w-full bg-[#F1F4F9] border-0 text-slate-800 text-xs sm:text-[13px] font-medium rounded-2xl px-4 py-3.5 pl-11 pr-11 focus:bg-white focus:ring-2 focus:ring-emerald-500 transition-all placeholder:text-slate-400"
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
                    <div class="flex items-center justify-between text-[11px] sm:text-xs mb-1.5">
                        <span class="font-semibold text-slate-600">Kekuatan Sandi</span>
                        <span id="strength-text" class="font-bold text-emerald-600">Aman &bull; Siap Digunakan</span>
                    </div>
                    <!-- 4 Segmented Green Bars -->
                    <div class="grid grid-cols-4 gap-2">
                        <div id="bar-1" class="h-1.5 rounded-full bg-emerald-500 transition-all"></div>
                        <div id="bar-2" class="h-1.5 rounded-full bg-emerald-500 transition-all"></div>
                        <div id="bar-3" class="h-1.5 rounded-full bg-emerald-500 transition-all"></div>
                        <div id="bar-4" class="h-1.5 rounded-full bg-emerald-500 transition-all"></div>
                    </div>
                </div>

                <!-- Terms & Privacy Agreement Checkbox -->
                <div class="flex items-start gap-2.5 pt-2">
                    <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        value="1"
                        required
                        class="mt-1 w-4 h-4 rounded text-blue-600 accent-blue-600 cursor-pointer border-slate-300 focus:ring-blue-500"
                    >
                    <label for="terms" class="text-xs text-slate-600 leading-snug cursor-pointer">
                        Saya menyetujui <span class="font-bold text-slate-900">Ketentuan Pembelajaran</span> &amp; <span class="font-bold text-slate-900">Kebijakan Privasi</span> Flowchart Lab.
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-[#0D6E38] hover:bg-[#09572B] text-white font-extrabold text-sm sm:text-base py-3.5 rounded-full shadow-lg shadow-emerald-950/20 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer mt-4"
                >
                    Daftar Akun
                </button>
            </form>

            <!-- Card Bottom Navigation -->
            <div class="text-center mt-6 text-xs text-slate-600 font-medium">
                Sudah memiliki akun siswa atau guru? 
                <a href="{{ route('login') }}" class="font-bold text-[#B45309] hover:text-[#92400E] inline-flex items-center gap-0.5 ml-1">
                    <span>Masuk di sini</span>
                    <span>&rsaquo;</span>
                </a>
            </div>

        </div>
    </main>

    <!-- Page Bottom Footer -->
    <footer class="w-full max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between text-[11px] sm:text-xs text-amber-950/80 font-medium pt-4 pb-2 gap-2 text-center sm:text-left relative z-10">
        <div>
            &copy; 2025 Flowchart Lab Indonesia &bull; Algoritma &amp; Pemrograman SMK
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}#panduan" class="hover:text-amber-950 underline underline-offset-2">Pusat Bantuan</a>
            <span>&bull;</span>
            <a href="#" class="hover:text-amber-950 underline underline-offset-2">Kebijakan Privasi</a>
        </div>
    </footer>

    <!-- Interactive Client Script -->
    <script>
        function setRole(role) {
            const input = document.getElementById('role-input');
            const tabSiswa = document.getElementById('tab-siswa');
            const tabGuru = document.getElementById('tab-guru');
            const nameLabel = document.getElementById('name-label');
            const emailLabel = document.getElementById('email-label');
            const subtitle = document.getElementById('form-subtitle');

            input.value = role;

            if (role === 'siswa') {
                tabSiswa.className = "bg-white text-slate-800 font-bold text-xs sm:text-[13px] px-4 py-2 rounded-full shadow-2xs flex items-center gap-1.5 transition-all cursor-pointer";
                tabGuru.className = "text-slate-500 font-semibold text-xs sm:text-[13px] px-4 py-2 rounded-full hover:text-slate-700 transition-all cursor-pointer";
                tabSiswa.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500"></span><span>Siswa SMK (Aktif)</span>';
                tabGuru.innerHTML = '<span>Guru / Pembimbing</span>';
                nameLabel.innerText = "Nama Lengkap Siswa";
                emailLabel.innerText = "Email Siswa / Akun Belajar.id";
                subtitle.innerText = "Lengkapi data dirimu untuk mulai mengumpulkan poin dan modul logika.";
            } else {
                tabGuru.className = "bg-white text-slate-800 font-bold text-xs sm:text-[13px] px-4 py-2 rounded-full shadow-2xs flex items-center gap-1.5 transition-all cursor-pointer";
                tabSiswa.className = "text-slate-500 font-semibold text-xs sm:text-[13px] px-4 py-2 rounded-full hover:text-slate-700 transition-all cursor-pointer";
                tabGuru.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500"></span><span>Guru / Pembimbing (Aktif)</span>';
                tabSiswa.innerHTML = '<span>Siswa SMK</span>';
                nameLabel.innerText = "Nama Lengkap Guru / Pembimbing";
                emailLabel.innerText = "Email Guru / Akun Belajar.id";
                subtitle.innerText = "Kelola kelas algoritma dan pantau autocheck tugas siswa SMK.";
            }
        }

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
