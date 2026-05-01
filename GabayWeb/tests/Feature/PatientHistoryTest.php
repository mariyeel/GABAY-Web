<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\NavigationSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PatientHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_history_shows_navigation_destinations(): void
    {
        $patient = User::create([
            'name' => 'Patient One',
            'email' => 'patient@example.com',
            'password' => 'password123',
            'role' => 'vi',
            'pairing_code' => 'ABC123',
            'code_expires_at' => now()->addDays(7),
        ]);

        NavigationSession::create([
            'user_id' => $patient->user_id,
            'origin' => 'Current Street, Davao City',
            'destination' => 'Peoples Park, Davao City',
            'start_time' => now()->subMinutes(20),
            'end_time' => now()->subMinutes(10),
            'status' => 'completed',
        ]);

        NavigationSession::create([
            'user_id' => $patient->user_id,
            'origin' => 'Current Street, Davao City',
            'destination' => 'Abreeza Mall, Davao City',
            'start_time' => now()->subMinutes(8),
            'status' => 'ongoing',
        ]);

        $this->actingAs($patient);

        $response = app(UserController::class)->patientHistory(Request::create('/patient/history', 'GET'));

        $this->assertSame('patient.history.history_page', $response->name());
        $this->assertCount(2, $response->getData()['sessions']);
        $this->assertSame('Abreeza Mall, Davao City', $response->getData()['sessions'][0]->destination);
        $this->assertSame('Peoples Park, Davao City', $response->getData()['sessions'][1]->destination);
    }
}
