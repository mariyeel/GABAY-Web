<?php

namespace Tests\Feature;

use App\Models\NavigationSession;
use App\Models\Pairing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_caregiver_can_start_navigation_session_for_connected_patient(): void
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
        ]);

        Pairing::create([
            'vi_user_id' => $patient->user_id,
            'caregiver_user_id' => $caregiver->user_id,
            'status' => 'active',
            'paired_at' => now(),
        ]);

        $response = $this->actingAs($caregiver)->postJson('/caregiver/navigation/session', [
            'destination' => 'Peoples Park, Davao City',
            'destination_latitude' => 7.0707,
            'destination_longitude' => 125.6087,
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.status', 'ongoing');

        $this->assertDatabaseHas('navigation_sessions', [
            'user_id' => $patient->user_id,
            'caregiver_user_id' => $caregiver->user_id,
            'origin' => 'Waiting for patient current location',
            'destination' => 'Peoples Park, Davao City',
            'status' => 'ongoing',
        ]);
    }

    public function test_patient_can_view_caregiver_assigned_navigation_session(): void
    {
        $patient = User::create([
            'name' => 'Patient One',
            'email' => 'patient-assigned@example.com',
            'password' => 'password123',
            'role' => 'vi',
            'pairing_code' => 'ABC123',
            'code_expires_at' => now()->addDays(7),
        ]);

        $session = NavigationSession::create([
            'user_id' => $patient->user_id,
            'origin' => 'Waiting for patient current location',
            'destination' => 'Peoples Park, Davao City',
            'destination_latitude' => 7.0707,
            'destination_longitude' => 125.6087,
            'start_time' => now(),
            'status' => 'ongoing',
        ]);

        $response = $this->actingAs($patient)->getJson('/patient/navigation/assigned-session');

        $response->assertOk();
        $response->assertJsonPath('data.session.id', $session->id);
        $response->assertJsonPath('data.session.destination', 'Peoples Park, Davao City');
        $response->assertJsonPath('data.session.destination_coordinates.lat', 7.0707);
    }

    public function test_patient_cannot_create_their_own_navigation_destination(): void
    {
        $patient = User::create([
            'name' => 'Patient One',
            'email' => 'patient-blocked@example.com',
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

        $response->assertForbidden();
        $this->assertDatabaseCount('navigation_sessions', 0);
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
