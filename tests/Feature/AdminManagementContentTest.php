<?php

namespace Tests\Feature;

use App\Models\KeterampilanTask;
use App\Models\Question;
use App\Models\Simulation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementContentTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@flowchatlab.test',
            'role' => 'admin',
        ]);
    }

    protected function createSiswa(): User
    {
        return User::factory()->create([
            'name' => 'Siswa Test',
            'email' => 'siswa@flowchatlab.test',
            'role' => 'siswa',
        ]);
    }

    public function test_guest_cannot_access_new_admin_routes(): void
    {
        $this->get(route('admin.quiz-pengetahuan.index'))->assertRedirect(route('login'));
        $this->get(route('admin.quiz-keterampilan.index'))->assertRedirect(route('login'));
        $this->get(route('admin.simulasi.index'))->assertRedirect(route('login'));
    }

    public function test_regular_siswa_cannot_access_new_admin_routes(): void
    {
        $siswa = $this->createSiswa();

        $this->actingAs($siswa)->get(route('admin.quiz-pengetahuan.index'))->assertStatus(403);
        $this->actingAs($siswa)->get(route('admin.quiz-keterampilan.index'))->assertStatus(403);
        $this->actingAs($siswa)->get(route('admin.simulasi.index'))->assertStatus(403);
    }

    public function test_admin_can_view_and_crud_quiz_pengetahuan(): void
    {
        $admin = $this->createAdmin();

        // 1. Index
        $response = $this->actingAs($admin)->get(route('admin.quiz-pengetahuan.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Quiz Pengetahuan');

        // 2. Create form
        $response = $this->actingAs($admin)->get(route('admin.quiz-pengetahuan.create'));
        $response->assertStatus(200);
        $response->assertSee('Tambah Soal Quiz');

        // 3. Store
        $storeResponse = $this->actingAs($admin)->post(route('admin.quiz-pengetahuan.store'), [
            'question_number' => 101,
            'level' => 'Mudah',
            'symbol_type' => 'terminator',
            'question_text' => 'Simbol apakah yang menandai awal flowchart?',
            'option_a' => 'Oval / Terminator',
            'option_b' => 'Persegi Panjang',
            'option_c' => 'Belah Ketupat',
            'option_d' => 'Jajar Genjang',
            'correct_key' => 'A',
            'explanation' => 'Terminator digunakan untuk Mulai dan Selesai.',
            'points' => 10,
        ]);

        $storeResponse->assertRedirect(route('admin.quiz-pengetahuan.index'));
        $this->assertDatabaseHas('questions', [
            'question_number' => 101,
            'correct_key' => 'A',
            'level' => 'Mudah',
        ]);

        $question = Question::where('question_number', 101)->firstOrFail();

        // 4. Show
        $this->actingAs($admin)->get(route('admin.quiz-pengetahuan.show', $question->id))
            ->assertStatus(200)
            ->assertSee('Simbol apakah yang menandai awal flowchart?');

        // 5. Edit form
        $this->actingAs($admin)->get(route('admin.quiz-pengetahuan.edit', $question->id))
            ->assertStatus(200)
            ->assertSee('Edit Soal Quiz');

        // 6. Update
        $updateResponse = $this->actingAs($admin)->put(route('admin.quiz-pengetahuan.update', $question->id), [
            'question_number' => 101,
            'level' => 'Sedang',
            'symbol_type' => 'terminator',
            'question_text' => 'Pertanyaan yang telah diperbarui admin?',
            'option_a' => 'Pilihan A baru',
            'option_b' => 'Pilihan B baru',
            'option_c' => 'Pilihan C baru',
            'option_d' => 'Pilihan D baru',
            'correct_key' => 'B',
            'explanation' => 'Penjelasan baru terupdate.',
            'points' => 15,
        ]);

        $updateResponse->assertRedirect(route('admin.quiz-pengetahuan.index'));
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_text' => 'Pertanyaan yang telah diperbarui admin?',
            'level' => 'Sedang',
            'correct_key' => 'B',
        ]);

        // 7. Destroy
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.quiz-pengetahuan.destroy', $question->id));
        $deleteResponse->assertRedirect(route('admin.quiz-pengetahuan.index'));
        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    public function test_admin_can_view_and_crud_quiz_keterampilan(): void
    {
        $admin = $this->createAdmin();

        // 1. Index
        $response = $this->actingAs($admin)->get(route('admin.quiz-keterampilan.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Quiz Keterampilan');

        // 2. Create form
        $response = $this->actingAs($admin)->get(route('admin.quiz-keterampilan.create'));
        $response->assertStatus(200);
        $response->assertSee('Tambah Tugas Keterampilan');

        // 3. Store
        $storeResponse = $this->actingAs($admin)->post(route('admin.quiz-keterampilan.store'), [
            'kode' => 'KS-TEST-01',
            'level' => 1,
            'judul' => 'Tugas Uji Coba Keterampilan',
            'soal' => 'Susunlah alur untuk mencetak nama pengguna.',
            'steps' => [
                ['teks' => 'Mulai', 'simbol' => 'T'],
                ['teks' => 'Input Nama', 'simbol' => 'IO'],
                ['teks' => 'Cetak Nama', 'simbol' => 'IO'],
                ['teks' => 'Selesai', 'simbol' => 'T'],
            ],
        ]);

        $storeResponse->assertRedirect(route('admin.quiz-keterampilan.index'));
        $this->assertDatabaseHas('keterampilan_tasks', [
            'kode' => 'KS-TEST-01',
            'judul' => 'Tugas Uji Coba Keterampilan',
            'level' => 1,
        ]);

        $task = KeterampilanTask::where('kode', 'KS-TEST-01')->firstOrFail();

        // 4. Show
        $this->actingAs($admin)->get(route('admin.quiz-keterampilan.show', $task->id))
            ->assertStatus(200)
            ->assertSee('Tugas Uji Coba Keterampilan');

        // 5. Edit form
        $this->actingAs($admin)->get(route('admin.quiz-keterampilan.edit', $task->id))
            ->assertStatus(200)
            ->assertSee('Edit Tugas Keterampilan');

        // 6. Update
        $updateResponse = $this->actingAs($admin)->put(route('admin.quiz-keterampilan.update', $task->id), [
            'kode' => 'KS-TEST-01',
            'level' => 2,
            'judul' => 'Tugas Keterampilan Diperbarui',
            'soal' => 'Skenario terupdate oleh admin.',
            'steps' => [
                ['teks' => 'Mulai Baru', 'simbol' => 'T'],
                ['teks' => 'Proses Data', 'simbol' => 'P'],
                ['teks' => 'Selesai Baru', 'simbol' => 'T'],
            ],
        ]);

        $updateResponse->assertRedirect(route('admin.quiz-keterampilan.index'));
        $this->assertDatabaseHas('keterampilan_tasks', [
            'id' => $task->id,
            'judul' => 'Tugas Keterampilan Diperbarui',
            'level' => 2,
        ]);

        // 7. Destroy
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.quiz-keterampilan.destroy', $task->id));
        $deleteResponse->assertRedirect(route('admin.quiz-keterampilan.index'));
        $this->assertDatabaseMissing('keterampilan_tasks', ['id' => $task->id]);
    }

    public function test_admin_can_view_and_crud_simulasi(): void
    {
        $admin = $this->createAdmin();

        // 1. Index
        $response = $this->actingAs($admin)->get(route('admin.simulasi.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Simulasi');

        // 2. Create form
        $response = $this->actingAs($admin)->get(route('admin.simulasi.create'));
        $response->assertStatus(200);
        $response->assertSee('Tambah Kasus Simulasi');

        // 3. Store
        $storeResponse = $this->actingAs($admin)->post(route('admin.simulasi.store'), [
            'level_number' => 99,
            'title' => 'Simulasi Testing Custom',
            'desc' => 'Deskripsi kasus simulasi custom untuk testing.',
            'layout_type' => 'branching',
            'expected_symbol' => 'decision',
            'slot_prompt' => 'Pasang Simbol Decision',
            'slot_condition_text' => '< Cek Nilai ? >',
            'slot_text_inside' => 'Cek Nilai ?',
            'hint_text' => 'Gunakan belah ketupat untuk percabangan.',
            'success_title' => 'Sukses Menguji',
            'success_desc' => 'Kasus berhasil dieksekusi dengan baik.',
            'input_label' => 'Input X = 50',
            'output_true' => 'Cetak True',
            'output_false' => 'Cetak False',
            'is_active' => true,
        ]);

        $storeResponse->assertRedirect(route('admin.simulasi.index'));
        $this->assertDatabaseHas('simulations', [
            'level_number' => 99,
            'title' => 'Simulasi Testing Custom',
            'expected_symbol' => 'decision',
        ]);

        $sim = Simulation::where('level_number', 99)->firstOrFail();

        // 4. Show
        $this->actingAs($admin)->get(route('admin.simulasi.show', $sim->id))
            ->assertStatus(200)
            ->assertSee('Simulasi Testing Custom');

        // 5. Edit form
        $this->actingAs($admin)->get(route('admin.simulasi.edit', $sim->id))
            ->assertStatus(200)
            ->assertSee('Edit Kasus Simulasi');

        // 6. Update
        $updateResponse = $this->actingAs($admin)->put(route('admin.simulasi.update', $sim->id), [
            'level_number' => 99,
            'title' => 'Simulasi Testing Terupdate',
            'desc' => 'Deskripsi baru terupdate.',
            'layout_type' => 'sequential',
            'expected_symbol' => 'process',
            'slot_prompt' => 'Pasang Simbol Proses',
            'slot_condition_text' => '< Hitung Total >',
            'slot_text_inside' => 'Total = X + Y',
            'hint_text' => 'Gunakan persegi panjang.',
            'success_title' => 'Perhitungan Berhasil',
            'success_desc' => 'Selesai dengan kalkulasi akurat.',
            'input_label' => 'Input X=10, Y=20',
            'output_label' => 'Cetak Total = 30',
            'is_active' => true,
        ]);

        $updateResponse->assertRedirect(route('admin.simulasi.index'));
        $this->assertDatabaseHas('simulations', [
            'id' => $sim->id,
            'title' => 'Simulasi Testing Terupdate',
            'layout_type' => 'sequential',
            'expected_symbol' => 'process',
        ]);

        // 7. Destroy
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.simulasi.destroy', $sim->id));
        $deleteResponse->assertRedirect(route('admin.simulasi.index'));
        $this->assertDatabaseMissing('simulations', ['id' => $sim->id]);
    }

    public function test_simulasi_page_loads_dynamic_simulations_from_db(): void
    {
        Simulation::create([
            'level_number' => 1,
            'order' => 1,
            'is_active' => true,
            'layout_type' => 'branching',
            'title' => 'Simulasi Dinamis DB Test',
            'desc' => 'Deskripsi simulasi dinamis dari database.',
            'expected_symbol' => 'decision',
            'slot_prompt' => 'Pasang Simbol Keputusan',
            'slot_condition_text' => '< Nilai >= 75 ? >',
            'slot_text_inside' => 'Nilai >= 75 ?',
            'hint_text' => 'Petunjuk decision.',
            'success_title' => 'Berhasil!',
            'success_desc' => 'Tuntas.',
        ]);

        $response = $this->get(route('simulasi'));
        $response->assertStatus(200);
        $response->assertSee('Simulasi Dinamis DB Test');
    }

    public function test_admin_sidebar_is_sticky_and_has_all_management_links(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Sidebar sticky screen-height class
        $response->assertSee('sticky top-0 h-screen');

        // Navigation links
        $response->assertSee(route('admin.quiz-pengetahuan.index'));
        $response->assertSee(route('admin.quiz-keterampilan.index'));
        $response->assertSee(route('admin.simulasi.index'));

        // Dashboard management quick cards
        $response->assertSee('Manajemen Bank Soal');
        $response->assertSee('Simulasi Flowchart');
    }

    public function test_website_shows_admin_panel_dropdown_for_admin_user(): void
    {
        $admin = $this->createAdmin();
        $siswa = $this->createSiswa();

        // Admin visiting website home sees panel link in profile hover dropdown
        $adminResponse = $this->actingAs($admin)->get(route('home'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Panel Administrator');
        $adminResponse->assertSee(route('admin.dashboard'));

        // Student visiting home does NOT see panel link
        $siswaResponse = $this->actingAs($siswa)->get(route('home'));
        $siswaResponse->assertStatus(200);
        $siswaResponse->assertDontSee('Panel Administrator');
        $siswaResponse->assertDontSee(route('admin.dashboard'));
    }

    public function test_admin_can_create_simulation_with_simplified_plain_text_form(): void
    {
        $admin = $this->createAdmin();

        $storeResponse = $this->actingAs($admin)->post(route('admin.simulasi.store'), [
            'level_number' => 88,
            'title' => 'Simulasi Tanpa Coding Test',
            'desc' => 'Deskripsi skenario tanpa coding sama sekali.',
            'layout_type' => 'branching',
            'expected_symbol' => 'decision',
            'slot_text_inside' => 'Status Nilai >= 75 ?',
            'hint_rule' => 'Kaidah ANSI/ISO menyatakan setiap percabangan memakai decision.',
            'hint_tip' => 'Pilih simbol belah ketupat di palet lalu seret ke slot alur.',
            'success_title' => 'Tantangan Selesai',
            'success_desc' => 'Kamu berhasil menyelesaikan alur.',
            'branch_input_label' => 'Input Nilai Siswa',
            'branch_output_true' => 'Cetak Lulus',
            'branch_output_false' => 'Cetak Ulang',
            'is_active' => true,
        ]);

        $storeResponse->assertRedirect(route('admin.simulasi.index'));
        $this->assertDatabaseHas('simulations', [
            'level_number' => 88,
            'title' => 'Simulasi Tanpa Coding Test',
            'expected_symbol' => 'decision',
            'slot_text_inside' => 'Status Nilai >= 75 ?',
            'slot_condition_text' => '< Status Nilai >= 75 ? >',
            'slot_prompt' => 'Klik / Pasang Simbol Keputusan',
        ]);

        $sim = Simulation::where('level_number', 88)->firstOrFail();
        $this->assertStringContainsString('Kaidah Standar ANSI/ISO 5807:', $sim->hint_text);
        $this->assertEquals('Kaidah ANSI/ISO menyatakan setiap percabangan memakai decision.', $sim->hint_rule_text);
        $this->assertEquals('Pilih simbol belah ketupat di palet lalu seret ke slot alur.', $sim->hint_tip_text);

        $sim->delete();
    }

    public function test_admin_edit_forms_are_full_width(): void
    {
        $admin = $this->createAdmin();

        $question = Question::first();
        if ($question) {
            $resp = $this->actingAs($admin)->get(route('admin.quiz-pengetahuan.edit', $question->id));
            $resp->assertStatus(200);
            $resp->assertDontSee('max-w-4xl');
            $resp->assertSee('w-full');
        }

        $task = KeterampilanTask::first();
        if ($task) {
            $resp = $this->actingAs($admin)->get(route('admin.quiz-keterampilan.edit', $task->id));
            $resp->assertStatus(200);
            $resp->assertDontSee('max-w-4xl');
            $resp->assertSee('w-full');
        }

        $sim = Simulation::first();
        if ($sim) {
            $resp = $this->actingAs($admin)->get(route('admin.simulasi.edit', $sim->id));
            $resp->assertStatus(200);
            $resp->assertDontSee('max-w-4xl');
            $resp->assertSee('w-full');
        }
    }
}
