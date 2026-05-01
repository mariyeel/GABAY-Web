<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GABAY | Patient History</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0d2640 0%, #06111a 100%);
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
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
            padding: 40px;
            overflow-y: auto;
        }

        .shell {
            max-width: 1100px;
        }

        .page-header {
            background: var(--card-blue);
            backdrop-filter: blur(15px);
            border: 1px solid var(--surface-border);
            border-radius: 24px;
            padding: 30px;
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
            margin-bottom: 14px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #fff;
        }

        .page-header p,
        .empty-state p,
        .session-meta,
        .session-detail {
            color: var(--text-dim);
            line-height: 1.6;
        }

        .history-grid {
            display: grid;
            gap: 18px;
        }

        .history-card,
        .empty-state {
            background: var(--card-blue);
            backdrop-filter: blur(15px);
            border: 1px solid var(--surface-border);
            border-radius: 22px;
            padding: 24px;
        }

        .history-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }

        .session-destination {
            font-size: 1.15rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 6px;
        }

        .status-pill {
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-pill.completed {
            background: rgba(76, 175, 80, 0.16);
            color: #b9f6ca;
        }

        .status-pill.ongoing {
            background: rgba(56, 189, 248, 0.16);
            color: #7dd3fc;
        }

        .status-pill.interrupted {
            background: rgba(255, 152, 0, 0.16);
            color: #ffd180;
        }

        .session-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .session-detail {
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .session-detail strong {
            display: block;
            color: #fff;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .empty-state {
            text-align: center;
        }

        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 24px 20px;
            }

            .main-content {
                padding: 20px;
            }

            .session-details {
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
            <li class="nav-item">
                <a href="{{ route('patient.navigation') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 21c4.97-4.54 8-8.15 8-11a8 8 0 1 0-16 0c0 2.85 3.03 6.46 8 11Z"
                            stroke="currentColor" stroke-width="1.8" />
                        <circle cx="12" cy="10" r="2.5" fill="currentColor" />
                    </svg>
                    <span>Navigation</span>
                </a>
            </li>
            <li class="nav-item active">
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
        <div class="shell">
            <section class="page-header">
                <div class="eyebrow">Navigation History</div>
                <h1>Your destination history</h1>
                <p>
                    Review your recent navigation sessions, including where you started, your chosen destination,
                    trip status, and the time each session was recorded.
                </p>
            </section>

            @if ($sessions->isEmpty())
                <section class="empty-state">
                    <h2 style="margin-bottom: 10px; color: #fff;">No trips yet</h2>
                    <p>Your destination history will appear here after you start and finish navigation sessions.</p>
                </section>
            @else
                <section class="history-grid">
                    @foreach ($sessions as $session)
                        <article class="history-card">
                            <div class="history-top">
                                <div>
                                    <div class="session-destination">{{ $session->destination }}</div>
                                    <div class="session-meta">
                                        Started {{ optional($session->start_time)?->toDayDateTimeString() ?? 'Not recorded' }}
                                    </div>
                                </div>
                                <span class="status-pill {{ $session->status }}">{{ $session->status }}</span>
                            </div>

                            <div class="session-details">
                                <div class="session-detail">
                                    <strong>Origin</strong>
                                    <span>{{ $session->origin }}</span>
                                </div>
                                <div class="session-detail">
                                    <strong>Destination</strong>
                                    <span>{{ $session->destination }}</span>
                                </div>
                                <div class="session-detail">
                                    <strong>Start Time</strong>
                                    <span>{{ optional($session->start_time)?->toDayDateTimeString() ?? 'Not recorded' }}</span>
                                </div>
                                <div class="session-detail">
                                    <strong>End Time</strong>
                                    <span>{{ optional($session->end_time)?->toDayDateTimeString() ?? 'Still ongoing' }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>
            @endif
        </div>
    </main>
</body>

</html>
