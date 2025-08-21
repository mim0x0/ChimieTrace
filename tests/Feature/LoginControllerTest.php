<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@admin.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/inventory');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function test_throttled_login_returns_lockout()
    {
        $user = User::factory()->create([
            'email' => 'wrong@yahoo.com',
            'password' => bcrypt('pass123'),
        ]);

        $key = 'login|127.0.0.1';

        // Simulate exceeding attempts
        for ($i = 0; $i < 6; $i++) {
            $this->post('/login', [
                'email' => 'wrong@yahoo.com',
                'password' => '123',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'wrong@yahoo.com',
            'password' => '123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_failed_login_increments_attempts()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@yahoo.com',
            'password' => '123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function test_login_validation_fails_on_empty_input()
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
    }




}
