@extends('admin.layout')

@section('title', 'Log Aktifitas')
@section('page-title', 'Log Aktifitas Sistem')
@section('page-subtitle', 'Catatan audit interaksi pengguna, pengerjaan kuis, pendaftaran akun, dan tindakan administratif.')

@section('header-actions')
    <!-- Purge Logs Form Button -->
    <form action="{{ route('admin.activity-logs.purge') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membersihkan log aktivitas yang lebih lama dari 30 hari?');">
        @csrf
        <input type="hidden" name="days" value="30">
        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border border-slate-200 bg-white hover:bg-rose-50 text-slate-700 hover:text-rose-700 font-bold text-xs transition cursor-pointer">
            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <span>Bersihkan Log &gt;30 Hari</span>
        </button>
    </form>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs space-y-4">
        <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Action Type Dropdown Filter -->
            <div class="flex items-center gap-3">
                <label for="action" class="text-xs font-bold text-slate-500 whitespace-nowrap">Filter Aksi:</label>
                <select name="action" id="action" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="">Semua Jenis Aksi ({{ $logs->total() }})</option>
                    @foreach($actionTypes as $key => $label)
                        <option value="{{ $key }}" {{ $currentAction === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Form -->
            <div class="flex items-center gap-2 w-full md:w-80">
                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           value="{{ $currentSearch }}"
                           placeholder="Cari keterangan, IP, atau user..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                    Cari
                </button>
                @if($currentSearch || $currentAction)
                    <a href="{{ route('admin.activity-logs.index') }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-600 text-xs font-semibold" title="Reset">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-bold text-[10px]">
                    <tr>
                        <th class="py-3.5 px-6">Waktu</th>
                        <th class="py-3.5 px-6">Pengguna</th>
                        <th class="py-3.5 px-6">Jenis Aksi</th>
                        <th class="py-3.5 px-6">Deskripsi Aktivitas</th>
                        <th class="py-3.5 px-6">Alamat IP & Perangkat</th>
                        <th class="py-3.5 px-6 text-right">Data Metadata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Timestamp -->
                            <td class="py-4 px-6 text-slate-600 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] text-slate-400">{{ $log->created_at->format('H:i:s') }} ({{ $log->created_at->diffForHumans() }})</div>
                            </td>

                            <!-- User -->
                            <td class="py-4 px-6">
                                @if($log->user)
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.users.show', $log->user->id) }}" class="font-bold text-slate-900 hover:text-amber-600 block">
                                                {{ $log->user->name }}
                                            </a>
                                            <span class="text-[10px] text-slate-400">{{ $log->user->role_label }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">User Tamu / Dihapus</span>
                                @endif
                            </td>

                            <!-- Action Badge -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase
                                    {{ str_contains($log->action, 'completed') ? 'bg-emerald-100 text-emerald-800' :
                                       (str_contains($log->action, 'login') ? 'bg-sky-100 text-sky-800' :
                                       (str_contains($log->action, 'logout') ? 'bg-slate-100 text-slate-700' :
                                       (str_contains($log->action, 'deleted') ? 'bg-rose-100 text-rose-800' :
                                       (str_contains($log->action, 'created') ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800')))) }}">
                                    {{ $actionTypes[$log->action] ?? $log->action }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="py-4 px-6 max-w-sm">
                                <span class="font-medium text-slate-800 leading-relaxed block">
                                    {{ $log->description }}
                                </span>
                            </td>

                            <!-- IP & User Agent -->
                            <td class="py-4 px-6 text-slate-500 text-[11px] max-w-xs truncate">
                                <div>{{ $log->ip_address ?: '127.0.0.1' }}</div>
                                @if($log->user_agent)
                                    <div class="text-[10px] text-slate-400 truncate" title="{{ $log->user_agent }}">
                                        {{ Str::limit($log->user_agent, 45) }}
                                    </div>
                                @endif
                            </td>

                            <!-- Properties Metadata -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @if(!empty($log->properties))
                                    <details class="cursor-pointer text-left inline-block">
                                        <summary class="text-[10px] font-bold text-amber-600 hover:text-amber-800">
                                            Lihat JSON
                                        </summary>
                                        <div class="mt-2 p-2.5 rounded-xl bg-slate-900 text-slate-200 text-[10px] font-mono max-w-xs overflow-x-auto shadow-lg">
                                            <pre>{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    </details>
                                @else
                                    <span class="text-slate-300 text-[11px]">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-slate-400">
                                Belum ada catatan aktivitas yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

