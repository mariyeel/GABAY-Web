<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\Pairing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PatientDashboardCaregiverCountTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_dashboard_shows_connected_caregiver_count(): void
    {
        $patient = User::create([
            'name' => 'Patient One',
            'email' => 'patient@example.com',
            'password' => 'password123',
            'role' => 'vi',
            'pairing_code' => 'ABC123',
            'code_expires_at' => now()->addDays(7),
        ]);

        $caregiverOne = User::create([
            'name' => 'Caregiver One',
            'email' => 'caregiver1@example.com',
            'password' => 'password123',
            'role' => 'caregiver',
            'pairing_code' => null,
            'code_expires_at' => null,
        ]);

        $caregiverTwo = User::create([
            'name' => 'Caregiver Two',
            'email' => 'caregiver2@example.com',
            'password' => 'password123',
            'role' => 'caregiver',
            'pairing_code' => null,
            'code_expires_at' => null,
        ]);

        $inactiveCaregiver = User::create([
            'name' => 'Caregiver Three',
            'email' => 'caregiver3@example.com',
            'password' => 'password123',
            'role' => 'caregiver',
            'pairing_code' => null,
            'code_expires_at' => null,
        ]);

        Pairing::create([
            'vi_user_id' => $patient->user_id,
            'caregiver_user_id' => $caregiverOne->user_id,
            'status' => 'active',
            'paired_at' => now(),
        ]);

        Pairing::create([
            'vi_user_id' => $patient->user_id,
            'caregiver_user_id' => $caregiverTwo->user_id,
            'status' => 'active',
            'paired_at' => now(),
        ]);

        Pairing::create([
            'vi_user_id' => $patient->user_id,
            'caregiver_user_id' => $inactiveCaregiver->user_id,
            'status' => 'inactive',
            'paired_at' => now()->subDay(),
            'unpaired_at' => now(),
        ]);

        $this->actingAs($patient);

        $response = app(UserController::class)->patientDashboard(Request::create('/patient/dashboard', 'GET'));

        $this->assertSame('patient.patient_dashboard.main_dashboard', $response->name());
        $this->assertSame(2, $response->getData()['connectedCaregiverCount']);
    }
}
