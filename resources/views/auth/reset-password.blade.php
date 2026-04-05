<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - Pulse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #f8fafc;
            --bg-glass: rgba(255, 255, 255, 0.7);
            --border-glass: rgba(0, 0, 0, 0.08);
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --accent-primary: #7c3aed;
            --accent-secondary: #db2777;
            --accent-gradient: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-pill: 9999px;
            --shadow-glow: 0 8px 25px rgba(124, 58, 237, 0.2);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body {
            background-color: var(--bg-dark);
            background-image:
                radial-gradient(circle at 15% 50%, rgba(124, 58, 237, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(219, 39, 119, 0.08) 0%, transparent 50%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-card {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 440px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            font-weight: 800;
            font-size: 32px;
            letter-spacing: -1px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 40px;
            text-decoration: none;
        }
        .logo img { width: 64px; height: 64px; object-fit: contain; filter: drop-shadow(0 12px 20px rgba(124, 58, 237, 0.3)); }
        .auth-title { font-size: 22px; font-weight: 700; text-align: center; margin-bottom: 12px; }
        .auth-subtitle { font-size: 14px; color: var(--text-secondary); text-align: center; line-height: 1.5; margin-bottom: 32px; }
        
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            border: 1px solid rgba(0,0,0,0.1);
            background: rgba(255,255,255,0.8);
            color: var(--text-primary);
            font-size: 15px;
            transition: var(--transition);
            outline: none;
        }
        .form-input:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1); }
        
        .btn-primary {
            width: 100%;
            padding: 14px;
            border-radius: var(--radius-pill);
            background: var(--accent-gradient);
            color: white;
            font-size: 15px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.2);
            transition: var(--transition);
        }
        .btn-primary:hover { box-shadow: var(--shadow-glow); transform: translateY(-1px); }
        .error-message { color: #ef4444; font-size: 12px; margin-top: 6px; display: block; }
    </style>
</head>
<body>
    <div class="auth-card">
        <a href="/" class="logo">
            <img src="/images/pulse_logo.png" alt="Pulse Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIiBmaWxsPSJub25lIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxNiIgZmlsbD0idXJsKCNncmFkKSIvPjxwYXRoIGQ9Ik0xMCAxN0wxNSAyMkwyMiAxMCIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIzIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48ZGVmcz48bGluZWFyR3JhZGllbnQgaWQ9ImdyYWQiIHgxPSIwIiB5MT0iMCIgeDI9IjMyIiB5Mj0iMzIiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBzdG9wLWNvbG9yPSIjOGI1Y2Y2Ii8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZWM0ODk5Ii8+PC9saW5lYXJHcmFkaWVudD48L2RlZnM+PC9zdmc+'" />
            Pulse
        </a>

        <h1 class="auth-title">Set New Password</h1>
        <p class="auth-subtitle">Choose a robust password that is different from your other services.</p>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" class="form-input" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                @error('email') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <input id="password" type="password" class="form-input" name="password" required autocomplete="new-password" placeholder="••••••••">
                @error('password') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input id="password_confirmation" type="password" class="form-input" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                @error('password_confirmation') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-primary">
                Reset Password
            </button>
        </form>
    </div>
</body>
</html>
