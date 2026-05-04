<?php

namespace Tests\Feature;

use App\Models\NavigationSession;
use App\Models\Pairing;
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

    public function test_caregiver_can_view_live_tracking_session_for_connected_patient(): void
    {
        $patient = User::create([
            'name' => 'Patient One',
            'email' => 'patient-live@example.com',
            'password' => 'password123',
            'role' => 'vi',
            'pairing_code' => 'ABC123',
            'code_expires_at' => now()->addDays(7),
        ]);

        $caregiver = User::create([
            'name' => 'Caregiver One',
            'email' => 'caregiver-live@example.com',
            'password' => 'password123',
            'role' => 'caregiver',
            'pairing_code' => null,
            'code_expires_at' => null,
        ]);

        Pairing::create([
            'vi_user_id' => $patient->user_id,
            'caregiver_user_id' => $caregiver->user_id,
            'status' => 'active',
            'paired_at' => now(),
        ]);

        $session = NavigationSession::create([
            'user_id' => $patient->user_id,
            'origin' => 'Current Street, Davao City',
            'origin_latitude' => 7.0731,
            'origin_longitude' => 125.6115,
            'destination' => 'Peoples Park, Davao City',
            'destination_latitude' => 7.0707,
            'destination_longitude' => 125.6087,
            'current_latitude' => 7.0742,
            'current_longitude' => 125.6128,
            'location_updated_at' => now(),
            'start_time' => now(),
            'status' => 'ongoing',
        ]);

        $pageResponse = $this->actingAs($caregiver)->get('/caregiver/live-tracking');
        $pageResponse->assertOk();
        $pageResponse->assertSee('Patient live tracking');

        $apiResponse = $this->actingAs($caregiver)->getJson('/caregiver/live-tracking/session');
        $apiResponse->assertOk();
        $apiResponse->assertJsonPath('data.session.id', $session->id);
        $apiResponse->assertJsonPath('data.session.current_coordinates.lat', 7.0742);
    }
}
