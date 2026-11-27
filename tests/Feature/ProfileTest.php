<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@test.com']);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('budi@test.com');
    }

    public function test_guest_is_redirected_from_profile_page(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_update_profile_info(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Nama Baru',
            'email' => 'baru@test.com',
            'school' => 'SMKN 1 Solo',
            'nis' => '12345',
            'kelas' => 'X RPL',
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'email' => 'baru@test.com',
            'school' => 'SMKN 1 Solo',
            'nis' => '12345',
            'kelas' => 'X RPL',
        ]);
    }

    public function test_user_can_change_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'NewPass1',
            'password_confirmation' => 'NewPass1',
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertTrue(Hash::check('NewPass1', $user->fresh()->password));
    }

    public function test_password_field_optional_when_blank(): void
    {
        $user = User::factory()->create(['password' => Hash::make('OldPass1')]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertTrue(Hash::check('OldPass1', $user->fresh()->password));
    }

    public function test_duplicate_email_validation_fails(): void
    {
        $other = User::factory()->create(['email' => 'taken@test.com']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => 'taken@test.com',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
