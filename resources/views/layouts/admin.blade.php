<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Scoola — Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
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
                
                console.log('Theme toggled to:', newTheme);
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
            --admin-bg: #fbfcfe;
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
            --sakura: var(--admin-blue);
            --cyber: var(--admin-soft-blue);
            --cosmo: var(--admin-blue);
            --gold: var(--admin-soft-blue);
            --midnight: var(--admin-dark);
            --mochi: var(--admin-bg);
            --white: var(--admin-white);
            --font-sans: Arial, Helvetica, 'Nunito', sans-serif;
        }

        body {
            background:
                radial-gradient(circle at 10% 5%, rgba(37, 99, 235, 0.05), transparent 30%),
                radial-gradient(circle at 90% 10%, rgba(96, 165, 250, 0.08), transparent 32%),
                linear-gradient(180deg, #f9fbff 0%, #ffffff 42%, #fbfcfe 100%);
            background-attachment: fixed;
            color: var(--admin-dark);
            font-family: var(--font-sans);
            margin: 0;
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar - Panel Komik Manga-Pop Style */
        .runway-sidebar {
            width: 260px;
            position: sticky;
            top: 0;
            align-self: flex-start;
            height: 100vh;
            background-color: rgba(255, 255, 255, 0.96);
            border-right: 1px solid var(--admin-line);
            box-shadow: 14px 0 36px rgba(16, 24, 40, 0.04);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow: hidden;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }

        @media (max-width: 992px) {
            .runway-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                transform: translateX(-100%);
            }
            .runway-sidebar.mobile-open {
                transform: translateX(0);
            }
        }

        .sb-brand {
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid var(--admin-line);
            background-color: var(--admin-white);
        }

        .brand-logo {
            font-family: var(--font-sans);
            font-size: 20px;
            color: var(--admin-dark);
            text-decoration: none;
            letter-spacing: -0.5px;
            text-transform: lowercase;
            font-weight: 900;
            text-shadow: none;
        }

        .sb-nav {
            padding: 24px 16px;
            flex: 1;
            overflow-y: auto;
        }

        .sb-section {
            font-family: var(--font-sans);
            font-size: 12px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--admin-muted);
            opacity: 0.8;
            margin-bottom: 12px;
            margin-top: 24px;
            padding: 0 8px;
        }
        
        .sb-section:first-child {
            margin-top: 0;
        }

        /* Tombol Navigasi Efek Hover Mekanis Neo-Brutalism */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            color: var(--admin-text);
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            margin-bottom: 8px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--admin-blue) !important;
            background-color: var(--admin-soft-blue) !important;
            border-color: var(--admin-blue-line) !important;
            box-shadow: none !important;
            transform: none;
        }

        .nav-link:active {
            transform: scale(0.99);
            box-shadow: none !important;
        }

        .nav-link i {
            font-size: 16px;
            color: inherit;
            transition: transform 0.2s;
        }

        .nav-link:hover i {
            transform: none;
        }

        .sb-footer {
            padding: 16px;
            border-top: 1px solid var(--admin-line);
            background-color: var(--admin-white);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background-color: var(--admin-soft-blue);
            color: var(--admin-blue);
            border: 1px solid var(--admin-blue-line);
            box-shadow: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Fredoka One', sans-serif;
            font-size: 14px;
        }

        /* Main Content Wrapper */
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
            border-bottom: 1px solid var(--admin-line);
            background-color: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
        }

        .page-content {
            flex: 1;
            padding: 40px 40px;
            overflow-y: auto;
            background-color: transparent; 
            padding-bottom: max(40px, env(safe-area-inset-bottom, 0px));
        }

        /* Responsive Engine untuk Kartu & Tabel Mengambang */
        @media (max-width: 768px) {
            .page-content {
                padding: 24px 16px !important;
            }
            .runway-topbar {
                padding: 0 16px !important;
            }
            .display-title {
                font-family: 'Fredoka One', sans-serif !important;
                font-size: 28px !important;
                letter-spacing: -0.5px !important;
                word-break: break-word !important;
            }

            .table-container-card {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                overflow: visible !important;
            }

            .responsive-table {
                background: transparent !important;
                border-collapse: separate !important;
                border-spacing: 0 32px !important;
                width: 100% !important;
                display: table !important;
            }

            .responsive-table thead {
                display: none !important;
            }

            .responsive-table tbody, 
            .responsive-table tr, 
            .responsive-table td {
                display: block !important;
                width: 100% !important;
            }

            .responsive-table tr {
                margin-bottom: 32px !important;
                border: 4px solid var(--midnight) !important;
                border-radius: 16px !important;
                padding: 24px !important;
                background: var(--white) !important;
                box-shadow: 6px 6px 0px var(--midnight) !important;
            }

            .responsive-table td {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                padding: 12px 0 !important;
                border: none !important;
                text-align: right !important;
                min-height: 48px;
            }

            .responsive-table td::before {
                content: attr(data-label);
                font-family: 'Fredoka One', sans-serif;
                text-transform: uppercase;
                font-size: 11px;
                letter-spacing: 0.05em;
                color: var(--midnight);
                opacity: 0.7;
                text-align: left;
                padding-right: 16px;
            }

            .stats-grid, 
            .responsive-card-grid,
            div[style*="display: grid"] {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 32px !important;
                width: 100% !important;
            }

            .card {
                border: 4px solid var(--midnight) !important;
                border-radius: 16px !important;
                padding: 24px !important;
                margin-bottom: 24px !important;
                box-shadow: 6px 6px 0px var(--midnight) !important;
            }

            .form-actions {
                display: flex !important;
                flex-direction: column !important;
                gap: 16px !important;
                align-items: stretch !important;
                margin-top: 40px !important;
                padding-top: 32px !important;
            }

            .form-actions button, 
            .form-actions a {
                width: 100% !important;
                justify-content: center !important;
            }

            .form-grid {
                grid-template-columns: 1fr !important;
                gap: 0 !important;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(30, 27, 41, 0.4);
            backdrop-filter: blur(4px);
            z-index: 999;
        }
        .sidebar-overlay.active {
            display: block;
        }
        
        .menu-toggle {
            display: none;
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
            border: 1px solid #d0d5dd;
            background: var(--admin-white);
            border-radius: 12px;
            cursor: pointer;
            color: var(--admin-text);
            box-shadow: none;
            transition: all 0.2s ease;
        }
        .menu-toggle:active {
            transform: scale(0.98);
            box-shadow: none;
        }

        @media (max-width: 992px) {
            .menu-toggle {
                display: flex;
            }
        }

        /* Editorial Lockup */
        .editorial-header {
            margin-bottom: 36px;
        }

        .eyebrow {
            font-family: var(--font-sans);
            font-size: 13px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--admin-blue);
            margin-bottom: 12px;
            display: block;
            font-weight: 900;
        }

        .display-title {
            font-family: var(--font-sans);
            font-size: 42px;
            line-height: 1.1;
            color: var(--admin-dark);
            margin-bottom: 24px;
            font-weight: 900;
            letter-spacing: 0;
        }

        input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]),
        select,
        textarea {
            background-color: var(--admin-white) !important;
            color: var(--admin-dark) !important;
            border: 1px solid #d0d5dd !important;
            border-radius: 14px !important;
            padding: 12px 16px !important;
            font-family: var(--font-sans) !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            outline: none !important;
            box-shadow: none !important;
            transition: border-color 0.18s ease, box-shadow 0.18s ease !important;
        }

        input:focus, select:focus, textarea:focus {
            transform: none !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10) !important;
            border-color: #93c5fd !important;
        }

        /* Floating Action Button (FAB) - Neo Brutalism Pop Style */
        .fab-container {
            position: fixed !important;
            bottom: 40px !important;
            right: 40px !important;
            z-index: 99999 !important;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 16px;
        }

        .btn-fab {
            width: 58px !important;
            height: 58px !important;
            border-radius: 16px !important;
            background-color: var(--admin-blue) !important;
            color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border: 1px solid var(--admin-blue) !important;
            box-shadow: 0 18px 38px rgba(37, 99, 235, 0.22) !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
            animation: fab-pop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) backwards;
        }

        @keyframes fab-pop {
            0% { transform: scale(0) rotate(-45deg); opacity: 0; }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }

        .btn-fab:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 24px 52px rgba(37, 99, 235, 0.28) !important;
            background-color: var(--admin-blue-dark) !important;
        }
        
        .btn-fab:active {
            transform: translateY(0) scale(0.98) !important;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.18) !important;
        }

        .fab-label {
            position: absolute !important;
            right: 80px !important;
            background: var(--admin-white) !important;
            color: var(--admin-dark) !important;
            border: 1px solid var(--admin-line) !important;
            padding: 8px 16px !important;
            border-radius: 12px !important;
            font-family: var(--font-sans) !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            white-space: nowrap !important;
            opacity: 0 !important;
            transform: translateX(20px) !important;
            pointer-events: none !important;
            transition: all 0.3s ease !important;
            box-shadow: var(--admin-shadow-sm) !important;
        }

        .btn-fab:hover .fab-label {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }

        .btn-primary {
            font-family: var(--font-sans) !important;
            background-color: var(--admin-blue) !important;
            color: #ffffff !important;
            border: 1px solid var(--admin-blue) !important;
            border-radius: 12px !important;
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.16) !important;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 900;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 36px rgba(37, 99, 235, 0.20) !important;
            background-color: var(--admin-blue-dark) !important;
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.12) !important;
        }

        .btn-logout {
            font-family: var(--font-sans) !important;
            background-color: var(--admin-white) !important;
            color: var(--admin-text) !important;
            border: 1px solid #d0d5dd !important;
            border-radius: 12px !important;
            box-shadow: none !important;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 40px;
            padding: 0 14px;
            font-weight: 900;
            white-space: nowrap;
        }

        .btn-logout:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(16, 24, 40, 0.08) !important;
            background-color: #f9fafb !important;
        }

        .btn-logout:active {
            transform: translateY(0);
            box-shadow: none !important;
        }
    </style>
    @include('layouts.partials.breadcrumb-styles')
    @include('layouts.partials.admin-manga-components')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileMenu()"></div>

<aside class="runway-sidebar" id="mainSidebar">
    <div class="sb-brand">
        <a href="/" class="brand-logo">scoola admin ✨</a>
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
            <div style="overflow:hidden">
                <div style="font-size: 13px; font-weight: 800; color: var(--admin-dark);">{{ auth()->user()->name ?? auth()->user()->email }}</div>
                <div style="font-size: 11px; font-weight: 700; color: var(--admin-blue); text-transform: uppercase;">{{ auth()->user()->role }}</div>
            </div>
        </div>
    </div>
</aside>

<div class="main-wrapper">
    <header class="runway-topbar">
        <div style="display: flex; align-items: center; gap: 16px;">
            <button type="button" class="menu-toggle" onclick="toggleMobileMenu()">
                <i class="bi bi-list" style="font-size: 24px;"></i>
            </button>
            <div class="scoola-breadcrumb-shell">
                @include('layouts.partials.breadcrumbs', ['viewData' => get_defined_vars()])
            </div>
        </div>

        <div style="display: flex; gap: 20px; align-items: center; position: relative; z-index: 9999;">
            <button type="button" 
                    id="theme-toggle-btn"
                    onclick="window.toggleTheme()" 
                    style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border: 1px solid #d0d5dd; background: var(--admin-white); font-size: 20px; color: var(--admin-text); cursor: pointer; border-radius: 12px; box-shadow: none; transition: all 0.2s ease;"
                    onmouseover="this.style.background='#f9fafb';"
                    onmouseout="this.style.background='var(--admin-white)';"
                    title="Toggle Theme">
                <i class="bi bi-moon-stars" style="pointer-events: none;"></i>
            </button>
            
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right" style="pointer-events: none;"></i>
                    Keluar
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
        }

        .page-content .scoola-container {
            background: transparent !important;
            color: var(--admin-dark) !important;
            font-family: var(--font-sans) !important;
            padding: 0 !important;
            gap: 28px !important;
        }

        .page-content .fredoka,
        .page-content .mp-title,
        .page-content .display-title,
        .page-content h1,
        .page-content h2,
        .page-content h3,
        .page-content h4,
        .page-content h5,
        .page-content h6 {
            font-family: var(--font-sans) !important;
            letter-spacing: 0 !important;
            text-shadow: none !important;
            -webkit-text-stroke: 0 !important;
        }

        .page-content .neo-card,
        .page-content .manga-card,
        .page-content .stats-card,
        .page-content .toolbar-card,
        .page-content .import-card,
        .page-content .table-shell,
        .page-content .guru-table-card,
        .page-content .table-container-card {
            background: var(--admin-white) !important;
            color: var(--admin-dark) !important;
            border: 1px solid var(--admin-line) !important;
            border-radius: var(--admin-radius) !important;
            box-shadow: var(--admin-shadow-sm) !important;
        }

        .page-content .neo-card:hover,
        .page-content .manga-card:hover,
        .page-content .manga-hover-effect:hover {
            transform: translateY(-4px) !important;
            box-shadow: var(--admin-shadow-md) !important;
        }

        .page-content .hero-section,
        .page-content .manga-card-cosmo,
        .page-content .neo-card[style*="#6C5CE7"],
        .page-content .neo-card[style*="rgb(108, 92, 231)"] {
            background:
                radial-gradient(circle at 12% 8%, rgba(37, 99, 235, 0.08), transparent 30%),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%) !important;
            color: var(--admin-dark) !important;
            overflow: hidden !important;
        }

        .page-content .manga-card-gold,
        .page-content .manga-card-cyber,
        .page-content .mp-card-gold,
        .page-content .mp-card-cyber,
        .page-content .mp-card-sakura,
        .page-content .neo-card[style*="#00CEC9"],
        .page-content .neo-card[style*="#FDCB6E"],
        .page-content .neo-card[style*="#FF7675"] {
            background: var(--admin-white) !important;
            color: var(--admin-dark) !important;
        }

        .page-content .neo-card *,
        .page-content .manga-card *,
        .page-content .hero-section * {
            text-shadow: none !important;
            -webkit-text-stroke: 0 !important;
        }

        .page-content .neo-card :is(h1, h2, h3, h4, h5, h6, p, span, div, label, small),
        .page-content .manga-card :is(h1, h2, h3, h4, h5, h6, p, span, div, label, small),
        .page-content .hero-section :is(h1, h2, h3, h4, h5, h6, p, span, div, label, small) {
            color: inherit !important;
        }

        .page-content .neo-btn-primary,
        .page-content .btn-primary,
        .page-content .btn-fab,
        .page-content .fab-button,
        .page-content .manga-btn-primary,
        .page-content button[type="submit"]:not(.btn-logout),
        .page-content a[class*="btn"][href]:not(.mp-btn-secondary):not(.btn-logout) {
            background: var(--admin-blue) !important;
            color: #ffffff !important;
            border: 1px solid var(--admin-blue) !important;
            border-radius: 12px !important;
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.16) !important;
            font-family: var(--font-sans) !important;
            font-weight: 900 !important;
            text-shadow: none !important;
            -webkit-text-stroke: 0 !important;
            transition: all 0.2s ease !important;
        }

        .page-content .neo-btn-primary:hover,
        .page-content .btn-primary:hover,
        .page-content .btn-fab:hover,
        .page-content .fab-button:hover,
        .page-content .manga-btn-primary:hover {
            background: var(--admin-blue-dark) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 18px 36px rgba(37, 99, 235, 0.20) !important;
        }

        .page-content .badge,
        .page-content .status-pill,
        .page-content .mini-chip,
        .page-content span[style*="background"] {
            border: 1px solid var(--admin-blue-line) !important;
            border-radius: 999px !important;
            background: var(--admin-soft-blue) !important;
            color: var(--admin-blue) !important;
            box-shadow: none !important;
            text-shadow: none !important;
            font-family: var(--font-sans) !important;
            font-weight: 900 !important;
        }

        .page-content table {
            border-collapse: collapse !important;
            color: var(--admin-text) !important;
        }

        .page-content th {
            background: #f8fbff !important;
            color: var(--admin-muted) !important;
            border-bottom: 1px solid var(--admin-line) !important;
            font-family: var(--font-sans) !important;
            font-size: 12px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
        }

        .page-content td {
            background: var(--admin-white) !important;
            color: var(--admin-text) !important;
            border-bottom: 1px solid var(--admin-line) !important;
        }

        .page-content .table-wrapper,
        .page-content .manga-table-wrap {
            border: 1px solid var(--admin-line) !important;
            border-radius: var(--admin-radius) !important;
            box-shadow: var(--admin-shadow-sm) !important;
            background: var(--admin-white) !important;
        }

        .page-content input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]),
        .page-content select,
        .page-content textarea {
            background: var(--admin-white) !important;
            color: var(--admin-dark) !important;
            border: 1px solid #d0d5dd !important;
            border-radius: 14px !important;
            box-shadow: none !important;
            font-family: var(--font-sans) !important;
            font-weight: 700 !important;
        }

        .page-content input:focus,
        .page-content select:focus,
        .page-content textarea:focus {
            border-color: #93c5fd !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10) !important;
            transform: none !important;
        }

        @media (max-width: 768px) {
            .page-content .responsive-table tr,
            .page-content .mobile-card,
            .page-content .student-row,
            .page-content .guru-row {
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

