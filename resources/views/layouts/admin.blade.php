<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola — Admin</title>
    
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
    @include('layouts.partials.theme-tokens')

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
            background-color: var(--color-surface);
            border-right: 1px solid var(--color-hairline);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
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
            background-color: var(--color-primary); /* Pure Black on light mode */
        }

        .nav-link i {
            font-size: 14px;
            color: var(--color-slate);
        }
        
        .nav-link.active i, .nav-link:hover i {
            color: var(--color-on-primary);
        }

        [data-theme="dark"] .nav-link:hover, [data-theme="dark"] .nav-link.active {
            background-color: var(--color-primary); /* Pure White on dark mode */
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

        @media (max-width: 768px) {
            .page-content {
                padding: 32px 20px !important;
            }
            .runway-topbar {
                padding: 0 16px !important;
            }
            .display-title {
                font-size: 28px !important;
                letter-spacing: -0.5px !important;
                word-break: break-word !important;
            }

            /* Global Floating Card Engine */
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
                display: table !important; /* Keep table but manage children */
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
                border: 1px solid var(--color-hairline) !important;
                border-radius: 16px !important;
                padding: 32px !important;
                background: #ffffff !important;
                box-shadow: 0 8px 24px rgba(0,0,0,0.04) !important;
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
                font-weight: 700;
                text-transform: uppercase;
                font-size: 10px;
                letter-spacing: 0.1em;
                color: var(--color-stone);
                text-align: left;
                padding-right: 16px;
            }

            /* Global Grid Transformation */
            .stats-grid, 
            .responsive-card-grid,
            div[style*="display: grid"] {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 32px !important;
                width: 100% !important;
            }

            .card {
                border-radius: 16px !important;
                padding: 32px !important;
                margin-bottom: 24px !important;
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
            background: rgba(0,0,0,0.3);
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
            border: 1px solid var(--color-hairline);
            background: var(--color-surface);
            border-radius: 50%;
            cursor: pointer;
            color: var(--color-ink);
        }

        @media (max-width: 992px) {
            .menu-toggle {
                display: flex;
            }
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

        /* Floating Action Button (FAB) - WhatsApp Style */
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
            width: 64px !important;
            height: 64px !important;
            border-radius: 50% !important;
            background-color: var(--color-ink) !important; /* Premium Ink Black */
            color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 12px 32px rgba(0,0,0,0.25) !important;
            cursor: pointer !important;
            border: none !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            text-decoration: none !important;
            animation: fab-pop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) backwards;
        }

        @keyframes fab-pop {
            0% { transform: scale(0) rotate(-45deg); opacity: 0; }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }

        .btn-fab:hover {
            transform: translateY(-8px) scale(1.1) !important;
            background-color: var(--color-primary) !important; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3) !important;
        }

        .fab-label {
            position: absolute !important;
            right: 80px !important;
            background: #030303 !important;
            color: #ffffff !important;
            padding: 10px 20px !important;
            border-radius: 12px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            white-space: nowrap !important;
            opacity: 0 !important;
            transform: translateX(20px) !important;
            pointer-events: none !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }

        .btn-fab:hover .fab-label {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileMenu()"></div>

<aside class="runway-sidebar" id="mainSidebar">
    <div class="sb-brand">
        <a href="/" class="brand-logo">scoola admin</a>
    </div>

    <div class="sb-nav">
        <div class="sb-section">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>

        <div class="sb-section">Data</div>
        <a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Siswa
        </a>
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
    </div>

    <div class="sb-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div style="overflow:hidden">
                <div style="font-size: 13px; font-weight: 600; color: var(--color-ink);">{{ auth()->user()->name ?? auth()->user()->email }}</div>
                <div style="font-size: 11px; color: var(--color-stone);">{{ auth()->user()->role }}</div>
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
            <span class="text-meta" style="color: var(--color-stone);">Admin / @yield('breadcrumb', 'Overview')</span>
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
