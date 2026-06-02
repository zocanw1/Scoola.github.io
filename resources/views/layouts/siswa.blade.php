<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
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
            display: flex;
            overflow-x: hidden;
            background-color: var(--mochi);
            background-image: radial-gradient(var(--cosmo) 1.25px, transparent 0);
            background-size: 32px 32px;
            background-attachment: fixed;
            color: var(--midnight);
            font-family: var(--font-sans);
            -webkit-font-smoothing: antialiased;
        }

        .runway-sidebar {
            width: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 1000;
            background: var(--white);
            border-right: 4px solid var(--midnight);
            transition: transform 0.25s ease;
        }

        .sb-brand {
            height: 66px;
            display: flex;
            align-items: center;
            padding: 0 22px;
            background: var(--gold);
            border-bottom: 4px solid var(--midnight);
        }

        .brand-logo {
            color: var(--midnight);
            font-family: var(--font-display);
            font-size: 22px;
            text-decoration: none;
            text-shadow: 2px 2px 0 var(--white);
            text-transform: lowercase;
        }

        .sb-nav {
            flex: 1;
            overflow-y: auto;
            padding: 24px 16px;
        }

        .sb-section {
            margin: 22px 8px 12px;
            color: var(--midnight);
            font-family: var(--font-display);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.75;
        }

        .sb-section:first-child { margin-top: 0; }

        .nav-link {
            min-height: 44px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            padding: 10px 14px;
            border: 3px solid transparent;
            border-radius: 12px;
            color: var(--midnight);
            font-size: 14px;
            font-weight: 900;
            text-decoration: none;
            transition: all 0.16s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--midnight) !important;
            background: var(--cyber) !important;
            border-color: var(--midnight);
            box-shadow: 4px 4px 0 var(--midnight);
            transform: translate(-2px, -2px);
        }

        .nav-link:active {
            box-shadow: 1px 1px 0 var(--midnight);
            transform: translate(1px, 1px);
        }

        .nav-link i {
            color: var(--midnight);
            font-size: 16px;
        }

        .sb-footer {
            padding: 16px;
            border-top: 4px solid var(--midnight);
            background: var(--white);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            flex: 0 0 auto;
            display: grid;
            place-items: center;
            border: 3px solid var(--midnight);
            border-radius: 12px;
            background: var(--sakura);
            color: var(--midnight);
            box-shadow: 3px 3px 0 var(--midnight);
            font-family: var(--font-display);
            font-size: 14px;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            min-width: 0;
            min-height: 100vh;
            flex-direction: column;
        }

        .runway-topbar {
            height: 66px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 0 32px;
            background: var(--white);
            border-bottom: 4px solid var(--midnight);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .topbar-user-name {
            max-width: 190px;
            color: var(--midnight);
            font-size: 13px;
            font-weight: 900;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .menu-toggle,
        .theme-toggle {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--midnight);
            border-radius: 999px;
            background: var(--white);
            color: var(--midnight);
            box-shadow: 3px 3px 0 var(--midnight);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .menu-toggle { display: none; }

        .btn-logout {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 16px;
            border: 3px solid var(--midnight);
            border-radius: 12px;
            background: var(--sakura);
            color: var(--midnight);
            box-shadow: 4px 4px 0 var(--midnight);
            font-family: var(--font-display);
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-logout:hover {
            box-shadow: 6px 6px 0 var(--midnight);
            transform: translate(-2px, -2px);
        }

        .menu-toggle:active,
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
            padding: 40px;
            background: transparent;
            overflow-y: auto;
            padding-bottom: max(40px, env(safe-area-inset-bottom, 0px));
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 999;
            background: rgba(30, 27, 41, 0.45);
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.active { display: block; }

        @media (max-width: 992px) {
            .runway-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                transform: translateX(-100%);
            }

            .runway-sidebar.mobile-open { transform: translateX(0); }

            .menu-toggle { display: inline-flex; }

            .runway-topbar { padding: 0 16px; }

            .page-content { padding: 24px 16px; }
        }

        @media (max-width: 640px) {
            .topbar-user-name { display: none; }

            .btn-logout span { display: none; }
        }
    </style>
    @include('layouts.partials.breadcrumb-styles')
    @include('layouts.partials.admin-manga-components')
</head>
<body>
@php
    $siswaName = auth()->user()->name ?? auth()->user()->email ?? 'Siswa';
@endphp

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileMenu()"></div>

<aside class="runway-sidebar" id="mainSidebar">
    <div class="sb-brand">
        <a href="/" class="brand-logo">scoola siswa</a>
    </div>

    <div class="sb-nav">
        <div class="sb-section">Utama</div>
        <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>

        <div class="sb-section">Presensi</div>
        <a href="{{ route('siswa.dashboard') }}#kode-presensi" class="nav-link">
            <i class="bi bi-journal-check"></i> Presensi Harian
        </a>
        <a href="{{ route('siswa.dashboard') }}#aktivitas-terakhir" class="nav-link">
            <i class="bi bi-clock-history"></i> Aktivitas
        </a>
    </div>

    <div class="sb-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr($siswaName, 0, 1)) }}</div>
            <div style="min-width:0; overflow:hidden;">
                <div style="font-size: 13px; font-weight: 900; color: var(--midnight); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $siswaName }}</div>
                <div style="font-size: 11px; font-weight: 900; color: var(--cosmo); text-transform: uppercase;">{{ auth()->user()->role ?? 'siswa' }}</div>
            </div>
        </div>
    </div>
</aside>

<div class="main-wrapper">
    <header class="runway-topbar">
        <div style="display:flex; align-items:center; gap:16px; min-width:0;">
            <button type="button" class="menu-toggle" onclick="toggleMobileMenu()" aria-label="Buka menu">
                <i class="bi bi-list" style="font-size:24px;"></i>
            </button>
            <div class="scoola-breadcrumb-shell">
                @include('layouts.partials.breadcrumbs', ['viewData' => get_defined_vars()])
            </div>
        </div>

        <div class="topbar-actions">
            <button type="button" id="theme-toggle-btn" onclick="window.toggleTheme()" class="theme-toggle" title="Toggle Theme" aria-label="Toggle Theme">
                <i class="bi bi-moon-stars" style="font-size:20px; pointer-events:none;"></i>
            </button>
            <div class="topbar-user-name">{{ $siswaName }}</div>
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
</div>

@stack('scripts')
<script>
    function toggleMobileMenu() {
        document.getElementById('mainSidebar').classList.toggle('mobile-open');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }
</script>
</body>
</html>
