@extends('admin.layout')

@section('title', 'Manajemen Simulasi Flowchart')
@section('page-title', 'Manajemen Simulasi')
@section('page-subtitle', 'Kelola tantangan simulasi interaktif logika flowchart, slot blok simbol, dan kunci kaidah alur.')

@section('header-actions')
    <a href="{{ route('admin.simulasi.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah Kasus Simulasi</span>
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
            
            <!-- Layout Type Filter Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.simulasi.index', array_merge(request()->except('layout_type', 'page'), [])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ empty($currentType) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $typeCounts['all'] }})
                </a>
                <a href="{{ route('admin.simulasi.index', array_merge(request()->except('layout_type', 'page'), ['layout_type' => 'branching'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentType === 'branching' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                    Percabangan ({{ $typeCounts['branching'] }})
                </a>
                <a href="{{ route('admin.simulasi.index', array_merge(request()->except('layout_type', 'page'), ['layout_type' => 'sequential'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentType === 'sequential' ? 'bg-sky-600 text-white' : 'bg-sky-50 text-sky-700 hover:bg-sky-100' }}">
                    Sekuensial ({{ $typeCounts['sequential'] }})
                </a>
                <a href="{{ route('admin.simulasi.index', array_merge(request()->except('layout_type', 'page'), ['layout_type' => 'loop'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentType === 'loop' ? 'bg-purple-600 text-white' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                    Perulangan ({{ $typeCounts['loop'] }})
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.simulasi.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-80">
                @if($currentType)
                    <input type="hidden" name="layout_type" value="{{ $currentType }}">
                @endif
                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           value="{{ $currentSearch }}"
                           placeholder="Cari level, judul, teks slot..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                    Cari
                </button>
                @if($currentSearch)
                    <a href="{{ route('admin.simulasi.index', ['layout_type' => $currentType]) }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-600 text-xs font-semibold" title="Reset">
                        Reset
                    </a>
                @endif
            </form>

        </div>
    </div>

    <!-- Simulations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($simulations as $sim)
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs hover:shadow-md transition flex flex-col justify-between">
                <div class="space-y-3">
                    <!-- Badges -->
                    <div class="flex items-center justify-between">
                        <span class="w-8 h-8 rounded-xl bg-amber-400 text-slate-950 font-black text-xs flex items-center justify-center shrink-0 shadow-xs">
                            {{ $sim->level_number }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            @if($sim->layout_type === 'branching')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 uppercase">Percabangan</span>
                            @elseif($sim->layout_type === 'sequential')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-100 text-sky-800 uppercase">Sekuensial</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800 uppercase">Perulangan</span>
                            @endif

                            @if($sim->is_active)
                                <span class="w-2 h-2 rounded-full bg-emerald-500" title="Aktif"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-slate-300" title="Nonaktif"></span>
                            @endif
                        </div>
                    </div>

                    <!-- Title -->
                    <h3 class="font-extrabold text-sm text-slate-900 line-clamp-1">
                        {{ $sim->title }}
                    </h3>

                    <!-- Description -->
                    <div class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                        {!! strip_tags($sim->desc) !!}
                    </div>

                    <!-- Key Configuration info -->
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5 text-[11px]">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-semibold">Jawaban Simbol:</span>
                            <span class="font-bold text-slate-800 uppercase bg-white px-2 py-0.5 rounded border border-slate-200">
                                {{ $sim->expected_symbol }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-semibold">Teks Slot:</span>
                            <span class="font-mono font-bold text-amber-800 truncate max-w-[140px]">
                                {{ $sim->slot_text_inside }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 mt-4 border-t border-slate-100">
                    <a href="{{ route('admin.simulasi.show', $sim->id) }}" class="text-xs font-bold text-slate-600 hover:text-slate-950">
                        Pratinjau Alur &rarr;
                    </a>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.simulasi.edit', $sim->id) }}" class="p-1.5 text-slate-400 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition" title="Edit Simulasi">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <button type="button" onclick="confirmDeleteSimulasi({{ $sim->id }}, '{{ $sim->level_number }}', '{{ addslashes($sim->title) }}')" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus Simulasi">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 font-medium bg-white rounded-3xl border border-slate-200/80">
                Tidak ada kasus simulasi ditemukan.
            </div>
        @endforelse
    </div>

    @if($simulations->hasPages())
        <div class="bg-white rounded-3xl p-4 border border-slate-200/80 shadow-2xs">
            {{ $simulations->links() }}
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
            <h3 class="text-base font-extrabold text-slate-900">Hapus Kasus Simulasi?</h3>
            <p class="text-xs text-slate-500 mt-1">Anda yakin ingin menghapus kasus <strong id="delete-sim-title" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
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
    function confirmDeleteSimulasi(id, level, title) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-form');
        const titleText = document.getElementById('delete-sim-title');
        form.action = `/admin/simulasi/${id}`;
        titleText.innerText = 'Level ' + level + ' (' + title + ')';
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
