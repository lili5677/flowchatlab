<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Rekap Nilai Siswa - Flowchart Lab SMK</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #FFFFFF !important;
                color: #000000 !important;
            }
            .print-table th, .print-table td {
                border-color: #94A3B8 !important;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8">

    <!-- Action Toolbar (Hidden in Print) -->
    <div class="no-print max-w-5xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <a href="{{ route('admin.progress.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 hover:text-slate-900">
            &larr; Kembali ke Panel Admin
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.progress.export') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-800 text-xs font-bold transition">
                Unduh CSV (Excel)
            </a>
            <button type="button" onclick="window.print()" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-extrabold shadow-sm transition cursor-pointer">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Official Document Sheet -->
    <div class="max-w-5xl mx-auto bg-white p-8 sm:p-12 rounded-3xl border border-slate-200/80 shadow-sm print:border-none print:shadow-none print:p-0">
        
        <!-- Header Kop Laporan -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-slate-900 mb-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-14 h-14 object-contain">
                <div>
                    <h1 class="text-xl font-black uppercase tracking-tight text-slate-900 leading-tight">Flowchart Lab SMK Indonesia</h1>
                    <p class="text-xs font-bold text-amber-600 uppercase tracking-widest mt-0.5">Media Pembelajaran Algoritma & Logika Pemrograman Visual</p>
                    <p class="text-[11px] text-slate-500 mt-1">Standar ANSI/ISO 5807 &bull; Capaian Pembelajaran Kurikulum Merdeka Kejuruan RPL / TKI</p>
                </div>
            </div>
            <div class="text-right text-xs text-slate-600">
                <div class="font-extrabold text-slate-900 uppercase">Rekapitulasi Nilai Asesmen</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Tanggal Cetak: {{ $generatedAt->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="mb-6 flex flex-wrap items-center justify-between text-xs text-slate-600 gap-4">
            <div>
                <span>Total Peserta Didik: <strong>{{ $students->count() }} Siswa</strong></span>
            </div>
            <div>
                <span>Kriteria Ketuntasan Minimal (KKM): <strong>75</strong></span>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left text-xs border-collapse print-table border border-slate-300">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 font-bold text-[11px] text-slate-800">
                    <th class="py-2.5 px-3 border-r border-slate-300 text-center w-10">No</th>
                    <th class="py-2.5 px-3 border-r border-slate-300">NIS</th>
                    <th class="py-2.5 px-3 border-r border-slate-300">Nama Siswa</th>
                    <th class="py-2.5 px-3 border-r border-slate-300">Kelas</th>
                    <th class="py-2.5 px-3 border-r border-slate-300 text-center">Attempt Teori</th>
                    <th class="py-2.5 px-3 border-r border-slate-300 text-center">Nilai Teori Tertinggi</th>
                    <th class="py-2.5 px-3 border-r border-slate-300 text-center">Status Teori</th>
                    <th class="py-2.5 px-3 border-r border-slate-300 text-center">Nilai Praktik</th>
                    <th class="py-2.5 px-3 border-r border-slate-300 text-center">Predikat Praktik</th>
                    <th class="py-2.5 px-3 text-right">Poin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($students as $idx => $student)
                    @php
                        $attempts = $student->quizAttempts;
                        $maxScore = $attempts->max('score');
                        $isPassed = $maxScore !== null && $maxScore >= 75;
                    @endphp
                    <tr class="{{ $idx % 2 === 1 ? 'bg-slate-50/50' : 'bg-white' }}">
                        <td class="py-2 px-3 border-r border-slate-200 text-center font-semibold">{{ $idx + 1 }}</td>
                        <td class="py-2 px-3 border-r border-slate-200 font-mono">{{ $student->display_nis }}</td>
                        <td class="py-2 px-3 border-r border-slate-200 font-bold text-slate-900">{{ $student->name }}</td>
                        <td class="py-2 px-3 border-r border-slate-200">{{ $student->display_kelas }}</td>
                        <td class="py-2 px-3 border-r border-slate-200 text-center">{{ $attempts->count() }}x</td>
                        <td class="py-2 px-3 border-r border-slate-200 text-center font-black {{ $maxScore >= 75 ? 'text-emerald-700' : 'text-slate-800' }}">
                            {{ $maxScore !== null ? $maxScore : '-' }}
                        </td>
                        <td class="py-2 px-3 border-r border-slate-200 text-center font-bold text-[10px]">
                            @if($maxScore !== null)
                                <span class="{{ $isPassed ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $isPassed ? 'LULUS' : 'REMEDIAL' }}
                                </span>
                            @else
                                <span class="text-slate-400 italic">BELUM</span>
                            @endif
                        </td>
                        <td class="py-2 px-3 border-r border-slate-200 text-center font-black text-purple-800">
                            {{ $student->skor_praktik !== null ? $student->skor_praktik : '-' }}
                        </td>
                        <td class="py-2 px-3 border-r border-slate-200 text-center font-semibold text-[10px]">
                            @if($student->skor_praktik !== null)
                                {{ $student->skor_praktik >= 85 ? 'Sangat Terampil' : ($student->skor_praktik >= 75 ? 'Kompeten' : 'Perlu Latihan') }}
                            @else
                                <span class="text-slate-400 italic">Belum</span>
                            @endif
                        </td>
                        <td class="py-2 px-3 text-right font-black text-amber-700">
                            {{ number_format($student->points) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="py-6 text-center text-slate-400 italic">Belum ada data siswa terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signatures Section -->
        <div class="mt-14 pt-6 grid grid-cols-2 gap-8 text-center text-xs break-inside-avoid">
            <div>
                <p class="text-slate-500 mb-16">Mengetahui,<br><strong class="text-slate-800">Kepala Laboratorium Komputer / Kaprog</strong></p>
                <div class="w-48 mx-auto border-b border-slate-800 pb-1 font-bold text-slate-900">
                    _________________________
                </div>
                <div class="text-[11px] text-slate-500 mt-1">NIP. ........................................</div>
            </div>
            <div>
                <p class="text-slate-500 mb-16">Kota .........................., {{ $generatedAt->translatedFormat('d F Y') }}<br><strong class="text-slate-800">Guru Pengampu / Pembimbing</strong></p>
                <div class="w-48 mx-auto border-b border-slate-800 pb-1 font-bold text-slate-900">
                    _________________________
                </div>
                <div class="text-[11px] text-slate-500 mt-1">NIP. ........................................</div>
            </div>
        </div>

    </div>

</body>
</html>

