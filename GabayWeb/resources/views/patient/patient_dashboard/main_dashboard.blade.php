<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GABAY | Patient Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #021B33;
            color: #fff;
        }

        .page {
            min-height: 100vh;
            padding: 16px;
        }

        .layout {
            display: flex;
            flex-direction: column;
            gap: 16px;
            min-height: calc(100vh - 32px);
        }

        .sidebar {
            width: 100%;
            background: linear-gradient(180deg, #0A2D4D, #0B3158);
            border-radius: 28px;
            border: 1px solid rgba(31, 93, 145, 0.4);
            box-shadow: 0 0 30px rgba(0, 140, 255, 0.08);
            padding: 28px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 32px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: rgba(14, 165, 233, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3BB8FF;
        }

        .logo h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0;
            color: #fff;
        }

        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
        }

        .avatar {
            width: 110px;
            height: 110px;
            border-radius: 999px;
            border: 1px solid rgba(83, 191, 255, 0.3);
            background: linear-gradient(180deg, #154A7D, #123A64);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 2px 12px rgba(255, 255, 255, 0.06);
        }

        .avatar span {
            font-size: 48px;
            font-weight: 600;
            color: #fff;
        }

        .profile h2 {
            margin-top: 20px;
            font-size: 16px;
            font-weight: 600;
        }

        .profile p {
            color: #82B3D8;
            font-size: 14px;
            margin-top: 4px;
        }

        .nav {
            display: grid;
            gap: 12px;
        }

        .sidebar-item,
        .logout-button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border-radius: 16px;
            border: 0;
            background: transparent;
            color: #B6D4EE;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .sidebar-item.active {
            background: #2A6CA2;
            color: #fff;
            box-shadow: 0 0 20px rgba(59, 184, 255, 0.15);
        }

        .sidebar-item:hover,
        .logout-button:hover {
            background: rgba(26, 78, 125, 0.4);
        }

        .main {
            flex: 1;
            min-width: 0;
        }

        .main-title {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .top-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .welcome-card,
        .navigation-card,
        .status-card,
        .history-section {
            border: 1px solid rgba(46, 121, 182, 0.3);
            box-shadow: 0 0 25px rgba(0, 140, 255, 0.08);
            transition: 0.3s ease;
        }

        .welcome-card:hover,
        .navigation-card:hover,
        .status-card:hover {
            transform: scale(1.01);
        }

        .welcome-card {
            background: linear-gradient(135deg, #0A3560, #0C3E6D);
            border-radius: 28px;
            padding: 28px;
        }

        .badge {
            display: inline-flex;
            padding: 4px 16px;
            border-radius: 999px;
            background: #1C6BAA;
            color: #D7EEFF;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .welcome-card h2 {
            font-size: 40px;
            line-height: 1.3;
            font-weight: 700;
        }

        .welcome-card p {
            margin-top: 20px;
            color: #8AB5D8;
            font-size: 18px;
        }

        .navigation-card {
            background: linear-gradient(135deg, #103F6C, #114978);
            border-radius: 28px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(43, 103, 154, 0.5);
            color: #7DD3FC;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
        }

        .navigation-card h3 {
            font-size: 30px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .navigation-card p,
        .section-subtitle,
        .muted {
            color: #87B3D6;
            line-height: 1.6;
        }

        .primary-action {
            margin-top: 32px;
            height: 58px;
            border: 0;
            border-radius: 12px;
            background: #3A6F9B;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 18px rgba(0, 0, 0, 0.16);
            transition: 0.3s ease;
        }

        .primary-action:hover {
            background: #4D83B0;
        }

        .status-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        .status-card {
            background: linear-gradient(135deg, #0C3B66, #0E4777);
            border-radius: 24px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .status-icon {
            min-width: 72px;
            height: 72px;
            border-radius: 999px;
            background: rgba(26, 91, 139, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #59C6FF;
        }

        .status-card p {
            color: #8DB5D8;
            margin-bottom: 8px;
        }

        .status-card h3 {
            font-size: 40px;
            font-weight: 700;
        }

        .online-pill {
            display: inline-flex;
            background: #0FB3FF;
            color: #fff;
            font-size: 14px;
            padding: 8px 20px;
            border-radius: 999px;
            font-weight: 500;
            box-shadow: 0 0 15px rgba(14, 165, 233, 0.4);
        }

        .pairing-code {
            font-size: 34px;
            line-height: 1;
            font-weight: 700;
            letter-spacing: 6px;
            color: #D6ECFF;
            word-break: break-word;
        }

        .caregiver-list {
            display: grid;
            gap: 12px;
            width: 100%;
        }

        .caregiver-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: #D6ECFF;
        }

        .caregiver-row strong {
            display: block;
            font-size: 15px;
        }

        .caregiver-row span {
            display: block;
            color: #9CC3E3;
            font-size: 13px;
            margin-top: 2px;
        }

        .history-section {
            margin-top: 20px;
            background: linear-gradient(135deg, #0B3258, #0C3B67);
            border-radius: 28px;
            padding: 24px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .section-header h2 {
            font-size: 30px;
            font-weight: 700;
        }

        .secondary-action {
            background: #3C6D97;
            color: #fff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            transition: 0.3s ease;
        }

        .secondary-action:hover {
            background: #4D83B0;
        }

        .table-header {
            display: none;
            grid-template-columns: repeat(4, 1fr);
            padding: 0 20px 16px;
            color: #9CC3E3;
            font-size: 14px;
            font-weight: 500;
        }

        .route-rows {
            display: grid;
            gap: 16px;
        }

        .route-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            background: rgba(21, 66, 107, 0.6);
            border: 1px solid rgba(45, 106, 154, 0.4);
            border-radius: 16px;
            padding: 20px;
            transition: 0.3s ease;
        }

        .route-row:hover {
            background: rgba(27, 78, 125, 0.6);
        }

        .route-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #D6ECFF;
            min-width: 0;
        }

        .route-cell svg {
            flex: 0 0 auto;
            color: #8ED5FF;
        }

        .empty-state {
            background: rgba(21, 66, 107, 0.6);
            border: 1px solid rgba(45, 106, 154, 0.4);
            border-radius: 16px;
            padding: 20px;
            color: #D6ECFF;
        }

        @media (min-width: 768px) {
            .status-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .layout {
                flex-direction: row;
            }

            .sidebar {
                width: 260px;
                flex: 0 0 260px;
            }

            .table-header,
            .route-row {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (min-width: 1280px) {
            .top-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .main-title,
            .welcome-card h2 {
                font-size: 32px;
            }

            .section-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    @php
        $nameParts = preg_split('/\s+/', trim($patient->name ?? '')) ?: [];
        $initials = collect($nameParts)
            ->filter()
            ->take(2)
            ->map(fn($part) => strtoupper(mb_substr($part, 0, 1)))
            ->join('');
        $initials = $initials !== '' ? $initials : strtoupper(mb_substr($patient->email ?? 'P', 0, 1));
        $firstName = trim(explode(' ', trim($patient->name))[0] ?? $patient->name);
        $caregivers = collect($connectedCaregivers ?? []);
        $sessions = collect($recentSessions ?? []);
    @endphp

    <div class="page">
        <div class="layout">
            <aside class="sidebar">
                <div>
                    <div class="logo">
                        <div class="logo-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 2 4 22l8-4 8 4-8-20Z" stroke="currentColor" stroke-width="1.9"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h1>GABAY</h1>
                    </div>

                    <div class="profile">
                        <div class="avatar">
                            <span>{{ $initials }}</span>
                        </div>
                        <h2>{{ $patient->name }}</h2>
                        <p>Patient Account</p>
                    </div>

                    <nav class="nav">
                        <a class="sidebar-item active" href="{{ route('dashboard.patient') }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 13h6v7H4zM14 4h6v16h-6zM4 4h6v5H4zM14 15h6v5h-6z"
                                    fill="currentColor" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                        <a class="sidebar-item" href="{{ route('patient.navigation') }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 21c4.97-4.54 8-8.15 8-11a8 8 0 1 0-16 0c0 2.85 3.03 6.46 8 11Z"
                                    stroke="currentColor" stroke-width="1.8" />
                                <circle cx="12" cy="10" r="2.5" fill="currentColor" />
                            </svg>
                            <span>Navigation</span>
                        </a>
                        <a class="sidebar-item" href="{{ route('patient.history') }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path
                                    d="M7 3v3M17 3v3M5 8h14M6 5h12a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span>Route History</span>
                        </a>
                        <a class="sidebar-item" href="#">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8" />
                                <path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" />
                            </svg>
                            <span>Profile</span>
                        </a>
                    </nav>
                </div>

                <form method="POST" action="{{ route('logout') }}"
                    onsubmit="return confirm('Are you sure you want to log out?');">
                    @csrf
                    <button type="submit" class="logout-button">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M15 17l5-5-5-5" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M20 12H9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M11 20H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </aside>

            <main class="main">
                <h1 class="main-title">Dashboard</h1>

                <div class="top-grid">
                    <section class="welcome-card">
                        <div class="badge">Navigator</div>
                        <h2>
                            Hi, {{ $firstName }}<br>
                            Welcome back!
                        </h2>
                        <p>
                            {{ $connectedCaregiverCount }}
                            {{ \Illuminate\Support\Str::plural('caregiver', $connectedCaregiverCount) }}
                            {{ $connectedCaregiverCount === 1 ? 'is' : 'are' }} connected.
                        </p>
                    </section>

                    <section class="navigation-card">
                        <div>
                            <div class="feature-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 2 4 22l8-4 8 4-8-20Z" stroke="currentColor" stroke-width="1.9"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3>Assigned navigation</h3>
                            <p>
                                Your caregiver can set your destination. Open navigation to view the assigned route
                                and start live guidance.
                            </p>
                        </div>

                        <a class="primary-action" href="{{ route('patient.navigation') }}">Open Navigation</a>
                    </section>
                </div>

                <div class="status-grid">
                    <section class="status-card">
                        <div class="status-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4.93 10.93a10 10 0 0 1 14.14 0M8.46 14.46a5 5 0 0 1 7.08 0"
                                    stroke="currentColor" stroke-width="1.9" stroke-linecap="round" />
                                <circle cx="12" cy="18" r="1.5" fill="currentColor" />
                            </svg>
                        </div>
                        <div>
                            <p>Account Status</p>
                            <div class="online-pill">Online</div>
                        </div>
                    </section>

                    @if ($isPairingCodeValid)
                        <section class="status-card">
                            <div class="status-icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M8 11V7a4 4 0 0 1 8 0v4" stroke="currentColor" stroke-width="1.9"
                                        stroke-linecap="round" />
                                    <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor"
                                        stroke-width="1.9" />
                                </svg>
                            </div>
                            <div>
                                <p>Pairing Code</p>
                                <div class="pairing-code">{{ $patient->pairing_code }}</div>
                                @if ($patient->code_expires_at)
                                    <div class="muted">Valid until {{ $patient->code_expires_at->format('M j, Y g:i A') }}</div>
                                @endif
                            </div>
                        </section>
                    @endif

                    <section class="status-card">
                        <div class="status-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" stroke="currentColor"
                                    stroke-width="1.9" stroke-linecap="round" />
                                <circle cx="9.5" cy="7" r="4" stroke="currentColor" stroke-width="1.9" />
                                <path d="M20 8v6M23 11h-6" stroke="currentColor" stroke-width="1.9"
                                    stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="caregiver-list">
                            <p>Caregivers</p>
                            @forelse ($caregivers as $caregiver)
                                @php
                                    $phone = $caregiver->phone_number
                                        ?? $caregiver->phone
                                        ?? $caregiver->contact_number
                                        ?? null;
                                @endphp
                                <div class="caregiver-row">
                                    <div>
                                        <strong>{{ $caregiver->name }}</strong>
                                        @if ($phone)
                                            <span>{{ $phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="muted">No connected caregivers.</div>
                            @endforelse
                        </div>
                    </section>
                </div>

                <section class="history-section">
                    <div class="section-header">
                        <div>
                            <h2>Route History</h2>
                            <p class="section-subtitle">Recent</p>
                        </div>
                        <a class="secondary-action" href="{{ route('patient.history') }}">View all</a>
                    </div>

                    <div class="table-header">
                        <p>Current Location</p>
                        <p>Destination</p>
                        <p>Status</p>
                        <p>Date</p>
                    </div>

                    <div class="route-rows">
                        @forelse ($sessions as $session)
                            <article class="route-row">
                                <div class="route-cell">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 21c4.97-4.54 8-8.15 8-11a8 8 0 1 0-16 0c0 2.85 3.03 6.46 8 11Z"
                                            stroke="currentColor" stroke-width="1.8" />
                                        <circle cx="12" cy="10" r="2.5" fill="currentColor" />
                                    </svg>
                                    <p>{{ $session->origin }}</p>
                                </div>
                                <div class="route-cell">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 21c4.97-4.54 8-8.15 8-11a8 8 0 1 0-16 0c0 2.85 3.03 6.46 8 11Z"
                                            stroke="currentColor" stroke-width="1.8" />
                                        <circle cx="12" cy="10" r="2.5" fill="currentColor" />
                                    </svg>
                                    <p>{{ $session->destination }}</p>
                                </div>
                                <div class="route-cell">
                                    <p>{{ ucfirst($session->status) }}</p>
                                </div>
                                <div class="route-cell">
                                    <p>{{ optional($session->start_time ?? $session->created_at)->format('F j, Y') }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="empty-state">No route history yet.</div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>

</html>
