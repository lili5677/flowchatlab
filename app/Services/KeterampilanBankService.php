<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use RuntimeException;

class KeterampilanBankService
{
    protected ?array $cache = null;

    /**
     * Get the absolute path to the question bank JSON file.
     */
    public function getBankFilePath(): string
    {
        return database_path('data/bank_ks.json');
    }

    /**
     * Load all data from bank_ks.json.
     *
     * @return array<string, mixed>
     */
    public function loadBank(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $filePath = $this->getBankFilePath();

        if (! File::exists($filePath)) {
            throw new RuntimeException("File bank soal keterampilan tidak ditemukan: {$filePath}");
        }

        $json = File::get($filePath);
        $data = json_decode($json, true);

        if (! is_array($data) || ! isset($data['levels'])) {
            throw new RuntimeException('Format bank soal bank_ks.json tidak valid.');
        }

        $this->cache = $data;

        return $this->cache;
    }

    /**
     * Get all levels with tasks.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getLevels(): array
    {
        $bank = $this->loadBank();

        return $bank['levels'] ?? [];
    }

    /**
     * Find a task by its unique code (e.g., 'KS-1-1').
     *
     * @return array<string, mixed>|null
     */
    public function findTaskByCode(string $code): ?array
    {
        $levels = $this->getLevels();

        foreach ($levels as $level) {
            foreach ($level['tugas'] ?? [] as $tugas) {
                if (($tugas['kode'] ?? '') === $code) {
                    $tugas['level'] = $level['level'] ?? 1;
                    $tugas['nama_level'] = $level['nama_level'] ?? 'Mudah';

                    return $tugas;
                }
            }
        }

        return null;
    }

    /**
     * Pick 3 random tasks: 1 from level 1 (Mudah), 1 from level 2 (Sedang), 1 from level 3 (Sulit).
     *
     * @return array<int, array<string, mixed>>
     */
    public function getRandomTasksForQuiz(): array
    {
        $levels = $this->getLevels();
        $selectedTasks = [];

        // Sort levels ascending by level number (1, 2, 3)
        usort($levels, fn ($a, $b) => ($a['level'] ?? 1) <=> ($b['level'] ?? 1));

        foreach ($levels as $lvl) {
            $tugasList = $lvl['tugas'] ?? [];
            if (! empty($tugasList)) {
                $randomIndex = array_rand($tugasList);
                $chosen = $tugasList[$randomIndex];
                $chosen['level'] = $lvl['level'] ?? 1;
                $chosen['nama_level'] = $lvl['nama_level'] ?? 'Mudah';
                $selectedTasks[] = $chosen;
            }
        }

        return $selectedTasks;
    }
}
