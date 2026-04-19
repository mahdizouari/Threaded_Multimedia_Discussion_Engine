<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Pulse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

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
            max-width: 820px; /* Refined width for better density */
            padding: 24px 40px; /* Compact padding to remove scroll */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            margin: auto;
        }

        .form-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .form-cols {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .auth-card {
                padding: 24px;
                margin: 16px;
            }
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
            margin-bottom: 24px; /* Reduced from 40px */
            text-decoration: none;
            transition: var(--transition);
        }

        .logo img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            filter: drop-shadow(0 12px 20px rgba(124, 58, 237, 0.3));
            transition: var(--transition);
        }

        .logo:hover {
            transform: translateY(-2px);
        }

        .logo:hover img {
            transform: scale(1.08) rotate(-3deg);
        }

        .auth-title {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 13px; /* Slightly smaller */
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 24px; /* Reduced from 32px */
            font-weight: 500;
        }

        .form-row {
            display: flex;
            gap: 12px;
            margin-bottom: 14px;
        }

        .form-row>.form-group {
            margin-bottom: 0;
            flex: 1;
        }

        .form-group {
            margin-bottom: 14px; /* Reduced from 20px */
        }

        .form-label {
            display: block;
            font-size: 12px; /* ISO Readability tweak */
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            border: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.8);
            color: var(--text-primary);
            font-size: 14px; /* Slightly smaller for density */
            transition: var(--transition);
            outline: none;
        }

        .form-input:focus {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        /* ISO Staggered Animations */
        @keyframes staggeredEntrance {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stagger-1 { animation: staggeredEntrance 0.4s ease forwards; opacity: 0; }
        .stagger-2 { animation: staggeredEntrance 0.4s ease 0.1s forwards; opacity: 0; }
        .stagger-3 { animation: staggeredEntrance 0.4s ease 0.2s forwards; opacity: 0; }
        .stagger-4 { animation: staggeredEntrance 0.4s ease 0.3s forwards; opacity: 0; }
        .stagger-5 { animation: staggeredEntrance 0.4s ease 0.4s forwards; opacity: 0; }
        .stagger-6 { animation: staggeredEntrance 0.4s ease 0.5s forwards; opacity: 0; }

        .input-group {
            display: flex;
            gap: 8px;
        }

        .btn-secondary {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            background: rgba(124, 58, 237, 0.1);
            color: var(--accent-primary);
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-secondary:hover {
            background: rgba(124, 58, 237, 0.2);
        }

        .interests-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .interest-pill {
            padding: 6px 12px;
            border-radius: var(--radius-pill);
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: var(--transition);
            user-select: none;
        }

        .interest-pill.active {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 2px 10px rgba(124, 58, 237, 0.2);
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            border-radius: var(--radius-pill);
            background: var(--accent-gradient);
            color: white;
            font-size: 15px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.2);
            transition: var(--transition);
            margin-top: 10px;
        }

        .btn-primary:hover {
            box-shadow: var(--shadow-glow);
            transform: translateY(-1px);
        }

        .auth-links {
            margin-top: 16px; /* Reduced from 24px */
            text-align: center;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .auth-links a {
            color: var(--accent-primary);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .auth-links a:hover {
            color: var(--accent-secondary);
        }

        .form-input.is-invalid {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.02);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .error-message {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #b91c1c;
            background: rgba(239, 68, 68, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 8px;
            animation: slideDown 0.3s ease-out forwards;
            opacity: 0;
            transform: translateY(-5px);
        }

        @keyframes slideDown {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    <div class="auth-card">
        <a href="/" class="logo">
            <img src="/images/pulse_logo.png" alt="Pulse Logo"
                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIiBmaWxsPSJub25lIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxNiIgZmlsbD0idXJsKCNncmFkKSIvPjxwYXRoIGQ9Ik0xMCAxN0wxNSAyMkwyMiAxMCIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIzIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48ZGVmcz48bGluZWFyR3JhZGllbnQgaWQ9ImdyYWQiIHgxPSIwIiB5MT0iMCIgeDI9IjMyIiB5Mj0iMzIiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBzdG9wLWNvbG9yPSIjOGI1Y2Y2Ii8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZWM0ODk5Ii8+PC9saW5lYXJHcmFkaWVudD48L2RlZnM+PC9zdmc+'" />
            Pulse
        </a>

        <h1 class="auth-title stagger-1">Create an Account</h1>
        <p class="auth-subtitle stagger-1">Join the Pulse community today</p>

        <form method="POST" action="{{ route('register') }}" id="register-form">
            @csrf
            
            <div class="form-cols">
                <!-- Column 1: Identity -->
                <div>
                    <div style="margin-bottom: 24px;" class="stagger-2">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                            <div style="width: 24px; height: 1px; background: var(--accent-gradient); opacity: 0.3;"></div>
                            <span style="font-size: 10px; font-weight: 800; color: var(--accent-primary); text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 6px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                Private System Identity
                            </span>
                            <div style="flex: 1; height: 1px; background: var(--border-glass);"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name</label>
                                <input id="first_name" type="text" class="form-input @error('first_name') is-invalid @enderror" name="first_name"
                                    value="{{ old('first_name') }}" required autofocus autocomplete="given-name"
                                    placeholder="John" @error('first_name') aria-invalid="true" aria-describedby="first_name-error" @enderror>
                                @error('first_name')
                                    <span class="error-message" id="first_name-error" role="alert">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input id="last_name" type="text" class="form-input @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}"
                                    required autocomplete="family-name" placeholder="Doe" @error('last_name') aria-invalid="true" aria-describedby="last_name-error" @enderror>
                                @error('last_name')
                                    <span class="error-message" id="last_name-error" role="alert">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group stagger-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <input id="username" type="text" class="form-input @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}"
                                required autocomplete="username" @error('username') aria-invalid="true" aria-describedby="username-error" @enderror>
                            <button type="button" class="btn-secondary" onclick="generateUsername()">Generate</button>
                        </div>
                        @error('username')
                            <span class="error-message" id="username-error" role="alert">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group stagger-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required
                            autocomplete="email" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        @error('email')
                            <span class="error-message" id="email-error" role="alert">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Column 2: Account & Interests -->
                <div>
                    <div class="form-group stagger-5">
                        <label class="form-label">Interests (Optional)</label>
                        <div class="interests-grid" id="interests-container">
                            @foreach($categories as $cat)
                                <div class="interest-pill" data-value="{{ $cat->id }}">{{ $cat->label }}</div>
                            @endforeach
                        </div>
                        <input type="hidden" name="interests" id="interests-input" value="">
                    </div>

                    <div class="form-group stagger-6">
                        <label for="password" class="form-label" style="display: flex; justify-content: space-between;">
                            <span>Password</span>
                            <span style="font-size: 10px; color: var(--text-muted); font-weight: 500;">Min. 8 characters</span>
                        </label>
                        <input id="password" type="password" class="form-input @error('password') is-invalid @enderror" name="password" required
                            autocomplete="new-password" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        @error('password')
                            <span class="error-message" id="password-error" role="alert">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group stagger-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-input" name="password_confirmation"
                            required autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="stagger-6">
                <button type="submit" class="btn-primary">
                    Create My Pulse Account
                </button>
            </div>
        </form>

        <div class="auth-links">
            <p>Already have an account? <a href="{{ route('login') }}">Log in here</a></p>
        </div>
    </div>

    <script>
        function generateUsername() {
            const adjectives = ['Cool', 'Fast', 'Smart', 'Brave', 'Wild', 'Neon', 'Cyber'];
            const nouns = ['Tiger', 'Eagle', 'Panda', 'Wolf', 'Fox', 'Dragon', 'Ninja'];
            const userBox = document.getElementById('username');

            const adj = adjectives[Math.floor(Math.random() * adjectives.length)];
            const noun = nouns[Math.floor(Math.random() * nouns.length)];
            const num = Math.floor(Math.random() * 9999);

            userBox.value = `${adj}${noun}${num}`;
        }

        // Handle interests selection
        const pills = document.querySelectorAll('.interest-pill');
        const interestsInput = document.getElementById('interests-input');
        let selectedInterests = [];

        pills.forEach(pill => {
            pill.addEventListener('click', () => {
                pill.classList.toggle('active');
                const val = pill.getAttribute('data-value');
                if (pill.classList.contains('active')) {
                    selectedInterests.push(val);
                } else {
                    selectedInterests = selectedInterests.filter(i => i !== val);
                }
                interestsInput.value = selectedInterests.join(',');
            });
        });
    </script>
    
    @include('partials.pulse-toast')
</body>

</html>