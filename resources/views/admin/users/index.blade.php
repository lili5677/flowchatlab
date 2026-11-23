@extends('admin.layout')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Daftar semua pengguna terdaftar, pencarian, pengelolaan data, dan pengaturan hak akses.')

@section('header-actions')
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah Pengguna</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Role Filter Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), [])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ empty($currentRole) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $roleCounts['all'] }})
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'siswa'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentRole === 'siswa' ? 'bg-sky-600 text-white' : 'bg-sky-50 text-sky-700 hover:bg-sky-100' }}">
                    Siswa ({{ $roleCounts['siswa'] }})
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'guru'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentRole === 'guru' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                    Guru ({{ $roleCounts['guru'] }})
                </a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'admin'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentRole === 'admin' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                    Admin ({{ $roleCounts['admin'] }})
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-80">
                @if($currentRole)
                    <input type="hidden" name="role" value="{{ $currentRole }}">
                @endif
                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           value="{{ $currentSearch }}"
                           placeholder="Cari nama, email, NIS, sekolah..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                    Cari
                </button>
                @if($currentSearch)
                    <a href="{{ route('admin.users.index', ['role' => $currentRole]) }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-600 text-xs font-semibold" title="Reset Pencarian">
                        Reset
                    </a>
                @endif
            </form>

        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-bold text-[10px]">
                    <tr>
                        <th class="py-3.5 px-6">Identitas Pengguna</th>
                        <th class="py-3.5 px-6">Sekolah & Kelas</th>
                        <th class="py-3.5 px-6">Peran (Role)</th>
                        <th class="py-3.5 px-6">Performa Belajar</th>
                        <th class="py-3.5 px-6">Terdaftar</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- User Identity -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs shrink-0
                                        {{ $user->isAdmin() ? 'bg-indigo-100 text-indigo-700' : ($user->isGuru() ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-700') }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="font-extrabold text-slate-900 hover:text-amber-600 block">
                                            {{ $user->name }}
                                        </a>
                                        <div class="text-[11px] text-slate-500">{{ $user->email }}</div>
                                        @if($user->nis)
                                            <span class="text-[10px] text-slate-400">NIS: {{ $user->nis }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- School & Class -->
                            <td class="py-4 px-6">
                                <div class="font-medium text-slate-800">{{ $user->school ?: '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $user->kelas ?: '-' }}</div>
                            </td>

                            <!-- Role Badge -->
                            <td class="py-4 px-6">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        Admin
                                    </span>
                                @elseif($user->isGuru())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Guru SMK
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-sky-50 text-sky-700 border border-sky-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                        Siswa
                                    </span>
                                @endif
                            </td>

                            <!-- Learning Stats -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block">Poin</span>
                                        <span class="font-black text-amber-600">{{ number_format($user->points) }}</span>
                                    </div>
                                    <div class="border-l border-slate-100 pl-3">
                                        <span class="text-[10px] text-slate-400 block">Praktik</span>
                                        <span class="font-bold {{ $user->skor_praktik >= 75 ? 'text-emerald-600' : ($user->skor_praktik ? 'text-rose-500' : 'text-slate-400') }}">
                                            {{ $user->skor_praktik !== null ? $user->skor_praktik : 'Belum' }}
                                        </span>
                                    </div>
                                    <div class="border-l border-slate-100 pl-3">
                                        <span class="text-[10px] text-slate-400 block">Attempt Quiz</span>
                                        <span class="font-semibold text-slate-700">{{ $user->quiz_attempts_count }}x</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Registered At -->
                            <td class="py-4 px-6 text-slate-500 text-[11px]">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                       title="Lihat Detail Siswa & Progres"
                                       class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       title="Edit User"
                                       class="p-1.5 rounded-lg text-amber-600 hover:text-amber-800 hover:bg-amber-50 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}? Seluruh riwayat pengerjaan quiz juga akan terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    title="Hapus User"
                                                    class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-slate-400">
                                Tidak ada data pengguna yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

