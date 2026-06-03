<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Scoola - Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            };
        })();
    </script>

    @vite('resources/css/app.css')
    @include('layouts.partials.theme-tokens')

    <style>
        :root {
            --admin-blue: #2563eb;
            --admin-blue-dark: #1d4ed8;
            --admin-soft-blue: #eff6ff;
            --admin-blue-line: #dbeafe;
            --admin-dark: #101828;
            --admin-text: #344054;
            --admin-muted: #667085;
            --admin-soft: #98a2b3;
            --admin-line: #eaecf0;
            --admin-line-soft: #f2f4f7;
            --admin-white: #ffffff;
            --admin-bg: #ffffff;
            --admin-section: #fbfcfe;
            --admin-green: #16a34a;
            --admin-green-soft: #ecfdf3;
            --admin-orange: #f59e0b;
            --admin-orange-soft: #fff7ed;
            --admin-red: #ef4444;
            --admin-red-soft: #fef2f2;
            --admin-shadow-sm: 0 10px 28px rgba(16, 24, 40, 0.06);
            --admin-shadow-md: 0 24px 70px rgba(16, 24, 40, 0.09);
            --admin-shadow-lg: 0 32px 90px rgba(16, 24, 40, 0.12);
            --admin-radius: 22px;
            --font-sans: Inter, Arial, Helvetica, sans-serif;

            --sakura: var(--admin-blue);
            --cyber: var(--admin-soft-blue);
            --cosmo: var(--admin-blue);
            --gold: var(--admin-soft-blue);
            --midnight: var(--admin-dark);
            --mochi: var(--admin-section);
            --white: var(--admin-white);
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            background: var(--admin-bg);
        }

        body {
            min-height: 100vh;
            display: flex;
            margin: 0;
            color: var(--admin-dark);
            background:
                linear-gradient(180deg, #ffffff 0%, #f8fbff 48%, #ffffff 100%);
            font-family: var(--font-sans);
            letter-spacing: 0;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        a {
            color: inherit;
        }

        .runway-sidebar {
            width: 280px;
            position: sticky;
            top: 0;
            align-self: flex-start;
            height: 100vh;
            display: flex;
            flex-shrink: 0;
            flex-direction: column;
            overflow: hidden;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.96);
            border-right: 1px solid var(--admin-line);
            box-shadow: 12px 0 42px rgba(16, 24, 40, 0.04);
            backdrop-filter: blur(18px);
            transition: transform 0.24s ease;
        }

        .sb-brand {
            min-height: 76px;
            display: flex;
            align-items: center;
            padding: 0 22px;
            border-bottom: 1px solid var(--admin-line);
            background: var(--admin-white);
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--admin-dark);
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -0.02em;
            text-decoration: none;
        }

        .brand-logo::before {
            content: "S";
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: var(--admin-blue);
            color: #ffffff;
            box-shadow: 0 16px 30px rgba(37, 99, 235, 0.20);
        }

        .sb-nav {
            flex: 1;
            overflow-y: auto;
            padding: 24px 16px;
        }

        .sb-nav::-webkit-scrollbar {
            width: 8px;
        }

        .sb-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sb-nav::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 999px;
        }

        .sb-section {
            margin: 24px 8px 11px;
            color: var(--admin-muted);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .sb-section:first-child {
            margin-top: 0;
        }

        .nav-link {
            min-height: 46px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
            padding: 0 13px;
            border: 1px solid transparent;
            border-radius: 14px;
            color: var(--admin-text);
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            transition: color 0.18s ease, background-color 0.18s ease, border-color 0.18s ease;
        }

        .nav-link i {
            width: 18px;
            color: var(--admin-soft);
            font-size: 17px;
            transition: color 0.18s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--admin-blue) !important;
            background: var(--admin-soft-blue) !important;
            border-color: var(--admin-blue-line) !important;
        }

        .nav-link:hover i,
        .nav-link.active i {
            color: var(--admin-blue);
        }

        .sb-footer {
            padding: 16px;
            border-top: 1px solid var(--admin-line);
            background: var(--admin-white);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding: 10px;
            border: 1px solid var(--admin-line);
            border-radius: 18px;
            background: #fbfcfe;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--admin-blue-line);
            border-radius: 14px;
            background: var(--admin-soft-blue);
            color: var(--admin-blue);
            font-size: 14px;
            font-weight: 900;
        }

        .user-name {
            overflow: hidden;
            color: var(--admin-dark);
            font-size: 13px;
            font-weight: 850;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-role {
            margin-top: 3px;
            color: var(--admin-blue);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .main-wrapper {
            min-width: 0;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .runway-topbar {
            min-height: 76px;
            position: sticky;
            top: 0;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 34px;
            border-bottom: 1px solid var(--admin-line);
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(18px);
        }

        .topbar-left,
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .scoola-breadcrumb-shell {
            min-width: 0;
        }

        .page-content {
            flex: 1;
            padding: 38px;
            overflow-x: hidden;
            background: transparent;
            padding-bottom: max(44px, env(safe-area-inset-bottom, 0px));
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 999;
            background: rgba(16, 24, 40, 0.36);
            backdrop-filter: blur(8px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        .menu-toggle,
        .top-icon-btn {
            width: 44px;
            height: 44px;
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--admin-line);
            border-radius: 14px;
            background: var(--admin-white);
            color: var(--admin-text);
            cursor: pointer;
            box-shadow: 0 10px 26px rgba(16, 24, 40, 0.05);
            transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
        }

        .menu-toggle {
            display: none;
        }

        .menu-toggle:hover,
        .top-icon-btn:hover {
            transform: translateY(-1px);
            background: #fbfcfe;
            box-shadow: 0 14px 30px rgba(16, 24, 40, 0.08);
        }

        .btn-logout {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 0 16px;
            border: 1px solid var(--admin-line);
            border-radius: 14px;
            background: var(--admin-white);
            color: var(--admin-text);
            font-size: 14px;
            font-weight: 850;
            cursor: pointer;
            box-shadow: 0 10px 26px rgba(16, 24, 40, 0.05);
            transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
            white-space: nowrap;
        }

        .btn-logout:hover {
            transform: translateY(-1px);
            background: #fbfcfe;
            box-shadow: 0 14px 30px rgba(16, 24, 40, 0.08);
        }

        input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]),
        select,
        textarea {
            width: 100%;
            min-height: 48px;
            padding: 12px 15px !important;
            border: 1px solid #d0d5dd !important;
            border-radius: 14px !important;
            outline: none !important;
            background: var(--admin-white) !important;
            color: var(--admin-dark) !important;
            box-shadow: none !important;
            font-family: var(--font-sans) !important;
            font-size: 14px !important;
            font-weight: 650 !important;
            transition: border-color 0.18s ease, box-shadow 0.18s ease !important;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #93c5fd !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10) !important;
            transform: none !important;
        }

        .btn-primary,
        .btn-fab,
        .fab-button,
        .manga-btn-primary,
        .neo-btn-primary {
            border: 1px solid var(--admin-blue) !important;
            border-radius: 14px !important;
            background: var(--admin-blue) !important;
            color: #ffffff !important;
            box-shadow: 0 16px 34px rgba(37, 99, 235, 0.18) !important;
            font-family: var(--font-sans) !important;
            font-weight: 850 !important;
            text-shadow: none !important;
            -webkit-text-stroke: 0 !important;
        }

        .btn-primary:hover,
        .btn-fab:hover,
        .fab-button:hover,
        .manga-btn-primary:hover,
        .neo-btn-primary:hover {
            transform: translateY(-2px) !important;
            background: var(--admin-blue-dark) !important;
            box-shadow: 0 20px 44px rgba(37, 99, 235, 0.22) !important;
        }

        @media (max-width: 992px) {
            .runway-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                transform: translateX(-100%);
            }

            .runway-sidebar.mobile-open {
                transform: translateX(0);
            }

            .menu-toggle {
                display: inline-flex;
            }

            .runway-topbar {
                padding: 0 18px;
            }

            .page-content {
                padding: 26px 18px;
            }
        }

        @media (max-width: 640px) {
            .topbar-actions {
                gap: 8px;
            }

            .btn-logout span {
                display: none;
            }
        }
    </style>
    @include('layouts.partials.breadcrumb-styles')
    @include('layouts.partials.admin-manga-components')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileMenu()"></div>

<aside class="runway-sidebar" id="mainSidebar">
    <div class="sb-brand">
        <a href="/" class="brand-logo">Scoola Admin</a>
    </div>

    <div class="sb-nav">
        @if(auth()->user()->role === 'admin')
        <div class="sb-section">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        @endif

        <div class="sb-section">Data</div>
        <a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Siswa
        </a>
        @if(in_array(auth()->user()->role, ['admin', 'kakonsli']))
        <a href="{{ route('admin.presensi-siswa.index') }}" class="nav-link {{ request()->routeIs('admin.presensi-siswa.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard2-data-fill"></i> Presensi Siswa
        </a>
        <a href="{{ route('admin.rekap.index') }}" class="nav-link {{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-spreadsheet-fill"></i> Rekap Presensi
        </a>
        @endif
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('guru.index') }}" class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill"></i> Guru
        </a>
        <a href="{{ route('admin.kelas.index') }}" class="nav-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Kelas
        </a>
        <a href="{{ route('admin.walikelas.index') }}" class="nav-link {{ request()->routeIs('admin.walikelas.*') ? 'active' : '' }}">
            <i class="bi bi-mortarboard-fill"></i> Wali Kelas
        </a>
        <a href="{{ route('mapel.index') }}" class="nav-link {{ request()->routeIs('mapel.*') ? 'active' : '' }}">
            <i class="bi bi-book-fill"></i> Mata Pelajaran
        </a>
        <a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i> Jadwal Pelajaran
        </a>

        <div class="sb-section">Sistem</div>
        <a href="{{ route('admin.akun.index') }}" class="nav-link {{ request()->routeIs('admin.akun.*') ? 'active' : '' }}">
            <i class="bi bi-shield-plus"></i> Kelola Admin
        </a>
        <a href="{{ route('admin.kakonsli.index') }}" class="nav-link {{ request()->routeIs('admin.kakonsli.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> Kelola Kakonsli
        </a>
        <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Log Aktivitas
        </a>
        @endif
    </div>

    <div class="sb-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div style="min-width: 0;">
                <div class="user-name">{{ auth()->user()->name ?? auth()->user()->email }}</div>
                <div class="user-role">{{ auth()->user()->role }}</div>
            </div>
        </div>
    </div>
</aside>

<div class="main-wrapper">
    <header class="runway-topbar">
        <div class="topbar-left">
            <button type="button" class="menu-toggle" onclick="toggleMobileMenu()" aria-label="Buka menu admin">
                <i class="bi bi-list" style="font-size: 23px;"></i>
            </button>
            <div class="scoola-breadcrumb-shell">
                @include('layouts.partials.breadcrumbs', ['viewData' => get_defined_vars()])
            </div>
        </div>

        <div class="topbar-actions">
            <button type="button"
                    id="theme-toggle-btn"
                    onclick="window.toggleTheme()"
                    class="top-icon-btn"
                    title="Toggle Theme"
                    aria-label="Toggle Theme">
                <i class="bi bi-moon-stars" style="pointer-events: none; font-size: 18px;"></i>
            </button>

            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right" style="pointer-events: none;"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </header>

    <main class="page-content">
        @yield('content')
    </main>

    <style id="admin-clean-modern-final-overrides">
        .page-content,
        .page-content * {
            letter-spacing: 0 !important;
            text-shadow: none !important;
            -webkit-text-stroke: 0 !important;
        }

        .page-content :is(.fredoka, .font-anime-header, [style*="Fredoka One"], [style*="Fredoka"]) {
            font-family: var(--font-sans) !important;
        }

        .page-content :is(h1, h2, h3, h4, h5, h6, .mp-title, .display-title, .card-title, .page-title, .mp-select-title) {
            color: var(--admin-dark) !important;
            font-family: var(--font-sans) !important;
            font-weight: 850 !important;
            letter-spacing: 0 !important;
            line-height: 1.12 !important;
        }

        .page-content .scoola-container,
        .page-content .mp-page {
            width: 100% !important;
            max-width: none !important;
            display: grid !important;
            gap: 28px !important;
            padding: 0 !important;
            background: transparent !important;
            color: var(--admin-dark) !important;
            font-family: var(--font-sans) !important;
        }

        .page-content :is(.neo-card, .manga-card, .mp-card, .mp-form-card, .mp-table-card, .mp-stat-card, .mp-empty-state, .mp-alert, .mp-select-card, .stats-card, .toolbar-card, .import-card, .table-shell, .guru-table-card, .table-container-card, .mobile-card, .student-row, .guru-row, .lesson-card, .agenda-card) {
            background: var(--admin-white) !important;
            color: var(--admin-dark) !important;
            border: 1px solid var(--admin-line) !important;
            border-radius: var(--admin-radius) !important;
            box-shadow: var(--admin-shadow-sm) !important;
        }

        .page-content :is(.neo-card:hover, .manga-card:hover, .mp-hover:hover, .manga-hover-effect:hover, .mp-select-card:hover) {
            transform: translateY(-3px) !important;
            border-color: var(--admin-blue-line) !important;
            box-shadow: var(--admin-shadow-md) !important;
        }

        .page-content :is(.hero-section, .mp-hero, .manga-card-cosmo) {
            background:
                radial-gradient(circle at 82% 18%, rgba(37, 99, 235, 0.10), transparent 28%),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%) !important;
            color: var(--admin-dark) !important;
            border-color: var(--admin-line) !important;
        }

        .page-content :is(.neo-card, .manga-card, .mp-card, .mp-form-card, .mp-stat-card, .mp-select-card, .stats-card, .toolbar-card, .import-card, .table-shell)[style*="#6C5CE7"],
        .page-content :is(.neo-card, .manga-card, .mp-card, .mp-form-card, .mp-stat-card, .mp-select-card, .stats-card, .toolbar-card, .import-card, .table-shell)[style*="rgb(108, 92, 231)"],
        .page-content :is(.neo-card, .manga-card, .mp-card, .mp-form-card, .mp-stat-card, .mp-select-card, .stats-card, .toolbar-card, .import-card, .table-shell)[style*="#00CEC9"],
        .page-content :is(.neo-card, .manga-card, .mp-card, .mp-form-card, .mp-stat-card, .mp-select-card, .stats-card, .toolbar-card, .import-card, .table-shell)[style*="#FDCB6E"],
        .page-content :is(.neo-card, .manga-card, .mp-card, .mp-form-card, .mp-stat-card, .mp-select-card, .stats-card, .toolbar-card, .import-card, .table-shell)[style*="#FF7675"],
        .page-content :is(.neo-card, .manga-card, .mp-card, .mp-form-card, .mp-stat-card, .mp-select-card, .stats-card, .toolbar-card, .import-card, .table-shell)[style*="var(--cyber)"],
        .page-content :is(.neo-card, .manga-card, .mp-card, .mp-form-card, .mp-stat-card, .mp-select-card, .stats-card, .toolbar-card, .import-card, .table-shell)[style*="var(--gold)"],
        .page-content :is(.neo-card, .manga-card, .mp-card, .mp-form-card, .mp-stat-card, .mp-select-card, .stats-card, .toolbar-card, .import-card, .table-shell)[style*="var(--sakura)"] {
            background: var(--admin-white) !important;
            color: var(--admin-dark) !important;
            border-color: var(--admin-line) !important;
        }

        .page-content :is(span, div, a, button, th, td)[style*="#00CEC9"],
        .page-content :is(span, div, a, button, th, td)[style*="#FDCB6E"],
        .page-content :is(span, div, a, button, th, td)[style*="#FF7675"],
        .page-content :is(span, div, a, button, th, td)[style*="#6C5CE7"],
        .page-content :is(span, div, a, button, th, td)[style*="var(--cyber)"],
        .page-content :is(span, div, a, button, th, td)[style*="var(--gold)"],
        .page-content :is(span, div, a, button, th, td)[style*="var(--sakura)"] {
            border-color: var(--admin-blue-line) !important;
            background: var(--admin-soft-blue) !important;
            color: var(--admin-blue) !important;
            box-shadow: none !important;
        }

        .page-content .mp-sticker {
            position: static !important;
            display: inline-flex !important;
            align-items: center !important;
            width: fit-content !important;
            margin-bottom: 14px !important;
            padding: 8px 13px !important;
            transform: none !important;
            border: 1px solid var(--admin-blue-line) !important;
            border-radius: 999px !important;
            background: var(--admin-soft-blue) !important;
            color: var(--admin-blue) !important;
            box-shadow: none !important;
            font-family: var(--font-sans) !important;
            font-size: 12px !important;
            font-weight: 850 !important;
            letter-spacing: 0.02em !important;
        }

        .page-content :is(.mp-kicker, .mp-label, .mp-small, .mp-badge, .mp-tab, .badge, .status-pill, .mini-chip, .manga-pagination__sticker, [class*="badge"]) {
            border: 1px solid var(--admin-blue-line) !important;
            border-radius: 999px !important;
            background: var(--admin-soft-blue) !important;
            color: var(--admin-blue) !important;
            box-shadow: none !important;
            font-family: var(--font-sans) !important;
            font-weight: 850 !important;
            text-shadow: none !important;
        }

        .page-content :is(.mp-btn, .mp-btn-green, .neo-btn, .neo-btn-primary, .btn-primary, .btn-fab, .fab-button, .manga-btn-primary, button[type="submit"]:not(.btn-logout)) {
            min-height: 44px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            border: 1px solid var(--admin-blue) !important;
            border-radius: 14px !important;
            background: var(--admin-blue) !important;
            color: #ffffff !important;
            box-shadow: 0 16px 34px rgba(37, 99, 235, 0.18) !important;
            font-family: var(--font-sans) !important;
            font-weight: 850 !important;
            text-decoration: none !important;
            text-shadow: none !important;
        }

        .page-content :is(.mp-btn-secondary, a[class*="btn"][href]:not(.btn-logout):not(.mp-btn):not(.btn-primary)) {
            min-height: 44px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            border: 1px solid var(--admin-line) !important;
            border-radius: 14px !important;
            background: var(--admin-white) !important;
            color: var(--admin-text) !important;
            box-shadow: 0 10px 26px rgba(16, 24, 40, 0.05) !important;
            font-family: var(--font-sans) !important;
            font-weight: 850 !important;
            text-decoration: none !important;
        }

        .page-content table {
            border-collapse: collapse !important;
            color: var(--admin-text) !important;
            font-family: var(--font-sans) !important;
        }

        .page-content th {
            background: #f8fbff !important;
            color: var(--admin-muted) !important;
            border: 0 !important;
            border-bottom: 1px solid var(--admin-line) !important;
            font-family: var(--font-sans) !important;
            font-size: 12px !important;
            font-weight: 850 !important;
            text-transform: uppercase !important;
        }

        .page-content td {
            background: var(--admin-white) !important;
            color: var(--admin-text) !important;
            border: 0 !important;
            border-bottom: 1px solid var(--admin-line) !important;
        }

        .page-content :is(.mp-table-wrap, .table-wrapper, .manga-table-wrap, .overflow-x-auto) {
            border: 1px solid var(--admin-line) !important;
            border-radius: var(--admin-radius) !important;
            background: var(--admin-white) !important;
            box-shadow: var(--admin-shadow-sm) !important;
        }

        .page-content :is(.manga-pagination-wrap, .manga-pagination) {
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        .page-content :is(.manga-page-link, .manga-page-gap) {
            border: 1px solid var(--admin-line) !important;
            border-radius: 12px !important;
            background: var(--admin-white) !important;
            color: var(--admin-text) !important;
            box-shadow: none !important;
            font-family: var(--font-sans) !important;
        }

        .page-content .manga-page-link.is-active {
            border-color: var(--admin-blue) !important;
            background: var(--admin-blue) !important;
            color: #ffffff !important;
        }

        @media (max-width: 768px) {
            .page-content :is(.responsive-table tr, .mobile-card, .student-row, .guru-row) {
                background: var(--admin-white) !important;
                border: 1px solid var(--admin-line) !important;
                border-radius: 18px !important;
                box-shadow: var(--admin-shadow-sm) !important;
            }
        }
    </style>
</div>

@stack('scripts')

<script>
    function toggleMobileMenu() {
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('active');
    }
</script>
</body>
</html>
