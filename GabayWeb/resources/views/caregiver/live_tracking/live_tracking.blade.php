<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GABAY | Caregiver Live Tracking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <style>
        :root {
            --bg: #07131d;
            --sidebar: #0d2638;
            --panel: rgba(10, 28, 43, 0.88);
            --panel-soft: rgba(255, 255, 255, 0.055);
            --border: rgba(255, 255, 255, 0.1);
            --text: #f4fbff;
            --muted: #96a8b8;
            --blue: #2f9df4;
            --cyan: #75d7ff;
            --green: #35d07f;
            --red: #ff5f6d;
            --amber: #ffc857;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            color: var(--text);
            background: var(--bg);
            display: flex;
            overflow: hidden;
        }

        .sidebar {
            width: 280px;
            flex: 0 0 280px;
            height: 100vh;
            padding: 40px 24px;
            background: linear-gradient(180deg, #0d2638 0%, #07131d 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 46px;
        }

        .logo-mark,
        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(47, 157, 244, 0.14);
            color: var(--cyan);
        }

        .logo-mark {
            width: 30px;
            height: 30px;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 34px;
        }

        .avatar {
            width: 82px;
            height: 82px;
            margin: 0 auto 14px;
            border: 2px solid var(--blue);
            font-size: 1.75rem;
            font-weight: 700;
        }

        .profile-name {
            color: #fff;
            font-weight: 600;
        }

        .profile-role {
            color: var(--muted);
            font-size: 0.76rem;
            margin-top: 5px;
        }

        .nav-menu {
            list-style: none;
            flex: 1;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link,
        .logout-button {
            width: 100%;
            border: 0;
            border-radius: 8px;
            padding: 14px 18px;
            color: var(--muted);
            text-decoration: none;
            background: transparent;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .nav-link:hover,
        .logout-button:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .nav-link.active {
            background: var(--blue);
            color: #fff;
        }

        .logout-form {
            margin-top: auto;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            height: 100vh;
            display: grid;
            grid-template-columns: minmax(320px, 430px) minmax(0, 1fr);
            overflow: hidden;
        }

        .tracking-panel {
            padding: 34px;
            overflow-y: auto;
            border-right: 1px solid var(--border);
            background: rgba(5, 16, 25, 0.56);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(47, 157, 244, 0.16);
            color: var(--cyan);
            font-size: 0.76rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        h1 {
            font-size: 2rem;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 10px;
        }

        .lede {
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .status-card,
        .detail-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 14px;
        }

        .status-pill {
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 200, 87, 0.14);
            color: var(--amber);
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .status-pill.ongoing {
            background: rgba(53, 208, 127, 0.14);
            color: #9effc5;
        }

        .status-pill.completed {
            background: rgba(47, 157, 244, 0.14);
            color: var(--cyan);
        }

        .status-pill.interrupted {
            background: rgba(255, 95, 109, 0.14);
            color: #ffb6bf;
        }

        .patient-name {
            color: #fff;
            font-size: 1.12rem;
            font-weight: 700;
        }

        .meta {
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .detail-card h2 {
            font-size: 1rem;
            color: #fff;
            margin-bottom: 14px;
        }

        .location-item {
            display: grid;
            grid-template-columns: 12px 1fr;
            gap: 12px;
            padding: 13px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .location-item:first-of-type {
            border-top: 0;
            padding-top: 0;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-top: 6px;
            background: var(--green);
        }

        .dot.destination {
            background: var(--red);
        }

        .dot.origin {
            background: var(--cyan);
        }

        .location-item strong {
            display: block;
            color: #fff;
            font-size: 0.9rem;
            margin-bottom: 3px;
        }

        .empty-state {
            border: 1px dashed rgba(255, 255, 255, 0.18);
            color: var(--muted);
            background: rgba(255, 255, 255, 0.035);
            border-radius: 8px;
            padding: 18px;
            line-height: 1.6;
        }

        .map-wrap {
            position: relative;
            min-width: 0;
            height: 100vh;
        }

        #caregiver-map {
            position: absolute;
            inset: 0;
        }

        .map-overlay {
            position: absolute;
            top: 24px;
            left: 24px;
            right: 24px;
            z-index: 2;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            pointer-events: none;
        }

        .map-badge,
        .refresh-box {
            pointer-events: auto;
            background: rgba(5, 16, 25, 0.82);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 13px 15px;
            backdrop-filter: blur(14px);
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.22);
        }

        .map-badge strong {
            display: block;
            color: #fff;
            margin-bottom: 3px;
        }

        .map-badge span,
        .refresh-box {
            color: var(--muted);
            font-size: 0.82rem;
        }

        .refresh-button {
            border: 0;
            border-radius: 8px;
            padding: 9px 12px;
            margin-top: 8px;
            color: #fff;
            background: var(--blue);
            cursor: pointer;
            font-weight: 700;
        }

        .patient-marker,
        .destination-marker {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 0 0 8px rgba(53, 208, 127, 0.18), 0 8px 18px rgba(0, 0, 0, 0.35);
            background: var(--green);
        }

        .destination-marker {
            background: var(--red);
            box-shadow: 0 0 0 8px rgba(255, 95, 109, 0.18), 0 8px 18px rgba(0, 0, 0, 0.35);
        }

        @media (max-width: 1020px) {
            body {
                display: block;
                overflow: auto;
            }

            .sidebar {
                width: 100%;
                height: auto;
                min-height: 0;
                padding: 24px 20px;
            }

            .main-content {
                height: auto;
                grid-template-columns: 1fr;
            }

            .tracking-panel {
                border-right: 0;
            }

            .map-wrap {
                height: 62vh;
                min-height: 420px;
            }
        }

        @media (max-width: 640px) {
            .tracking-panel {
                padding: 22px;
            }

            .map-overlay {
                left: 14px;
                right: 14px;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    @php
        $nameParts = preg_split('/\s+/', trim($caregiver->name ?? 'Caregiver')) ?: [];
        $initials = collect($nameParts)
            ->filter()
            ->take(2)
            ->map(fn($part) => strtoupper(mb_substr($part, 0, 1)))
            ->join('');
        $initials = $initials !== '' ? $initials : 'C';
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
            <p class="profile-name">{{ $caregiver->name }}</p>
            <p class="profile-role">Caregiver Account</p>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('dashboard.caregiver') }}" class="nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 13h6v7H4zM14 4h6v16h-6zM4 4h6v5H4zM14 15h6v5h-6z" fill="currentColor" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('caregiver.live_tracking') }}" class="nav-link active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 21c4.97-4.54 8-8.15 8-11a8 8 0 1 0-16 0c0 2.85 3.03 6.46 8 11Z"
                            stroke="currentColor" stroke-width="1.8" />
                        <circle cx="12" cy="10" r="2.5" fill="currentColor" />
                    </svg>
                    <span>Tracking</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dashboard.caregiver') }}#reports" class="nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7 3v18M17 8v13M12 12v9" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                        <path d="M5 21h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <span>Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dashboard.caregiver') }}#notifications" class="nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M6 9a6 6 0 0 1 12 0v4.5l1.5 2.5H4.5L6 13.5V9Z" stroke="currentColor" stroke-width="1.8"
                            stroke-linejoin="round" />
                        <path d="M10 19a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg>
                    <span>Notifications</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dashboard.caregiver') }}#profile" class="nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8" />
                        <path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg>
                    <span>Profile</span>
                </a>
            </li>
        </ul>

        <form class="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">
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
        <section class="tracking-panel">
            <div class="eyebrow">Live Monitoring</div>
            <h1>Patient live tracking</h1>
            <p class="lede">
                Monitor the connected patient's current navigation session, live GPS updates, and selected destination
                from the navigation session records.
            </p>

            <article class="status-card">
                <div class="status-row">
                    <div>
                        <div class="patient-name" id="patient-name">
                            {{ $connectedPatient?->name ?? 'No patient connected' }}
                        </div>
                        <div class="meta" id="session-meta">Waiting for navigation data.</div>
                    </div>
                    <span class="status-pill" id="session-status">Offline</span>
                </div>
                <div class="meta" id="last-update">No live update received yet.</div>
            </article>

            @if (!$connectedPatient)
                <div class="empty-state">
                    Connect this caregiver account to a patient from the dashboard before opening live tracking.
                </div>
            @else
                <article class="detail-card">
                    <h2>Route Details</h2>
                    <div class="location-item">
                        <span class="dot origin"></span>
                        <div>
                            <strong>Origin</strong>
                            <span class="meta" id="origin-text">Waiting for origin.</span>
                        </div>
                    </div>
                    <div class="location-item">
                        <span class="dot"></span>
                        <div>
                            <strong>Current Location</strong>
                            <span class="meta" id="current-text">Waiting for patient GPS.</span>
                        </div>
                    </div>
                    <div class="location-item">
                        <span class="dot destination"></span>
                        <div>
                            <strong>Destination</strong>
                            <span class="meta" id="destination-text">Waiting for destination.</span>
                        </div>
                    </div>
                </article>
            @endif
        </section>

        <section class="map-wrap">
            <div id="caregiver-map"></div>
            <div class="map-overlay">
                <div class="map-badge">
                    <strong>Mapbox Live Tracking</strong>
                    <span id="map-status">Preparing caregiver map.</span>
                </div>
                <div class="refresh-box">
                    <div id="polling-status">Auto-refresh every 5 seconds</div>
                    <button type="button" class="refresh-button" id="refresh-tracking">Refresh</button>
                </div>
            </div>
        </section>
    </main>

    <script>
        const mapboxToken = @json(config('services.mapbox.token'));
        mapboxgl.accessToken = mapboxToken || '';

        const initialTrackingData = @json($initialTrackingData);

        const routes = {
            session: @json(route('caregiver.live_tracking.session', [], false)),
            directions: @json(route('caregiver.live_tracking.mapbox.directions', [], false)),
        };

        const elements = {
            patientName: document.getElementById('patient-name'),
            sessionMeta: document.getElementById('session-meta'),
            sessionStatus: document.getElementById('session-status'),
            lastUpdate: document.getElementById('last-update'),
            originText: document.getElementById('origin-text'),
            currentText: document.getElementById('current-text'),
            destinationText: document.getElementById('destination-text'),
            mapStatus: document.getElementById('map-status'),
            refreshButton: document.getElementById('refresh-tracking'),
        };

        const state = {
            patientMarker: null,
            destinationMarker: null,
            routeLoaded: false,
            lastSessionId: null,
            lastRouteKey: '',
        };

        if (!mapboxgl.accessToken) {
            elements.mapStatus.textContent = 'Mapbox is not configured. Add MAPBOX_TOKEN to the environment.';
            elements.refreshButton.disabled = true;
            throw new Error('MAPBOX_TOKEN is not configured.');
        }

        const map = new mapboxgl.Map({
            container: 'caregiver-map',
            style: 'mapbox://styles/mapbox/navigation-night-v1',
            center: [125.6115, 7.0731],
            zoom: 13,
        });

        map.addControl(new mapboxgl.NavigationControl({
            visualizePitch: true,
        }), 'bottom-right');

        function coordsToLngLat(coordinates) {
            if (!coordinates) {
                return null;
            }

            return [Number(coordinates.lng), Number(coordinates.lat)];
        }

        function formatCoords(coordinates) {
            if (!coordinates) {
                return 'Coordinates not available.';
            }

            return `${Number(coordinates.lat).toFixed(6)}, ${Number(coordinates.lng).toFixed(6)}`;
        }

        function formatTime(value) {
            if (!value) {
                return 'Not recorded';
            }

            return new Date(value).toLocaleString([], {
                dateStyle: 'medium',
                timeStyle: 'short',
            });
        }

        function setStatusPill(status) {
            elements.sessionStatus.className = `status-pill ${status || ''}`;
            elements.sessionStatus.textContent = status || 'Offline';
        }

        function ensureRouteLayer() {
            if (map.getSource('patient-route')) {
                return;
            }

            map.addSource('patient-route', {
                type: 'geojson',
                data: {
                    type: 'FeatureCollection',
                    features: [],
                },
            });

            map.addLayer({
                id: 'patient-route-line',
                type: 'line',
                source: 'patient-route',
                layout: {
                    'line-cap': 'round',
                    'line-join': 'round',
                },
                paint: {
                    'line-color': '#75d7ff',
                    'line-width': 6,
                    'line-opacity': 0.92,
                },
            });
        }

        function markerElement(className) {
            const element = document.createElement('div');
            element.className = className;
            return element;
        }

        function setMarkers(session) {
            const current = coordsToLngLat(session.current_coordinates || session.origin_coordinates);
            const destination = coordsToLngLat(session.destination_coordinates);

            if (current) {
                if (!state.patientMarker) {
                    state.patientMarker = new mapboxgl.Marker({
                        element: markerElement('patient-marker'),
                    }).addTo(map);
                }

                state.patientMarker.setLngLat(current);
            }

            if (destination) {
                if (!state.destinationMarker) {
                    state.destinationMarker = new mapboxgl.Marker({
                        element: markerElement('destination-marker'),
                    }).addTo(map);
                }

                state.destinationMarker
                    .setLngLat(destination)
                    .setPopup(new mapboxgl.Popup({
                        offset: 20,
                    }).setHTML(`<strong>Destination</strong><br>${session.destination || ''}`));
            }

            if (current && destination) {
                const bounds = new mapboxgl.LngLatBounds(current, current).extend(destination);
                map.fitBounds(bounds, {
                    padding: 90,
                    maxZoom: 16,
                    duration: 800,
                });
            } else if (current) {
                map.flyTo({
                    center: current,
                    zoom: 15,
                    duration: 800,
                });
            }
        }

        async function drawRoute(session) {
            const current = coordsToLngLat(session.current_coordinates || session.origin_coordinates);
            const destination = coordsToLngLat(session.destination_coordinates);

            if (!current || !destination) {
                return;
            }

            const routeKey = `${current.join(',')};${destination.join(',')}`;
            if (routeKey === state.lastRouteKey) {
                return;
            }

            state.lastRouteKey = routeKey;
            const params = new URLSearchParams({
                profile: 'walking',
                start_lng: current[0],
                start_lat: current[1],
                end_lng: destination[0],
                end_lat: destination[1],
            });

            const response = await fetch(`${routes.directions}?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
            });
            const data = await response.json().catch(() => ({}));

            if (!response.ok || !data.routes?.[0]?.geometry) {
                throw new Error(data.message || 'Unable to load route geometry.');
            }

            ensureRouteLayer();
            map.getSource('patient-route').setData({
                type: 'Feature',
                properties: {},
                geometry: data.routes[0].geometry,
            });
        }

        function renderTrackingData(data) {
            const patient = data?.patient;
            const session = data?.session;

            if (patient?.name) {
                elements.patientName.textContent = patient.name;
            }

            if (!session) {
                setStatusPill('Offline');
                elements.sessionMeta.textContent = 'No active navigation session yet.';
                elements.lastUpdate.textContent = 'Waiting for the patient to start navigation.';
                elements.mapStatus.textContent = 'No route to display yet.';
                return;
            }

            state.lastSessionId = session.id;
            setStatusPill(session.status);
            elements.sessionMeta.textContent = `Started ${formatTime(session.start_time)}`;
            elements.lastUpdate.textContent = `Last GPS update: ${formatTime(session.location_updated_at)}`;
            elements.originText.textContent =
                `${session.origin || 'Origin not recorded'} (${formatCoords(session.origin_coordinates)})`;
            elements.currentText.textContent = formatCoords(session.current_coordinates);
            elements.destinationText.textContent =
                `${session.destination || 'Destination not recorded'} (${formatCoords(session.destination_coordinates)})`;
            elements.mapStatus.textContent = session.status === 'ongoing' ?
                'Monitoring live patient movement.' :
                `Showing latest ${session.status} session.`;

            setMarkers(session);
            drawRoute(session).catch(error => {
                console.error(error);
                elements.mapStatus.textContent = error.message || 'Unable to draw patient route.';
            });
        }

        async function loadTrackingData() {
            try {
                const response = await fetch(routes.session, {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });
                const payload = await response.json().catch(() => ({}));

                if (!response.ok && response.status !== 404) {
                    throw new Error(payload.message || 'Unable to load live tracking data.');
                }

                renderTrackingData(payload.data);
            } catch (error) {
                console.error(error);
                elements.mapStatus.textContent = error.message || 'Unable to refresh live tracking data.';
            }
        }

        elements.refreshButton.addEventListener('click', loadTrackingData);

        map.on('load', () => {
            ensureRouteLayer();

            if (initialTrackingData) {
                renderTrackingData(initialTrackingData);
            } else {
                loadTrackingData();
            }

            window.setInterval(loadTrackingData, 5000);
        });
    </script>
</body>

</html>
