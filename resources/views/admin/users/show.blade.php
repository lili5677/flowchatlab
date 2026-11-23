@extends('admin.layout')

@section('title', 'Detail Siswa: ' . $user->name)
@section('page-title', 'Detail Pengguna')
@section('page-subtitle', 'Informasi lengkap akun, capaian belajar, dan riwayat pengerjaan evaluasi.')

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs transition">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            <span>Edit User</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition">
            &larr; Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Profile Overview Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-black text-2xl shrink-0
                    {{ $user->isAdmin() ? 'bg-indigo-100 text-indigo-700' : ($user->isGuru() ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-700') }}">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-black text-slate-900">{{ $user->name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                            {{ $user->isAdmin() ? 'bg-indigo-100 text-indigo-700' : ($user->isGuru() ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-700') }}">
                            {{ $user->role_label }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 mt-1">
                        {{ $user->email }} &bull; Terdaftar pada {{ $user->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <!-- Learning Quick Stats -->
            <div class="flex items-center gap-4 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                <div class="text-center px-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Poin</span>
                    <span class="text-base font-black text-amber-600">{{ number_format($user->points) }}</span>
                </div>
                <div class="w-px h-8 bg-slate-200"></div>
                <div class="text-center px-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Skor Praktik</span>
                    <span class="text-base font-black {{ $user->skor_praktik >= 75 ? 'text-emerald-600' : ($user->skor_praktik ? 'text-rose-500' : 'text-slate-400') }}">
                        {{ $user->skor_praktik !== null ? $user->skor_praktik : '-' }}
                    </span>
                </div>
                <div class="w-px h-8 bg-slate-200"></div>
                <div class="text-center px-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Percobaan Quiz</span>
                    <span class="text-base font-black text-slate-800">{{ $quizAttempts->count() }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 text-xs">
            <div>
                <span class="text-slate-400 block font-semibold">Nomor Induk Siswa (NIS)</span>
                <span class="font-bold text-slate-800 text-sm">{{ $user->display_nis }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">Kelas / Rombel</span>
                <span class="font-bold text-slate-800 text-sm">{{ $user->display_kelas }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">Asal Sekolah</span>
                <span class="font-bold text-slate-800 text-sm">{{ $user->school ?: 'Belum diisi' }}</span>
            </div>
        </div>
    </div>

    <!-- Riwayat Quiz Pengetahuan -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-extrabold text-sm text-slate-900">Riwayat Quiz Pengetahuan (Standar ANSI/ISO)</h3>
                <p class="text-xs text-slate-500">Daftar semua percobaan kuis 20 soal yang dilakukan oleh siswa ini.</p>
            </div>
            <span class="text-xs font-bold text-slate-500">{{ $quizAttempts->count() }} Percobaan</span>
        </div>

        @if($quizAttempts->isEmpty())
            <div class="py-8 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl">
                Siswa ini belum pernah memulai Quiz Pengetahuan.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-bold text-[10px]">
                        <tr>
                            <th class="py-3 px-4">Attempt #</th>
                            <th class="py-3 px-4">Tanggal Mulai</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Durasi</th>
                            <th class="py-3 px-4">Benar / Soal</th>
                            <th class="py-3 px-4">Skor</th>
                            <th class="py-3 px-4">Predikat</th>
                            <th class="py-3 px-4 text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($quizAttempts as $attempt)
                            <tr class="hover:bg-slate-50/70">
                                <td class="py-3.5 px-4 font-bold text-slate-900">#{{ $attempt->id }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $attempt->started_at->format('d M Y, H:i') }}</td>
                                <td class="py-3.5 px-4">
                                    @if($attempt->status === 'completed')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Selesai</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Sedang Dikerjakan</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ gmdate('i:s', $attempt->duration_seconds) }} menit
                                </td>
                                <td class="py-3.5 px-4 text-slate-800 font-semibold">
                                    {{ $attempt->correct_count }} / {{ $attempt->total_questions }}
                                </td>
                                <td class="py-3.5 px-4 font-black text-base {{ $attempt->is_passed ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $attempt->score }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-xs {{ $attempt->is_passed ? 'text-emerald-700' : 'text-rose-600' }}">
                                        {{ $attempt->predicate ?: ($attempt->is_passed ? 'Lulus' : 'Remedial') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('admin.progress.quiz-detail', $attempt->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 hover:text-amber-800">
                                        <span>Periksa Jawaban</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Riwayat Aktivitas Akun Ini -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs">
        <h3 class="font-extrabold text-sm text-slate-900 mb-4">Aktivitas Terkini Akun Ini</h3>

        @if($activityLogs->isEmpty())
            <div class="py-6 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl">
                Belum ada aktivitas yang tercatat untuk akun ini.
            </div>
        @else
            <div class="space-y-2.5">
                @foreach($activityLogs as $log)
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span class="font-medium text-slate-800">{{ $log->description }}</span>
                        </div>
                        <div class="text-[10px] text-slate-400 shrink-0">
                            {{ $log->created_at->format('d M Y, H:i') }} ({{ $log->created_at->diffForHumans() }})
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection

