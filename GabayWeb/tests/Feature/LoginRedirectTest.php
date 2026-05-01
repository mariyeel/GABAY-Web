<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_caregiver_login_redirects_to_caregiver_dashboard(): void
    {
        User::create([
            'name' => 'Caregiver User',
            'email' => 'caregiver@example.com',
            'password' => 'password123',
            'role' => 'caregiver',
            'pairing_code' => null,
            'code_expires_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'caregiver@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/caregiver/dashboard');
        $this->assertAuthenticated();
    }

    public function test_vi_login_redirects_to_patient_dashboard(): void
    {
        User::create([
            'name' => 'Navigator User',
            'email' => 'navigator@example.com',
            'password' => 'password123',
            'role' => 'vi',
            'pairing_code' => 'ABC123',
            'code_expires_at' => now()->addDays(7),
        ]);

        $response = $this->post('/login', [
            'email' => 'navigator@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/patient/dashboard');
        $this->assertAuthenticated();
    }
}
