@extends('admin.layout')

@section('title', 'Progress Pengerjaan Siswa')
@section('page-title', 'Progress Pengerjaan Siswa & Statistik')
@section('page-subtitle', 'Pantau kemajuan belajar siswa pada evaluasi Quiz Pengetahuan dan Quiz Keterampilan Praktik.')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Siswa -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Total Siswa</span>
            <div class="text-3xl font-black text-slate-900">{{ $stats['totalSiswa'] }}</div>
            <div class="text-[11px] text-slate-500 mt-2">Terdaftar di Flowchart Lab</div>
        </div>

        <!-- Pengetahuan Selesai -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Rata-rata Pengetahuan</span>
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-black text-slate-900">{{ $stats['avgPengetahuan'] }}</span>
                <span class="text-xs font-bold text-slate-400">/ 100</span>
            </div>
            <div class="text-[11px] font-semibold text-emerald-600 mt-2">
                {{ $stats['passedPengetahuan'] }} dari {{ $stats['totalCompletedPengetahuan'] }} percobaan tuntas (&ge;75)
            </div>
        </div>

        <!-- Praktik Selesai -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Siswa Tuntas Praktik</span>
            <div class="text-3xl font-black text-purple-700">{{ $stats['totalPraktikCompleted'] }} <span class="text-xs font-bold text-slate-400">/ {{ $stats['totalSiswa'] }}</span></div>
            <div class="text-[11px] text-slate-500 mt-2">
                Partisipasi: <strong>{{ $stats['totalSiswa'] > 0 ? round(($stats['totalPraktikCompleted'] / $stats['totalSiswa']) * 100) : 0 }}%</strong>
            </div>
        </div>

        <!-- Rata-rata Praktik -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Rata-rata Skor Praktik</span>
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-black text-slate-900">{{ $stats['avgPraktik'] }}</span>
                <span class="text-xs font-bold text-slate-400">/ 100</span>
            </div>
            <div class="text-[11px] text-slate-500 mt-2">
                Standar Kelulusan Praktik: <strong>75</strong>
            </div>
        </div>

    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Filter Options -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.progress.index', array_merge(request()->except('filter', 'page'), [])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ empty($currentFilter) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua Siswa
                </a>
                <a href="{{ route('admin.progress.index', array_merge(request()->except('filter', 'page'), ['filter' => 'pengetahuan_lulus'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentFilter === 'pengetahuan_lulus' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                    Lulus Pengetahuan (&ge;75)
                </a>
                <a href="{{ route('admin.progress.index', array_merge(request()->except('filter', 'page'), ['filter' => 'pengetahuan_remedial'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentFilter === 'pengetahuan_remedial' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-800 hover:bg-rose-100' }}">
                    Remedial Pengetahuan (&lt;75)
                </a>
                <a href="{{ route('admin.progress.index', array_merge(request()->except('filter', 'page'), ['filter' => 'praktik_selesai'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentFilter === 'praktik_selesai' ? 'bg-purple-600 text-white' : 'bg-purple-50 text-purple-800 hover:bg-purple-100' }}">
                    Praktik Selesai
                </a>
                <a href="{{ route('admin.progress.index', array_merge(request()->except('filter', 'page'), ['filter' => 'praktik_belum'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentFilter === 'praktik_belum' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Belum Praktik
                </a>
            </div>

            <!-- Search -->
            <form action="{{ route('admin.progress.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-80">
                @if($currentFilter)
                    <input type="hidden" name="filter" value="{{ $currentFilter }}">
                @endif
                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           value="{{ $currentSearch }}"
                           placeholder="Cari nama, NIS, kelas..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                    Cari
                </button>
            </form>

        </div>
    </div>

    <!-- Progress Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-bold text-[10px]">
                    <tr>
                        <th class="py-3.5 px-6">Siswa</th>
                        <th class="py-3.5 px-6">Kelas & Sekolah</th>
                        <th class="py-3.5 px-6">Quiz Pengetahuan (20 Soal)</th>
                        <th class="py-3.5 px-6">Quiz Praktik Flowchart</th>
                        <th class="py-3.5 px-6">Total Poin</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        @php
                            $attempts = $student->quizAttempts;
                            $completedAttempts = $attempts->where('status', 'completed');
                            $latestAttempt = $completedAttempts->first();
                            $maxScore = $completedAttempts->max('score');
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            
                            <!-- Siswa Identity -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-black text-xs shrink-0">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $student->id) }}" class="font-extrabold text-slate-900 hover:text-amber-600 block">
                                            {{ $student->name }}
                                        </a>
                                        <span class="text-[11px] text-slate-400">NIS: {{ $student->display_nis }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Class & School -->
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $student->display_kelas }}</div>
                                <div class="text-[11px] text-slate-400">{{ $student->school ?: 'SMK Vokasi' }}</div>
                            </td>

                            <!-- Quiz Pengetahuan Progress -->
                            <td class="py-4 px-6">
                                @if($completedAttempts->isNotEmpty())
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-black {{ $maxScore >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $maxScore }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-medium">(Tertinggi)</span>
                                            @if($maxScore >= 75)
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-emerald-100 text-emerald-800 uppercase">Lulus</span>
                                            @else
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-rose-100 text-rose-800 uppercase">Remedial</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-slate-500">
                                            {{ $completedAttempts->count() }}x percobaan &bull; Terakhir: {{ $latestAttempt ? $latestAttempt->score : '-' }}
                                        </div>
                                    </div>
                                @elseif($attempts->isNotEmpty())
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        Sedang Mengerjakan
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Belum Memulai</span>
                                @endif
                            </td>

                            <!-- Quiz Keterampilan Progress -->
                            <td class="py-4 px-6">
                                @if($student->skor_praktik !== null)
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-black {{ $student->skor_praktik >= 75 ? 'text-purple-700' : 'text-rose-600' }}">
                                                {{ $student->skor_praktik }}
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-400">/ 100</span>
                                            @if($student->skor_praktik >= 85)
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-purple-100 text-purple-800 uppercase">Sangat Terampil</span>
                                            @elseif($student->skor_praktik >= 75)
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-emerald-100 text-emerald-800 uppercase">Kompeten</span>
                                            @else
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-rose-100 text-rose-800 uppercase">Perlu Latihan</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">3 Tugas Studi Kasus</div>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Belum Mengerjakan</span>
                                @endif
                            </td>

                            <!-- Total Points -->
                            <td class="py-4 px-6 font-black text-amber-600 text-sm">
                                {{ number_format($student->points) }} Pts
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.users.show', $student->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition">
                                    <span>Lihat Detail</span>
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-slate-400">
                                Tidak ada data siswa yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

