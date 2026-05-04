<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GABAY | Caregiver Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #06111a;
            --sidebar-blue: #0b243b;
            --card-blue: rgba(13, 38, 64, 0.68);
            --card-soft: rgba(9, 27, 44, 0.84);
            --accent-blue: #2196f3;
            --accent-cyan: #7dd3fc;
            --text-main: #ecfeff;
            --text-dim: #8fa0b5;
            --border-soft: rgba(255, 255, 255, 0.08);
            --danger: #ffb4ab;
            --success: #b9f6ca;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background:
                radial-gradient(circle at top right, rgba(33, 150, 243, 0.18), transparent 24%),
                linear-gradient(135deg, #06111a 0%, #081b2d 55%, #04101a 100%);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0d2640 0%, #06111a 100%);
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            position: sticky;
            top: 0;
            height: 100vh;
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
            color: var(--accent-cyan);
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
            margin-bottom: 8px;
        }

        .nav-link,
        .logout-button {
            width: 100%;
            padding: 14px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-dim);
            text-decoration: none;
            background: transparent;
            border: 0;
            cursor: pointer;
            transition: 0.25s ease;
            font-size: 0.95rem;
        }

        .nav-link:hover,
        .logout-button:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .nav-link.active {
            background: var(--accent-blue);
            color: #fff;
        }

        .nav-link svg,
        .logout-button svg {
            flex-shrink: 0;
        }

        .logout-form {
            margin-top: auto;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .shell {
            max-width: 980px;
            margin: 0 auto;
        }

        .dashboard-header {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 26px;
        }

        .hero,
        .section-card {
            background: var(--card-blue);
            backdrop-filter: blur(15px);
            border: 1px solid var(--border-soft);
            border-radius: 22px;
        }

        .hero {
            padding: 32px;
            margin-bottom: 24px;
        }

        .section-card {
            padding: 24px;
        }

        .eyebrow {
            display: inline-block;
            background: #00427c;
            color: #fff;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 14px;
        }

        h1,
        h2 {
            color: #fff;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 1.15rem;
            margin-bottom: 8px;
        }

        p,
        .meta-text,
        .helper-text,
        .info-line span {
            color: var(--text-dim);
            line-height: 1.6;
        }

        .section-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(33, 150, 243, 0.16);
            color: var(--accent-cyan);
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .patient-name {
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #fff;
        }

        .status-box {
            margin-top: 18px;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid rgba(185, 246, 202, 0.16);
            background: rgba(76, 175, 80, 0.12);
            color: var(--success);
        }

        .error-box {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid rgba(255, 180, 171, 0.18);
            background: rgba(255, 82, 82, 0.12);
            color: var(--danger);
        }

        .pair-form {
            display: grid;
            gap: 14px;
            margin-top: 16px;
        }

        .pair-form label {
            font-size: 0.88rem;
            color: #fff;
            font-weight: 500;
        }

        .pair-form input {
            width: 100%;
            border: 1px solid rgba(125, 211, 252, 0.18);
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 1rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            outline: none;
        }

        .pair-form input:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.12);
        }

        .pair-form button {
            width: fit-content;
            padding: 12px 18px;
            border: 0;
            border-radius: 14px;
            background: var(--accent-blue);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .mini-list {
            list-style: none;
            display: grid;
            gap: 12px;
            margin-top: 12px;
        }

        .mini-list li,
        .info-line {
            padding: 14px 16px;
            border-radius: 14px;
            background: var(--card-soft);
            border: 1px solid var(--border-soft);
        }

        .info-line strong {
            display: block;
            margin-bottom: 4px;
            color: #fff;
            font-size: 0.92rem;
        }

        .section-block {
            margin-bottom: 24px;
            scroll-margin-top: 24px;
        }

        @media (max-width: 960px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                padding: 24px;
            }

            .section-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .sidebar {
                padding: 28px 18px;
            }

            .main-content {
                padding: 18px;
            }

            .hero,
            .section-card {
                padding: 20px;
            }

            h1 {
                font-size: 1.7rem;
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
                <a href="#dashboard" class="nav-link active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 13h6v7H4zM14 4h6v16h-6zM4 4h6v5H4zM14 15h6v5h-6z" fill="currentColor" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('caregiver.live_tracking') }}" class="nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 21c4.97-4.54 8-8.15 8-11a8 8 0 1 0-16 0c0 2.85 3.03 6.46 8 11Z"
                            stroke="currentColor" stroke-width="1.8" />
                        <circle cx="12" cy="10" r="2.5" fill="currentColor" />
                    </svg>
                    <span>Tracking</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#reports" class="nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7 3v18M17 8v13M12 12v9" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                        <path d="M5 21h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <span>Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#notifications" class="nav-link">
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
                <a href="#profile" class="nav-link">
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
        <div class="shell">
            <div class="dashboard-header">Caregiver Dashboard</div>

            <section class="hero" id="dashboard">
                <div class="eyebrow">Caregiver Overview</div>
                <h1>{{ $connectedPatient ? 'Connected to your patient' : 'Connect to a patient' }}</h1>
                <p>
                    {{ $connectedPatient
                        ? 'Your caregiver account is linked successfully. The connected patient is shown below.'
                        : 'Enter the pairing code from the patient dashboard to connect this caregiver account.' }}
                </p>

                @if (session('status'))
                    <div class="status-box">{{ session('status') }}</div>
                @endif
            </section>

            <div class="section-grid">
                <section class="section-card">
                    <div class="pill">Patient Connection</div>

                    @if ($errors->has('pairing_code'))
                        <div class="error-box">{{ $errors->first('pairing_code') }}</div>
                    @endif

                    @if ($connectedPatient)
                        <div class="patient-name">{{ $connectedPatient->name }}</div>
                        <p class="meta-text">
                            This caregiver account is currently linked using the patient pairing code.
                        </p>
                    @else
                        <form method="POST" action="{{ route('caregiver.connect') }}" class="pair-form">
                            @csrf
                            <div>
                                <label for="pairing_code">Patient Pairing Code</label>
                            </div>
                            <input id="pairing_code" name="pairing_code" type="text" maxlength="6"
                                value="{{ old('pairing_code') }}" placeholder="Enter 6-character code" required>
                            <button type="submit">Connect Patient</button>
                        </form>
                        <p class="helper-text">
                            Ask the patient for the pairing code shown on their dashboard, then enter it here.
                        </p>
                    @endif
                </section>

                <section class="section-card">
                    <div class="pill">Account Summary</div>
                    <div class="info-line">
                        <strong>Caregiver</strong>
                        <span>{{ $caregiver->name }}</span>
                    </div>
                    <div class="info-line" style="margin-top: 12px;">
                        <strong>Status</strong>
                        <span>{{ $connectedPatient ? 'Connected to a patient' : 'Waiting for patient connection' }}</span>
                    </div>
                </section>
            </div>

            <section class="section-block section-card" id="tracking">
                <div class="pill">Tracking</div>
                <h2>Tracking</h2>
                <p>
                    {{ $connectedPatient
                        ? 'Tracking content for ' . $connectedPatient->name . ' can appear here once live location features are added.'
                        : 'Connect to a patient first to view tracking details here.' }}
                </p>
            </section>

            <section class="section-block section-card" id="reports">
                <div class="pill">Reports</div>
                <h2>Reports</h2>
                <p>
                    Simple caregiver reports and travel summaries can be shown here for the connected patient.
                </p>
            </section>

            <section class="section-block section-card" id="notifications">
                <div class="pill">Notifications</div>
                <h2>Notifications</h2>
                <p>
                    Route updates, reminders, and alerts can appear here in a smaller notification feed.
                </p>
            </section>

            <section class="section-block section-card" id="profile">
                <div class="pill">Profile</div>
                <h2>Profile</h2>
                <ul class="mini-list">
                    <li>{{ $caregiver->name }}</li>
                    <li>{{ $caregiver->email }}</li>
                    <li>Caregiver account</li>
                </ul>
            </section>
        </div>
    </main>
</body>

</html>
