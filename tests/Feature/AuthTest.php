<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the login page renders successfully.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Your email');
        $response->assertSee('Your password');
    }

    /**
     * Test a user with admin role can login with valid credentials and is redirected to /administrator/invoice.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@pos.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->post('/', [
            'email' => 'admin@pos.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/administrator/invoice');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test an administrator can login and is redirected to the dashboard.
     */
    public function test_administrator_can_login_and_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'administrator@pos.com',
            'password' => bcrypt('password'),
            'role' => 'administrator',
        ]);

        $response = $this->post('/', [
            'email' => 'administrator@pos.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/administrator/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test an administrator can access the dashboard.
     */
    public function test_administrator_can_access_the_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'administrator',
        ]);

        $response = $this->actingAs($user)->get('/administrator/dashboard');

        $response->assertStatus(200);
        $response->assertSee('SALE POS');
    }

    /**
     * Test a regular admin user cannot access the administrator dashboard.
     */
    public function test_admin_cannot_access_the_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/administrator/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test a user cannot login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test an authenticated user can access /me and get user details.
     */
    public function test_user_can_access_me_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/me');

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ]);
    }

    /**
     * Test an unauthenticated user cannot access /me and is redirected to login.
     */
    public function test_user_cannot_access_me_when_unauthenticated(): void
    {
        $response = $this->get('/me');

        $response->assertRedirect('/');
    }

    /**
     * Test a user can logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
