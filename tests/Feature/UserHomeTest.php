<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserHomeTest extends TestCase
{
    /**
     * Test that the user role homepage renders with all required visual elements and copy.
     */
    public function test_user_home_screen_renders_successfully(): void
    {
        $user = User::factory()->make([
            'name' => 'Bayu Pratama (RPL-1)',
            'role' => 'siswa',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Flowchart Lab');
        $response->assertSee('SMK ALGORITMA VISUAL');
        $response->assertSee('Bayu Pratama (RPL-1)');
        $response->assertSee('Siswa SMK');
        $response->assertSee('Modul Tuntas');
        $response->assertSee('Kuasai');
        $response->assertSee('Logika Pemrograman');
        $response->assertSee('Lewat Flowchart');
        $response->assertSee('Pilih Jalur Belajar Kamu');
        $response->assertSee('Materi');
        $response->assertSee('Simulasi');
        $response->assertSee('Quiz Interaktif');
        $response->assertSee('Berstandar Kompetensi');
    }

    /**
     * Test that the /home alias route also renders successfully.
     */
    public function test_home_route_alias_renders_successfully(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Flowchart Lab');
    }

    /**
     * Test that the /simulasi route renders successfully with its interactive components.
     */
    public function test_simulasi_screen_renders_successfully(): void
    {
        $response = $this->get('/simulasi');

        $response->assertStatus(200);
        $response->assertSee('Flowchart Lab');
        $response->assertSee('STATUS EKSEKUSI ALUR');
        $response->assertSee('TANTANGAN KASUS');
        $response->assertSee('Palet Simbol Logika');
        $response->assertSee('KEPUTUSAN (DECISION)');
        $response->assertSee('PROSES (PROCESS)');
        $response->assertSee('OUTPUT / I/O');
        $response->assertSee('TERMINATOR');
    }
}
