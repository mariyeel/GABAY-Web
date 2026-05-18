<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GABAY | Caregiver Navigation</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <style>
        :root {
            --bg: #06111a;
            --sidebar: #0d2640;
            --panel: rgba(13, 38, 64, 0.78);
            --field: rgba(4, 18, 30, 0.75);
            --border: rgba(255, 255, 255, 0.09);
            --blue: #2196f3;
            --cyan: #7dd3fc;
            --text: #ecfeff;
            --muted: #8fa0b5;
            --success: #b9f6ca;
            --danger: #ffb4ab;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            display: flex;
            overflow: hidden;
        }

        .sidebar {
            width: 280px;
            flex: 0 0 280px;
            height: 100vh;
            padding: 40px 24px;
            background: linear-gradient(180deg, var(--sidebar) 0%, #06111a 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
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
            background: rgba(33, 150, 243, 0.14);
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
        }

        .nav-link:hover,
        .logout-button:hover,
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
            grid-template-columns: minmax(320px, 420px) minmax(0, 1fr);
        }

        .panel {
            padding: 34px;
            overflow-y: auto;
            background: rgba(5, 16, 25, 0.56);
            border-right: 1px solid var(--border);
        }

        .map-wrap {
            position: relative;
            min-width: 0;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        .eyebrow {
            display: inline-flex;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(33, 150, 243, 0.16);
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

        .lede,
        .meta,
        .status-text {
            color: var(--muted);
            line-height: 1.6;
        }

        .lede {
            margin-bottom: 24px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 16px;
        }

        label {
            display: block;
            color: var(--cyan);
            font-size: 0.84rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 13px 14px;
            background: var(--field);
            color: #fff;
            outline: none;
        }

        input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.13);
        }

        .suggestions-box {
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border);
            display: none;
        }

        .suggestions-box.visible {
            display: block;
        }

        .suggestion-item {
            width: 100%;
            border: 0;
            border-bottom: 1px solid var(--border);
            background: rgba(4, 18, 30, 0.94);
            color: #fff;
            text-align: left;
            padding: 12px 14px;
            cursor: pointer;
        }

        .suggestion-item:last-child {
            border-bottom: 0;
        }

        .suggestion-item:hover {
            background: rgba(56, 189, 248, 0.12);
        }

        .suggestion-title {
            display: block;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .suggestion-meta {
            display: block;
            color: var(--muted);
            font-size: 0.82rem;
            line-height: 1.4;
        }

        .button-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 14px;
        }

        button {
            border: 0;
            border-radius: 8px;
            padding: 13px 14px;
            cursor: pointer;
            font-weight: 700;
        }

        .primary-btn {
            background: var(--blue);
            color: #fff;
        }

        .secondary-btn {
            background: rgba(125, 211, 252, 0.12);
            color: var(--cyan);
            border: 1px solid rgba(125, 211, 252, 0.22);
        }

        button:disabled {
            opacity: 0.58;
            cursor: not-allowed;
        }

        .status-box {
            color: var(--success);
        }

        .error-box {
            color: var(--danger);
        }

        .map-overlay {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;
            z-index: 5;
            display: flex;
            justify-content: space-between;
            pointer-events: none;
            gap: 16px;
        }

        .map-badge,
        .zoom-controls {
            pointer-events: auto;
        }

        .map-badge {
            max-width: 340px;
            border-radius: 8px;
            padding: 12px 14px;
            background: rgba(4, 18, 30, 0.84);
            border: 1px solid var(--border);
        }

        .map-badge strong {
            display: block;
            margin-bottom: 4px;
        }

        .zoom-controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .zoom-btn {
            width: 44px;
            height: 44px;
            padding: 0;
            color: #fff;
            background: rgba(4, 18, 30, 0.88);
            border: 1px solid var(--border);
            font-size: 1.4rem;
        }

        @media (max-width: 960px) {
            body {
                display: block;
                overflow: auto;
            }

            .sidebar,
            .main-content {
                width: 100%;
                height: auto;
            }

            .main-content {
                display: block;
            }

            .map-wrap {
                height: 560px;
            }
        }
    </style>
</head>

<body>
    @php
        $nameParts = preg_split('/\s+/', trim($caregiver->name ?? 'Caregiver')) ?: [];
        $initials = collect($nameParts)->filter()->take(2)->map(fn($part) => strtoupper(mb_substr($part, 0, 1)))->join('');
        $initials = $initials !== '' ? $initials : 'C';
    @endphp

    <aside class="sidebar">
        <div class="logo">
            <span class="logo-mark">G</span>
            <span>GABAY</span>
        </div>

        <div class="profile-section">
            <div class="avatar">{{ $initials }}</div>
            <p class="profile-name">{{ $caregiver->name }}</p>
            <p class="profile-role">Caregiver Account</p>
        </div>

        <ul class="nav-menu">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.caregiver') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('caregiver.navigation') }}">Navigation</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('caregiver.live_tracking') }}">Tracking</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.caregiver') }}#reports">Reports</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.caregiver') }}#notifications">Notifications</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.caregiver') }}#profile">Profile</a></li>
        </ul>

        <form class="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </aside>

    <main class="main-content">
        <section class="panel">
            <div class="eyebrow">Destination Setup</div>
            <h1>Set patient destination</h1>
            <p class="lede">
                {{ $connectedPatient
                    ? 'Choose where ' . $connectedPatient->name . ' should navigate. The patient page will display the assigned route.'
                    : 'Connect to a patient before assigning a navigation destination.' }}
            </p>

            @if (!$connectedPatient)
                <article class="card error-box">No patient is connected to this caregiver account.</article>
            @endif

            <article class="card">
                <p class="meta">Connected patient</p>
                <strong>{{ $connectedPatient?->name ?? 'None' }}</strong>
            </article>

            <article class="card">
                <label for="destination">Destination</label>
                <input id="destination" type="text" placeholder="Search address or place name" @disabled(!$connectedPatient)>
                <div class="suggestions-box" id="destination-suggestions"></div>

                <div class="button-row">
                    <button type="button" class="secondary-btn" id="preview-destination" @disabled(!$connectedPatient)>Preview</button>
                    <button type="button" class="primary-btn" id="send-destination" @disabled(!$connectedPatient)>Send to Patient</button>
                </div>
            </article>

            <article class="card">
                <p class="meta">Assigned destination</p>
                <strong id="assigned-destination">{{ $initialNavigationSession['destination'] ?? 'No destination assigned yet.' }}</strong>
                <p class="status-text" id="status-text">Waiting for destination selection.</p>
            </article>
        </section>

        <section class="map-wrap">
            <div id="map"></div>
            <div class="map-overlay">
                <div class="map-badge">
                    <strong>Caregiver Map</strong>
                    <span id="map-status">Search for a destination or click on the map.</span>
                </div>
                <div class="zoom-controls">
                    <button type="button" class="zoom-btn" id="zoom-in" aria-label="Zoom in">+</button>
                    <button type="button" class="zoom-btn" id="zoom-out" aria-label="Zoom out">-</button>
                </div>
            </div>
        </section>
    </main>

    <script>
        const mapboxToken = @json(config('services.mapbox.token'));
        const connectedPatient = @json($connectedPatient ? ['id' => $connectedPatient->user_id, 'name' => $connectedPatient->name] : null);
        const initialSession = @json($initialNavigationSession);
        const routes = {
            search: @json(route('caregiver.navigation.mapbox.search', [], false)),
            reverse: @json(route('caregiver.navigation.mapbox.reverse', [], false)),
            session: @json(route('caregiver.navigation.session.start', [], false)),
        };
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const destinationInput = document.getElementById('destination');
        const suggestionsBox = document.getElementById('destination-suggestions');
        const previewButton = document.getElementById('preview-destination');
        const sendButton = document.getElementById('send-destination');
        const statusText = document.getElementById('status-text');
        const mapStatus = document.getElementById('map-status');
        const assignedDestination = document.getElementById('assigned-destination');
        const state = {
            selectedFeature: null,
            suggestions: [],
            timeoutId: null,
            destinationMarker: null,
        };

        mapboxgl.accessToken = mapboxToken || '';

        function setStatus(message) {
            statusText.textContent = message;
            mapStatus.textContent = message;
        }

        if (!mapboxgl.accessToken) {
            setStatus('Mapbox is not configured. Add MAPBOX_TOKEN to the environment.');
            previewButton.disabled = true;
            sendButton.disabled = true;
            throw new Error('MAPBOX_TOKEN is not configured.');
        }

        const initialCenter = initialSession?.destination_coordinates ?
            [initialSession.destination_coordinates.lng, initialSession.destination_coordinates.lat] :
            [125.6115, 7.0731];

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/navigation-night-v1',
            center: initialCenter,
            zoom: initialSession?.destination_coordinates ? 15 : 13,
        });

        function hideSuggestions() {
            suggestionsBox.classList.remove('visible');
            suggestionsBox.innerHTML = '';
        }

        function setDestinationMarker(feature) {
            if (!feature?.center) {
                return;
            }

            if (!state.destinationMarker) {
                state.destinationMarker = new mapboxgl.Marker({
                    color: '#ef4444',
                }).addTo(map);
            }

            state.destinationMarker
                .setLngLat(feature.center)
                .setPopup(new mapboxgl.Popup({
                    offset: 20,
                }).setHTML(`<strong>Destination</strong><br>${feature.place_name || ''}`));

            map.flyTo({
                center: feature.center,
                zoom: 15,
                duration: 700,
                essential: true,
            });
        }

        async function fetchJson(url, options = {}) {
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    ...(options.headers || {}),
                },
                credentials: 'same-origin',
                ...options,
            });
            const payload = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(payload.message || 'Unable to complete the navigation request.');
            }

            return payload;
        }

        async function searchDestinations(query) {
            const params = new URLSearchParams({
                q: query,
            });
            const data = await fetchJson(`${routes.search}?${params.toString()}`);
            return data.features || [];
        }

        function renderSuggestions(features) {
            state.suggestions = features;

            if (!features.length) {
                hideSuggestions();
                return;
            }

            suggestionsBox.innerHTML = features.map((feature, index) => `
                <button type="button" class="suggestion-item" data-index="${index}">
                    <span class="suggestion-title">${feature.text || feature.place_name}</span>
                    <span class="suggestion-meta">${feature.place_name}</span>
                </button>
            `).join('');
            suggestionsBox.classList.add('visible');
        }

        async function reverseGeocode(coords) {
            const params = new URLSearchParams({
                lng: coords[0],
                lat: coords[1],
            });
            const data = await fetchJson(`${routes.reverse}?${params.toString()}`);
            return data.features?.[0]?.place_name || `${coords[1].toFixed(6)}, ${coords[0].toFixed(6)}`;
        }

        async function resolveDestination() {
            const query = destinationInput.value.trim();

            if (state.selectedFeature && query === state.selectedFeature.place_name) {
                return state.selectedFeature;
            }

            if (!query) {
                throw new Error('Enter a destination first.');
            }

            const features = await searchDestinations(query);
            if (!features[0]) {
                throw new Error('No destination match was found.');
            }

            return features[0];
        }

        async function previewDestination() {
            try {
                setStatus('Loading destination preview...');
                const feature = await resolveDestination();
                state.selectedFeature = feature;
                destinationInput.value = feature.place_name;
                assignedDestination.textContent = feature.place_name;
                setDestinationMarker(feature);
                hideSuggestions();
                setStatus('Destination preview is ready.');
            } catch (error) {
                console.error(error);
                setStatus(error.message || 'Unable to preview destination.');
            }
        }

        async function sendDestination() {
            try {
                sendButton.disabled = true;
                setStatus('Saving destination for the patient...');
                const feature = await resolveDestination();
                const [lng, lat] = feature.center;
                const payload = await fetchJson(routes.session, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        destination: feature.place_name,
                        destination_latitude: lat,
                        destination_longitude: lng,
                    }),
                });

                state.selectedFeature = feature;
                assignedDestination.textContent = payload.data?.destination || feature.place_name;
                setDestinationMarker(feature);
                hideSuggestions();
                setStatus(`Destination sent to ${connectedPatient?.name || 'the patient'}.`);
            } catch (error) {
                console.error(error);
                setStatus(error.message || 'Unable to save destination.');
            } finally {
                sendButton.disabled = !connectedPatient;
            }
        }

        destinationInput.addEventListener('input', () => {
            state.selectedFeature = null;
            clearTimeout(state.timeoutId);
            const query = destinationInput.value.trim();

            if (query.length < 2) {
                hideSuggestions();
                return;
            }

            state.timeoutId = window.setTimeout(async () => {
                try {
                    renderSuggestions(await searchDestinations(query));
                } catch (error) {
                    console.error(error);
                    hideSuggestions();
                }
            }, 250);
        });

        destinationInput.addEventListener('keydown', event => {
            if (event.key === 'Enter') {
                event.preventDefault();
                previewDestination();
            }
        });

        suggestionsBox.addEventListener('click', event => {
            const button = event.target.closest('.suggestion-item');
            if (!button) {
                return;
            }

            const feature = state.suggestions[Number(button.dataset.index)];
            if (!feature) {
                return;
            }

            state.selectedFeature = feature;
            destinationInput.value = feature.place_name;
            assignedDestination.textContent = feature.place_name;
            setDestinationMarker(feature);
            hideSuggestions();
            setStatus('Destination preview is ready.');
        });

        previewButton.addEventListener('click', previewDestination);
        sendButton.addEventListener('click', sendDestination);
        document.getElementById('zoom-in').addEventListener('click', () => map.zoomIn());
        document.getElementById('zoom-out').addEventListener('click', () => map.zoomOut());

        map.on('click', async event => {
            try {
                const coords = [event.lngLat.lng, event.lngLat.lat];
                const placeName = await reverseGeocode(coords);
                const feature = {
                    center: coords,
                    place_name: placeName,
                    text: placeName,
                };
                state.selectedFeature = feature;
                destinationInput.value = placeName;
                assignedDestination.textContent = placeName;
                setDestinationMarker(feature);
                setStatus('Map destination preview is ready.');
            } catch (error) {
                console.error(error);
                setStatus(error.message || 'Unable to select that map destination.');
            }
        });

        map.on('load', () => {
            if (initialSession?.destination_coordinates) {
                const feature = {
                    center: [initialSession.destination_coordinates.lng, initialSession.destination_coordinates.lat],
                    place_name: initialSession.destination || 'Assigned destination',
                };
                state.selectedFeature = feature;
                destinationInput.value = feature.place_name;
                setDestinationMarker(feature);
                setStatus('Latest assigned destination is shown.');
            }
        });
    </script>
</body>

</html>
