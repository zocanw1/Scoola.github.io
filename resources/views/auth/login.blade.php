<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scoola — Masuk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            const storageKey = 'scoola-theme';
            const getTheme = () => {
                try { return localStorage.getItem(storageKey) || 'light'; } 
                catch (e) { return 'light'; }
            };
            const setTheme = (theme) => {
                document.documentElement.setAttribute('data-theme', theme);
                try { localStorage.setItem(storageKey, theme); } catch (e) {}
            };
            
            setTheme(getTheme());

            window.toggleTheme = function() {
                const html = document.documentElement;
                const current = html.getAttribute('data-theme') || 'light';
                const newTheme = current === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-theme', newTheme);
                try { localStorage.setItem(storageKey, newTheme); } catch (e) {}
                console.log('Theme toggled to:', newTheme);
            };
        })();
    </script>
    <style>
        :root {
            --color-canvas: #C8C8C8;
            --color-surface: #FFFFFF;
            --color-primary: #000000;
            --color-on-primary: #ffffff;
            --color-hairline: #e7eaf0;
            --color-hairline-soft: #c9ccd1;
            --color-ink: #030303;
            --color-graphite: #404040;
            --color-stone: #939393;
            --color-ash: #999999;
        }
        body {
            background-color: var(--color-canvas);
            height: 100vh;
            margin: 0;
            display: flex;
            overflow: hidden;
            font-family: 'Inter', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .editorial-split {
            display: flex;
            width: 100%;
            height: 100%;
        }
        .hero-panel {
            flex: 1;
            background-color: var(--color-primary);
            color: var(--color-on-primary);
            padding: 80px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .form-panel {
            width: 520px;
            background-color: var(--color-canvas);
            padding: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-left: 1px solid var(--color-hairline);
        }
        .brand-text {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            text-transform: lowercase;
        }
        .hero-text {
            max-width: 500px;
        }
        .hero-title {
            font-size: clamp(48px, 8vw, 110px);
            line-height: 0.85;
            
            font-weight: 400;
            margin-bottom: 40px;
            letter-spacing: -0.5px;
        }
        .hero-desc {
            font-size: 18px;
            line-height: 1.5;
            color: var(--color-ash);
            font-weight: 400;
        }
        .login-form-container {
            width: 100%;
            max-width: 360px;
            margin: 0 auto;
        }
        .form-header {
            margin-bottom: 64px;
        }
        .input-group {
            margin-bottom: 40px;
        }
        .input-label {
            display: block;
            margin-bottom: 12px;
            color: var(--color-stone);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .input-field {
            width: 100%;
            padding: 12px 0;
            border: none;
            border-bottom: 1px solid var(--color-hairline-soft);
            font-size: 16px;
            background: transparent;
            outline: none;
            transition: border-color 0.3s;
            color: var(--color-ink);
        }
        .input-field:focus {
            border-bottom-color: var(--color-ink);
        }
        .btn-full {
            width: 100%;
            margin-top: 24px;
            background: var(--color-primary);
            color: var(--color-on-primary);
            border: none;
            padding: 16px;
            font-weight: 600;
            font-size: 14px;
            border-radius: 99px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-full:hover {
            opacity: 0.9;
        }
        .alert-minimal {
            padding: 16px 0;
            border-bottom: 1px solid var(--color-hairline);
            margin-bottom: 40px;
            color: var(--color-ink);
            font-size: 14px;
        }
        @media (max-width: 1100px) {
            .form-panel { width: 440px; padding: 60px; }
        }
        @media (max-width: 992px) {
            body { overflow: auto; }
            .editorial-split { flex-direction: column; }
            .hero-panel { padding: 40px; height: 360px; justify-content: center; }
            .hero-title { font-size: 64px; }
            .form-panel { width: 100%; padding: 60px 40px; border-left: none; }
        }
    </style>
</head>
<body>

<div class="editorial-split">
    <!-- Hero Panel -->
    <div class="hero-panel">
        <div class="brand-text">scoola.</div>
        <div class="hero-text">
            <h1 class="hero-title">Refined.</h1>
            <p class="hero-desc">Sistem manajemen kehadiran digital yang dirancang untuk efisiensi dan ketepatan data secara real-time.</p>
        </div>
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: var(--color-stone);">© 2026 Editorial System</div>
    </div>

    <!-- Form Panel -->
    <div class="form-panel">
        <div class="login-form-container">
            <div class="form-header" style="display: flex; justify-content: space-between; align-items: flex-start; position: relative; z-index: 1000;">
                <div>
                    <span style="font-size: 13px; text-transform: uppercase; letter-spacing: 2px; color: var(--color-stone); font-weight: 500;">Identification</span>
                    <h2 style="font-size: 32px; font-weight: 400; margin-top: 12px; letter-spacing: -1px; color: var(--color-ink);">Login</h2>
                </div>
                <button type="button" onclick="window.toggleTheme()" style="background: none; border: none; color: var(--color-stone); cursor: pointer; font-size: 20px; padding: 12px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-moon-stars" style="pointer-events: none;"></i>
                </button>
            </div>

            @if(session('info') || request()->has('expired'))
                <div class="alert-minimal">
                    {{ session('info') ?? 'Sesi Anda telah berakhir. Silakan login kembali.' }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-minimal" style="color: #e53e3e; border-bottom-color: rgba(229, 62, 62, 0.2);">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="input-group">
                    <label class="input-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="input-field" placeholder="name@domain.com">
                    @error('email')
                        <span style="color: #e53e3e; font-size: 12px; margin-top: 8px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label class="input-label">Password</label>
                    <input type="password" name="password" required class="input-field" placeholder="••••••••">
                    @error('password')
                        <span style="color: #e53e3e; font-size: 12px; margin-top: 8px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-full">
                    Proceed
                </button>

                <div style="margin-top: 48px; border-top: 1px solid var(--color-hairline); padding-top: 24px;">
                    <a href="#" style="font-size: 12px; color: var(--color-stone); text-decoration: none; text-transform: uppercase; letter-spacing: 1px;">Lost Credentials?</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
