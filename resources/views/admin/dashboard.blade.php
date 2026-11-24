@extends('admin.layout')

@section('title', 'Dashboard & Statistik')
@section('page-title', 'Dashboard Administrator')
@section('page-subtitle', 'Statistik pembelajaran algoritma flowchart, performa siswa, dan ringkasan aktivitas.')

@section('header-actions')
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah User Baru</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- ================= METRIC CARDS ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Siswa -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Siswa</span>
                <span class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['totalSiswa'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Siswa Terdaftar</span>
            </div>
            <div class="mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Total Guru: <strong class="text-slate-800">{{ $stats['totalGuru'] }}</strong></span>
                <span>Admin: <strong class="text-slate-800">{{ $stats['totalAdmin'] }}</strong></span>
            </div>
        </div>

        <!-- Quiz Pengetahuan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Quiz Pengetahuan</span>
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['completedAttempts'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Selesai</span>
            </div>
            <div class="mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500">Kelulusan:</span>
                <span class="font-extrabold {{ $stats['passingRate'] >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $stats['passingRate'] }}% ({{ $stats['passedAttempts'] }} lulus)</span>
            </div>
        </div>

        <!-- Rata-rata Skor Pengetahuan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata Pengetahuan</span>
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['avgScore'] }}</span>
                <span class="text-xs font-bold text-slate-400">/ 100</span>
            </div>
            <div class="mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Passing Grade: <strong class="text-slate-800">75</strong></span>
                <span class="font-bold {{ $stats['avgScore'] >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $stats['avgScore'] >= 75 ? 'Tuntas Kelas' : 'Perlu Bimbingan' }}
                </span>
            </div>
        </div>

        <!-- Quiz Keterampilan Praktik -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Praktik Keterampilan</span>
                <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['praktikCount'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Siswa Tuntas</span>
            </div>
            <div class="mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Rata-rata: <strong class="text-slate-800">{{ $stats['avgPraktikScore'] }}</strong></span>
                <span>Tertinggi: <strong class="text-purple-600">{{ $stats['maxPraktikScore'] }}</strong></span>
            </div>
        </div>

    </div>

    <!-- ================= KELOLA KONTEN & BANK SOAL ================= -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Manajemen Bank Soal & Simulasi</h2>
                <p class="text-xs text-slate-500">Kelola soal kuis teori, kasus praktik keterampilan, dan tantangan simulasi flowchart.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Card Quiz Pengetahuan -->
            <a href="{{ route('admin.quiz-pengetahuan.index') }}" class="group p-4 rounded-2xl border border-slate-200/90 hover:border-amber-400 bg-slate-50/50 hover:bg-white hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">Teori</span>
                    </div>
                    <div class="text-sm font-extrabold text-slate-900 group-hover:text-amber-600 transition">Quiz Pengetahuan</div>
                    <div class="text-xs text-slate-500 mt-1">Soal pilihan ganda teori simbol ANSI/ISO & logika.</div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                    <span class="font-extrabold text-slate-900">{{ $stats['totalQuestions'] }} Soal</span>
                    <span class="font-bold text-amber-600 group-hover:translate-x-0.5 transition">Kelola &rarr;</span>
                </div>
            </a>

            <!-- Card Quiz Keterampilan -->
            <a href="{{ route('admin.quiz-keterampilan.index') }}" class="group p-4 rounded-2xl border border-slate-200/90 hover:border-emerald-400 bg-slate-50/50 hover:bg-white hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </span>
                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Praktik</span>
                    </div>
                    <div class="text-sm font-extrabold text-slate-900 group-hover:text-emerald-600 transition">Quiz Keterampilan</div>
                    <div class="text-xs text-slate-500 mt-1">Studi kasus penyusunan blok urutan flowchart.</div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                    <span class="font-extrabold text-slate-900">{{ $stats['totalKeterampilanTasks'] }} Kasus</span>
                    <span class="font-bold text-emerald-600 group-hover:translate-x-0.5 transition">Kelola &rarr;</span>
                </div>
            </a>

            <!-- Card Simulasi Flowchart -->
            <a href="{{ route('admin.simulasi.index') }}" class="group p-4 rounded-2xl border border-slate-200/90 hover:border-sky-400 bg-slate-50/50 hover:bg-white hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-sky-100 text-sky-800">Interaktif</span>
                    </div>
                    <div class="text-sm font-extrabold text-slate-900 group-hover:text-sky-600 transition">Simulasi Flowchart</div>
                    <div class="text-xs text-slate-500 mt-1">Level tantangan alur visual, slot, dan animasi.</div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                    <span class="font-extrabold text-slate-900">{{ $stats['totalSimulations'] }} Level</span>
                    <span class="font-bold text-sky-600 group-hover:translate-x-0.5 transition">Kelola &rarr;</span>
                </div>
            </a>
        </div>
    </div>
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Distribusi Hasil Evaluasi Quiz Pengetahuan</h2>
                <p class="text-xs text-slate-500">Proporsi perolehan predikat kompetensi siswa dari {{ $stats['completedAttempts'] }} pengerjaan selesai.</p>
            </div>
            <a href="{{ route('admin.progress.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 inline-flex items-center gap-1">
                <span>Lihat Rekap Lengkap Siswa</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Sangat Baik -->
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/60 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-extrabold text-emerald-900 uppercase tracking-wider">Sangat Baik (90 - 100)</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-950">{{ $stats['predicates']['sangat_baik'] }} <span class="text-xs font-semibold text-emerald-700">Attempt</span></div>
                </div>
                <div class="text-right">
                    <span class="text-lg font-extrabold text-emerald-700">
                        {{ $stats['completedAttempts'] > 0 ? round(($stats['predicates']['sangat_baik'] / $stats['completedAttempts']) * 100) : 0 }}%
                    </span>
                </div>
            </div>

            <!-- Baik / Kompeten -->
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/60 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <span class="text-xs font-extrabold text-amber-900 uppercase tracking-wider">Baik (75 - 89)</span>
                    </div>
                    <div class="text-2xl font-black text-amber-950">{{ $stats['predicates']['baik'] }} <span class="text-xs font-semibold text-amber-700">Attempt</span></div>
                </div>
                <div class="text-right">
                    <span class="text-lg font-extrabold text-amber-700">
                        {{ $stats['completedAttempts'] > 0 ? round(($stats['predicates']['baik'] / $stats['completedAttempts']) * 100) : 0 }}%
                    </span>
                </div>
            </div>

            <!-- Perlu Bimbingan / Remedial -->
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/60 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="text-xs font-extrabold text-rose-900 uppercase tracking-wider">Remedial (&lt; 75)</span>
                    </div>
                    <div class="text-2xl font-black text-rose-950">{{ $stats['predicates']['remedial'] }} <span class="text-xs font-semibold text-rose-700">Attempt</span></div>
                </div>
                <div class="text-right">
                    <span class="text-lg font-extrabold text-rose-700">
                        {{ $stats['completedAttempts'] > 0 ? round(($stats['predicates']['remedial'] / $stats['completedAttempts']) * 100) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= 2-COLUMN SECTION: TOP STUDENTS & ACTIVITY LOG ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Left: Siswa Teratas (Leaderboard) -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">🏆</span>
                    <div>
                        <h3 class="font-extrabold text-sm text-slate-900">Siswa Berprestasi Teratas</h3>
                        <p class="text-[11px] text-slate-500">Peringkat poin akumulasi belajar & skor praktik</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">
                    Semua Siswa &rarr;
                </a>
            </div>

            @if($topStudents->isEmpty())
                <div class="py-8 text-center text-xs text-slate-400">Belum ada data siswa terdaftar.</div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($topStudents as $idx => $student)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $idx === 0 ? 'bg-amber-100 text-amber-800' : ($idx === 1 ? 'bg-slate-200 text-slate-700' : 'bg-slate-100 text-slate-500') }}">
                                    {{ $idx + 1 }}
                                </span>
                                <div class="truncate">
                                    <a href="{{ route('admin.users.show', $student->id) }}" class="text-xs font-extrabold text-slate-900 hover:text-amber-600 truncate block">
                                        {{ $student->name }}
                                    </a>
                                    <div class="text-[10px] text-slate-400 truncate">
                                        NIS: {{ $student->display_nis }} &bull; {{ $student->display_kelas }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-xs font-black text-amber-600">{{ number_format($student->points) }} Pts</div>
                                <div class="text-[10px] font-semibold text-slate-500">
                                    Praktik: <strong class="{{ $student->skor_praktik >= 75 ? 'text-emerald-600' : 'text-slate-700' }}">{{ $student->skor_praktik ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Log Aktifitas Terbaru -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">📋</span>
                    <div>
                        <h3 class="font-extrabold text-sm text-slate-900">Aktivitas Terkini Sistem</h3>
                        <p class="text-[11px] text-slate-500">Audit log interaksi user secara real-time</p>
                    </div>
                </div>
                <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($recentLogs->isEmpty())
                <div class="py-8 text-center text-xs text-slate-400">Belum ada catatan log aktivitas.</div>
            @else
                <div class="space-y-3">
                    @foreach($recentLogs as $log)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-3 text-xs">
                            <span class="w-7 h-7 rounded-lg shrink-0 flex items-center justify-center text-xs
                                {{ str_contains($log->action, 'completed') ? 'bg-emerald-100 text-emerald-700' :
                                   (str_contains($log->action, 'login') ? 'bg-sky-100 text-sky-700' :
                                   (str_contains($log->action, 'deleted') ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800')) }}">
                                @if(str_contains($log->action, 'login')) 🔑
                                @elseif(str_contains($log->action, 'quiz') || str_contains($log->action, 'keterampilan')) 📝
                                @elseif(str_contains($log->action, 'user')) 👤
                                @else 📌
                                @endif
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-slate-800 leading-snug break-words">
                                    {{ $log->description }}
                                </p>
                                <div class="mt-1 flex items-center gap-2 text-[10px] text-slate-400">
                                    <span>{{ $log->user ? $log->user->name : 'Sistem' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $log->created_at->diffForHumans() }}</span>
                                    @if($log->ip_address)
                                        <span>&bull;</span>
                                        <span>IP: {{ $log->ip_address }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
@endsection

