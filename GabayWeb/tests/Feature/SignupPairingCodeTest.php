<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignupPairingCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_vi_signup_generates_pairing_code(): void
    {
        $response = $this->post('/signup', [
            'name' => 'Navigator User',
            'email' => 'navigator@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'vi',
            'terms' => '1',
        ]);

        $response->assertRedirect('/patient/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'navigator@example.com',
            'role' => 'vi',
        ]);

        $user = User::where('email', 'navigator@example.com')->firstOrFail();

        $this->assertNotNull($user->pairing_code);
        $this->assertNotNull($user->code_expires_at);
        $this->assertSame(6, strlen($user->pairing_code));

        $dashboardResponse = $this->get('/patient/dashboard');
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee($user->pairing_code);
    }

    public function test_caregiver_signup_does_not_generate_pairing_code(): void
    {
        $response = $this->post('/signup', [
            'name' => 'Caregiver User',
            'email' => 'caregiver@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'caregiver',
            'terms' => '1',
        ]);

        $response->assertRedirect('/caregiver/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'caregiver@example.com',
            'role' => 'caregiver',
            'pairing_code' => null,
            'code_expires_at' => null,
        ]);
    }
}
