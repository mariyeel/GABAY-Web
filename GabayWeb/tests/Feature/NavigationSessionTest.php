<?php

namespace Tests\Feature;

use App\Models\NavigationSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_start_navigation_session(): void
    {
        $patient = User::create([
            'name' => 'Patient One',
            'email' => 'patient@example.com',
            'password' => 'password123',
            'role' => 'vi',
            'pairing_code' => 'ABC123',
            'code_expires_at' => now()->addDays(7),
        ]);

        $response = $this->actingAs($patient)->postJson('/patient/navigation/session', [
            'origin' => 'Current Street, Davao City',
            'origin_latitude' => 7.0731,
            'origin_longitude' => 125.6115,
            'destination' => 'Peoples Park, Davao City',
            'destination_latitude' => 7.0707,
            'destination_longitude' => 125.6087,
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.status', 'ongoing');

        $this->assertDatabaseHas('navigation_sessions', [
            'user_id' => $patient->user_id,
            'origin' => 'Current Street, Davao City',
            'destination' => 'Peoples Park, Davao City',
            'current_latitude' => 7.0731,
            'current_longitude' => 125.6115,
            'status' => 'ongoing',
        ]);
    }

    public function test_patient_can_update_navigation_session_location(): void
    {
        $patient = User::create([
            'name' => 'Patient One',
            'email' => 'patient-location@example.com',
            'password' => 'password123',
            'role' => 'vi',
            'pairing_code' => 'ABC123',
            'code_expires_at' => now()->addDays(7),
        ]);

        $session = NavigationSession::create([
            'user_id' => $patient->user_id,
            'origin' => 'Current Street, Davao City',
            'destination' => 'Peoples Park, Davao City',
            'start_time' => now(),
            'status' => 'ongoing',
        ]);

        $response = $this->actingAs($patient)->patchJson("/patient/navigation/session/{$session->id}/location", [
            'current_latitude' => 7.0742,
            'current_longitude' => 125.6128,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('navigation_sessions', [
            'id' => $session->id,
            'current_latitude' => 7.0742,
            'current_longitude' => 125.6128,
        ]);
    }

    public function test_patient_can_complete_navigation_session(): void
    {
        $patient = User::create([
            'name' => 'Patient One',
            'email' => 'patient@example.com',
            'password' => 'password123',
            'role' => 'vi',
            'pairing_code' => 'ABC123',
            'code_expires_at' => now()->addDays(7),
        ]);

        $session = NavigationSession::create([
            'user_id' => $patient->user_id,
            'origin' => 'Current Street, Davao City',
            'destination' => 'Peoples Park, Davao City',
            'start_time' => now()->subMinutes(10),
            'status' => 'ongoing',
        ]);

        $response = $this->actingAs($patient)->patchJson("/patient/navigation/session/{$session->id}", [
            'status' => 'completed',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.status', 'completed');

        $this->assertDatabaseHas('navigation_sessions', [
            'id' => $session->id,
            'status' => 'completed',
        ]);

        $this->assertNotNull($session->fresh()->end_time);
    }
}
