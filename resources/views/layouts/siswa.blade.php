<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola - Siswa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
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
                setTheme(current === 'light' ? 'dark' : 'light');
            };
        })();
    </script>

    @vite('resources/css/app.css')

    <style>
        :root {
            --sakura: #FF7675;
            --cyber: #00CEC9;
            --cosmo: #6C5CE7;
            --gold: #FDCB6E;
            --midnight: #1E1B29;
            --mochi: #FAF9FF;
            --white: #FFFFFF;
            --font-sans: 'Nunito', system-ui, sans-serif;
            --font-display: 'Fredoka One', cursive;
        }

        :root[data-theme="dark"] {
            --mochi: #13111C;
            --white: #1E1B29;
            --midnight: #FAF9FF;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            background-color: var(--mochi);
            background-image: radial-gradient(var(--cosmo) 1.25px, transparent 0);
            background-size: 32px 32px;
            background-attachment: fixed;
            color: var(--midnight);
            font-family: var(--font-sans);
            -webkit-font-smoothing: antialiased;
        }

        .main-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .runway-nav {
            min-height: 66px;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 42px;
            background: var(--white);
            border-bottom: 4px solid var(--midnight);
        }

        .brand-logo {
            color: var(--midnight);
            font-family: var(--font-display);
            font-size: 24px;
            text-decoration: none;
            text-shadow: 2px 2px 0 var(--gold);
            text-transform: lowercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-links a {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            padding: 0 16px;
            border: 3px solid transparent;
            border-radius: 12px;
            color: var(--midnight);
            font-size: 13px;
            font-weight: 900;
            text-decoration: none;
            transition: all 0.16s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: var(--cyber);
            border-color: var(--midnight);
            box-shadow: 3px 3px 0 var(--midnight);
            transform: translate(-2px, -2px);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .user-info {
            max-width: 180px;
            color: var(--midnight);
            font-size: 13px;
            font-weight: 900;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .theme-toggle,
        .btn-logout {
            border: 3px solid var(--midnight);
            color: var(--midnight);
            box-shadow: 4px 4px 0 var(--midnight);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .theme-toggle {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: var(--white);
        }

        .btn-logout {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 16px;
            border-radius: 12px;
            background: var(--sakura);
            font-family: var(--font-display);
            font-size: 13px;
        }

        .theme-toggle:active,
        .btn-logout:active {
            box-shadow: 1px 1px 0 var(--midnight);
            transform: translate(2px, 2px);
        }

        .page-content {
            flex: 1;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 42px;
        }

        .footer {
            background: var(--white);
            border-top: 4px solid var(--midnight);
            color: var(--midnight);
        }

        @media (max-width: 768px) {
            .runway-nav {
                min-height: auto;
                flex-wrap: wrap;
                padding: 12px 16px;
            }

            .nav-links {
                order: 3;
                width: 100%;
            }

            .nav-links a {
                width: 100%;
                justify-content: center;
                background: var(--cyber);
                border-color: var(--midnight);
                box-shadow: 3px 3px 0 var(--midnight);
            }

            .user-info { display: none; }

            .page-content { padding: 24px 16px; }
        }

        @media (max-width: 480px) {
            .btn-logout span { display: none; }
        }
    </style>
    @include('layouts.partials.admin-manga-components')
</head>
<body>
<div class="main-wrapper">
    <header class="runway-nav">
        <div class="nav-left">
            <a href="/" class="brand-logo">scoola</a>
        </div>

        <nav class="nav-links">
            <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill" style="margin-right:8px;"></i> Dashboard
            </a>
        </nav>

        <div class="nav-right">
            <button type="button" id="theme-toggle-btn" onclick="window.toggleTheme()" class="theme-toggle" title="Toggle Theme" aria-label="Toggle Theme">
                <i class="bi bi-moon-stars" style="font-size:20px; pointer-events:none;"></i>
            </button>
            <div class="user-info">{{ auth()->user()->name ?? 'Siswa' }}</div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </header>

    <main class="page-content">
        @yield('content')
    </main>

    <footer class="footer">
        <div style="max-width:1200px; margin:0 auto; padding:24px 32px; display:flex; flex-wrap:wrap; justify-content:space-between; gap:12px; align-items:center;">
            <span style="font-size:12px; font-weight:900; letter-spacing:.08em; text-transform:uppercase;">2026 Scoola. Presensi belajar yang rapi.</span>
            <span style="font-size:12px; font-weight:900; color:var(--cosmo);">Manga-Pop Edition</span>
        </div>
    </footer>
</div>

@stack('scripts')
</body>
</html>
