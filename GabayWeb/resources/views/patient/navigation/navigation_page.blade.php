<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GABAY | Patient Navigation</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <style>
        :root {
            --bg-dark: #06111a;
            --sidebar-blue: #0b243b;
            --card-blue: rgba(13, 38, 64, 0.72);
            --accent-blue: #2196f3;
            --accent-cyan: #7dd3fc;
            --text-main: #ecfeff;
            --text-dim: #8fa0b5;
            --surface-border: rgba(255, 255, 255, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 280px;
            flex: 0 0 280px;
            height: 100vh;
            min-height: 0;
            background: linear-gradient(180deg, #0d2640 0%, #06111a 100%);
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ffffff;
        }

        .logo-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(33, 150, 243, 0.12);
            color: #7dd3fc;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid var(--accent-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .profile-name {
            font-weight: 600;
            color: #fff;
        }

        .profile-role {
            font-size: 0.7rem;
            color: var(--text-dim);
            margin-top: 4px;
        }

        .nav-menu {
            list-style: none;
            flex-grow: 1;
        }

        .nav-item {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-dim);
            transition: 0.3s;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 15px;
            color: inherit;
            text-decoration: none;
            width: 100%;
        }

        .nav-item.active {
            background: var(--accent-blue);
            color: white;
        }

        .main-content {
            flex-grow: 1;
            min-width: 0;
            height: 100vh;
            padding: 32px;
            overflow-y: auto;
        }

        .page-shell {
            height: calc(100vh - 64px);
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 22px;
        }

        .panel,
        .map-panel {
            background: var(--card-blue);
            backdrop-filter: blur(15px);
            border: 1px solid var(--surface-border);
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.24);
        }

        .panel {
            padding: 26px;
            overflow-y: auto;
        }

        .page-title {
            font-size: 1.9rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: var(--text-dim);
            line-height: 1.6;
            font-size: 0.92rem;
            margin-bottom: 26px;
        }

        .field-group {
            margin-bottom: 18px;
        }

        .field-group label {
            display: block;
            font-size: 0.84rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--accent-cyan);
        }

        .field-group input {
            width: 100%;
            padding: 13px 14px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(4, 18, 30, 0.75);
            color: #fff;
            outline: none;
        }

        .field-group input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .suggestions-box {
            margin-top: 10px;
            border-radius: 16px;
            background: rgba(4, 18, 30, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            display: none;
        }

        .suggestions-box.visible {
            display: block;
        }

        .suggestion-item {
            width: 100%;
            padding: 12px 14px;
            border: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            background: transparent;
            color: #fff;
            text-align: left;
            cursor: pointer;
        }

        .suggestion-item:last-child {
            border-bottom: 0;
        }

        .suggestion-item:hover {
            background: rgba(56, 189, 248, 0.1);
        }

        .suggestion-title {
            display: block;
            font-size: 0.92rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .suggestion-meta {
            display: block;
            color: var(--text-dim);
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: end;
        }

        .primary-btn,
        .secondary-btn,
        .zoom-btn {
            border: none;
            cursor: pointer;
            transition: 0.25s ease;
        }

        .primary-btn {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            background: linear-gradient(135deg, #1d4ed8, #38bdf8);
            color: #fff;
            font-weight: 600;
            margin-top: 6px;
        }

        .primary-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }

        .secondary-btn {
            padding: 13px 14px;
            border-radius: 14px;
            background: rgba(125, 211, 252, 0.12);
            color: var(--accent-cyan);
            font-weight: 600;
            border: 1px solid rgba(125, 211, 252, 0.22);
        }

        .action-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 6px;
        }

        .start-btn {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            background: rgba(125, 211, 252, 0.12);
            color: var(--accent-cyan);
            font-weight: 600;
            border: 1px solid rgba(125, 211, 252, 0.22);
        }

        .start-btn:hover,
        .secondary-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }

        .stats-card {
            margin-top: 22px;
            padding: 18px;
            border-radius: 18px;
            background: rgba(4, 18, 30, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .stats-label {
            font-size: 0.8rem;
            color: var(--text-dim);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
        }

        .stats-meta {
            margin-top: 6px;
            color: var(--text-dim);
            line-height: 1.5;
            font-size: 0.9rem;
        }

        .directions-card {
            margin-top: 22px;
            padding: 18px;
            border-radius: 18px;
            background: rgba(4, 18, 30, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .directions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .directions-header h2 {
            font-size: 1rem;
            color: #fff;
        }

        .directions-summary {
            color: var(--text-dim);
            font-size: 0.84rem;
        }

        .directions-list {
            list-style: none;
            display: grid;
            gap: 12px;
            max-height: 260px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .direction-step {
            padding: 12px 14px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .direction-step strong {
            display: block;
            color: #fff;
            margin-bottom: 4px;
            font-size: 0.92rem;
        }

        .direction-step span {
            color: var(--text-dim);
            font-size: 0.84rem;
            line-height: 1.5;
        }

        .direction-step.active-step {
            border-color: rgba(56, 189, 248, 0.4);
            background: rgba(56, 189, 248, 0.12);
        }

        .tips-list {
            list-style: none;
            margin-top: 24px;
        }

        .tips-list li {
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            color: var(--text-dim);
            line-height: 1.55;
        }

        .tips-list li::before {
            content: '�';
            color: var(--accent-blue);
            margin-right: 10px;
        }

        .map-panel {
            position: relative;
            overflow: hidden;
            min-height: 640px;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        .map-overlay {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            z-index: 5;
            pointer-events: none;
        }

        .map-badge,
        .zoom-controls {
            pointer-events: auto;
        }

        .map-badge {
            padding: 12px 14px;
            border-radius: 16px;
            background: rgba(4, 18, 30, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.08);
            max-width: 320px;
        }

        .map-badge strong {
            display: block;
            margin-bottom: 4px;
            font-size: 0.95rem;
        }

        .map-badge span {
            color: var(--text-dim);
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .zoom-controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .zoom-btn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(4, 18, 30, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .zoom-btn:hover {
            background: rgba(29, 78, 216, 0.9);
        }

        .mapboxgl-popup-content {
            background: #082238;
            color: #fff;
            border-radius: 12px;
            padding: 12px 14px;
        }

        .mapboxgl-popup-tip {
            border-top-color: #082238 !important;
        }

        @media (max-width: 1100px) {
            .page-shell {
                grid-template-columns: 1fr;
                height: auto;
            }

            .map-panel {
                min-height: 520px;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
                overflow: auto;
            }

            .sidebar {
                width: 100%;
                flex: none;
                height: auto;
                padding: 20px;
            }

            .main-content {
                height: auto;
                overflow: visible;
                padding: 18px;
            }

            .page-shell {
                gap: 16px;
            }

            .field-row {
                grid-template-columns: 1fr;
            }

            .action-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    @php
        $nameParts = preg_split('/\s+/', trim($patient->name ?? 'Patient')) ?: [];
        $initials = collect($nameParts)
            ->filter()
            ->take(2)
            ->map(fn($part) => strtoupper(mb_substr($part, 0, 1)))
            ->join('');
        $initials = $initials !== '' ? $initials : 'P';
    @endphp

    <aside class="sidebar">
        <div class="logo">
            <span class="logo-mark">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.8" />
                    <circle cx="12" cy="12" r="3.2" fill="currentColor" stroke="none" />
                </svg>
            </span>
            <span>GABAY</span>
        </div>

        <div class="profile-section">
            <div class="avatar">{{ $initials }}</div>
            <p class="profile-name">{{ $patient->name }}</p>
            <p class="profile-role">Patient Account</p>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('dashboard.patient') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 13h6v7H4zM14 4h6v16h-6zM4 4h6v5H4zM14 15h6v5h-6z" fill="currentColor" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item active">
                <a href="{{ route('patient.navigation') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 21c4.97-4.54 8-8.15 8-11a8 8 0 1 0-16 0c0 2.85 3.03 6.46 8 11Z"
                            stroke="currentColor" stroke-width="1.8" />
                        <circle cx="12" cy="10" r="2.5" fill="currentColor" />
                    </svg>
                    <span>Navigation</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('patient.history') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path
                            d="M7 3v3M17 3v3M5 8h14M6 5h12a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>History</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8" />
                        <path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg>
                    <span>Profile</span>
                </a>
            </li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" style="margin-top:auto;">
            @csrf
            <button type="submit" class="nav-item" style="width:100%; background:none; border:none; text-align:left;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M15 17l5-5-5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M20 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    <path d="M11 20H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" />
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </aside>

    <main class="main-content">
        <div class="page-shell">
            <section class="panel">
                <h1 class="page-title">Navigation Setup</h1>

                <div class="field-group">
                    <label for="current-location">Current Location</label>
                    <div class="field-row">
                        <input id="current-location" type="text" placeholder="Detecting your current location..."
                            readonly>
                        <button type="button" class="secondary-btn" id="refresh-location">Refresh</button>
                    </div>
                </div>

                <div class="field-group">
                    <label for="destination">Destination</label>
                    <input id="destination" type="text" placeholder="Enter destination address or place name">
                    <div class="suggestions-box" id="destination-suggestions"></div>
                </div>

                <div class="action-row">
                    <button type="button" class="primary-btn" id="build-route">Show Route</button>
                    <button type="button" class="secondary-btn start-btn" id="start-navigation">Start</button>
                </div>

                <section class="stats-card">
                    <div class="stats-label">Total Distance</div>
                    <div class="stats-value" id="distance-display">0.00 km</div>
                    <div class="stats-meta" id="route-status">
                        Waiting for your current location and destination.
                    </div>
                </section>

                <section class="directions-card">
                    <div class="directions-header">
                        <h2>Directions</h2>
                        <span class="directions-summary" id="directions-summary">No active route yet.</span>
                    </div>
                    <ol class="directions-list" id="directions-list">
                        <li class="direction-step">
                            <strong>Waiting for destination</strong>
                            <span>Enter a destination name, use Show Route to preview distance, then press Start for
                                directions.</span>
                        </li>
                    </ol>
                </section>
            </section>

            <section class="map-panel">
                <div class="map-overlay">
                    <div class="map-badge">
                        <strong>Mapbox Navigation</strong>
                        <span id="map-status">Waiting for location permission and destination input.</span>
                    </div>

                    <div class="zoom-controls">
                        <button type="button" class="zoom-btn" id="zoom-in" aria-label="Zoom in">+</button>
                        <button type="button" class="zoom-btn" id="zoom-out" aria-label="Zoom out">-</button>
                    </div>
                </div>

                <div id="map"></div>
            </section>
        </div>
    </main>

    <script>
        const mapboxToken = @json(config('services.mapbox.token'));
        mapboxgl.accessToken = mapboxToken || '';
        const mapboxApiRoutes = {
            reverseGeocode: @json(route('patient.navigation.mapbox.reverse', [], false)),
            search: @json(route('patient.navigation.mapbox.search', [], false)),
            directions: @json(route('patient.navigation.mapbox.directions', [], false)),
        };

        const currentLocationInput = document.getElementById('current-location');
        const destinationInput = document.getElementById('destination');
        const distanceDisplay = document.getElementById('distance-display');
        const routeStatus = document.getElementById('route-status');
        const mapStatus = document.getElementById('map-status');
        const buildRouteButton = document.getElementById('build-route');
        const startNavigationButton = document.getElementById('start-navigation');
        const refreshLocationButton = document.getElementById('refresh-location');
        const directionsList = document.getElementById('directions-list');
        const directionsSummary = document.getElementById('directions-summary');
        const destinationSuggestions = document.getElementById('destination-suggestions');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const state = {
            currentCoordinates: null,
            currentPlaceLabel: '',
            currentMarker: null,
            destinationMarker: null,
            routeSourceLoaded: false,
            destinationFeature: null,
            syncingDestinationInput: false,
            previewRoute: null,
            previewProfile: null,
            activeRoute: null,
            activeProfile: null,
            activeSteps: [],
            currentStepIndex: -1,
            watchId: null,
            navigationStarted: false,
            hasAnnouncedArrival: false,
            suggestionResults: [],
            suggestionTimeoutId: null,
            navigationSessionId: null,
            lastLocationSyncAt: 0,
            liveSyncIntervalId: null,
            lastKnownAccuracy: null,
        };

        const NAVIGATION_MANEUVER_THRESHOLD_METERS = 30;
        const LOCATION_SYNC_INTERVAL_MS = 3000;
        const GEOLOCATION_OPTIONS = {
            enableHighAccuracy: true,
            timeout: 20000,
            maximumAge: 0,
        };

        if (!mapboxgl.accessToken) {
            setStatus('Mapbox is not configured. Please add MAPBOX_TOKEN to the server environment variables.');
            buildRouteButton.disabled = true;
            startNavigationButton.disabled = true;
            refreshLocationButton.disabled = true;
            throw new Error('MAPBOX_TOKEN is not configured.');
        }

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/navigation-night-v1',
            center: [125.6115, 7.0731],
            zoom: 13,
        });

        function setStatus(message) {
            routeStatus.textContent = message;
            mapStatus.textContent = message;
        }

        function formatAccuracy(accuracy) {
            return Number.isFinite(accuracy) ? `Accuracy about ${Math.round(accuracy)} m.` : 'Accuracy unavailable.';
        }

        function geolocationErrorMessage(error) {
            if (error?.message) {
                return error.message;
            }

            if (error?.code === 1) {
                return 'Location permission was denied. Allow location access on the patient phone.';
            }

            if (error?.code === 2) {
                return 'The phone could not determine its current location. Turn on GPS/location services and try again.';
            }

            if (error?.code === 3) {
                return 'Location detection timed out. Move near a window or open area, then refresh location.';
            }

            return 'Location is unavailable. Please check phone location settings.';
        }

        async function fetchNavigationJson(url) {
            let response;

            try {
                response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });
            } catch (error) {
                console.error(error);
                throw new Error(
                'Unable to contact the navigation service. Please check your connection and try again.');
            }

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(data.message || 'Unable to complete the navigation request right now.');
            }

            return data;
        }

        function setActionState(isLoading, trigger = 'preview') {
            buildRouteButton.disabled = isLoading;
            startNavigationButton.disabled = isLoading;

            if (!isLoading) {
                buildRouteButton.textContent = 'Show Route';
                startNavigationButton.textContent = 'Start';
                return;
            }

            if (trigger === 'start') {
                buildRouteButton.textContent = 'Show Route';
                startNavigationButton.textContent = 'Starting...';
            } else {
                buildRouteButton.textContent = 'Loading route...';
                startNavigationButton.textContent = 'Start';
            }
        }

        function updateDistance(kilometers) {
            distanceDisplay.textContent = `${kilometers.toFixed(2)} km`;
        }

        function formatDistance(meters) {
            if (meters >= 1000) {
                return `${(meters / 1000).toFixed(2)} km`;
            }

            return `${Math.round(meters)} m`;
        }

        function formatDuration(seconds) {
            const minutes = Math.max(1, Math.round(seconds / 60));

            if (minutes < 60) {
                return `${minutes} min`;
            }

            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;
            return remainingMinutes === 0 ? `${hours} hr` : `${hours} hr ${remainingMinutes} min`;
        }

        function resetDirections(message = 'No active route yet.') {
            directionsSummary.textContent = message;
            directionsList.innerHTML = `
                <li class="direction-step">
                    <strong>Waiting for destination</strong>
                    <span>Enter a destination name, use Show Route to preview the trip, then press Start for directions.</span>
                </li>
            `;
        }

        function hideSuggestions() {
            destinationSuggestions.classList.remove('visible');
            destinationSuggestions.innerHTML = '';
        }

        function renderSuggestions(features) {
            const sortedFeatures = [...features].sort((left, right) => {
                if (!state.currentCoordinates) {
                    return 0;
                }

                return getDistanceBetweenCoordsMeters(state.currentCoordinates, left.center) -
                    getDistanceBetweenCoordsMeters(state.currentCoordinates, right.center);
            });

            state.suggestionResults = sortedFeatures;

            if (!sortedFeatures.length) {
                hideSuggestions();
                return;
            }

            destinationSuggestions.innerHTML = sortedFeatures.map((feature, index) => {
                const distanceLabel = state.currentCoordinates ?
                    formatDistance(getDistanceBetweenCoordsMeters(state.currentCoordinates, feature.center)) :
                    'Nearby result';

                return `
                    <button type="button" class="suggestion-item" data-index="${index}">
                        <span class="suggestion-title">${feature.text || feature.place_name}</span>
                        <span class="suggestion-meta">${distanceLabel} - ${feature.place_name}</span>
                    </button>
                `;
            }).join('');

            destinationSuggestions.classList.add('visible');
        }

        function renderDirections(route, activeStepIndex = -1) {
            const steps = route.legs?.flatMap(leg => leg.steps || []) || [];

            if (!steps.length) {
                directionsSummary.textContent = 'Route found, but no step-by-step directions were returned.';
                directionsList.innerHTML = `
                    <li class="direction-step">
                        <strong>Route ready</strong>
                        <span>The map route is available, but step instructions could not be loaded.</span>
                    </li>
                `;
                return;
            }

            directionsSummary.textContent = `${steps.length} steps - ${formatDuration(route.duration)}`;
            directionsList.innerHTML = steps.map((step, index) => `
                <li class="direction-step ${index === activeStepIndex ? 'active-step' : ''}">
                    <strong>Step ${index + 1}: ${step.maneuver?.instruction || 'Continue straight'}</strong>
                    <span>${formatDistance(step.distance)}${step.name ? ` via ${step.name}` : ''}</span>
                </li>
            `).join('');
        }

        function stopNavigationGuidance() {
            const shouldInterruptSession = state.navigationStarted && state.navigationSessionId !== null && !state
                .hasAnnouncedArrival;

            if (state.watchId !== null) {
                navigator.geolocation.clearWatch(state.watchId);
                state.watchId = null;
            }

            if (state.liveSyncIntervalId !== null) {
                window.clearInterval(state.liveSyncIntervalId);
                state.liveSyncIntervalId = null;
            }

            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }

            state.activeRoute = null;
            state.activeProfile = null;
            state.activeSteps = [];
            state.currentStepIndex = -1;
            state.navigationStarted = false;
            state.hasAnnouncedArrival = false;

            if (shouldInterruptSession) {
                updateNavigationSessionRecord('interrupted').catch(error => console.error(error));
            }
        }

        function speakInstruction(text) {
            if (!text || !('speechSynthesis' in window)) {
                return;
            }

            window.speechSynthesis.cancel();

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.rate = 1;
            utterance.pitch = 1;
            utterance.volume = 1;
            window.speechSynthesis.speak(utterance);
        }

        async function sendNavigationSessionRequest(url, method, payload) {
            const response = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload),
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(data.message || 'Unable to save the navigation session.');
            }

            return data;
        }

        async function startNavigationSessionRecord() {
            if (!state.currentPlaceLabel || !state.currentCoordinates || !state.destinationFeature?.place_name || !state
                .destinationFeature?.center) {
                return;
            }

            const [originLng, originLat] = state.currentCoordinates;
            const [destinationLng, destinationLat] = state.destinationFeature.center;
            const data = await sendNavigationSessionRequest(
                `{{ route('patient.navigation.session.start', [], false) }}`,
                'POST', {
                    origin: state.currentPlaceLabel,
                    origin_latitude: originLat,
                    origin_longitude: originLng,
                    destination: state.destinationFeature.place_name,
                    destination_latitude: destinationLat,
                    destination_longitude: destinationLng,
                }
            );

            state.navigationSessionId = data.data?.id || null;
        }

        async function syncNavigationSessionLocation(force = false) {
            if (!state.navigationSessionId || !state.currentCoordinates) {
                return;
            }

            const now = Date.now();
            if (!force && now - state.lastLocationSyncAt < LOCATION_SYNC_INTERVAL_MS) {
                return;
            }

            state.lastLocationSyncAt = now;
            const [currentLng, currentLat] = state.currentCoordinates;

            await sendNavigationSessionRequest(
                `/patient/navigation/session/${state.navigationSessionId}/location`,
                'PATCH', {
                    current_latitude: currentLat,
                    current_longitude: currentLng,
                }
            );
        }

        function applyPhonePosition(position, options = {}) {
            const coords = [position.coords.longitude, position.coords.latitude];
            state.currentCoordinates = coords;
            state.lastKnownAccuracy = position.coords.accuracy;
            setCurrentMarker(coords);

            if (options.recenter) {
                map.flyTo({
                    center: coords,
                    zoom: 15,
                    duration: 800,
                });
            }

            if (state.navigationStarted) {
                syncNavigationSessionLocation(options.forceSync).catch(error => console.error(error));
                handleNavigationProgress();
                setStatus(`Live tracking active. ${formatAccuracy(state.lastKnownAccuracy)}`);
            }

            return coords;
        }

        function getFreshPhonePosition() {
            if (!navigator.geolocation) {
                return Promise.reject(new Error('This browser does not support geolocation.'));
            }

            if (!window.isSecureContext) {
                return Promise.reject(new Error('Open Gabay over HTTPS on the patient phone so the browser can use precise GPS.'));
            }

            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, GEOLOCATION_OPTIONS);
            });
        }

        async function updateNavigationSessionRecord(status) {
            if (!state.navigationSessionId) {
                return;
            }

            const [currentLng, currentLat] = state.currentCoordinates || [];
            await sendNavigationSessionRequest(
                `/patient/navigation/session/${state.navigationSessionId}`,
                'PATCH', {
                    status,
                    destination: state.destinationFeature?.place_name || destinationInput.value.trim(),
                    current_latitude: currentLat,
                    current_longitude: currentLng,
                }
            );

            state.navigationSessionId = null;
        }

        function getDistanceBetweenCoordsMeters([lng1, lat1], [lng2, lat2]) {
            const toRadians = value => value * (Math.PI / 180);
            const earthRadius = 6371000;
            const dLat = toRadians(lat2 - lat1);
            const dLng = toRadians(lng2 - lng1);
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);

            return 2 * earthRadius * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function getUpcomingManeuverCoords(stepIndex) {
            const upcomingStep = state.activeSteps[stepIndex + 1];
            return upcomingStep?.maneuver?.location || null;
        }

        function announceCurrentStep() {
            if (!state.navigationStarted || !state.activeSteps.length) {
                return;
            }

            const currentStep = state.activeSteps[state.currentStepIndex];
            if (!currentStep) {
                return;
            }

            renderDirections(state.activeRoute, state.currentStepIndex);

            const nextCoords = getUpcomingManeuverCoords(state.currentStepIndex);
            const extraDistance = nextCoords && state.currentCoordinates ?
                ` Next turn in ${formatDistance(getDistanceBetweenCoordsMeters(state.currentCoordinates, nextCoords))}.` :
                '';
            const spokenText = `${currentStep.maneuver?.instruction || 'Continue straight.'}${extraDistance}`;

            speakInstruction(spokenText);
            setStatus(`Navigation active: ${currentStep.maneuver?.instruction || 'Continue straight.'}`);
        }

        function handleNavigationProgress() {
            if (!state.navigationStarted || !state.currentCoordinates || !state.activeSteps.length) {
                return;
            }

            const nextCoords = getUpcomingManeuverCoords(state.currentStepIndex);

            if (nextCoords) {
                const distanceToNext = getDistanceBetweenCoordsMeters(state.currentCoordinates, nextCoords);

                if (distanceToNext <= NAVIGATION_MANEUVER_THRESHOLD_METERS) {
                    state.currentStepIndex += 1;
                    announceCurrentStep();
                    return;
                }
            }

            const destinationCoords = state.destinationFeature?.center || state.activeRoute?.geometry?.coordinates?.slice(-
                1)[0];
            if (destinationCoords && !state.hasAnnouncedArrival) {
                const distanceToDestination = getDistanceBetweenCoordsMeters(state.currentCoordinates, destinationCoords);

                if (distanceToDestination <= NAVIGATION_MANEUVER_THRESHOLD_METERS) {
                    state.hasAnnouncedArrival = true;
                    renderDirections(state.activeRoute, state.activeSteps.length - 1);
                    speakInstruction('You have arrived at your destination.');
                    setStatus('You have arrived at your destination.');
                    updateNavigationSessionRecord('completed').catch(error => console.error(error));
                }
            }
        }

        function startLiveTracking() {
            if (!navigator.geolocation) {
                setStatus('Live tracking is not supported on this device.');
                return;
            }

            if (state.watchId !== null) {
                navigator.geolocation.clearWatch(state.watchId);
            }

            state.watchId = navigator.geolocation.watchPosition(position => {
                applyPhonePosition(position);
            }, error => {
                console.error(error);
                setStatus(geolocationErrorMessage(error));
            }, GEOLOCATION_OPTIONS);

            if (state.liveSyncIntervalId !== null) {
                window.clearInterval(state.liveSyncIntervalId);
            }

            state.liveSyncIntervalId = window.setInterval(() => {
                syncNavigationSessionLocation(true).catch(error => console.error(error));
            }, 5000);
        }

        async function startNavigationGuidance(route, profile) {
            const steps = route.legs?.flatMap(leg => leg.steps || []) || [];

            stopNavigationGuidance();

            state.activeRoute = route;
            state.activeProfile = profile;
            state.activeSteps = steps;
            state.currentStepIndex = 0;
            state.navigationStarted = true;
            state.hasAnnouncedArrival = false;

            if (!steps.length) {
                setStatus('Route started, but no navigation steps are available.');
                return;
            }

            await startNavigationSessionRecord();
            if (!state.navigationSessionId) {
                throw new Error('Navigation session could not be started. Please try again.');
            }

            await syncNavigationSessionLocation(true);
            startLiveTracking();
            announceCurrentStep();
        }

        function ensureRouteLayer() {
            if (map.getSource('route')) {
                return;
            }

            map.addSource('route', {
                type: 'geojson',
                data: {
                    type: 'FeatureCollection',
                    features: [],
                },
            });

            map.addLayer({
                id: 'route-line',
                type: 'line',
                source: 'route',
                layout: {
                    'line-cap': 'round',
                    'line-join': 'round',
                },
                paint: {
                    'line-color': '#38bdf8',
                    'line-width': 6,
                    'line-opacity': 0.9,
                },
            });
        }

        function setCurrentMarker(coords) {
            if (!state.currentMarker) {
                state.currentMarker = new mapboxgl.Marker({
                    color: '#22c55e'
                }).setLngLat(coords).addTo(map);
            } else {
                state.currentMarker.setLngLat(coords);
            }
        }

        function setDestinationMarker(coords, label) {
            if (!state.destinationMarker) {
                state.destinationMarker = new mapboxgl.Marker({
                    color: '#ef4444'
                }).setLngLat(coords).addTo(map);
            } else {
                state.destinationMarker.setLngLat(coords);
            }

            const popup = new mapboxgl.Popup({
                offset: 20
            }).setHTML(`<strong>Destination</strong><br>${label}`);
            state.destinationMarker.setPopup(popup);
        }

        async function reverseGeocode(coords) {
            const [lng, lat] = coords;
            const params = new URLSearchParams({
                lng,
                lat,
            });

            const data = await fetchNavigationJson(`${mapboxApiRoutes.reverseGeocode}?${params.toString()}`);
            return data.features?.[0]?.place_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
        }

        async function searchDestinations(query) {
            const params = new URLSearchParams({
                q: query,
                limit: '5',
            });

            if (state.currentCoordinates) {
                params.set('proximity_lng', state.currentCoordinates[0]);
                params.set('proximity_lat', state.currentCoordinates[1]);
            }

            const data = await fetchNavigationJson(`${mapboxApiRoutes.search}?${params.toString()}`);
            return data.features || [];
        }

        async function geocodeDestination(query) {
            const features = await searchDestinations(query);
            const feature = features[0];

            if (!feature) {
                throw new Error('No destination match was found.');
            }

            return feature;
        }

        async function updateDestinationSuggestions() {
            const query = destinationInput.value.trim();

            if (query.length < 2) {
                state.suggestionResults = [];
                hideSuggestions();
                return;
            }

            try {
                const features = await searchDestinations(query);
                renderSuggestions(features);
            } catch (error) {
                console.error(error);
                state.suggestionResults = [];
                hideSuggestions();
            }
        }

        async function previewSelectedDestination(feature) {
            stopNavigationGuidance();
            setActionState(true, 'preview');
            setStatus(`Loading the nearest route to ${feature.place_name}...`);

            try {
                const result = await fetchRoute(state.currentCoordinates, feature);
                state.destinationFeature = feature;
                state.previewRoute = result.route;
                state.previewProfile = result.profile;
                renderRoute(result.route, feature, result.profile, false);
                renderDirections(result.route);
                directionsSummary.textContent = `Route ready - ${formatDuration(result.route.duration)}`;
                setStatus(
                    `Route, distance, and directions are ready for ${feature.place_name}. Press Start for live voice navigation.`
                );
            } catch (error) {
                console.error(error);
                setStatus(error.message || 'Unable to load the selected destination route right now.');
                resetDirections(error.message || 'Unable to load the selected destination route right now.');
            } finally {
                setActionState(false);
            }
        }

        function selectSuggestion(feature) {
            if (!feature) {
                return;
            }

            state.destinationFeature = feature;
            state.previewRoute = null;
            state.previewProfile = null;
            state.syncingDestinationInput = true;
            destinationInput.value = feature.place_name;
            state.syncingDestinationInput = false;
            hideSuggestions();
            previewSelectedDestination(feature);
        }

        async function fetchRoute(startCoords, destinationFeature) {
            const [startLng, startLat] = startCoords;
            const [endLng, endLat] = destinationFeature.center;
            const profiles = ['walking', 'driving'];
            let lastErrorMessage = 'No route could be generated.';

            for (const profile of profiles) {
                const params = new URLSearchParams({
                    profile,
                    start_lng: startLng,
                    start_lat: startLat,
                    end_lng: endLng,
                    end_lat: endLat,
                });

                try {
                    const data = await fetchNavigationJson(`${mapboxApiRoutes.directions}?${params.toString()}`);

                    if (data.routes?.[0]) {
                        return {
                            route: data.routes[0],
                            profile,
                        };
                    }
                } catch (error) {
                    lastErrorMessage = error.message || `Unable to load ${profile} directions.`;
                    continue;
                }

                lastErrorMessage = `Unable to load ${profile} directions.`;
            }

            if (String(lastErrorMessage).toLowerCase().includes('maximum distance')) {
                throw new Error(
                    'The selected destination appears too far from your current location. Please enter a more specific nearby place or address.'
                );
            }

            throw new Error(lastErrorMessage);
        }

        function renderRoute(route, destinationFeature, profile, showDirections = false) {
            ensureRouteLayer();
            map.getSource('route').setData({
                type: 'Feature',
                properties: {},
                geometry: route.geometry,
            });

            setDestinationMarker(destinationFeature.center, destinationFeature.place_name);

            const distanceKm = route.distance / 1000;
            updateDistance(distanceKm);
            if (showDirections) {
                renderDirections(route, state.currentStepIndex);
                setStatus(
                    `Navigation started to ${destinationFeature.place_name} using ${profile === 'walking' ? 'walking' : 'driving'} directions.`
                );
            } else {
                resetDirections(`Preview ready - ${formatDuration(route.duration)}`);
                setStatus(
                    `Route preview ready to ${destinationFeature.place_name}. Press Start to begin turn-by-turn directions.`
                );
            }

            const bounds = route.geometry.coordinates.reduce((bounds, coord) => bounds.extend(coord), new mapboxgl
                .LngLatBounds(route.geometry.coordinates[0], route.geometry.coordinates[0]));
            map.fitBounds(bounds, {
                padding: 70,
                duration: 1000
            });
        }

        async function buildRouteFromFeature(destinationFeature, showDirections = false) {
            const result = await fetchRoute(state.currentCoordinates, destinationFeature);
            state.destinationFeature = destinationFeature;
            state.previewRoute = result.route;
            state.previewProfile = result.profile;
            state.syncingDestinationInput = true;
            destinationInput.value = destinationFeature.place_name;
            state.syncingDestinationInput = false;
            renderRoute(result.route, destinationFeature, result.profile, showDirections);
        }

        async function detectCurrentLocation() {
            if (!navigator.geolocation) {
                setStatus('This browser does not support geolocation.');
                currentLocationInput.value = 'Location unavailable';
                return;
            }

            setStatus('Detecting your current location...');

            try {
                const position = await getFreshPhonePosition();
                const coords = applyPhonePosition(position, {
                    recenter: true,
                });
                state.previewRoute = null;
                state.previewProfile = null;
                if (!state.navigationStarted) {
                    state.destinationFeature = null;
                }

                try {
                    const label = await reverseGeocode(coords);
                    state.currentPlaceLabel = label;
                    currentLocationInput.value = label;
                } catch (error) {
                    currentLocationInput.value = `${coords[1].toFixed(5)}, ${coords[0].toFixed(5)}`;
                }

                setStatus(`Current location is ready. ${formatAccuracy(state.lastKnownAccuracy)} Enter a destination to build the route.`);
            } catch (error) {
                console.error(error);
                currentLocationInput.value = 'Permission denied or location unavailable';
                setStatus(geolocationErrorMessage(error));
            }
        }

        async function resolveDestinationFeature() {
            const destinationQuery = destinationInput.value.trim();

            if (!destinationQuery) {
                throw new Error('Please enter a destination first.');
            }

            if (state.destinationFeature && destinationQuery === state.destinationFeature.place_name) {
                return state.destinationFeature;
            }

            if (state.suggestionResults.length) {
                const exactMatch = state.suggestionResults.find(feature =>
                    feature.place_name === destinationQuery || feature.text === destinationQuery
                );
                return exactMatch || state.suggestionResults[0];
            }

            return await geocodeDestination(destinationQuery);
        }

        async function previewRoute() {
            if (!state.currentCoordinates) {
                setStatus('Current location is not ready yet. Please refresh location first.');
                resetDirections('Current location is not ready yet.');
                return;
            }

            stopNavigationGuidance();
            setActionState(true, 'preview');
            setStatus('Searching destination and computing route preview...');

            try {
                const destination = await resolveDestinationFeature();
                await buildRouteFromFeature(destination, false);
            } catch (error) {
                console.error(error);
                setStatus(error.message || 'Unable to create the route right now.');
                resetDirections(error.message || 'Unable to create the route right now.');
            } finally {
                setActionState(false);
            }
        }

        async function startNavigation() {
            if (!state.currentCoordinates) {
                setStatus('Current location is not ready yet. Please refresh location first.');
                resetDirections('Current location is not ready yet.');
                return;
            }

            setActionState(true, 'start');
            setStatus('Refreshing phone GPS before starting...');

            try {
                const position = await getFreshPhonePosition();
                applyPhonePosition(position, {
                    recenter: true,
                });
                state.previewRoute = null;
                state.previewProfile = null;

                if (!state.currentPlaceLabel) {
                    state.currentPlaceLabel =
                        `Current location (${state.currentCoordinates[1].toFixed(5)}, ${state.currentCoordinates[0].toFixed(5)})`;
                }

                setStatus('Preparing turn-by-turn directions...');
                const destination = await resolveDestinationFeature();
                const hasPreviewForDestination = state.previewRoute && state.destinationFeature &&
                    state.destinationFeature.place_name === destination.place_name;

                if (hasPreviewForDestination) {
                    await startNavigationGuidance(state.previewRoute, state.previewProfile || 'walking');
                    renderRoute(state.previewRoute, destination, state.previewProfile || 'walking', true);
                } else {
                    await buildRouteFromFeature(destination, true);
                    await startNavigationGuidance(state.previewRoute, state.previewProfile || 'walking');
                }
            } catch (error) {
                console.error(error);
                setStatus(error.message || 'Unable to start navigation right now.');
                resetDirections(error.message || 'Unable to start navigation right now.');
            } finally {
                setActionState(false);
            }
        }

        document.getElementById('zoom-in').addEventListener('click', () => map.zoomIn());
        document.getElementById('zoom-out').addEventListener('click', () => map.zoomOut());
        buildRouteButton.addEventListener('click', previewRoute);
        startNavigationButton.addEventListener('click', startNavigation);
        refreshLocationButton.addEventListener('click', detectCurrentLocation);
        destinationInput.addEventListener('input', () => {
            if (state.syncingDestinationInput) {
                return;
            }

            state.destinationFeature = null;
            state.previewRoute = null;
            state.previewProfile = null;
            stopNavigationGuidance();
            resetDirections();

            if (state.suggestionTimeoutId) {
                clearTimeout(state.suggestionTimeoutId);
            }

            state.suggestionTimeoutId = window.setTimeout(updateDestinationSuggestions, 250);
        });
        destinationInput.addEventListener('keydown', event => {
            if (event.key === 'Enter') {
                event.preventDefault();
                hideSuggestions();
                previewRoute();
            }
        });
        destinationInput.addEventListener('blur', () => {
            window.setTimeout(() => {
                hideSuggestions();
            }, 150);
        });

        destinationSuggestions.addEventListener('click', event => {
            const suggestionButton = event.target.closest('.suggestion-item');
            if (!suggestionButton) {
                return;
            }

            const feature = state.suggestionResults[Number(suggestionButton.dataset.index)];
            selectSuggestion(feature);
        });

        map.on('click', async event => {
            if (!state.currentCoordinates) {
                setStatus('Current location is not ready yet. Please refresh location first.');
                resetDirections('Current location is not ready yet.');
                return;
            }

            stopNavigationGuidance();
            setActionState(true, 'preview');
            setStatus('Map destination selected. Computing route preview...');

            try {
                const coords = [event.lngLat.lng, event.lngLat.lat];
                const placeName = await reverseGeocode(coords);
                const destinationFeature = {
                    center: coords,
                    place_name: placeName,
                };

                await buildRouteFromFeature(destinationFeature, false);
            } catch (error) {
                console.error(error);
                setStatus(error.message || 'Unable to create a route from the selected map location.');
                resetDirections(error.message || 'Unable to create a route from the selected map location.');
            } finally {
                setActionState(false);
            }
        });

        map.on('load', () => {
            ensureRouteLayer();
            resetDirections();
            detectCurrentLocation();
        });
    </script>
</body>

</html>
