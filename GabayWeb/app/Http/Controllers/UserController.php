<?php

namespace App\Http\Controllers;

use App\Models\NavigationSession;
use App\Models\Pairing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function landing()
    {
        return view('landing_page');
    }

    public function create()
    {
        return view('signup_page');
    }

    public function login()
    {
        return view('login_page');
    }

    public function caregiverDashboard()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.create')
                ->withErrors(['login' => 'Please log in to access the caregiver dashboard.']);
        }

        if ($user->role !== 'caregiver') {
            return $this->redirectToDashboard($user);
        }

        return view('caregiver.caregiver_dashboard.main_dashboard', [
            'caregiver' => $user,
            'connectedPatient' => $this->getActivePatientForCaregiver($user),
        ]);
    }

    public function connectPatient(Request $request)
    {
        /** @var \App\Models\User|null $caregiver */
        $caregiver = Auth::user();

        if (!$caregiver) {
            return redirect()->route('login.create')
                ->withErrors(['login' => 'Please log in to connect to a patient account.']);
        }

        if ($caregiver->role !== 'caregiver') {
            return $this->redirectToDashboard($caregiver);
        }

        $validated = $request->validate([
            'pairing_code' => ['required', 'string', 'size:6'],
        ]);

        $pairingCode = Str::upper(trim($validated['pairing_code']));

        $patient = User::query()
            ->where('role', 'vi')
            ->where('pairing_code', $pairingCode)
            ->where(function ($query) {
                $query->whereNull('code_expires_at')
                    ->orWhere('code_expires_at', '>', now());
            })
            ->first();

        if (!$patient) {
            return redirect()->route('dashboard.caregiver')
                ->withErrors(['pairing_code' => 'The pairing code is invalid or has already expired.'])
                ->withInput();
        }

        Pairing::where('caregiver_user_id', $caregiver->user_id)
            ->where('status', 'active')
            ->update([
                'status' => 'inactive',
                'unpaired_at' => now(),
            ]);

        $pairing = Pairing::query()
            ->where('caregiver_user_id', $caregiver->user_id)
            ->where('vi_user_id', $patient->user_id)
            ->first();

        if ($pairing) {
            $pairing->update([
                'status' => 'active',
                'paired_at' => now(),
                'unpaired_at' => null,
            ]);
        } else {
            Pairing::create([
                'vi_user_id' => $patient->user_id,
                'caregiver_user_id' => $caregiver->user_id,
                'status' => 'active',
                'paired_at' => now(),
            ]);
        }

        return redirect()->route('dashboard.caregiver')
            ->with('status', 'Connected to patient: ' . $patient->name);
    }

    public function patientDashboard(Request $request)
    {
        /** @var \App\Models\User|null $patient */
        $patient = Auth::user();

        if (!$patient || $patient->role !== 'vi') {
            if ($patient) {
                return $this->redirectToDashboard($patient);
            }

            return redirect()->route('login.create')
                ->withErrors(['login' => 'Please log in as a navigator user first to view the patient dashboard.']);
        }

        return view('patient.patient_dashboard.main_dashboard', [
            'patient' => $patient,
            'connectedCaregiverCount' => Pairing::query()
                ->where('vi_user_id', $patient->user_id)
                ->where('status', 'active')
                ->count(),
        ]);
    }

    public function patientNavigation()
    {
        /** @var \App\Models\User|null $patient */
        $patient = Auth::user();

        if (!$patient || $patient->role !== 'vi') {
            if ($patient) {
                return $this->redirectToDashboard($patient);
            }

            return redirect()->route('login.create')
                ->withErrors(['login' => 'Please log in as a navigator user first to view the navigation page.']);
        }

        return view('patient.navigation.navigation_page', [
            'patient' => $patient,
        ]);
    }

    public function patientHistory()
    {
        /** @var \App\Models\User|null $patient */
        $patient = Auth::user();

        if (!$patient || $patient->role !== 'vi') {
            if ($patient) {
                return $this->redirectToDashboard($patient);
            }

            return redirect()->route('login.create')
                ->withErrors(['login' => 'Please log in as a navigator user first to view the history page.']);
        }

        return view('patient.history.history_page', [
            'patient' => $patient,
            'sessions' => NavigationSession::query()
                ->where('user_id', $patient->user_id)
                ->latest('start_time')
                ->latest('id')
                ->get(),
        ]);
    }

    public function startNavigationSession(Request $request): JsonResponse
    {
        /** @var \App\Models\User|null $patient */
        $patient = Auth::user();

        if (!$patient || $patient->role !== 'vi') {
            return response()->json([
                'message' => 'Only patient accounts can start a navigation session.',
            ], 403);
        }

        $validated = $request->validate([
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
        ]);

        NavigationSession::query()
            ->where('user_id', $patient->user_id)
            ->where('status', 'ongoing')
            ->update([
                'status' => 'interrupted',
                'end_time' => now(),
            ]);

        $session = NavigationSession::create([
            'user_id' => $patient->user_id,
            'origin' => $validated['origin'],
            'destination' => $validated['destination'],
            'start_time' => now(),
            'status' => 'ongoing',
        ]);

        return response()->json([
            'message' => 'Navigation session started.',
            'data' => [
                'id' => $session->id,
                'status' => $session->status,
            ],
        ]);
    }

    public function completeNavigationSession(Request $request, NavigationSession $navigationSession): JsonResponse
    {
        /** @var \App\Models\User|null $patient */
        $patient = Auth::user();

        if (
            !$patient ||
            $patient->role !== 'vi' ||
            (int) $navigationSession->user_id !== (int) $patient->user_id
        ) {
            return response()->json([
                'message' => 'You are not allowed to update this navigation session.',
            ], 403);
        }

        $validated = $request->validate([
            'status' => ['nullable', 'in:completed,interrupted'],
            'destination' => ['nullable', 'string', 'max:255'],
        ]);

        $navigationSession->update([
            'destination' => $validated['destination'] ?? $navigationSession->destination,
            'status' => $validated['status'] ?? 'completed',
            'end_time' => now(),
        ]);

        return response()->json([
            'message' => 'Navigation session updated.',
            'data' => [
                'id' => $navigationSession->id,
                'status' => $navigationSession->status,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', 'in:vi,caregiver'],
            'terms' => ['accepted'],
        ]);

        $pairingCode = $validated['role'] === 'vi'
            ? $this->generatePairingCode()
            : null;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'pairing_code' => $pairingCode,
            'code_expires_at' => $pairingCode ? now()->addDays(7) : null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectToDashboard($user);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['login' => 'The provided email or password is incorrect.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $this->redirectToDashboard($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    private function generatePairingCode(): string
    {
        do {
            $code = Str::upper(Str::random(6));
        } while (User::where('pairing_code', $code)->exists());

        return $code;
    }

    private function redirectToDashboard(User $user)
    {
        return redirect()->route(
            $user->role === 'caregiver' ? 'dashboard.caregiver' : 'dashboard.patient'
        );
    }

    private function getActivePatientForCaregiver(User $caregiver): ?User
    {
        $pairing = Pairing::query()
            ->with('viUser')
            ->where('caregiver_user_id', $caregiver->user_id)
            ->where('status', 'active')
            ->latest('paired_at')
            ->latest('id')
            ->first();

        return $pairing?->viUser;
    }
}
