<?php

namespace Database\Seeders;

use App\Models\KeterampilanTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class KeterampilanTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/bank_ks.json');

        if (! File::exists($jsonPath)) {
            $this->command?->error("File bank soal {$jsonPath} tidak ditemukan.");

            return;
        }

        $data = json_decode(File::get($jsonPath), true);

        if (! is_array($data) || ! isset($data['levels'])) {
            $this->command?->error('Format data bank_ks.json tidak valid.');

            return;
        }

        $count = 0;
        foreach ($data['levels'] as $levelData) {
            $level = $levelData['level'] ?? 1;
            $namaLevel = $levelData['nama_level'] ?? 'Mudah';

            foreach ($levelData['tugas'] ?? [] as $tugas) {
                KeterampilanTask::updateOrCreate(
                    ['kode' => $tugas['kode']],
                    [
                        'level' => $level,
                        'nama_level' => $namaLevel,
                        'judul' => $tugas['judul'],
                        'soal' => $tugas['soal'],
                        'langkah' => $tugas['langkah'] ?? [],
                    ]
                );
                $count++;
            }
        }

        $this->command?->info("Berhasil mengimpor {$count} soal quiz keterampilan algoritma.");
    }
}
