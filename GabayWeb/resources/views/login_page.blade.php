<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GABAY Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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
            max-width: 900px;
            padding: 20px;
        }

        .login-card {
            display: flex;
            background: #e6e6e6;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            min-height: 550px;
        }

        .brand-side {
            flex: 1;
            background: linear-gradient(rgba(20, 32, 44, 0.8), rgba(20, 32, 44, 0.8)),
                url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80');
            background-size: cover;
            padding: 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .logo-mark {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
        }

        .welcome-content h1 {
            font-size: 3rem;
            line-height: 1.1;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .welcome-content p {
            font-size: 0.9rem;
            line-height: 1.6;
            opacity: 0.85;
            max-width: 300px;
        }

        .form-side {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-side h2 {
            color: #003366;
            text-align: center;
            margin-bottom: 24px;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .form-side p.lead {
            text-align: center;
            color: #5b6470;
            font-size: 0.9rem;
            margin-bottom: 28px;
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

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #f0f0f0;
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

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            margin-bottom: 30px;
            gap: 12px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-weight: 600;
            color: #334155;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: #00335b;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background-color: #004a85;
        }

        .helper-links {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 22px;
            font-size: 0.85rem;
        }

        .helper-links a {
            color: #000;
            text-decoration: none;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
            }

            .brand-side {
                padding: 30px;
                min-height: 250px;
            }

            .form-side {
                padding: 40px 30px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-card">
            <div class="brand-side">
                <div class="logo">
                    <span class="logo-mark">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="8.5" stroke="#7dd3fc" stroke-width="1.8" />
                            <circle cx="12" cy="12" r="3.2" fill="#38bdf8" />
                        </svg>
                    </span>
                    <span>GABAY</span>
                </div>
                <div class="welcome-content">
                    <h1>Hello,<br>welcome</h1>
                    <p>Log in with your registered Gabay account to continue to the correct dashboard for your role.</p>
                </div>
            </div>

            <div class="form-side">
                <h2>Login</h2>

                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
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

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" value="1"> <span>Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="login-btn">Login</button>
                </form>

                <div class="helper-links">
                    Don't have an account?
                    <a href="{{ route('signup.create') }}">Sign up</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(isHidden));
                button.setAttribute('aria-label', `${isHidden ? 'Hide' : 'Show'} password`);

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
    </script>
</body>

</html>
