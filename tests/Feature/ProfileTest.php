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
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('administrator.profile'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Akun');
        $response->assertSee($user->email);
    }

    public function test_unauthenticated_user_cannot_view_profile_page(): void
    {
        $response = $this->get(route('administrator.profile'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_profile_information(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($user)->put(route('administrator.profile.update'), [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $response->assertRedirect(route('administrator.profile'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_email_must_be_unique_when_updating_profile(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->actingAs($user)->put(route('administrator.profile.update'), [
            'name' => 'User Name',
            'email' => 'existing@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($user)->put(route('administrator.profile.password'), [
            'current_password' => 'old-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ]);

        $response->assertRedirect(route('administrator.profile'));
        $response->assertSessionHas('success', 'Kata sandi berhasil diperbarui.');

        $this->assertTrue(Hash::check('new-password123', $user->fresh()->password));
    }

    public function test_password_update_fails_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->actingAs($user)->put(route('administrator.profile.password'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }
}
