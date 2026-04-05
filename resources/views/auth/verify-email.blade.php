<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Pulse</title>
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
            display: flex; align-items: center; justify-content: center; gap: 16px; font-weight: 800; font-size: 32px; letter-spacing: -1px;
            background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 40px; text-decoration: none;
        }
        .logo img { width: 64px; height: 64px; object-fit: contain; filter: drop-shadow(0 12px 20px rgba(124, 58, 237, 0.3)); }
        .auth-title { font-size: 22px; font-weight: 700; text-align: center; margin-bottom: 12px; }
        .auth-subtitle { font-size: 14px; color: var(--text-secondary); text-align: center; line-height: 1.5; margin-bottom: 32px; }
        
        .btn-primary {
            width: 100%; padding: 14px; border-radius: var(--radius-pill); background: var(--accent-gradient); color: white;
            font-size: 15px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.2); transition: var(--transition);
        }
        .btn-primary:hover { box-shadow: var(--shadow-glow); transform: translateY(-1px); }
        
        .btn-outline {
            width: 100%; padding: 12px; border-radius: var(--radius-pill); background: transparent; color: var(--text-muted);
            font-size: 14px; font-weight: 600; border: 1px solid var(--border-glass); cursor: pointer; transition: var(--transition); text-decoration: none; display: block; text-align: center;
        }
        .btn-outline:hover { background: rgba(0,0,0,0.02); color: var(--text-primary); }

        .status-message {
            background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 12px; border-radius: var(--radius-md); font-size: 13px; font-weight: 500; text-align: center; margin-bottom: 24px;
        }
        .actions { display: flex; flex-direction: column; gap: 12px; }
    </style>
</head>
<body>
    <div class="auth-card">
        <a href="/" class="logo">
            <img src="/images/pulse_logo.png" alt="Pulse Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIiBmaWxsPSJub25lIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxNiIgZmlsbD0idXJsKCNncmFkKSIvPjxwYXRoIGQ9Ik0xMCAxN0wxNSAyMkwyMiAxMCIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIzIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48ZGVmcz48bGluZWFyR3JhZGllbnQgaWQ9ImdyYWQiIHgxPSIwIiB5MT0iMCIgeDI9IjMyIiB5Mj0iMzIiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBzdG9wLWNvbG9yPSIjOGI1Y2Y2Ii8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZWM0ODk5Ii8+PC9saW5lYXJHcmFkaWVudD48L2RlZnM+PC9zdmc+'" />
            Pulse
        </a>

        <h1 class="auth-title">Verify Email</h1>
        <p class="auth-subtitle">Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent you. If you didn't receive it, we can send it again.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="status-message">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-outline">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</body>
</html>
