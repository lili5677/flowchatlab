{{--
    Auth Footer — warm/amber variant for login & register pages.
    Usage: <x-auth-footer />
--}}
<footer class="w-full relative z-10 mt-6">
    <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] font-medium text-amber-950/70 text-center sm:text-left">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-5 h-5 object-contain opacity-70">
            <span>&copy; {{ date('Y') }} <strong class="font-bold text-amber-950/90">Flowchart Lab</strong> &mdash; Media Pembelajaran Logika Algoritma Interaktif</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}"
               class="hover:text-amber-950 transition-colors underline underline-offset-2">Beranda</a>
            <span class="opacity-40">&bull;</span>
            <a href="{{ route('materi') }}"
               class="hover:text-amber-950 transition-colors underline underline-offset-2">Materi</a>
            <span class="opacity-40">&bull;</span>
            <a href="{{ route('simulasi') }}"
               class="hover:text-amber-950 transition-colors underline underline-offset-2">Simulasi</a>
        </div>
    </div>
</footer>
