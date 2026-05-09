<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GABAY | Patient Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #06111a;
            --sidebar-blue: #0b243b;
            --card-blue: rgba(13, 38, 64, 0.6);
            --accent-blue: #2196f3;
            --text-main: #ecfeff;
            --text-dim: #8fa0b5;
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
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-dim);
            transition: 0.3s;
        }

        .nav-item svg {
            flex-shrink: 0;
        }

        .nav-item.active {
            background: var(--accent-blue);
            color: white;
        }

        .logout-form {
            margin-top: auto;
        }

        .logout-button {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
        }

        .main-content {
            flex-grow: 1;
            min-width: 0;
            height: 100vh;
            padding: 40px;
            overflow-y: auto;
        }

        .dashboard-header {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .shell {
            max-width: 1100px;
        }

        .hero {
            background: var(--card-blue);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 24px;
        }

        .eyebrow {
            display: inline-block;
            background: #00427c;
            color: #fff;
            padding: 5px 15px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: #fff;
        }

        .hero p {
            color: var(--text-dim);
            line-height: 1.6;
            max-width: 800px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 24px;
        }

        .card {
            background: var(--card-blue);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 28px;
        }

        .card h2 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #fff;
        }

        .pairing-code {
            display: block;
            margin: 20px 0;
            padding: 25px;
            border-radius: 15px;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(33, 150, 243, 0.3);
            font-size: 2.5rem;
            letter-spacing: 8px;
            font-weight: 700;
            text-align: center;
            color: #fff;
            text-shadow: 0 0 15px rgba(33, 150, 243, 0.5);
        }

        .meta {
            color: var(--text-dim);
            font-size: 0.85rem;
            text-align: center;
        }

        .caregiver-count {
            margin-top: 18px;
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(33, 150, 243, 0.12);
            border: 1px solid rgba(33, 150, 243, 0.18);
            text-align: center;
        }

        .caregiver-count strong {
            display: block;
            color: #fff;
            font-size: 1.8rem;
            margin-bottom: 6px;
        }

        .caregiver-count span {
            color: var(--text-dim);
            font-size: 0.9rem;
        }

        ul {
            list-style: none;
            color: var(--text-dim);
        }

        ul li {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        ul li::before {
            /* content: "�"; */
            color: var(--accent-blue);
            font-weight: bold;
        }

        .pill {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            background: #2196f3;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
                flex-basis: 80px;
                padding: 20px 10px;
            }

            .sidebar span,
            .profile-section {
                display: none;
            }

            .grid {
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
            <li class="nav-item active">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 13h6v7H4zM14 4h6v16h-6zM4 4h6v5H4zM14 15h6v5h-6z" fill="currentColor" />
                </svg>
                <span>Dashboard</span>
            </li>
            <li class="nav-item">
                <a href="{{ route('patient.navigation') }}" style="display:flex; align-items:center; gap:15px; color:inherit; text-decoration:none; width:100%;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 21c4.97-4.54 8-8.15 8-11a8 8 0 1 0-16 0c0 2.85 3.03 6.46 8 11Z" stroke="currentColor"
                            stroke-width="1.8" />
                        <circle cx="12" cy="10" r="2.5" fill="currentColor" />
                    </svg>
                    <span>Navigation</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('patient.history') }}" style="display:flex; align-items:center; gap:15px; color:inherit; text-decoration:none; width:100%;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path
                            d="M7 3v3M17 3v3M5 8h14M6 5h12a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>History</span>
                </a>
            </li>
            <li class="nav-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8" />
                    <path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
                <span>Profile</span>
            </li>
        </ul>

        <form class="logout-form" method="POST" action="{{ route('logout') }}"
            onsubmit="return confirm('Are you sure you want to log out?');">
            @csrf
            <button type="submit" class="nav-item logout-button">
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
        <div class="dashboard-header">Dashboard</div>

        <div class="shell">
            <section class="hero">
                <div class="eyebrow">Patient Dashboard</div>
                <h1>Welcome to Gabay</h1>
                <p>
                    This dashboard is for the visually impaired or navigator user. Share the pairing code below with
                    your caregiver so they can connect to your account and assist with tracking and navigation.
                </p>
            </section>

            <div class="grid">
                <section class="card">
                    <div class="pill">Account ready</div>
                    <h2>Caregiver Pairing Code</h2>

                    @if (!empty($patient->pairing_code))
                        <div class="pairing-code">{{ $patient->pairing_code }}</div>
                        <div class="meta">
                            Valid until {{ optional($patient->code_expires_at)?->toDayDateTimeString() }}
                        </div>
                    @else
                        <p style="color: var(--text-dim); font-size: 0.9rem;">
                            No new pairing code was found in this account. Create or refresh the pairing code from your
                            patient account settings when needed.
                        </p>
                    @endif

                    <div class="caregiver-count">
                        <strong>{{ $connectedCaregiverCount }}</strong>
                        <span>
                            {{ \Illuminate\Support\Str::plural('caregiver', $connectedCaregiverCount) }} currently connected to your account
                        </span>
                    </div>
                </section>

                <section class="card">
                    <h2>Next Steps</h2>
                    <ul>
                        <li>Share the pairing code with your caregiver.</li>
                        <li>Use the smart cane or patient device to start location sharing.</li>
                        <li>Complete pairing before using live tracking features.</li>
                    </ul>
                </section>
            </div>
        </div>
    </main>
</body>

</html>
