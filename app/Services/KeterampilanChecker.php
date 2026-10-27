<?php

namespace App\Services;

class KeterampilanChecker
{
    /**
     * Map of symbol abbreviations to their full Indonesian display names.
     */
    public const SIMBOL_NAMES = [
        'T' => 'Terminator',
        'P' => 'Proses',
        'IO' => 'Input/Output',
        'D' => 'Keputusan',
    ];

    /**
     * Check student's answers for a single task.
     *
     * @param  array<int, array<string, mixed>>  $kunciLangkah  Array of key steps: [{teks, simbol, urutan}, ...]
     * @param  array<int, array<string, mixed>>  $jawabanSiswa  Array of submitted steps: [{teks, simbol}, ...]
     * @return array<string, mixed>
     */
    public function check(array $kunciLangkah, array $jawabanSiswa): array
    {
        $n = count($kunciLangkah);
        $langkahTerpasang = count($jawabanSiswa);
        $pesanKoreksi = [];

        // Build lookup map for key steps by normalized text
        $kunciMap = [];
        foreach ($kunciLangkah as $item) {
            $norm = $this->normalizeText($item['teks'] ?? '');
            $kunciMap[$norm] = $item;
        }

        // 1. Completeness Check
        $isLengkap = ($langkahTerpasang === $n);
        if ($langkahTerpasang < $n) {
            $sisa = $n - $langkahTerpasang;
            $pesanKoreksi[] = "Masih ada {$sisa} langkah yang belum dipasang.";
        }

        // 2. Symbol Check & Per-step Evaluation
        $simbolBenar = 0;
        $evaluasiLangkah = [];
        $urutanNumbers = [];

        foreach ($jawabanSiswa as $step) {
            $teks = $step['teks'] ?? '';
            $cleanTeks = html_entity_decode(html_entity_decode($teks, ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $norm = $this->normalizeText($cleanTeks);
            $simbolSiswa = ! empty($step['simbol']) ? strtoupper(trim($step['simbol'])) : null;

            $kunci = $kunciMap[$norm] ?? null;

            // Fallback 1: match against normalized keys
            if ($kunci === null) {
                foreach ($kunciMap as $kNorm => $kItem) {
                    if ($this->normalizeText($kNorm) === $norm) {
                        $kunci = $kItem;
                        break;
                    }
                }
            }

            // Fallback 2: match by comparing stripped alphanumeric characters
            if ($kunci === null) {
                $strippedNorm = preg_replace('/[^a-z0-9]/', '', $norm);
                foreach ($kunciMap as $kNorm => $kItem) {
                    if (preg_replace('/[^a-z0-9]/', '', $kNorm) === $strippedNorm) {
                        $kunci = $kItem;
                        break;
                    }
                }
            }

            $simbolKunci = $kunci['simbol'] ?? null;
            $urutanKunci = $kunci['urutan'] ?? 999;
            $urutanNumbers[] = $urutanKunci;

            $namaSimbolKunci = self::SIMBOL_NAMES[$simbolKunci] ?? $simbolKunci;
            $isSimbolTepat = false;

            if ($simbolSiswa === null || $simbolSiswa === '') {
                $pesanKoreksi[] = "“{$cleanTeks}”: pilih bentuk simbolnya.";
            } elseif ($simbolKunci !== null && $simbolSiswa === strtoupper($simbolKunci)) {
                $simbolBenar++;
                $isSimbolTepat = true;
            } else {
                $pesanKoreksi[] = "“{$cleanTeks}”: seharusnya {$namaSimbolKunci}.";
            }

            $evaluasiLangkah[] = [
                'teks' => $kunci !== null ? $kunci['teks'] : $cleanTeks,
                'simbol_siswa' => $simbolSiswa,
                'simbol_kunci' => $simbolKunci,
                'nama_simbol_kunci' => $namaSimbolKunci,
                'is_simbol_benar' => $isSimbolTepat,
                'urutan_kunci' => $urutanKunci,
                'is_salah' => ! $isSimbolTepat, // may also be marked if sequence is wrong
            ];
        }

        // 3. Sequence Check
        // "Urutan tepat: dari atas ke bawah, nomor urutan tidak pernah menurun; laporkan hanya pelanggaran urutan PERTAMA."
        // A sequence is valid if for every i < j, urutan[i] <= urutan[j].
        // The first violation occurs at index i if urutan[i] > min(urutan[j] for all j > i).
        $urutanBenar = true;
        $firstWrongIndex = null;
        $totalInstalled = count($urutanNumbers);

        for ($i = 0; $i < $totalInstalled; $i++) {
            for ($j = $i + 1; $j < $totalInstalled; $j++) {
                if ($urutanNumbers[$i] > $urutanNumbers[$j]) {
                    $urutanBenar = false;
                    $firstWrongIndex = $i;
                    break 2;
                }
            }
        }

        if (! $urutanBenar && $firstWrongIndex !== null) {
            $langkahSalah = $evaluasiLangkah[$firstWrongIndex]['teks'] ?? '';
            $pesanKoreksi[] = "Urutan “{$langkahSalah}” terlalu awal.";
            $evaluasiLangkah[$firstWrongIndex]['is_salah'] = true;
        }

        // 4. Calculate Score
        // Skor tugas = round( 50 × (simbol_benar / n) + 50 × (urutan_benar ? langkah_terpasang / n : 0) )
        $rasioSimbol = $n > 0 ? ($simbolBenar / $n) : 0;
        $rasioUrutan = ($urutanBenar && $n > 0) ? ($langkahTerpasang / $n) : 0;
        $skor = (int) round((50 * $rasioSimbol) + (50 * $rasioUrutan));

        // 5. Final Success Message
        $isSemuaBenar = ($isLengkap && $simbolBenar === $n && $urutanBenar);
        if ($isSemuaBenar) {
            $pesanKoreksi = ['Semua langkah sudah tepat.'];
        }

        return [
            'skor' => $skor,
            'simbol_benar' => $simbolBenar,
            'urutan_benar' => $urutanBenar,
            'langkah_terpasang' => $langkahTerpasang,
            'total_langkah' => $n,
            'is_lengkap' => $isLengkap,
            'is_semua_benar' => $isSemuaBenar,
            'pesan' => $pesanKoreksi,
            'evaluasi_langkah' => $evaluasiLangkah,
        ];
    }

    /**
     * Calculate final overall score from an array of individual task scores.
     *
     * @param  array<int, int>  $taskScores
     */
    public function calculateFinalScore(array $taskScores): int
    {
        if (empty($taskScores)) {
            return 0;
        }

        $avg = array_sum($taskScores) / count($taskScores);

        return (int) round($avg);
    }

    /**
     * Normalize text for fuzzy matching of quotes, entities, and whitespace.
     */
    public function normalizeText(string $text): string
    {
        // Decode HTML entities (decode twice to catch double-encoded strings like &amp;gt;)
        $text = html_entity_decode(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Replace typographic single and double quotes with standard quotes
        $search = ['“', '”', '‘', '’', '"', "'"];
        $replace = ['"', '"', "'", "'", '"', "'"];
        $text = str_replace($search, $replace, $text);

        // Normalize multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);

        return trim(mb_strtolower($text));
    }
}
