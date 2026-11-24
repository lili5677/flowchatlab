<?php

namespace Database\Seeders;

use App\Models\Simulation;
use Illuminate\Database\Seeder;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $simulations = [
            [
                'level_number' => 1,
                'order' => 1,
                'is_active' => true,
                'layout_type' => 'branching',
                'title' => 'Menentukan Kelulusan Nilai Siswa (KKM ≥ 75)',
                'desc' => 'Sebuah sistem harus mengevaluasi nilai siswa. Jika nilai ujian minimal 75, cetak status <span class="bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded text-xs">"Lulus"</span>. Lengkapi blok keputusan yang belum terpasang dan periksa kesesuaian alurnya.',
                'expected_symbol' => 'decision',
                'slot_prompt' => 'Klik / Pasang Simbol Keputusan',
                'slot_condition_text' => '< Nilai >= 75 ? >',
                'slot_text_inside' => 'Nilai >= 75 ?',
                'hint_text' => '<div class="p-3 bg-amber-50 rounded-2xl border border-amber-200 mb-2"><strong class="text-amber-900 block mb-1">Kaidah Standar ANSI/ISO 5807:</strong>Setiap titik dalam algoritma yang menghasilkan dua kemungkinan cabang (seperti <em>True/False</em> atau <em>Ya/Tidak</em>) WAJIB menggunakan <strong>Simbol Belah Ketupat (Decision)</strong>.</div><p>Pada kasus kelulusan ini, sistem mengevaluasi ekspresi kondisi perbandingan. Pilih simbol <strong>KEPUTUSAN (DECISION)</strong> pada palet di sebelah kiri lalu klik tombol Putar (▶).</p>',
                'success_title' => 'Luar Biasa! Tantangan 1/3 Berhasil!',
                'success_desc' => 'Kamu berhasil memasang simbol Keputusan (Decision) dan logika percabangan IF-ELSE berjalan sesuai standar ANSI/ISO 5807.',
                'config_data' => [
                    'input_label' => 'Input Nilai_Ujian = 80',
                    'output_true' => 'Cetak "Lulus"',
                    'output_false' => 'Cetak "Remedial"',
                ],
            ],
            [
                'level_number' => 2,
                'order' => 2,
                'is_active' => true,
                'layout_type' => 'sequential',
                'title' => 'Menghitung Luas Persegi Panjang (L = p × l)',
                'desc' => 'Sebuah sistem kalkulasi menerima input panjang dan lebar. Hitung luas dengan rumus <span class="bg-sky-100 text-sky-800 font-bold px-1.5 py-0.5 rounded text-xs">"Luas = p × l"</span>. Lengkapi blok proses perhitungan yang hilang agar program menghasilkan output yang akurat.',
                'expected_symbol' => 'process',
                'slot_prompt' => 'Klik / Pasang Simbol Proses',
                'slot_condition_text' => '< Luas = Panjang * Lebar >',
                'slot_text_inside' => 'Luas = Panjang × Lebar',
                'hint_text' => '<div class="p-3 bg-sky-50 rounded-2xl border border-sky-200 mb-2"><strong class="text-sky-900 block mb-1">Kaidah Standar ANSI/ISO 5807:</strong>Setiap operasi kalkulasi aritmatika, rumus, atau penugasan variabel (assignment) WAJIB menggunakan <strong>Simbol Persegi Panjang (Process)</strong>.</div><p>Pada kasus ini, algoritma melakukan perkalian antara <code>Panjang &times; Lebar</code>. Pilih simbol <strong>PROSES (PROCESS)</strong> pada palet lalu uji kembali alurnya.</p>',
                'success_title' => 'Mantap! Tantangan 2/3 Selesai!',
                'success_desc' => 'Simbol Proses (Process) berhasil mengeksekusi rumus perhitungan luas dengan akurat sebelum hasil dicetak.',
                'config_data' => [
                    'input_label' => 'Input Panjang (p)=10, Lebar (l)=6',
                    'output_label' => 'Cetak Luas = 60',
                ],
            ],
            [
                'level_number' => 3,
                'order' => 3,
                'is_active' => true,
                'layout_type' => 'loop',
                'title' => 'Perulangan Menampilkan Angka (Loop While i ≤ 5)',
                'desc' => 'Algoritma harus menampilkan angka 1 sampai 5 secara berurutan. Pasang blok penambahan counter <span class="bg-purple-100 text-purple-800 font-bold px-1.5 py-0.5 rounded text-xs">"i = i + 1"</span> agar loop berjalan teratur dan tidak menimbulkan perulangan tanpa henti (infinite loop).',
                'expected_symbol' => 'process',
                'slot_prompt' => 'Klik / Pasang Simbol Proses Counter',
                'slot_condition_text' => '< i = i + 1 (Increment) >',
                'slot_text_inside' => 'i = i + 1',
                'hint_text' => '<div class="p-3 bg-purple-50 rounded-2xl border border-purple-200 mb-2"><strong class="text-purple-900 block mb-1">Kaidah Standar ANSI/ISO 5807:</strong>Dalam struktur perulangan (looping), penambahan counter <code>i = i + 1</code> merupakan operasi aritmatika yang memerlukan <strong>Simbol Persegi Panjang (Process)</strong>.</div><p>Tanpa penambahan nilai <code>i</code>, kondisi <code>i &le; 5</code> akan selalu bernilai Benar sehingga terjadi <em>infinite loop</em>. Pasang simbol Proses pada slot perulangan.</p>',
                'success_title' => '🏆 Selamat! Seluruh 3 Tantangan Tuntas!',
                'success_desc' => 'Kamu telah berhasil menuntaskan ketiga pondasi utama algoritma: Percabangan (Branching), Kalkulasi Sekuensial, dan Perulangan (Looping)!',
                'config_data' => [
                    'init_label' => 'Inisialisasi: i = 1',
                    'condition_label' => 'Apakah i ≤ 5 ?',
                    'body_label' => 'Cetak nilai i',
                ],
            ],
        ];

        foreach ($simulations as $sim) {
            Simulation::updateOrCreate(
                ['level_number' => $sim['level_number']],
                $sim
            );
        }

        $this->command?->info('Berhasil mengimpor '.count($simulations).' simulasi algoritma visual.');
    }
}
