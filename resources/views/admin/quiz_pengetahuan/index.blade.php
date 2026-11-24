@extends('admin.layout')

@section('title', 'Manajemen Quiz Pengetahuan')
@section('page-title', 'Manajemen Quiz Pengetahuan')
@section('page-subtitle', 'Kelola bank soal teori pilihan ganda, kunci jawaban, dan pembahasan logika algoritma.')

@section('header-actions')
    <a href="{{ route('admin.quiz-pengetahuan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah Soal Quiz</span>
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
                <a href="{{ route('admin.quiz-pengetahuan.index', array_merge(request()->except('level', 'page'), [])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ empty($currentLevel) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $levelCounts['all'] }})
                </a>
                <a href="{{ route('admin.quiz-pengetahuan.index', array_merge(request()->except('level', 'page'), ['level' => 'Mudah'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentLevel === 'Mudah' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Mudah ({{ $levelCounts['Mudah'] }})
                </a>
                <a href="{{ route('admin.quiz-pengetahuan.index', array_merge(request()->except('level', 'page'), ['level' => 'Sedang'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentLevel === 'Sedang' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                    Sedang ({{ $levelCounts['Sedang'] }})
                </a>
                <a href="{{ route('admin.quiz-pengetahuan.index', array_merge(request()->except('level', 'page'), ['level' => 'Sulit'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $currentLevel === 'Sulit' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                    Sulit ({{ $levelCounts['Sulit'] }})
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.quiz-pengetahuan.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-80">
                @if($currentLevel)
                    <input type="hidden" name="level" value="{{ $currentLevel }}">
                @endif
                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           value="{{ $currentSearch }}"
                           placeholder="Cari teks soal, simbol, penjelasan..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                    Cari
                </button>
                @if($currentSearch)
                    <a href="{{ route('admin.quiz-pengetahuan.index', ['level' => $currentLevel]) }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-600 text-xs font-semibold" title="Reset Pencarian">
                        Reset
                    </a>
                @endif
            </form>

        </div>
    </div>

    <!-- Questions Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-bold text-[10px]">
                    <tr>
                        <th class="py-3.5 px-6">No</th>
                        <th class="py-3.5 px-6">Tingkat</th>
                        <th class="py-3.5 px-6">Pertanyaan</th>
                        <th class="py-3.5 px-6">Tipe Simbol</th>
                        <th class="py-3.5 px-6">Kunci & Poin</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($questions as $q)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-4 px-6 font-extrabold text-slate-900">
                                #{{ $q->question_number }}
                            </td>
                            <td class="py-4 px-6">
                                @if($q->level === 'Mudah')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider">Mudah</span>
                                @elseif($q->level === 'Sedang')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 uppercase tracking-wider">Sedang</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 uppercase tracking-wider">Sulit</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 max-w-md">
                                <div class="font-bold text-slate-900 line-clamp-2 leading-relaxed">
                                    {{ $q->question_text }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-1 truncate">
                                    {{ count($q->options ?? []) }} Pilihan Jawaban
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($q->symbol_type)
                                    <span class="font-mono text-[11px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $q->symbol_type }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-md bg-emerald-500 text-white font-black text-xs flex items-center justify-center">
                                        {{ $q->correct_key }}
                                    </span>
                                    <span class="text-[11px] font-bold text-slate-500">
                                        {{ $q->points }} Poin
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.quiz-pengetahuan.show', $q->id) }}" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.quiz-pengetahuan.edit', $q->id) }}" class="p-1.5 text-slate-500 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition" title="Edit Soal">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button type="button" onclick="confirmDeleteQuestion({{ $q->id }}, '{{ $q->question_number }}')" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus Soal">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                                Tidak ada soal ditemukan untuk kriteria filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($questions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $questions->links() }}
            </div>
        @endif
    </div>

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
            <h3 class="text-base font-extrabold text-slate-900">Hapus Soal Quiz?</h3>
            <p class="text-xs text-slate-500 mt-1">Anda yakin ingin menghapus soal nomor <strong id="delete-question-num" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
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
    function confirmDeleteQuestion(id, num) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-form');
        const numText = document.getElementById('delete-question-num');
        form.action = `/admin/quiz-pengetahuan/${id}`;
        numText.innerText = '#' + num;
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
