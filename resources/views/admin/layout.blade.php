<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - Flowchart Lab SMK</title>

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
<body class="h-screen w-screen overflow-hidden bg-slate-50 text-slate-900 antialiased selection:bg-amber-200 selection:text-amber-900 flex flex-col md:flex-row" style="height: 100vh; max-height: 100vh; overflow: hidden;">

    <!-- ================= SIDEBAR (DESKTOP) ================= -->
    <aside class="hidden md:flex flex-col w-64 lg:w-72 shrink-0 bg-slate-900 text-white border-r border-slate-800 sticky top-0 h-screen z-30 select-none" style="width: 18rem; min-width: 18rem; max-width: 18rem; height: 100vh; max-height: 100vh; flex-shrink: 0;">
        <!-- Logo & Brand -->
        <div class="h-20 px-6 flex items-center gap-3 border-b border-slate-800/80 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 group-hover:scale-105 transition-transform">
                <div class="flex flex-col">
                    <span class="font-extrabold text-base tracking-tight text-white leading-tight">Flowchart Lab</span>
                    <span class="text-[10px] font-bold tracking-wider text-amber-400 uppercase">Panel Administrator</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <div class="px-3 pb-2 text-[10px] font-black uppercase tracking-wider text-slate-400">Menu Utama</div>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard & Statistik</span>
            </a>

            <!-- Manajemen User -->
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.users.*') ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Manajemen User</span>
            </a>

            <!-- Progress Pengerjaan Siswa -->
            <a href="{{ route('admin.progress.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.progress.*') ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Progress Siswa</span>
            </a>

            <div class="pt-4 px-3 pb-2 text-[10px] font-black uppercase tracking-wider text-slate-400">Konten & Soal</div>

            <!-- Manajemen Quiz Pengetahuan -->
            <a href="{{ route('admin.quiz-pengetahuan.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.quiz-pengetahuan.*') ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Quiz Pengetahuan</span>
            </a>

            <!-- Manajemen Quiz Keterampilan -->
            <a href="{{ route('admin.quiz-keterampilan.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.quiz-keterampilan.*') ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <span>Quiz Keterampilan</span>
            </a>

            <!-- Manajemen Simulasi -->
            <a href="{{ route('admin.simulasi.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.simulasi.*') ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Simulasi Flowchart</span>
            </a>

            <div class="pt-4 px-3 pb-2 text-[10px] font-black uppercase tracking-wider text-slate-400">Sistem</div>

            <!-- Log Aktifitas -->
            <a href="{{ route('admin.activity-logs.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.activity-logs.*') ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Log Aktifitas</span>
            </a>

            <div class="pt-6 px-3 pb-2 text-[10px] font-black uppercase tracking-wider text-slate-400">Navigasi Luar</div>

            <!-- Kembali ke Lab -->
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800/70 transition">
                <svg class="w-4 h-4 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Website Lab</span>
            </a>
        </nav>

        <!-- Admin Profile & Logout in Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/50 shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-amber-400 text-slate-950 font-black flex items-center justify-center text-xs shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] font-medium text-slate-400 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" title="Keluar" class="p-1.5 text-slate-400 hover:text-rose-400 rounded-lg hover:bg-slate-800 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ================= MOBILE HEADER & NAV ================= -->
    <div class="md:hidden bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-50">
        <div class="flex items-center justify-between px-4 h-16">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-7 h-7">
                <span class="font-extrabold text-sm text-white">Flowchart Lab <span class="text-amber-400 text-xs">Admin</span></span>
            </a>
            <button type="button" onclick="toggleMobileAdminNav()" class="p-2 text-slate-300 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        <div id="mobile-admin-menu" class="hidden px-4 pb-4 space-y-1 bg-slate-900 border-t border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-slate-950' : 'text-slate-200' }}">Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('admin.users.*') ? 'bg-amber-500 text-slate-950' : 'text-slate-200' }}">Manajemen User</a>
            <a href="{{ route('admin.progress.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('admin.progress.*') ? 'bg-amber-500 text-slate-950' : 'text-slate-200' }}">Progress Siswa</a>
            <a href="{{ route('admin.quiz-pengetahuan.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('admin.quiz-pengetahuan.*') ? 'bg-amber-500 text-slate-950' : 'text-slate-200' }}">Quiz Pengetahuan</a>
            <a href="{{ route('admin.quiz-keterampilan.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('admin.quiz-keterampilan.*') ? 'bg-amber-500 text-slate-950' : 'text-slate-200' }}">Quiz Keterampilan</a>
            <a href="{{ route('admin.simulasi.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('admin.simulasi.*') ? 'bg-amber-500 text-slate-950' : 'text-slate-200' }}">Simulasi Flowchart</a>
            <a href="{{ route('admin.activity-logs.index') }}" class="block px-3 py-2 rounded-lg text-xs font-bold {{ request()->routeIs('admin.activity-logs.*') ? 'bg-amber-500 text-slate-950' : 'text-slate-200' }}">Log Aktifitas</a>
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-xs font-semibold text-amber-400">Kembali ke Website Lab</a>
            <form action="{{ route('logout') }}" method="POST" class="pt-2">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold text-rose-400 bg-rose-950/30">Keluar (Logout)</button>
            </form>
        </div>
    </div>

    <!-- ================= MAIN CONTENT AREA ================= -->
    <div class="flex-1 flex flex-col h-full min-w-0 overflow-y-auto bg-slate-50" style="height: 100vh; max-height: 100vh; min-width: 0; flex: 1; overflow-y: auto;">
        <!-- Top App Bar -->
        <header class="h-20 bg-white border-b border-slate-200/80 px-4 sm:px-8 flex items-center justify-between shrink-0">
            <div>
                <h1 class="text-lg sm:text-xl font-extrabold text-slate-900">@yield('page-title', 'Panel Administrator')</h1>
                <p class="text-xs text-slate-500 font-medium">@yield('page-subtitle', 'Kelola pengguna, pantau progres pembelajaran, dan audit log sistem.')</p>
            </div>
            <div class="flex items-center gap-3">
                @yield('header-actions')
                <div class="hidden sm:flex items-center gap-2 pl-3 border-l border-slate-200">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                        Role: Administrator
                    </span>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 sm:px-8 pt-6">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 mb-4 animate-fade-in">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <div class="text-xs sm:text-sm font-semibold">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center gap-3 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 mb-4 animate-fade-in">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <div class="text-xs sm:text-sm font-semibold">{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 mb-4 animate-fade-in">
                    <div class="font-bold text-xs mb-1">Terdapat kesalahan pada isian form:</div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Page Body -->
        <main class="flex-1 px-4 sm:px-8 pb-12">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleMobileAdminNav() {
            const menu = document.getElementById('mobile-admin-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }
    </script>
</body>
</html>

