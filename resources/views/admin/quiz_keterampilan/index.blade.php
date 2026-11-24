@extends('admin.layout')

@section('title', 'Manajemen Quiz Keterampilan')
@section('page-title', 'Manajemen Quiz Keterampilan')
@section('page-subtitle', 'Kelola bank studi kasus penyusunan alur algoritma flowchart dan urutan simbol praktikum.')

@section('header-actions')
    <a href="{{ route('admin.quiz-keterampilan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah Tugas Keterampilan</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Flash Message -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
        </div>
    @endif

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Level Filter Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.quiz-keterampilan.index', array_merge(request()->except('level', 'page'), [])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ empty($currentLevel) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $levelCounts['all'] }})
                </a>
                <a href="{{ route('admin.quiz-keterampilan.index', array_merge(request()->except('level', 'page'), ['level' => '1'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentLevel === '1' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Level 1 - Mudah ({{ $levelCounts['1'] }})
                </a>
                <a href="{{ route('admin.quiz-keterampilan.index', array_merge(request()->except('level', 'page'), ['level' => '2'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentLevel === '2' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                    Level 2 - Sedang ({{ $levelCounts['2'] }})
                </a>
                <a href="{{ route('admin.quiz-keterampilan.index', array_merge(request()->except('level', 'page'), ['level' => '3'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentLevel === '3' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                    Level 3 - Sulit ({{ $levelCounts['3'] }})
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.quiz-keterampilan.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-80">
                @if($currentLevel)
                    <input type="hidden" name="level" value="{{ $currentLevel }}">
                @endif
                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           value="{{ $currentSearch }}"
                           placeholder="Cari kode (KS-1-1), judul, soal..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                    Cari
                </button>
                @if($currentSearch)
                    <a href="{{ route('admin.quiz-keterampilan.index', ['level' => $currentLevel]) }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-600 text-xs font-semibold" title="Reset Pencarian">
                        Reset
                    </a>
                @endif
            </form>

        </div>
    </div>

    <!-- Tasks Grid / Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($tasks as $task)
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs hover:shadow-md transition flex flex-col justify-between">
                <div class="space-y-3">
                    <!-- Badges -->
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-xs font-extrabold px-2.5 py-1 rounded-lg bg-slate-900 text-white">
                            {{ $task->kode }}
                        </span>
                        @if($task->level == 1)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase">Mudah</span>
                        @elseif($task->level == 2)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 uppercase">Sedang</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 uppercase">Sulit</span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h3 class="font-extrabold text-sm text-slate-900 line-clamp-1">
                        {{ $task->judul }}
                    </h3>

                    <!-- Question preview -->
                    <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                        {{ $task->soal }}
                    </p>

                    <!-- Steps preview pills -->
                    <div class="pt-2 border-t border-slate-100">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                            {{ count($task->langkah ?? []) }} Langkah Flowchart
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($task->langkah ?? [], 0, 5) as $st)
                                <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">
                                    {{ $st['simbol'] ?? '?' }}
                                </span>
                            @endforeach
                            @if(count($task->langkah ?? []) > 5)
                                <span class="text-[9px] font-bold text-slate-400">+{{ count($task->langkah) - 5 }} lainnya</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="flex items-center justify-between pt-4 mt-4 border-t border-slate-100">
                    <a href="{{ route('admin.quiz-keterampilan.show', $task->id) }}" class="text-xs font-bold text-slate-600 hover:text-slate-950">
                        Lihat Alur &rarr;
                    </a>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.quiz-keterampilan.edit', $task->id) }}" class="p-1.5 text-slate-400 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition" title="Edit Tugas">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <button type="button" onclick="confirmDeleteTask({{ $task->id }}, '{{ $task->kode }}')" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus Tugas">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 font-medium bg-white rounded-3xl border border-slate-200/80">
                Tidak ada tugas keterampilan ditemukan untuk kriteria filter ini.
            </div>
        @endforelse
    </div>

    @if($tasks->hasPages())
        <div class="bg-white rounded-3xl p-4 border border-slate-200/80 shadow-2xs">
            {{ $tasks->links() }}
        </div>
    @endif

</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center space-y-4 shadow-xl">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-extrabold text-slate-900">Hapus Tugas Keterampilan?</h3>
            <p class="text-xs text-slate-500 mt-1">Anda yakin ingin menghapus tugas <strong id="delete-task-code" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <form id="delete-form" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmDeleteTask(id, code) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-form');
        const codeText = document.getElementById('delete-task-code');
        form.action = `/admin/quiz-keterampilan/${id}`;
        codeText.innerText = '[' + code + ']';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
