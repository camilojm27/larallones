<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo svg { width: 48px; height: 48px; }
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1E293B;
            text-align: center;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 14px;
            color: #64748B;
            text-align: center;
            margin-bottom: 32px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert-success { background: #D1FAE5; color: #065F46; }
        .alert-error { background: #FEE2E2; color: #991B1B; }
        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 6px;
        }
        input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            font-size: 15px;
            color: #1E293B;
            background: #F8FAFC;
            outline: none;
            transition: border-color 0.15s;
        }
        input:focus { border-color: #6B4C9A; background: white; }
        .field { margin-bottom: 20px; }
        .error-msg {
            font-size: 13px;
            color: #DC2626;
            margin-top: 4px;
        }
        button {
            width: 100%;
            padding: 13px;
            background: #6B4C9A;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.15s;
        }
        button:hover { background: #5A3D87; }
        .success-state {
            text-align: center;
        }
        .success-state .icon { font-size: 48px; margin-bottom: 16px; }
        .success-state p {
            font-size: 15px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #6B4C9A;
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="48" height="48" rx="12" fill="#6B4C9A"/>
                <path d="M24 14C20.13 14 17 17.13 17 21v2h-2v14h18V23h-2v-2c0-3.87-3.13-7-7-7zm0 3c2.21 0 4 1.79 4 4v2h-8v-2c0-2.21 1.79-4 4-4zm0 10a2 2 0 110 4 2 2 0 010-4z" fill="white"/>
            </svg>
        </div>

        @if (session('status'))
            <div class="success-state">
                <div class="icon">✅</div>
                <h1>Password Reset!</h1>
                <p>Your password has been reset successfully. You can now log in to the app with your new password.</p>
                <p style="font-size: 13px; color: #94A3B8;">You may close this window and return to the app.</p>
            </div>
        @else
            <h1>Reset Password</h1>
            <p class="subtitle">Enter your new password below.</p>

            @if ($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        required
                        autocomplete="email"
                    >
                    @error('email')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">New Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        minlength="8"
                    >
                    @error('password')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        minlength="8"
                    >
                </div>

                <button type="submit">Reset Password</button>
            </form>
        @endif
    </div>
</body>
</html>
