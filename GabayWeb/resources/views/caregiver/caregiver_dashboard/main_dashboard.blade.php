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

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            min-height: calc(100vh - 32px);
            padding: 2rem;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            background: radial-gradient(circle at right, #0C65A9 0%, transparent 80%), var(--glass);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
        }

        /* LOGO */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .logo-icon {
            width: 30px;
            height: 30px;
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-text {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        /* PROFILE */
        .user-profile {
            text-align: center;
            margin-top: 5%;
            margin-bottom: 15%;
        }

        .user-profile h3 {
            font-size: 15px;
            font-weight: 500;
        }

        .user-profile p {
            font-size: 12px;
            color: #ffffff69;
        }

        .avatar {
            width: 80px;
            border-radius: 50%;
            border: 3px solid #2a85ff;
        }

        /* NAV */
        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            text-decoration: none;
            color: #ffffff;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }

        .nav-menu a i {
            font-size: 16px;
            min-width: 18px;
            color: #5ECCFF;
        }

        .nav-menu a:hover {
            background: #2a73aa6e;
        }

        .nav-menu a.active {
            background: #2A73AA;
        }

        /* LOGOUT */
        .logout-btn {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            width: 100%;
            background: transparent;
            border: none;
            color: #ffffff;
            cursor: pointer;
            border-radius: 8px;
            font-size: 14px;
        }

        .logout-btn:hover {
            color: #5ECCFF;
        }

        /* MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            width: 400px;
            padding: 40px;
            text-align: center;
            border-radius: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .modal-icon {
            font-size: 40px;
            color: #00AEFF;
        }

        .modal-content h2 {
            font-size: 22px;
            font-weight: 600;
        }

        .modal-content p {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 20px;
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
            width: 100%;
        }

        .btn-cancel,
        .btn-logout-confirm {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-cancel {
            background: #03355C;
            color: white;
            border: 1px solid var(--border);
        }

        .btn-logout-confirm {
            background: #00AEFF;
            color: white;
        }

        .btn-cancel:hover {
            background: #03355ca1;
        }

        .btn-logout-confirm:hover {
            background: #008ecc;
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

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <img src="../images/GABAY_Logo.png" alt="GABAY Logo">
            </div>
            <span class="logo-text">GABAY</span>
        </div>

        <div class="user-profile">
            <div class="avatar">{{ $initials }}</div>
            <h3>{{ $caregiver->name }}</h3>
            <p>New York, USA</p>
        </div>

        <nav class="nav-menu">
            <a href="dashboard.html"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="tracking.html"><i class="fa-solid fa-location-crosshairs"></i> Tracking</a>
            <a href="reports.html"><i class="fa-solid fa-route"></i> Reports</a>
            <a href="notification.html"><i class="fa-solid fa-bell"></i> Notifications</a>
            <a href="profile.html" class="active"><i class="fa-solid fa-user"></i> Profile</a>
        </nav>

        <form class="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn" onclick="openModal()">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
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






    <!-- LOGOUT MODAL -->
    <div class="modal-overlay" id="logoutModal">
        <div class="glass-card modal-content">
            <i class="fa-solid fa-right-from-bracket modal-icon"></i>
            <h2>Logout Account?</h2>
            <p>Are you sure you want to logout your account?</p>

            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button class="btn-logout-confirm">Logout</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('logoutModal');

        function openModal() {
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>
