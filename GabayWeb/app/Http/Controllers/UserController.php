<?php

namespace App\Http\Controllers;

use App\Models\NavigationSession;
use App\Models\Pairing;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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

        $this->syncActivePairingToFirebase($patient, $caregiver);

        return redirect()->route('dashboard.caregiver')
            ->with('status', 'Connected to patient: ' . $patient->name);
    }

    public function caregiverLiveTracking()
    {
        /** @var \App\Models\User|null $caregiver */
        $caregiver = Auth::user();

        if (!$caregiver) {
            return redirect()->route('login.create')
                ->withErrors(['login' => 'Please log in to access live tracking.']);
        }

        if ($caregiver->role !== 'caregiver') {
            return $this->redirectToDashboard($caregiver);
        }

        $connectedPatient = $this->getActivePatientForCaregiver($caregiver);

        $latestSession = $connectedPatient
            ? $this->getLatestNavigationSessionForPatient($connectedPatient)
            : null;

        return view('caregiver.live_tracking.live_tracking', [
            'caregiver' => $caregiver,
            'connectedPatient' => $connectedPatient,
            'initialTrackingData' => $latestSession ? [
                'patient' => [
                    'id' => $connectedPatient->user_id,
                    'name' => $connectedPatient->name,
                ],
                'session' => $this->formatNavigationSessionForTracking($latestSession),
            ] : null,
        ]);
    }

    public function caregiverNavigation()
    {
        /** @var \App\Models\User|null $caregiver */
        $caregiver = Auth::user();

        if (!$caregiver) {
            return redirect()->route('login.create')
                ->withErrors(['login' => 'Please log in to set a patient destination.']);
        }

        if ($caregiver->role !== 'caregiver') {
            return $this->redirectToDashboard($caregiver);
        }

        $connectedPatient = $this->getActivePatientForCaregiver($caregiver);
        $latestSession = $connectedPatient
            ? $this->getLatestNavigationSessionForPatient($connectedPatient)
            : null;

        return view('caregiver.navigation.navigation_page', [
            'caregiver' => $caregiver,
            'connectedPatient' => $connectedPatient,
            'initialNavigationSession' => $latestSession ? $this->formatNavigationSessionForTracking($latestSession) : null,
        ]);
    }

    public function caregiverStartNavigationSession(Request $request): JsonResponse
    {
        /** @var \App\Models\User|null $caregiver */
        $caregiver = Auth::user();

        if (!$caregiver || $caregiver->role !== 'caregiver') {
            return response()->json([
                'message' => 'Only caregiver accounts can set a patient destination.',
            ], 403);
        }

        $patient = $this->getActivePatientForCaregiver($caregiver);

        if (!$patient) {
            return response()->json([
                'message' => 'Connect to a patient first before setting a destination.',
            ], 404);
        }

        $validated = $request->validate([
            'origin' => ['nullable', 'string', 'max:255'],
            'origin_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'origin_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'destination' => ['required', 'string', 'max:255'],
            'destination_latitude' => ['required', 'numeric', 'between:-90,90'],
            'destination_longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $latestSession = $this->getLatestNavigationSessionForPatient($patient);
        $originLatitude = $validated['origin_latitude'] ?? $latestSession?->current_latitude;
        $originLongitude = $validated['origin_longitude'] ?? $latestSession?->current_longitude;
        $origin = $validated['origin']
            ?? ($originLatitude !== null && $originLongitude !== null
                ? 'Latest patient location'
                : 'Waiting for patient current location');

        NavigationSession::query()
            ->where('user_id', $patient->user_id)
            ->where('status', 'ongoing')
            ->update([
                'status' => 'interrupted',
                'end_time' => now(),
            ]);

        $session = NavigationSession::create([
            'user_id' => $patient->user_id,
            'caregiver_user_id' => $caregiver->user_id,
            'origin' => $origin,
            'origin_latitude' => $originLatitude,
            'origin_longitude' => $originLongitude,
            'destination' => $validated['destination'],
            'destination_latitude' => $validated['destination_latitude'],
            'destination_longitude' => $validated['destination_longitude'],
            'current_latitude' => $originLatitude,
            'current_longitude' => $originLongitude,
            'location_updated_at' => $originLatitude !== null && $originLongitude !== null ? now() : null,
            'start_time' => now(),
            'status' => 'ongoing',
        ]);

        return response()->json([
            'message' => 'Patient destination saved.',
            'data' => $this->formatNavigationSessionForTracking($session),
        ]);
    }

    public function caregiverLiveTrackingSession(): JsonResponse
    {
        /** @var \App\Models\User|null $caregiver */
        $caregiver = Auth::user();

        if (!$caregiver || $caregiver->role !== 'caregiver') {
            return response()->json([
                'message' => 'Only caregiver accounts can view patient live tracking.',
            ], 403);
        }

        $patient = $this->getActivePatientForCaregiver($caregiver);

        if (!$patient) {
            return response()->json([
                'message' => 'Connect to a patient first to view live tracking.',
                'data' => null,
            ], 404);
        }

        $session = $this->getLatestNavigationSessionForPatient($patient);

        if (!$session) {
            return response()->json([
                'message' => 'No navigation session has been started by this patient yet.',
                'data' => [
                    'patient' => [
                        'id' => $patient->user_id,
                        'name' => $patient->name,
                    ],
                    'session' => null,
                ],
            ], 404);
        }

        return response()->json([
            'data' => [
                'patient' => [
                    'id' => $patient->user_id,
                    'name' => $patient->name,
                ],
                'session' => $this->formatNavigationSessionForTracking($session),
            ],
        ]);
    }

    public function caregiverLiveTrackingFirebaseAuth(): JsonResponse
    {
        /** @var \App\Models\User|null $caregiver */
        $caregiver = Auth::user();

        if (!$caregiver || $caregiver->role !== 'caregiver') {
            return response()->json([
                'message' => 'Only caregiver accounts can view patient live tracking.',
            ], 403);
        }

        $patient = $this->getActivePatientForCaregiver($caregiver);

        if (!$patient) {
            return response()->json([
                'message' => 'Connect to a patient first to view live tracking.',
            ], 404);
        }

        return $this->firebaseAuthResponse(
            'caregiver:' . $caregiver->user_id,
            [
                'role' => 'caregiver',
                'caregiver_user_id' => (string) $caregiver->user_id,
                'paired_vi_users' => [(string) $patient->user_id => true],
            ],
            'live_locations/' . $patient->user_id
        );
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

        $connectedCaregivers = Pairing::query()
            ->with('caregiver')
            ->where('vi_user_id', $patient->user_id)
            ->where('status', 'active')
            ->latest('paired_at')
            ->latest('id')
            ->get()
            ->pluck('caregiver')
            ->filter()
            ->values();

        return view('patient.patient_dashboard.main_dashboard', [
            'patient' => $patient,
            'connectedCaregiverCount' => $connectedCaregivers->count(),
            'connectedCaregivers' => $connectedCaregivers,
            'isPairingCodeValid' => !empty($patient->pairing_code)
                && (!$patient->code_expires_at || $patient->code_expires_at->isFuture()),
            'recentSessions' => NavigationSession::query()
                ->where('user_id', $patient->user_id)
                ->latest('start_time')
                ->latest('id')
                ->limit(3)
                ->get(),
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
            'initialAssignedSession' => ($session = NavigationSession::query()
                ->where('user_id', $patient->user_id)
                ->where('status', 'ongoing')
                ->latest('start_time')
                ->latest('id')
                ->first()) ? $this->formatNavigationSessionForTracking($session) : null,
        ]);
    }

    public function patientLiveLocationFirebaseAuth(): JsonResponse
    {
        /** @var \App\Models\User|null $patient */
        $patient = Auth::user();

        if (!$patient || $patient->role !== 'vi') {
            return response()->json([
                'message' => 'Only patient accounts can share live location.',
            ], 403);
        }

        return $this->firebaseAuthResponse(
            'vi:' . $patient->user_id,
            [
                'role' => 'vi',
                'vi_user_id' => (string) $patient->user_id,
            ],
            'live_locations/' . $patient->user_id
        );
    }

    public function patientAssignedNavigationSession(): JsonResponse
    {
        /** @var \App\Models\User|null $patient */
        $patient = Auth::user();

        if (!$patient || $patient->role !== 'vi') {
            return response()->json([
                'message' => 'Only patient accounts can view assigned navigation sessions.',
            ], 403);
        }

        $session = NavigationSession::query()
            ->where('user_id', $patient->user_id)
            ->where('status', 'ongoing')
            ->latest('start_time')
            ->latest('id')
            ->first();

        return response()->json([
            'data' => [
                'session' => $session ? $this->formatNavigationSessionForTracking($session) : null,
            ],
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
        return response()->json([
            'message' => 'Navigation destinations are assigned by the connected caregiver.',
        ], 403);
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
            'current_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'current_longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $navigationSession->update([
            'current_latitude' => $validated['current_latitude'] ?? $navigationSession->current_latitude,
            'current_longitude' => $validated['current_longitude'] ?? $navigationSession->current_longitude,
            'location_updated_at' => isset($validated['current_latitude'], $validated['current_longitude'])
                ? now()
                : $navigationSession->location_updated_at,
            'status' => $validated['status'] ?? 'completed',
            'end_time' => now(),
        ]);

        if (isset($validated['current_latitude'], $validated['current_longitude'])) {
            $this->writeLiveLocationToFirebase($patient, [
                'latitude' => (float) $validated['current_latitude'],
                'longitude' => (float) $validated['current_longitude'],
                'accuracy' => null,
                'navigation_session_id' => $navigationSession->id,
                'session_status' => $navigationSession->status,
                'connection_state' => 'offline',
                'source' => 'laravel-session-end',
            ]);
        }

        return response()->json([
            'message' => 'Navigation session updated.',
            'data' => [
                'id' => $navigationSession->id,
                'status' => $navigationSession->status,
            ],
        ]);
    }

    public function updateNavigationSessionLocation(Request $request, NavigationSession $navigationSession): JsonResponse
    {
        /** @var \App\Models\User|null $patient */
        $patient = Auth::user();

        if (
            !$patient ||
            $patient->role !== 'vi' ||
            (int) $navigationSession->user_id !== (int) $patient->user_id
        ) {
            return response()->json([
                'message' => 'You are not allowed to update this navigation session location.',
            ], 403);
        }

        $validated = $request->validate([
            'current_latitude' => ['required', 'numeric', 'between:-90,90'],
            'current_longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($navigationSession->status !== 'ongoing') {
            return response()->json([
                'message' => 'Only ongoing navigation sessions can receive live location updates.',
            ], 409);
        }

        $navigationSession->update([
            'current_latitude' => $validated['current_latitude'],
            'current_longitude' => $validated['current_longitude'],
            'location_updated_at' => now(),
        ]);

        $this->writeLiveLocationToFirebase($patient, [
            'latitude' => (float) $validated['current_latitude'],
            'longitude' => (float) $validated['current_longitude'],
            'accuracy' => isset($validated['accuracy']) ? (float) $validated['accuracy'] : null,
            'navigation_session_id' => $navigationSession->id,
            'session_status' => $navigationSession->status,
            'connection_state' => 'online',
            'source' => 'laravel-session-location',
        ]);

        return response()->json([
            'message' => 'Live location updated.',
            'data' => [
                'id' => $navigationSession->id,
                'location_updated_at' => $navigationSession->location_updated_at?->toIso8601String(),
            ],
        ]);
    }

    public function mapboxReverseGeocode(Request $request): JsonResponse
    {
        if ($response = $this->requirePatientNavigationAccess()) {
            return $response;
        }

        $validated = $request->validate([
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
        ]);

        return $this->sendMapboxRequest(
            "https://api.mapbox.com/geocoding/v5/mapbox.places/{$validated['lng']},{$validated['lat']}.json",
            ['limit' => 1]
        );
    }

    public function mapboxSearch(Request $request): JsonResponse
    {
        if ($response = $this->requirePatientNavigationAccess()) {
            return $response;
        }

        $validated = $request->validate([
            'q' => ['required', 'string', 'max:255'],
            'proximity_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'proximity_lat' => ['nullable', 'numeric', 'between:-90,90'],
        ]);

        $params = [
            'limit' => 5,
            'autocomplete' => 'true',
            'language' => 'en',
            'country' => 'PH',
            'types' => 'poi,address,place,locality,neighborhood',
        ];

        if (isset($validated['proximity_lng'], $validated['proximity_lat'])) {
            $params['proximity'] = $validated['proximity_lng'] . ',' . $validated['proximity_lat'];
        }

        return $this->sendMapboxRequest(
            'https://api.mapbox.com/geocoding/v5/mapbox.places/' . rawurlencode($validated['q']) . '.json',
            $params
        );
    }

    public function mapboxDirections(Request $request): JsonResponse
    {
        if ($response = $this->requirePatientNavigationAccess()) {
            return $response;
        }

        $validated = $request->validate([
            'profile' => ['required', 'in:walking,driving'],
            'start_lng' => ['required', 'numeric', 'between:-180,180'],
            'start_lat' => ['required', 'numeric', 'between:-90,90'],
            'end_lng' => ['required', 'numeric', 'between:-180,180'],
            'end_lat' => ['required', 'numeric', 'between:-90,90'],
        ]);

        $coordinates = implode(',', [$validated['start_lng'], $validated['start_lat']])
            . ';'
            . implode(',', [$validated['end_lng'], $validated['end_lat']]);

        return $this->sendMapboxRequest(
            "https://api.mapbox.com/directions/v5/mapbox/{$validated['profile']}/{$coordinates}",
            [
                'geometries' => 'geojson',
                'overview' => 'full',
                'steps' => 'true',
                'language' => 'en',
            ]
        );
    }

    public function caregiverMapboxDirections(Request $request): JsonResponse
    {
        if ($response = $this->requireCaregiverNavigationAccess()) {
            return $response;
        }

        $validated = $request->validate([
            'profile' => ['required', 'in:walking,driving'],
            'start_lng' => ['required', 'numeric', 'between:-180,180'],
            'start_lat' => ['required', 'numeric', 'between:-90,90'],
            'end_lng' => ['required', 'numeric', 'between:-180,180'],
            'end_lat' => ['required', 'numeric', 'between:-90,90'],
        ]);

        $coordinates = implode(',', [$validated['start_lng'], $validated['start_lat']])
            . ';'
            . implode(',', [$validated['end_lng'], $validated['end_lat']]);

        return $this->sendMapboxRequest(
            "https://api.mapbox.com/directions/v5/mapbox/{$validated['profile']}/{$coordinates}",
            [
                'geometries' => 'geojson',
                'overview' => 'full',
                'steps' => 'false',
                'language' => 'en',
            ]
        );
    }

    public function caregiverMapboxReverseGeocode(Request $request): JsonResponse
    {
        if ($response = $this->requireCaregiverNavigationAccess()) {
            return $response;
        }

        $validated = $request->validate([
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
        ]);

        return $this->sendMapboxRequest(
            "https://api.mapbox.com/geocoding/v5/mapbox.places/{$validated['lng']},{$validated['lat']}.json",
            ['limit' => 1]
        );
    }

    public function caregiverMapboxSearch(Request $request): JsonResponse
    {
        if ($response = $this->requireCaregiverNavigationAccess()) {
            return $response;
        }

        $validated = $request->validate([
            'q' => ['required', 'string', 'max:255'],
            'proximity_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'proximity_lat' => ['nullable', 'numeric', 'between:-90,90'],
        ]);

        $params = [
            'limit' => 5,
            'autocomplete' => 'true',
            'language' => 'en',
            'country' => 'PH',
            'types' => 'poi,address,place,locality,neighborhood',
        ];

        if (isset($validated['proximity_lng'], $validated['proximity_lat'])) {
            $params['proximity'] = $validated['proximity_lng'] . ',' . $validated['proximity_lat'];
        }

        return $this->sendMapboxRequest(
            'https://api.mapbox.com/geocoding/v5/mapbox.places/' . rawurlencode($validated['q']) . '.json',
            $params
        );
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

    private function getLatestNavigationSessionForPatient(User $patient): ?NavigationSession
    {
        return NavigationSession::query()
            ->where('user_id', $patient->user_id)
            ->latest('start_time')
            ->latest('id')
            ->first();
    }

    private function formatNavigationSessionForTracking(NavigationSession $session): array
    {
        return [
            'id' => $session->id,
            'origin' => $session->origin,
            'destination' => $session->destination,
            'status' => $session->status,
            'start_time' => $session->start_time?->toIso8601String(),
            'end_time' => $session->end_time?->toIso8601String(),
            'location_updated_at' => $session->location_updated_at?->toIso8601String(),
            'origin_coordinates' => $this->coordinatesPayload($session->origin_latitude, $session->origin_longitude),
            'destination_coordinates' => $this->coordinatesPayload($session->destination_latitude, $session->destination_longitude),
            'current_coordinates' => $this->coordinatesPayload($session->current_latitude, $session->current_longitude),
        ];
    }

    private function coordinatesPayload(?float $latitude, ?float $longitude): ?array
    {
        if ($latitude === null || $longitude === null) {
            return null;
        }

        return [
            'lat' => $latitude,
            'lng' => $longitude,
        ];
    }

    private function firebaseAuthResponse(string $uid, array $claims, string $liveLocationPath): JsonResponse
    {
        $config = $this->firebaseBrowserConfig();

        if (!$this->hasFirebaseBrowserConfig($config)) {
            return response()->json([
                'message' => 'Firebase browser configuration is incomplete. Set FIREBASE_API_KEY and FIREBASE_DATABASE_URL.',
            ], 503);
        }

        try {
            /** @var FirebaseService $firebase */
            $firebase = app(FirebaseService::class);

            return response()->json([
                'data' => [
                    'config' => $config,
                    'token' => $firebase->createCustomToken($uid, $claims),
                    'live_location_path' => $liveLocationPath,
                    'bus_tracker_path' => $this->busTrackerPath(),
                ],
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Unable to create Firebase live tracking token.', [
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Firebase live tracking is not available right now.',
            ], 503);
        }
    }

    private function firebaseBrowserConfig(): array
    {
        return [
            'apiKey' => config('services.firebase.api_key'),
            'authDomain' => config('services.firebase.auth_domain'),
            'databaseURL' => config('services.firebase.database_url'),
            'projectId' => config('services.firebase.project_id'),
            'appId' => config('services.firebase.app_id'),
        ];
    }

    private function hasFirebaseBrowserConfig(array $config): bool
    {
        return is_string($config['apiKey'] ?? null)
            && trim($config['apiKey']) !== ''
            && is_string($config['databaseURL'] ?? null)
            && trim($config['databaseURL']) !== '';
    }

    private function busTrackerPath(): string
    {
        $deviceId = config('services.firebase.bus_tracker_device_id', 'BUS_001');
        $deviceId = is_string($deviceId) && trim($deviceId) !== '' ? trim($deviceId) : 'BUS_001';

        return 'BusTracker/' . $deviceId;
    }

    private function writeLiveLocationToFirebase(User $patient, array $payload): void
    {
        if (!$this->shouldUseFirebaseAdmin()) {
            return;
        }

        try {
            /** @var FirebaseService $firebase */
            $firebase = app(FirebaseService::class);
            $firebase->getDatabase()
                ->getReference('live_locations/' . $patient->user_id)
                ->update(array_merge($payload, [
                    'vi_user_id' => (string) $patient->user_id,
                    'updated_at' => now()->toIso8601String(),
                    'updated_at_ms' => now()->valueOf(),
                ]));
        } catch (\Throwable $exception) {
            Log::warning('Unable to write live location to Firebase.', [
                'patient_id' => $patient->user_id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function syncActivePairingToFirebase(User $patient, User $caregiver): void
    {
        if (!$this->shouldUseFirebaseAdmin()) {
            return;
        }

        try {
            /** @var FirebaseService $firebase */
            $firebase = app(FirebaseService::class);
            $firebase->getDatabase()
                ->getReference('pairings/' . $patient->user_id . '/' . $caregiver->user_id)
                ->set([
                    'active' => true,
                    'paired_at' => now()->toIso8601String(),
                ]);
        } catch (\Throwable $exception) {
            Log::warning('Unable to sync pairing to Firebase.', [
                'patient_id' => $patient->user_id,
                'caregiver_id' => $caregiver->user_id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function shouldUseFirebaseAdmin(): bool
    {
        if (app()->environment('testing')) {
            return false;
        }

        $credentials = config('services.firebase.credentials');
        $credentialsJson = config('services.firebase.credentials_json');
        $databaseUrl = config('services.firebase.database_url');

        $hasCredentials = (is_string($credentials) && trim($credentials) !== '')
            || (is_string($credentialsJson) && trim($credentialsJson) !== '');

        return $hasCredentials
            && is_string($databaseUrl)
            && trim($databaseUrl) !== '';
    }

    private function requirePatientNavigationAccess(): ?JsonResponse
    {
        /** @var \App\Models\User|null $patient */
        $patient = Auth::user();

        if ($patient && $patient->role === 'vi') {
            return null;
        }

        return response()->json([
            'message' => 'Please log in as a navigator user first to use navigation.',
        ], 403);
    }

    private function requireCaregiverNavigationAccess(): ?JsonResponse
    {
        /** @var \App\Models\User|null $caregiver */
        $caregiver = Auth::user();

        if ($caregiver && $caregiver->role === 'caregiver' && $this->getActivePatientForCaregiver($caregiver)) {
            return null;
        }

        return response()->json([
            'message' => 'Connect to a patient first to use caregiver navigation.',
        ], 403);
    }

    private function sendMapboxRequest(string $url, array $params): JsonResponse
    {
        $token = config('services.mapbox.token');

        if (!is_string($token) || trim($token) === '') {
            return response()->json([
                'message' => 'Mapbox is not configured. Please add MAPBOX_TOKEN to Railway variables.',
            ], 500);
        }

        try {
            $mapboxResponse = Http::acceptJson()
                ->withOptions(['proxy' => ''])
                ->timeout(15)
                ->get($url, array_merge($params, [
                    'access_token' => trim($token),
                ]));
        } catch (\Throwable) {
            return response()->json([
                'message' => 'Unable to reach Mapbox right now. Please check the server network and try again.',
            ], 502);
        }

        $data = $mapboxResponse->json();

        if (!$mapboxResponse->ok()) {
            return response()->json([
                'message' => $data['message'] ?? $data['code'] ?? 'Mapbox could not complete the navigation request.',
            ], $mapboxResponse->status());
        }

        return response()->json($data);
    }
}
