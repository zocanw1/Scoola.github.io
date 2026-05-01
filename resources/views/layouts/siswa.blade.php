<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola — Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        (function() {
            var t = localStorage.getItem('scoola-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>

    @include('layouts.partials.theme-tokens')
    @include('layouts.partials.topbar-styles')
    @include('layouts.partials.shared-components')

    <style>
        body { overflow-x: hidden; }

        /* Student uses topbar-only layout (no sidebar) */
        .main-wrapper { margin-left: 0 !important; }

        .topbar { padding: 0 24px; gap: 14px; }

        .tb-brand { display: flex; align-items: center; gap: 10px; }

        .tb-logo {
            width: 28px; height: 28px;
            background: var(--accent);
            border-radius: 7px;
            display: grid; place-items: center;
            font-size: 14px; font-weight: 800;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .tb-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; font-weight: 800;
            color: var(--text1);
        }

        .page-body {
            padding: 30px 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .user-pill {
            font-size: 11px;
            padding: 4px 10px;
            background: var(--glass);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            font-weight: 600;
            color: var(--text1);
        }

        /* Navigation Tabs */
        .siswa-nav {
            display: flex;
            gap: 4px;
            padding: 4px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
        }

        .siswa-nav a {
            padding: 6px 16px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text2);
            text-decoration: none;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .siswa-nav a:hover { color: var(--text1); background: var(--glass-hover); }
        .siswa-nav a.active {
            background: var(--gradient-accent);
            color: #fff;
        }

        .izin-badge {
            font-size: 9px;
            background: var(--red);
            color: #fff;
            width: 16px; height: 16px;
            border-radius: 50%;
            display: inline-grid;
            place-items: center;
            font-weight: 700;
            line-height: 1;
        }

        /* ══ MOBILE SISWA ══ */
        @media (max-width: 768px) {
            .topbar { padding: 0 12px; gap: 8px; flex-wrap: nowrap; }

            .siswa-nav {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                white-space: nowrap;
                flex-shrink: 1;
                min-width: 0;
            }
            .siswa-nav::-webkit-scrollbar { display: none; }

            .user-pill { display: none; }

            .tb-title { font-size: 12px; }

            .theme-toggle span { display: none !important; }
            .theme-toggle { padding: 5px 8px; }

            .top-logout {
                padding: 6px 10px;
                font-size: 13px;
                white-space: nowrap;
            }

            .page-body { padding: 16px 14px; }
        }

        @media (max-width: 480px) {
            .topbar { padding: 0 8px; gap: 6px; }
            .tb-brand { gap: 6px; }
            .tb-title { font-size: 11px; }
            .siswa-nav a { padding: 5px 12px; font-size: 11px; }
            .page-body { padding: 12px 10px; }
        }
    </style>
</head>
<body>

<!-- MAIN (no sidebar for students) -->
<div class="main-wrapper">
    <header class="topbar">
        <div class="tb-brand">
            <div class="tb-logo">S</div>
            <div class="tb-title">Scoola Student</div>
        </div>

        <nav class="siswa-nav">
            <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i> Presensi
            </a>

        </nav>

        <div class="top-spacer"></div>

        <button class="theme-toggle" onclick="scoolaToggleTheme()" id="scoolaThemeBtn" title="Ganti tema">
            <i class="bi bi-sun-fill" id="scoolaThemeIcon" style="font-size:13px"></i>
            <div class="toggle-track"><div class="toggle-thumb"></div></div>
            <span class="d-none d-sm-inline" id="scoolaThemeLabel">Light</span>
        </button>

        <div class="user-pill">
            <i class="bi bi-person-fill" style="color:var(--accent); margin-right:4px;"></i>
            {{ auth()->user()->name ?? auth()->user()->email }}
        </div>

        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="top-logout">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
        </form>
    </header>

    <main class="page-body">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@include('layouts.partials.theme-engine')

</body>
</html>
