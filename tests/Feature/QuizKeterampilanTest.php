<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\KeterampilanBankService;
use App\Services\KeterampilanChecker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizKeterampilanTest extends TestCase
{
    use RefreshDatabase;

    protected KeterampilanBankService $bankService;

    protected KeterampilanChecker $checker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bankService = app(KeterampilanBankService::class);
        $this->checker = app(KeterampilanChecker::class);
    }

    /**
     * 1. Tugas KS-1-1 (keliling persegi) dijawab benar semua: skor 100.
     */
    public function test_ks_1_1_dijawab_benar_semua_menghasilkan_skor_100(): void
    {
        $task = $this->bankService->findTaskByCode('KS-1-1');
        $this->assertNotNull($task, 'Tugas KS-1-1 harus ditemukan di bank soal');

        $kunciLangkah = $task['langkah'];

        // Siswa menjawab semua langkah dengan urutan dan simbol yang benar
        $jawabanSiswa = [];
        foreach ($kunciLangkah as $langkah) {
            $jawabanSiswa[] = [
                'teks' => $langkah['teks'],
                'simbol' => $langkah['simbol'],
            ];
        }

        $result = $this->checker->check($kunciLangkah, $jawabanSiswa);

        $this->assertSame(100, $result['skor']);
        $this->assertTrue($result['is_lengkap']);
        $this->assertTrue($result['is_semua_benar']);
        $this->assertTrue($result['urutan_benar']);
        $this->assertSame(5, $result['simbol_benar']);
        $this->assertContains('Semua langkah sudah tepat.', $result['pesan']);
    }

    /**
     * 2. Tugas KS-1-1: urutan benar tapi semua simbol salah: skor 50.
     */
    public function test_ks_1_1_urutan_benar_tapi_semua_simbol_salah_menghasilkan_skor_50(): void
    {
        $task = $this->bankService->findTaskByCode('KS-1-1');
        $this->assertNotNull($task);

        $kunciLangkah = $task['langkah'];

        // Semua simbol sengaja dibuat salah, tapi urutan tetap benar
        $jawabanSiswa = [
            ['teks' => 'Mulai', 'simbol' => 'P'], // kunci: T
            ['teks' => 'Masukkan panjang sisi (s)', 'simbol' => 'T'], // kunci: IO
            ['teks' => 'Hitung Keliling = 4 * s', 'simbol' => 'D'], // kunci: P
            ['teks' => 'Tampilkan Keliling', 'simbol' => 'P'], // kunci: IO
            ['teks' => 'Selesai', 'simbol' => 'IO'], // kunci: T
        ];

        $result = $this->checker->check($kunciLangkah, $jawabanSiswa);

        $this->assertSame(50, $result['skor']);
        $this->assertTrue($result['urutan_benar']);
        $this->assertSame(0, $result['simbol_benar']);
        $this->assertFalse($result['is_semua_benar']);
    }

    /**
     * 3. Tugas KS-1-1: 1 dari 5 simbol salah, urutan benar: skor 90.
     */
    public function test_ks_1_1_satu_dari_lima_simbol_salah_urutan_benar_menghasilkan_skor_90(): void
    {
        $task = $this->bankService->findTaskByCode('KS-1-1');
        $this->assertNotNull($task);

        $kunciLangkah = $task['langkah'];

        // 4 simbol benar, 1 simbol salah (langkah ke-3 diubah jadi D)
        $jawabanSiswa = [
            ['teks' => 'Mulai', 'simbol' => 'T'], // benar
            ['teks' => 'Masukkan panjang sisi (s)', 'simbol' => 'IO'], // benar
            ['teks' => 'Hitung Keliling = 4 * s', 'simbol' => 'D'], // SALAH (kunci: P)
            ['teks' => 'Tampilkan Keliling', 'simbol' => 'IO'], // benar
            ['teks' => 'Selesai', 'simbol' => 'T'], // benar
        ];

        $result = $this->checker->check($kunciLangkah, $jawabanSiswa);

        $this->assertSame(90, $result['skor']);
        $this->assertTrue($result['urutan_benar']);
        $this->assertSame(4, $result['simbol_benar']);
        $this->assertContains('“Hitung Keliling = 4 * s”: seharusnya Proses.', $result['pesan']);
    }

    /**
     * 4. Satu langkah belum dipasang lalu kirim: ada pesan "Masih ada 1 langkah yang belum dipasang."
     */
    public function test_satu_langkah_belum_dipasang_memiliki_pesan_koreksi_yang_tepat(): void
    {
        $task = $this->bankService->findTaskByCode('KS-1-1');
        $this->assertNotNull($task);

        $kunciLangkah = $task['langkah'];

        // Hanya pasang 4 dari 5 langkah
        $jawabanSiswa = [
            ['teks' => 'Mulai', 'simbol' => 'T'],
            ['teks' => 'Masukkan panjang sisi (s)', 'simbol' => 'IO'],
            ['teks' => 'Hitung Keliling = 4 * s', 'simbol' => 'P'],
            ['teks' => 'Tampilkan Keliling', 'simbol' => 'IO'],
            // 'Selesai' belum dipasang
        ];

        $result = $this->checker->check($kunciLangkah, $jawabanSiswa);

        $this->assertFalse($result['is_lengkap']);
        $this->assertSame(4, $result['langkah_terpasang']);
        $this->assertContains('Masih ada 1 langkah yang belum dipasang.', $result['pesan']);
    }

    /**
     * 5. Pada kasus percabangan (KS-2-1), langkah "Ya: cetak “Genap”" dan "Tidak: cetak “Ganjil”" bertukar tempat tetap dianggap benar.
     */
    public function test_ks_2_1_percabangan_bertukar_tempat_tetap_dianggap_benar(): void
    {
        $task = $this->bankService->findTaskByCode('KS-2-1');
        $this->assertNotNull($task, 'Tugas KS-2-1 harus ditemukan di bank soal');

        $kunciLangkah = $task['langkah'];

        // Langkah percabangan: urutan normal adalah Ya lalu Tidak.
        // Di sini ditukar: "Tidak: cetak “Ganjil”" dipasang sebelum "Ya: cetak “Genap”"
        $jawabanSiswa = [
            ['teks' => 'Mulai', 'simbol' => 'T'],
            ['teks' => 'Masukkan sebuah bilangan bulat (bil)', 'simbol' => 'IO'],
            ['teks' => 'Apakah bil mod 2 == 0?', 'simbol' => 'D'],
            ['teks' => 'Tidak: cetak “Ganjil”', 'simbol' => 'IO'], // bertukar tempat
            ['teks' => 'Ya: cetak “Genap”', 'simbol' => 'IO'], // bertukar tempat
            ['teks' => 'Selesai', 'simbol' => 'T'],
        ];

        $result = $this->checker->check($kunciLangkah, $jawabanSiswa);

        $this->assertSame(100, $result['skor']);
        $this->assertTrue($result['urutan_benar']);
        $this->assertTrue($result['is_semua_benar']);
        $this->assertSame(6, $result['simbol_benar']);
        $this->assertContains('Semua langkah sudah tepat.', $result['pesan']);
    }

    /**
     * 6. Skor akhir tersimpan untuk user yang login dan tampil terpisah dari skor Quiz Pengetahuan.
     */
    public function test_skor_akhir_tersimpan_untuk_user_login_dan_tampil_terpisah(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'Ahmad Siswa',
            'email' => 'ahmad@example.com',
            'role' => 'siswa',
            'skor_praktik' => null,
        ]);

        $this->actingAs($user);

        // Siapkan simulasi sesi kuis dengan 3 tugas
        $task1 = $this->bankService->findTaskByCode('KS-1-1');
        $task2 = $this->bankService->findTaskByCode('KS-2-1');
        $task3 = $this->bankService->findTaskByCode('KS-3-1');

        $sessionData = [
            'tasks' => [$task1, $task2, $task3],
            'current_index' => 2, // Pada tugas terakhir (ke-3)
            'results' => [
                0 => ['skor' => 100, 'is_semua_benar' => true],
                1 => ['skor' => 90, 'is_semua_benar' => false],
                2 => ['skor' => 80, 'is_semua_benar' => false],
            ],
            'user_answers' => [0 => [], 1 => [], 2 => []],
            'skor_akhir' => null,
            'status' => 'in_progress',
        ];

        session(['quiz_keterampilan' => $sessionData]);

        // Kirim request ke route next untuk menyelesaikan kuis
        $response = $this->post(route('quiz.keterampilan.next'));

        $response->assertRedirect(route('quiz.keterampilan.hasil'));

        // Rata-rata dari [100, 90, 80] = 90
        $user->refresh();
        $this->assertSame(90, $user->skor_praktik);

        // Kunjungi halaman hasil kuis
        $hasilResponse = $this->get(route('quiz.keterampilan.hasil'));

        $hasilResponse->assertStatus(200);
        $hasilResponse->assertSee('SKOR PRAKTIK');
        $hasilResponse->assertSee('90');
        $hasilResponse->assertSee('Quiz Pengetahuan');
        $hasilResponse->assertSee('Ahmad Siswa');
    }

    /**
     * 7. Tugas KS-3-4 dengan operator perbandingan (>=) atau entity (&gt;=) pada keputusan dijawab benar: skor 100.
     */
    public function test_ks_3_4_dengan_operator_perbandingan_dijawab_benar_menghasilkan_skor_100(): void
    {
        $task = $this->bankService->findTaskByCode('KS-3-4');
        $this->assertNotNull($task, 'Tugas KS-3-4 harus ditemukan di bank soal');

        $kunciLangkah = $task['langkah'];

        // Simulasi input dari form HTML yang mungkin membawa entity &gt;=
        $jawabanSiswa = [
            ['teks' => 'Mulai', 'simbol' => 'T'],
            ['teks' => 'Masukkan teks password dari user', 'simbol' => 'IO'],
            ['teks' => 'Apakah panjang password &gt;= 8 karakter?', 'simbol' => 'D'],
            ['teks' => 'Ya: cetak “Password Memenuhi Syarat”', 'simbol' => 'IO'],
            ['teks' => 'Tidak: cetak “Password Terlalu Pendek”', 'simbol' => 'IO'],
            ['teks' => 'Selesai', 'simbol' => 'T'],
        ];

        $result = $this->checker->check($kunciLangkah, $jawabanSiswa);

        $this->assertSame(100, $result['skor']);
        $this->assertTrue($result['is_semua_benar']);
        $this->assertSame(6, $result['simbol_benar']);

        // Pastikan langkah keputusan terdeteksi tepat dan memiliki nama simbol kunci Keputusan
        $dEval = $result['evaluasi_langkah'][2];
        $this->assertTrue($dEval['is_simbol_benar']);
        $this->assertSame('D', $dEval['simbol_kunci']);
        $this->assertSame('Keputusan', $dEval['nama_simbol_kunci']);
    }
}
