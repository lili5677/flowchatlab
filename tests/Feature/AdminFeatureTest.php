<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeatureTest extends TestCase
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
            'name' => 'Budi Siswa',
            'email' => 'budi@smk.sch.id',
            'role' => 'siswa',
            'nis' => '12345',
            'kelas' => 'X RPL 1',
        ]);
    }

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_login_page_renders_single_unified_form_without_guru_tab(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun');
        $response->assertSee('EMAIL');
        $response->assertSee('KATA SANDI');
        $response->assertDontSee('tab-login-guru');
        $response->assertDontSee('Guru / Pembimbing');
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_student_login_redirects_to_home(): void
    {
        $siswa = $this->createSiswa();

        $response = $this->post(route('login'), [
            'email' => $siswa->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($siswa);
    }

    public function test_regular_siswa_cannot_access_admin_dashboard(): void
    {
        $siswa = $this->createSiswa();

        $response = $this->actingAs($siswa)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_and_see_statistics(): void
    {
        $admin = $this->createAdmin();
        $this->createSiswa();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Administrator');
        $response->assertSee('Total Siswa');
        $response->assertSee('Quiz Pengetahuan');
        $response->assertSee('Praktik Keterampilan');
    }

    public function test_admin_can_view_user_management_list(): void
    {
        $admin = $this->createAdmin();
        $siswa = $this->createSiswa();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna');
        $response->assertSee($siswa->name);
        $response->assertSee($siswa->email);
        $response->assertSee($siswa->nis);
    }

    public function test_admin_can_create_new_user(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Siswa Baru',
            'email' => 'siswabaru@smk.sch.id',
            'password' => 'secret1234',
            'role' => 'siswa',
            'school' => 'SMK Negeri 2',
            'nis' => '998877',
            'kelas' => 'X RPL 2',
            'points' => 10,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'siswabaru@smk.sch.id',
            'role' => 'siswa',
            'nis' => '998877',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'user_created',
        ]);
    }

    public function test_admin_can_update_existing_user(): void
    {
        $admin = $this->createAdmin();
        $siswa = $this->createSiswa();

        $response = $this->actingAs($admin)->put(route('admin.users.update', $siswa->id), [
            'name' => 'Budi Diperbarui',
            'email' => $siswa->email,
            'role' => 'siswa',
            'school' => 'SMKN 1 Bandung',
            'nis' => '54321',
            'kelas' => 'XI RPL 1',
            'points' => 50,
            'skor_praktik' => 88,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $siswa->refresh();
        $this->assertSame('Budi Diperbarui', $siswa->name);
        $this->assertSame('54321', $siswa->nis);
        $this->assertSame(88, $siswa->skor_praktik);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'user_updated',
        ]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $admin = $this->createAdmin();
        $siswa = $this->createSiswa();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $siswa->id));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $siswa->id]);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'user_deleted',
        ]);
    }

    public function test_admin_can_view_student_progress_page(): void
    {
        $admin = $this->createAdmin();
        $siswa = $this->createSiswa();
        $siswa->update(['skor_praktik' => 95]);

        $response = $this->actingAs($admin)->get(route('admin.progress.index'));

        $response->assertStatus(200);
        $response->assertSee('Progress Pengerjaan Siswa &amp; Statistik', false);
        $response->assertSee($siswa->name);
        $response->assertSee('95');
    }

    public function test_admin_can_inspect_quiz_attempt_details(): void
    {
        $admin = $this->createAdmin();
        $siswa = $this->createSiswa();

        $question = Question::create([
            'question_number' => 1,
            'level' => 'Mudah',
            'question_text' => 'Simbol terminator berfungsi untuk apa?',
            'symbol_type' => 'terminator',
            'options' => [
                ['id' => 'a', 'text' => 'Awal/Akhir alur'],
                ['id' => 'b', 'text' => 'Proses'],
            ],
            'correct_key' => 'a',
            'explanation' => 'Terminator melambangkan awal atau akhir program.',
            'points' => 5,
        ]);

        $attempt = QuizAttempt::create([
            'user_id' => $siswa->id,
            'status' => 'completed',
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
            'score' => 100,
            'points_earned' => 5,
            'correct_count' => 1,
            'total_questions' => 1,
            'is_passed' => true,
            'predicate' => 'Sangat Baik',
        ]);

        $attempt->answers()->create([
            'question_id' => $question->id,
            'selected_option' => 'a',
            'is_correct' => true,
            'points_awarded' => 5,
            'answered_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.progress.quiz-detail', $attempt->id));

        $response->assertStatus(200);
        $response->assertSee('Detail Evaluasi Quiz Pengetahuan');
        $response->assertSee('Simbol terminator berfungsi untuk apa?');
        $response->assertSee('Awal/Akhir alur');
        $response->assertSee('Benar (+5 Pts)');
    }

    public function test_admin_can_view_and_purge_activity_logs(): void
    {
        $admin = $this->createAdmin();

        $oldLog = ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'login',
            'description' => 'User Admin Utama berhasil masuk.',
        ]);
        $oldLog->timestamps = false;
        $oldLog->created_at = now()->subDays(40);
        $oldLog->save();

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'quiz_started',
            'description' => 'Siswa memulai kuis.',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.activity-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('Log Aktifitas Sistem');
        $response->assertSee('User Admin Utama berhasil masuk.');
        $response->assertSee('Siswa memulai kuis.');

        // Purge logs older than 30 days
        $purgeResponse = $this->actingAs($admin)->post(route('admin.activity-logs.purge'), [
            'days' => 30,
        ]);

        $purgeResponse->assertRedirect(route('admin.activity-logs.index'));
        $this->assertDatabaseMissing('activity_logs', [
            'description' => 'User Admin Utama berhasil masuk.',
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'description' => 'Siswa memulai kuis.',
        ]);
    }
}
