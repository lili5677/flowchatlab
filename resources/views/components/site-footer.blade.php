{{--
    Site Footer — dark variant used on all main public pages.
    Usage: <x-site-footer />
--}}
<footer class="bg-[#111113] border-t border-zinc-800/80 text-slate-400">

    {{-- ── Top Grid ── --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">

            {{-- Brand Column --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Flowchart Lab"
                         class="w-9 h-9 object-contain">
                    <div>
                        <div class="text-white font-extrabold text-base tracking-tight leading-tight">Flowchart Lab</div>
                        <div class="text-[11px] text-amber-400 font-semibold uppercase tracking-widest">Algoritma Visual</div>
                    </div>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed mb-5">
                    Media pembelajaran interaktif logika algoritma dan flowchart untuk siswa SMA/SMK/MA sederajat, berbasis standar <span class="text-slate-400 font-medium">ANSI/ISO&nbsp;5807</span>.
                </p>
                <span class="inline-flex items-center gap-1.5 bg-amber-500/10 text-amber-400 text-[10px] font-bold px-3 py-1.5 rounded-full border border-amber-500/20 tracking-wide">
                    <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ANSI/ISO 5807 Standard
                </span>
            </div>

            {{-- Navigasi --}}
            <div>
                <div class="text-white font-semibold text-sm tracking-wide mb-4">Navigasi</div>
                <ul class="space-y-3 text-xs font-medium">
                    <li>
                        <a href="{{ route('home') }}"
                           class="text-slate-400 hover:text-white transition-colors duration-150">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('materi') }}"
                           class="text-slate-400 hover:text-white transition-colors duration-150">
                            Materi Flowchart
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('simulasi') }}"
                           class="text-slate-400 hover:text-white transition-colors duration-150">
                            Simulasi Interaktif
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Evaluasi --}}
            <div>
                <div class="text-white font-semibold text-sm tracking-wide mb-4">Evaluasi</div>
                <ul class="space-y-3 text-xs font-medium">
                    <li>
                        <a href="{{ route('quiz.index') }}"
                           class="text-slate-400 hover:text-white transition-colors duration-150">
                            Quiz Pengetahuan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('quiz.keterampilan') }}"
                           class="text-slate-400 hover:text-white transition-colors duration-150">
                            Quiz Keterampilan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('materi') }}"
                           class="text-slate-400 hover:text-white transition-colors duration-150">
                            Panduan Simbol
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Akun --}}
            <div>
                <div class="text-white font-semibold text-sm tracking-wide mb-4">Akun</div>
                <ul class="space-y-3 text-xs font-medium">
                    @auth
                        <li>
                            <a href="{{ route('profile.show') }}"
                               class="text-slate-400 hover:text-white transition-colors duration-150">
                                Detail Profil
                            </a>
                        </li>
                        @if(Auth::user()->isAdmin())
                            <li>
                                <a href="{{ route('admin.dashboard') }}"
                                   class="text-slate-400 hover:text-white transition-colors duration-150">
                                    Panel Admin
                                </a>
                            </li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="text-slate-400 hover:text-white transition-colors duration-150 text-xs font-medium">
                                    Keluar
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}"
                               class="text-slate-400 hover:text-white transition-colors duration-150">
                                Masuk
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}"
                               class="text-slate-400 hover:text-white transition-colors duration-150">
                                Daftar Akun
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

        </div>{{-- /grid --}}

        {{-- ── Bottom Bar ── --}}
        <div class="border-t border-zinc-800/80 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[11px] text-slate-500">
                &copy; {{ date('Y') }} <span class="text-slate-400 font-medium">Flowchart Lab</span>. Media Pembelajaran Logika Algoritma Interaktif.
            </p>
        </div>

    </div>{{-- /container --}}
</footer>
