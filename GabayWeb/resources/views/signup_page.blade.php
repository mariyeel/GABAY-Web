<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabay Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: radial-gradient(circle at center, #1a2a3a, #0a0e14);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px 0;
        }

        .container {
            width: 100%;
            max-width: 980px;
            padding: 20px;
        }

        .card {
            display: flex;
            background: #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            min-height: 620px;
        }

        .left-panel {
            flex: 1;
            background: linear-gradient(rgba(20, 30, 48, 0.8), rgba(36, 59, 85, 0.8)),
                url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80');
            background-size: cover;
            padding: 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo {
            font-weight: 700;
            letter-spacing: 2px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .welcome-text h1 {
            font-size: 3rem;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .welcome-text p {
            font-size: 0.95rem;
            opacity: 0.85;
            max-width: 300px;
            line-height: 1.6;
        }

        .right-panel {
            flex: 1;
            padding: 42px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel h2 {
            color: #004071;
            text-align: center;
            margin-bottom: 24px;
            font-size: 1.8rem;
        }

        .alert {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 0.88rem;
            line-height: 1.45;
        }

        .alert-error {
            background: rgba(220, 38, 38, 0.12);
            border: 1px solid rgba(220, 38, 38, 0.25);
            color: #9f1239;
        }

        .alert ul {
            margin-left: 18px;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f5f5f5;
            outline: none;
        }

        .input-group input:focus {
            border-color: #1b6ca8;
            box-shadow: 0 0 0 3px rgba(27, 108, 168, 0.12);
        }

        .password-field {
            position: relative;
        }

        .password-field input {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #5b6572;
            cursor: pointer;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: #00335b;
        }

        .toggle-password:focus-visible {
            outline: 2px solid #004a85;
            outline-offset: 2px;
            border-radius: 6px;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 0.85rem;
            margin-bottom: 22px;
            font-weight: 600;
            color: #334155;
        }

        .checkbox-group input {
            margin-top: 2px;
        }

        .signup-btn {
            width: 100%;
            padding: 14px;
            background-color: #00335b;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .signup-btn:hover {
            background-color: #004a85;
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            font-size: 0.8rem;
            color: #666;
        }

        .footer-text a {
            color: #000;
            text-decoration: none;
            font-weight: 700;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 20px;
        }

        .role-modal {
            background: linear-gradient(135deg, #005a9e, #00335b);
            width: 420px;
            max-width: 100%;
            padding: 36px;
            border-radius: 20px;
            text-align: center;
            color: white;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        }

        .role-modal h3 {
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .role-modal p {
            font-size: 0.92rem;
            opacity: 0.88;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .role-options {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .role-card {
            position: relative;
            width: 120px;
            height: 120px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.05);
        }

        .role-card.active {
            border-color: #4facfe;
            background: rgba(79, 172, 254, 0.14);
            transform: translateY(-2px);
        }

        .check-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #4facfe;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            font-size: 12px;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .role-card.active .check-badge {
            display: flex;
        }

        .role-icon {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 15px;
            align-items: center;
        }

        .back-btn {
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
        }

        .select-btn {
            background: #002a4d;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 10px 40px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .card {
                flex-direction: column;
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 32px 22px;
            }

        }
    </style>
</head>

<body>
    @php
        $selectedRole = old('role', 'caregiver');
    @endphp

    <div class="container">
        <div class="card">
            <div class="left-panel">
                <div class="logo">
                    <span class="logo-icon">G</span> GABAY
                </div>
                <div class="welcome-text">
                    <h1>Hello,<br>Join Us</h1>
                    <p>Create an account and set up safe, guided navigation between the visually impaired user and their
                        caregiver.</p>
                </div>
            </div>

            <div class="right-panel">
                <h2>Sign up</h2>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="signupForm" method="POST" action="{{ route('signup.store') }}">
                    @csrf
                    <input type="hidden" name="role" id="roleInput" value="{{ $selectedRole }}">

                    <div class="input-group">
                        <label for="name">Full Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}"
                            placeholder="Enter your full name" required>
                    </div>

                    <div class="input-group">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            placeholder="Enter your email" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <div class="password-field">
                            <input id="password" name="password" type="password" placeholder="Enter your password"
                                required>
                            <button type="button" class="toggle-password" data-target="password"
                                aria-label="Show password" aria-pressed="false">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="password-field">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                placeholder="Confirm your password" required>
                            <button type="button" class="toggle-password" data-target="password_confirmation"
                                aria-label="Show confirm password" aria-pressed="false">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" value="1"
                            {{ old('terms') ? 'checked' : '' }} required>
                        <label for="terms">I agree to the terms and conditions</label>
                    </div>

                    <button type="button" class="signup-btn" id="openRoleModalBtn">Sign up</button>
                </form>

                <p class="footer-text">Already have an account? <a href="{{ route('login.create') }}">Login</a></p>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="roleModal">
        <div class="role-modal">
            <h3>Select user type</h3>
            <p>Choose the role that matches the account you are creating. This maps directly to the <code>role</code>
                field in your <code>users</code> table.</p>

            <div class="role-options">
                <div class="role-card {{ $selectedRole === 'caregiver' ? 'active' : '' }}" data-role="caregiver">
                    <div class="check-badge">&#10003;</div>
                    <div class="role-icon">C</div>
                    <p>Caregiver</p>
                </div>

                <div class="role-card {{ $selectedRole === 'vi' ? 'active' : '' }}" data-role="vi">
                    <div class="check-badge">&#10003;</div>
                    <div class="role-icon">N</div>
                    <p>Navigator</p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="back-btn" id="closeRoleModalBtn">&larr;</button>
                <button type="button" class="select-btn" id="confirmRoleBtn">Continue</button>
            </div>
        </div>
    </div>

    <script>
        const signupForm = document.getElementById('signupForm');
        const roleModal = document.getElementById('roleModal');
        const roleInput = document.getElementById('roleInput');
        const roleCards = document.querySelectorAll('.role-card');
        let pendingRole = roleInput.value || 'caregiver';

        function openModal() {
            roleModal.style.display = 'flex';
        }

        function closeModal() {
            roleModal.style.display = 'none';
        }

        function selectRole(role) {
            pendingRole = role;
            roleCards.forEach(card => {
                card.classList.toggle('active', card.dataset.role === role);
            });
        }

        document.getElementById('openRoleModalBtn').addEventListener('click', () => {
            if (!signupForm.reportValidity()) {
                return;
            }

            openModal();
        });
        document.getElementById('closeRoleModalBtn').addEventListener('click', closeModal);
        document.getElementById('confirmRoleBtn').addEventListener('click', () => {
            roleInput.value = pendingRole;
            closeModal();
            signupForm.submit();
        });

        roleCards.forEach(card => {
            card.addEventListener('click', () => selectRole(card.dataset.role));
        });

        roleModal.addEventListener('click', event => {
            if (event.target === roleModal) {
                closeModal();
            }
        });

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(isHidden));
                button.setAttribute(
                    'aria-label',
                    `${isHidden ? 'Hide' : 'Show'} ${button.dataset.target === 'password' ? 'password' : 'confirm password'}`
                );

                button.innerHTML = isHidden ?
                    `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m3 3 18 18" />
                        <path d="M10.6 10.6A3 3 0 0 0 12 15a3 3 0 0 0 2.4-4.4" />
                        <path d="M9.4 5.2A9.8 9.8 0 0 1 12 5c6.4 0 10 7 10 7a17.7 17.7 0 0 1-3.2 4.2" />
                        <path d="M6.2 6.2A17.2 17.2 0 0 0 2 12s3.6 7 10 7a9.7 9.7 0 0 0 2.8-.4" />
                      </svg>` :
                    `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                        <circle cx="12" cy="12" r="3" />
                      </svg>`;
            });
        });

        selectRole(pendingRole);
    </script>
</body>

</html>
