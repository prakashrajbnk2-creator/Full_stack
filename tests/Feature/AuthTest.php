<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
    }

    public function test_can_view_register_page(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Create Your Account');
    }

    public function test_user_can_register_successfully(): void
    {
        $response = $this->post('/register', [
            'name' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'johndoe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_user_cannot_register_with_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'newuser',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'name' => 'testuser',
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'name' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'name' => 'testuser',
            'password' => 'correctpassword',
        ]);

        $response = $this->post('/login', [
            'name' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
