<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola — Student</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
                
                // Diagnostic check
                console.log('Theme toggled to:', newTheme);
            };
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-sans: 'Inter', system-ui, sans-serif;
        }

        body {
            background-color: var(--color-canvas); /* #C8C8C8 */
            color: var(--color-ink);
            font-family: var(--font-sans);
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        .main-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Nav Bar - Runway Style */
        .runway-nav {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            border-bottom: 1px solid var(--color-hairline);
            background-color: var(--color-surface);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand-logo {
            font-size: 20px;
            font-weight: 700;
            color: var(--color-ink);
            text-decoration: none;
            letter-spacing: var(--tracking-tight);
            text-transform: lowercase;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-links a {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-ink-soft);
            text-decoration: none;
            transition: color 0.2s;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--color-ink);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            font-size: 14px;
            font-weight: 500;
            color: var(--color-slate);
        }

        /* Content Area */
        .page-content {
            flex: 1;
            padding: 120px 48px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Editorial Lockup */
        .editorial-header {
            margin-bottom: 48px;
        }

        .eyebrow {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.35px;
            text-transform: uppercase;
            color: var(--color-stone);
            margin-bottom: 12px;
            display: block;
        }

        .display-title {
            font-size: 64px;
            font-weight: 400;
            line-height: 1;
            letter-spacing: -2px;
            margin-bottom: 24px;
        }

        /* Force White Form Fields */
        input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]),
        select,
        textarea {
            background-color: #ffffff !important;
            background: #ffffff !important;
            color: var(--color-ink) !important;
            border: 1px solid var(--color-hairline) !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-family: var(--font-sans) !important;
            font-size: 14px !important;
            outline: none !important;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.05) !important;
        }

        @media (max-width: 768px) {
            .runway-nav {
                padding: 0 16px;
            }
            .nav-links {
                display: none; /* Mobile would need a menu, but keeping it simple for now */
            }
            .display-title {
                font-size: 36px;
                letter-spacing: -0.8px;
            }
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <header class="runway-nav">
        <div class="nav-left">
            <a href="/" class="brand-logo">scoola</a>
        </div>

        <nav class="nav-links">
            <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">Dashboard</a>
        </nav>

        <div class="nav-right" style="position: relative; z-index: 9999;">
            <button type="button" 
                    id="theme-toggle-btn"
                    onclick="window.toggleTheme()" 
                    style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--color-hairline); background: var(--color-canvas); font-size: 20px; color: var(--color-ink); cursor: pointer; border-radius: 50%; transition: all 0.2s;" 
                    onmouseover="this.style.background='var(--color-canvas-warm)'; this.style.borderColor='var(--color-ink)';"
                    onmouseout="this.style.background='var(--color-canvas)'; this.style.borderColor='var(--color-hairline)';"
                    title="Toggle Theme">
                <i class="bi bi-moon-stars" style="pointer-events: none;"></i>
            </button>
            <div class="user-info d-none d-md-block">
                {{ auth()->user()->name ?? 'Student' }}
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-primary" style="height: 36px; padding: 0 16px; font-size: 13px;">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main class="page-content">
        @yield('content')
    </main>

    <footer class="footer">
        <div style="max-width: 1200px; margin: 0 auto; padding: var(--spacing-lg) var(--spacing-xxl); display: flex; justify-content: space-between; align-items: center;">
            <span class="text-micro-caps" style="color: var(--color-stone)">© 2026 Scoola. Built for the future of education.</span>
            <div style="display: flex; gap: 16px;">
                <a href="#" class="text-micro-caps" style="color: var(--color-stone); text-decoration: none;">Privacy</a>
                <a href="#" class="text-micro-caps" style="color: var(--color-stone); text-decoration: none;">Terms</a>
            </div>
        </div>
    </footer>
</div>

@stack('scripts')
</body>
</html>
