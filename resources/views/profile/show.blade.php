<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Akun - Flowchart Lab</title>
    <meta name="description" content="Kelola informasi profil akun Anda di Flowchart Lab.">

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
                    <span class="font-bold text-[10px] tracking-wider text-[#EA580C] uppercase leading-none mt-0.5">BELAJAR ALGORITMA VISUAL</span>
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
                            class="text-slate-600 hover:text-slate-900 font-semibold px-3.5 py-1.5 rounded-full text-[13px] transition hover:bg-white inline-flex items-center gap-1.5 cursor-pointer">
                        <span>Quiz</span>
                        <svg id="quiz-dropdown-arrow" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
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
                <!-- User Profile Container (Hover Dropdown + Click) -->
                <div class="relative group" id="profile-dropdown-wrapper" onmouseenter="openProfileDropdown()" onmouseleave="closeProfileDropdown()">
                    <div class="flex items-center gap-2.5 pl-1 sm:pl-2 cursor-pointer py-1" onclick="handleProfileClick(event)">
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

                        <!-- User Avatar -->
                        <div class="relative cursor-pointer" onclick="event.stopPropagation(); handleProfileClick(event);" title="{{ Auth::user()->name }}">
                            <div class="w-9 h-9 rounded-full ring-2 {{ Auth::user()->isAdmin() ? 'ring-amber-400 bg-amber-50' : 'ring-sky-300 bg-sky-50' }} ring-offset-2 overflow-hidden flex items-center justify-center shrink-0 shadow-2xs hover:ring-amber-400 transition">
                                <svg class="w-full h-full" viewBox="0 0 40 40" fill="none">
                                    <circle cx="20" cy="20" r="20" fill="{{ Auth::user()->isAdmin() ? '#FEF3C7' : '#E0F2FE' }}" />
                                    <circle cx="20" cy="15" r="7" fill="#FBCFE8" />
                                    <path d="M20 9C15 9 14 13 14 15C14 16 15 17 20 17C25 17 26 16 26 15C26 13 25 9 20 9Z" fill="#1E293B" />
                                    <path d="M9 36C9 28 14 25 20 25C26 25 31 28 31 36" fill="#FFFFFF" />
                                    <path d="M18 25L20 31L22 25" fill="{{ Auth::user()->isAdmin() ? '#D97706' : '#0284C7' }}" />
                                    <circle cx="18" cy="14" r="1" fill="#0F172A" />
                                    <circle cx="22" cy="14" r="1" fill="#0F172A" />
                                    <path d="M18.5 17C19 18 21 18 21.5 17" stroke="#0F172A" stroke-width="0.8" stroke-linecap="round" />
                                </svg>
                            </div>
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 {{ Auth::user()->isAdmin() ? 'bg-amber-500' : 'bg-emerald-500' }} border-2 border-white rounded-full"></span>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div id="profile-dropdown-menu" class="absolute right-0 top-full pt-1.5 w-72 hidden group-hover:block transition-all duration-150 z-50">
                        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/90 p-3.5 text-slate-800 space-y-3">
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
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-50 text-sky-800 border border-sky-200">
                                            {{ Auth::user()->role === 'guru' ? 'Guru Pembimbing' : 'Siswa' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-1">
                                @if(Auth::user()->isAdmin())
                                    <div class="p-0.5">
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between gap-3 w-full px-4 py-3 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 hover:from-slate-800 hover:to-slate-750 text-amber-400 font-black text-xs rounded-xl shadow-md border border-slate-800 transition transform hover:-translate-y-0.5">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-7 h-7 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center text-sm font-bold">⚙️</div>
                                                <div class="text-left">
                                                    <span class="block text-white font-extrabold text-xs leading-tight">Panel Administrator</span>
                                                    <span class="block text-[10px] text-amber-300 font-normal">Kelola Soal, Kasus & Siswa</span>
                                                </div>
                                            </div>
                                            <span class="text-amber-400 font-extrabold text-sm">&rarr;</span>
                                        </a>
                                    </div>
                                @endif

                                <div class="space-y-1 pt-1 border-t border-slate-100">
                                    <a href="{{ route('profile.show') }}" class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold bg-amber-50 text-amber-900 flex items-center gap-2.5 transition">
                                        <span>👤</span>
                                        <span>Profil Saya (Aktif)</span>
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2.5 transition cursor-pointer">
                                            <span>🚪</span>
                                            <span>Keluar (Logout)</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
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

        <!-- Mobile Nav -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white px-4 pt-2 pb-4 space-y-2">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Beranda</a>
            <a href="{{ route('materi') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Materi</a>
            <a href="{{ route('simulasi') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Simulasi</a>
            <a href="{{ route('quiz.index') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Quiz Pengetahuan</a>
            <a href="{{ route('quiz.keterampilan') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">Quiz Keterampilan</a>
        </div>
    </header>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-6">
                <a href="{{ route('home') }}" class="hover:text-slate-700 font-semibold transition">Beranda</a>
                <span>/</span>
                <span class="font-bold text-slate-800">Profil Akun</span>
            </div>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Profil Akun</h1>
                <p class="text-sm text-slate-500 mt-1 font-medium">Kelola informasi pribadi dan pengaturan akun kamu di Flowchart Lab.</p>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 mb-6">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <div class="text-sm font-semibold">{{ session('success') }}</div>
                </div>
            @endif

            <!-- Profile Card: Identity Summary -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 mb-6 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <!-- Avatar -->
                <div class="shrink-0">
                    <div class="w-20 h-20 rounded-full ring-4 {{ $user->isAdmin() ? 'ring-amber-300' : 'ring-sky-200' }} bg-sky-50 overflow-hidden flex items-center justify-center shadow-md">
                        <svg class="w-full h-full" viewBox="0 0 40 40" fill="none">
                            <circle cx="20" cy="20" r="20" fill="{{ $user->isAdmin() ? '#FEF3C7' : '#E0F2FE' }}" />
                            <circle cx="20" cy="15" r="7" fill="#FBCFE8" />
                            <path d="M20 9C15 9 14 13 14 15C14 16 15 17 20 17C25 17 26 16 26 15C26 13 25 9 20 9Z" fill="#1E293B" />
                            <path d="M9 36C9 28 14 25 20 25C26 25 31 28 31 36" fill="#FFFFFF" />
                            <path d="M18 25L20 31L22 25" fill="{{ $user->isAdmin() ? '#D97706' : '#0284C7' }}" />
                            <circle cx="18" cy="14" r="1" fill="#0F172A" />
                            <circle cx="22" cy="14" r="1" fill="#0F172A" />
                            <path d="M18.5 17C19 18 21 18 21.5 17" stroke="#0F172A" stroke-width="0.8" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>

                <!-- Identity Info -->
                <div class="flex-1 text-center sm:text-left">
                    <h2 class="text-xl font-black text-slate-900 leading-tight">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-2 justify-center sm:justify-start">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold
                            {{ $user->isAdmin() ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-sky-100 text-sky-800 border border-sky-200' }}">
                            @if($user->isAdmin())
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Administrator
                            @else
                                {{ $user->role === 'guru' ? 'Guru Pembimbing' : 'Siswa' }}
                            @endif
                        </span>
                        @if($user->kelas)
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $user->kelas }}
                            </span>
                        @endif
                        @if($user->school)
                            <span class="px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-50 text-slate-600 border border-slate-200">
                                {{ $user->school }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="shrink-0 grid grid-cols-2 gap-3 sm:gap-4">
                    <div class="text-center bg-amber-50 rounded-2xl p-3 border border-amber-100">
                        <div class="text-lg font-black text-amber-700">{{ $user->points ?? 0 }}</div>
                        <div class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Poin</div>
                    </div>
                    <div class="text-center bg-sky-50 rounded-2xl p-3 border border-sky-100">
                        <div class="text-lg font-black text-sky-700">{{ $user->skor_praktik ?? 0 }}</div>
                        <div class="text-[10px] font-bold text-sky-600 uppercase tracking-wider">Skor Praktik</div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('profile.update') }}" method="POST" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Section: Informasi Dasar -->
                <div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-xs">✏️</span>
                        Informasi Dasar
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border @error('name') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white transition">
                            @error('name')
                                <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-3.5 py-2.5 rounded-xl border @error('email') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white transition">
                            @error('email')
                                <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sekolah -->
                        <div>
                            <label for="school" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Sekolah</label>
                            <input type="text" name="school" id="school" value="{{ old('school', $user->school) }}" placeholder="Contoh: SMK Negeri 1 Surakarta"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white transition">
                        </div>

                        @if(!$user->isAdmin())
                            <!-- NIS -->
                            <div>
                                <label for="nis" class="block text-xs font-bold text-slate-700 mb-1.5">NIS (Nomor Induk Siswa)</label>
                                <input type="text" name="nis" id="nis" value="{{ old('nis', $user->nis) }}" placeholder="Contoh: 220914"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-mono font-bold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white transition">
                            </div>

                            <!-- Kelas -->
                            <div>
                                <label for="kelas" class="block text-xs font-bold text-slate-700 mb-1.5">Kelas</label>
                                <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $user->kelas) }}" placeholder="Contoh: Kelas X RPL 1"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white transition">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section: Ganti Password -->
                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-1 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center text-xs">🔑</span>
                        Ganti Password
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium mb-4">Biarkan kosong jika tidak ingin mengubah password.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Password Baru -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Password Baru</label>
                            <input type="password" name="password" id="password" autocomplete="new-password"
                                   placeholder="Min. 8 karakter, huruf & angka"
                                   class="w-full px-3.5 py-2.5 rounded-xl border @error('password') border-rose-400 bg-rose-50/50 @else border-slate-200 bg-slate-50/50 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white transition">
                            @error('password')
                                <p class="text-rose-500 text-[11px] mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                                   placeholder="Ulangi password baru"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        ← Kembali ke Beranda
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-amber-400 hover:bg-amber-500 text-slate-950 font-black text-sm shadow-sm transition active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </main>

    <!-- ================= FOOTER ================= -->
    <x-site-footer />

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu) menu.classList.toggle('hidden');
        }

        function toggleQuizDropdown(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('quiz-dropdown-menu');
            const arrow = document.getElementById('quiz-dropdown-arrow');
            const btn = document.getElementById('quiz-dropdown-btn');
            const isOpen = !menu.classList.contains('hidden');
            if (isOpen) {
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

        function openProfileDropdown() {
            const menu = document.getElementById('profile-dropdown-menu');
            if (menu) menu.classList.remove('hidden');
        }

        function closeProfileDropdown() {
            const menu = document.getElementById('profile-dropdown-menu');
            if (menu) menu.classList.add('hidden');
        }

        function handleProfileClick(event) {
            const menu = document.getElementById('profile-dropdown-menu');
            if (menu) menu.classList.toggle('hidden');
        }

        document.addEventListener('click', function (e) {
            const qWrapper = document.getElementById('quiz-dropdown-wrapper');
            if (qWrapper && !qWrapper.contains(e.target)) closeQuizDropdown();
            const pWrapper = document.getElementById('profile-dropdown-wrapper');
            if (pWrapper && !pWrapper.contains(e.target)) closeProfileDropdown();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeQuizDropdown();
                closeProfileDropdown();
            }
        });
    </script>
</body>
</html>
