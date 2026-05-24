<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('layouts.partials.theme-tokens')

    <style>
        :root {
            /* Definisi Palet Warna Resmi Manga-Pop */
            --sakura: #FF7675;
            --cyber: #00CEC9;
            --cosmo: #6C5CE7;
            --gold: #FDCB6E;
            --midnight: #1E1B29;
            --mochi: #FAF9FF;
            --white: #FFFFFF;
            --font-sans: 'Nunito', 'Inter', system-ui, sans-serif;
        }

        body {
            /* Latar Belakang Komik Titik-Titik Pop (Mochi Cream + Radial Cosmo Violet Dots) */
            background-color: var(--mochi);
            background-image: radial-gradient(var(--cosmo) 1.5px, transparent 0);
            background-size: 32px 32px;
            background-attachment: fixed;
            
            color: var(--midnight);
            font-family: var(--font-sans);
            margin: 0;
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar - Panel Komik Manga-Pop Style */
        .runway-sidebar {
            width: 260px;
            background-color: var(--white);
            /* Garis pembatas tinta tebal khas panel manga */
            border-right: 4px solid var(--midnight);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
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
            border-bottom: 4px solid var(--midnight);
            background-color: var(--gold); /* Header brand bernuansa ceria */
        }

        .brand-logo {
            font-family: 'Fredoka One', sans-serif;
            font-size: 22px;
            color: var(--midnight);
            text-decoration: none;
            letter-spacing: -0.5px;
            text-transform: lowercase;
            text-shadow: 2px 2px 0px var(--white);
        }

        .sb-nav {
            padding: 24px 16px;
            flex: 1;
            overflow-y: auto;
        }

        .sb-section {
            font-family: 'Fredoka One', sans-serif;
            font-size: 12px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--midnight);
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
            color: var(--midnight);
            text-decoration: none;
            border: 3px solid transparent;
            transition: all 0.15s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 8px;
        }

        /* Efek Timbul & Melayang Solid Khas Neobrutalism */
        .nav-link:hover, .nav-link.active {
            color: var(--midnight) !important;
            background-color: var(--cyber) !important; /* Cyber Oasis */
            border-color: var(--midnight) !important;
            box-shadow: 4px 4px 0px var(--midnight) !important;
            transform: translate(-3px, -3px);
        }

        /* Efek Tekan yang Memuaskan saat Di-klik */
        .nav-link:active {
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0px var(--midnight) !important;
        }

        .nav-link i {
            font-size: 16px;
            color: var(--midnight);
            transition: transform 0.2s;
        }

        .nav-link:hover i {
            transform: scale(1.15) rotate(-5deg);
        }

        .sb-footer {
            padding: 16px;
            border-top: 4px solid var(--midnight);
            background-color: var(--white);
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
            border-radius: 10px;
            background-color: var(--sakura); /* Sakura Burst */
            color: var(--midnight);
            border: 3px solid var(--midnight);
            box-shadow: 3px 3px 0px var(--midnight);
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
            border-bottom: 4px solid var(--midnight);
            background-color: var(--white);
        }

        .page-content {
            flex: 1;
            padding: 40px 40px;
            overflow-y: auto;
            /* Dibuat transparent agar pola titik komik latar belakang menembus sempurna */
            background-color: transparent; 
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
            border: 3px solid var(--midnight);
            background: var(--white);
            border-radius: 50%;
            cursor: pointer;
            color: var(--midnight);
            box-shadow: 3px 3px 0px var(--midnight);
            transition: all 0.2s;
        }
        .menu-toggle:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px var(--midnight);
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
            font-family: 'Fredoka One', sans-serif;
            font-size: 13px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--cosmo);
            margin-bottom: 12px;
            display: block;
        }

        .display-title {
            font-family: 'Fredoka One', sans-serif;
            font-size: 42px;
            line-height: 1.1;
            color: var(--midnight);
            margin-bottom: 24px;
        }

        /* Form Input Bergaya Komik Panel Neobrutalism */
        input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]),
        select,
        textarea {
            background-color: var(--white) !important;
            color: var(--midnight) !important;
            border: 4px solid var(--midnight) !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            font-family: var(--font-sans) !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            outline: none !important;
            box-shadow: 4px 4px 0px var(--midnight) !important;
            transition: all 0.15s ease !important;
        }

        input:focus, select:focus, textarea:focus {
            transform: translate(-2px, -2px) !important;
            box-shadow: 6px 6px 0px var(--midnight) !important;
            border-color: var(--cosmo) !important;
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
            width: 60px !important;
            height: 60px !important;
            border-radius: 50% !important;
            background-color: var(--gold) !important; 
            color: var(--midnight) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border: 4px solid var(--midnight) !important;
            box-shadow: 5px 5px 0px var(--midnight) !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
            text-decoration: none !important;
            animation: fab-pop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) backwards;
        }

        @keyframes fab-pop {
            0% { transform: scale(0) rotate(-45deg); opacity: 0; }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }

        .btn-fab:hover {
            transform: translateY(-4px) scale(1.05) !important;
            box-shadow: 8px 8px 0px var(--midnight) !important;
            background-color: var(--sakura) !important; 
        }
        
        .btn-fab:active {
            transform: translateY(2px) scale(0.95) !important;
            box-shadow: 2px 2px 0px var(--midnight) !important;
        }

        .fab-label {
            position: absolute !important;
            right: 80px !important;
            background: var(--white) !important;
            color: var(--midnight) !important;
            border: 3px solid var(--midnight) !important;
            padding: 8px 16px !important;
            border-radius: 12px !important;
            font-family: 'Fredoka One', sans-serif !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            white-space: nowrap !important;
            opacity: 0 !important;
            transform: translateX(20px) !important;
            pointer-events: none !important;
            transition: all 0.3s ease !important;
            box-shadow: 4px 4px 0px var(--midnight) !important;
        }

        .btn-fab:hover .fab-label {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }

        /* Utilitas Button Utama Bergaya Neobrutalism Berani (Manga-Pop Action Button) */
        .btn-primary {
            font-family: 'Fredoka One', sans-serif !important;
            background-color: var(--sakura) !important; /* Sakura Burst */
            color: var(--midnight) !important;
            border: 3px solid var(--midnight) !important;
            border-radius: 12px !important;
            box-shadow: 4px 4px 0px var(--midnight) !important;
            transition: all 0.15s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: bold;
        }
        .btn-primary:hover {
            transform: translate(-3px, -3px);
            box-shadow: 7px 7px 0px var(--midnight) !important;
            background-color: #ff5252 !important; /* Sedikit lebih menyala saat hover */
        }
        .btn-primary:active {
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0px var(--midnight) !important;
        }

        .btn-logout {
            font-family: 'Fredoka One', sans-serif !important;
            background-color: var(--sakura) !important;
            color: var(--midnight) !important;
            border: 3px solid var(--midnight) !important;
            border-radius: 12px !important;
            box-shadow: 3px 3px 0px var(--midnight) !important;
            transition: all 0.15s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
            transform: translate(-2px, -2px);
            box-shadow: 5px 5px 0px var(--midnight) !important;
            background-color: #ff6665 !important;
        }

        .btn-logout:active {
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0px var(--midnight) !important;
        }
    </style>
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
        <a href="{{ route('admin.rekap.index') }}" class="nav-link {{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-spreadsheet-fill"></i> Rekap Mingguan
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
                <div style="font-size: 13px; font-weight: 800; color: var(--midnight);">{{ auth()->user()->name ?? auth()->user()->email }}</div>
                <div style="font-size: 11px; font-weight: 700; color: var(--cosmo);">{{ auth()->user()->role }}</div>
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
            <span class="text-meta" style="font-weight: 800; color: var(--midnight); opacity: 0.7;">Admin / @yield('breadcrumb', 'Overview')</span>
        </div>

        <div style="display: flex; gap: 20px; align-items: center; position: relative; z-index: 9999;">
            <button type="button" 
                    id="theme-toggle-btn"
                    onclick="window.toggleTheme()" 
                    style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border: 3px solid var(--midnight); background: var(--white); font-size: 20px; color: var(--midnight); cursor: pointer; border-radius: 50%; box-shadow: 3px 3px 0px var(--midnight); transition: all 0.15s;" 
                    onmouseover="this.style.background='var(--mochi)';"
                    onmouseout="this.style.background='var(--white)';"
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

