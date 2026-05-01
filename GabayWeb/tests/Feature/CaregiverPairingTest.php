<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaregiverPairingTest extends TestCase
{
    use RefreshDatabase;

    public function test_caregiver_can_connect_to_patient_with_valid_pairing_code(): void
    {
        $patient = User::create([
            'name' => 'Patient One',
            'email' => 'patient@example.com',
            'password' => 'password123',
            'role' => 'vi',
            'pairing_code' => 'ABC123',
            'code_expires_at' => now()->addDays(7),
        ]);

        $caregiver = User::create([
            'name' => 'Caregiver One',
            'email' => 'caregiver@example.com',
            'password' => 'password123',
            'role' => 'caregiver',
            'pairing_code' => null,
            'code_expires_at' => null,
        ]);

        $response = $this->actingAs($caregiver)->post('/caregiver/connect', [
            'pairing_code' => 'abc123',
        ]);

        $response->assertRedirect('/caregiver/dashboard');
        $response->assertSessionHas('status', 'Connected to patient: Patient One');

        $this->assertDatabaseHas('pairings', [
            'vi_user_id' => $patient->user_id,
            'caregiver_user_id' => $caregiver->user_id,
            'status' => 'active',
        ]);

    }

    public function test_caregiver_sees_error_for_invalid_pairing_code(): void
    {
        $caregiver = User::create([
            'name' => 'Caregiver One',
            'email' => 'caregiver@example.com',
            'password' => 'password123',
            'role' => 'caregiver',
            'pairing_code' => null,
            'code_expires_at' => null,
        ]);

        $response = $this->actingAs($caregiver)->from('/caregiver/dashboard')->post('/caregiver/connect', [
            'pairing_code' => 'ZZZ999',
        ]);

        $response->assertRedirect('/caregiver/dashboard');
        $response->assertSessionHasErrors('pairing_code');

        $this->assertDatabaseCount('pairings', 0);
    }
}
