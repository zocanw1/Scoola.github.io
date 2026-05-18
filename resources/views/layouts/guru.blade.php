<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola — Guru</title>
    
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
            background-color: var(--color-canvas);
            color: var(--color-ink);
            font-family: var(--font-sans);
            margin: 0;
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar - Runway Style */
        .runway-sidebar {
            width: 260px;
            background-color: #ffffff;
            border-right: 1px solid var(--color-hairline);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 10;
        }

        .sb-brand {
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid var(--color-hairline);
        }

        .brand-logo {
            font-size: 20px;
            font-weight: 700;
            color: var(--color-ink);
            text-decoration: none;
            letter-spacing: var(--tracking-tight);
            text-transform: lowercase;
        }

        .sb-nav {
            padding: 24px 16px;
            flex: 1;
            overflow-y: auto;
        }

        .sb-section {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.35px;
            text-transform: uppercase;
            color: var(--color-stone);
            margin-bottom: 12px;
            margin-top: 24px;
            padding: 0 8px;
        }
        
        .sb-section:first-child {
            margin-top: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: var(--rounded-md);
            font-size: 14px;
            font-weight: 500;
            color: var(--color-ink-soft);
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--color-on-primary);
            background-color: var(--color-primary);
        }

        .nav-link i {
            font-size: 14px;
            color: var(--color-slate);
        }
        
        .nav-link.active i, .nav-link:hover i {
            color: var(--color-on-primary);
        }

        [data-theme="dark"] .nav-link:hover, [data-theme="dark"] .nav-link.active {
            background-color: var(--color-primary);
            color: var(--color-on-primary);
        }

        .sb-footer {
            padding: 16px;
            border-top: 1px solid var(--color-hairline);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: var(--rounded-full);
            background-color: var(--color-primary);
            color: var(--color-on-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        /* Main Content */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .runway-topbar {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            border-bottom: 1px solid var(--color-hairline);
            background-color: var(--color-surface);
        }

        .page-content {
            flex: 1;
            padding: 80px 64px;
            overflow-y: auto;
            background-color: var(--color-canvas);
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
            font-size: 48px;
            font-weight: 400;
            line-height: 1;
            letter-spacing: -1.2px;
            color: var(--color-ink);
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
    </style>
</head>
<body>

<aside class="runway-sidebar">
    <div class="sb-brand">
        <a href="/" class="brand-logo">scoola guru</a>
    </div>

    <div class="sb-nav">
        <div class="sb-section">Utama</div>
        <a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="{{ route('guru.presensi.index') }}" class="nav-link {{ request()->routeIs('guru.presensi.*') ? 'active' : '' }}">
            <i class="bi bi-journal-check"></i> Mulai Mengajar
        </a>
    </div>

    <div class="sb-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}</div>
            <div style="overflow:hidden">
                <div style="font-size: 13px; font-weight: 600; color: var(--color-ink);">{{ auth()->user()->name ?? auth()->user()->email }}</div>
                <div style="font-size: 11px; color: var(--color-stone);">{{ auth()->user()->role ?? 'guru' }}</div>
            </div>
        </div>
    </div>
</aside>

<div class="main-wrapper">
    <header class="runway-topbar">
        <div>
            <span class="text-meta" style="color: var(--color-stone);">Guru / @yield('breadcrumb', 'Overview')</span>
        </div>

        <div style="display: flex; gap: 24px; align-items: center; position: relative; z-index: 9999;">
            <button type="button" 
                    id="theme-toggle-btn"
                    onclick="window.toggleTheme()" 
                    style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--color-hairline); background: var(--color-canvas); font-size: 20px; color: var(--color-ink); cursor: pointer; border-radius: 50%; transition: all 0.2s;" 
                    onmouseover="this.style.background='var(--color-canvas-warm)'; this.style.borderColor='var(--color-ink)';"
                    onmouseout="this.style.background='var(--color-canvas)'; this.style.borderColor='var(--color-hairline)';"
                    title="Toggle Theme">
                <i class="bi bi-moon-stars" style="pointer-events: none;"></i>
            </button>
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
</div>

@stack('scripts')
</body>
</html>
