<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserHomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the user role homepage renders with all required visual elements and copy.
     */
    public function test_user_home_screen_renders_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'Bayu Pratama (RPL-1)',
            'role' => 'siswa',
            'points' => 850,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Flowchart Lab');
        $response->assertSee('SMK ALGORITMA VISUAL');
        $response->assertSee('Bayu Pratama (RPL-1)');
        $response->assertSee('Siswa SMK');
        $response->assertSee('Kuasai');
        $response->assertSee('Logika Pemrograman');
        $response->assertSee('Lewat Flowchart');
        $response->assertSee('Pilih Jalur Belajar Kamu');
        $response->assertSee('Materi');
        $response->assertSee('Simulasi');
        $response->assertSee('Quiz Interaktif');
        $response->assertSee('Standar Kompetensi Algoritma SMK');
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
}
