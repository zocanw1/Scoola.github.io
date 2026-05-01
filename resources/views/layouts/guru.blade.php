<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola — Guru</title>
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
    @include('layouts.partials.sidebar-styles')
    @include('layouts.partials.topbar-styles')
    @include('layouts.partials.shared-components')
</head>
<body>

<!-- SIDEBAR OVERLAY (mobile) -->
<div class="sidebar-overlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sb-brand">
        <div class="sb-logo">S</div>
        <div class="sb-title">Scoola</div>
        <div class="sb-ver">v2.0</div>
    </div>

    <div class="sb-nav">
        <div class="sb-section">Utama</div>
        <a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="{{ route('guru.presensi.index') }}" class="nav-link {{ request()->routeIs('guru.presensi.*') ? 'active' : '' }}">
            <i class="bi bi-journal-check"></i> Mulai Mengajar
        </a>

        @if(auth()->user()->guru && auth()->user()->guru->kelasWali)
        <div class="sb-section" style="margin-top:6px">Wali Kelas</div>
        <a href="#" class="nav-link disabled" title="Segera hadir">
            <i class="bi bi-clipboard2-check"></i> Rekap Per Sesi
            <span class="nav-badge nb-amber">Soon</span>
        </a>
        <a href="#" class="nav-link disabled" title="Segera hadir">
            <i class="bi bi-calendar-day"></i> Rekap Harian
            <span class="nav-badge nb-amber">Soon</span>
        </a>
        <a href="#" class="nav-link disabled" title="Segera hadir">
            <i class="bi bi-calendar-month"></i> Rekap Bulanan
            <span class="nav-badge nb-amber">Soon</span>
        </a>
        @endif

        <div class="sb-section" style="margin-top:6px">Sistem</div>
        <a href="#" class="nav-link disabled" title="Segera hadir">
            <i class="bi bi-person-circle"></i> Profil Saya
            <span class="nav-badge nb-amber">Soon</span>
        </a>
        <a href="#" class="nav-link disabled" title="Segera hadir">
            <i class="bi bi-gear-fill"></i> Pengaturan
            <span class="nav-badge nb-amber">Soon</span>
        </a>
    </div>

    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}</div>
            <div style="overflow:hidden">
                <div class="sb-uname">{{ auth()->user()->name ?? auth()->user()->email }}</div>
                <div class="sb-urole">{{ auth()->user()->role ?? 'guru' }}</div>
            </div>
        </div>
    </div>
</aside>

<!-- MAIN -->
<div class="main-wrapper">
    <header class="topbar">
        <button class="hamburger-btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div class="top-title">@yield('page-title', 'Dashboard')</div>
            <div class="top-breadcrumb">Guru <span>/ @yield('breadcrumb', 'Overview')</span></div>
        </div>

        <div class="top-spacer"></div>

        <div class="top-search">
            <i class="bi bi-search" style="font-size:12px"></i>
            <input type="text" placeholder="Cari siswa atau kelas...">
        </div>

        <button class="theme-toggle" onclick="scoolaToggleTheme()" id="scoolaThemeBtn" title="Ganti tema">
            <i class="bi bi-sun-fill" id="scoolaThemeIcon" style="font-size:13px"></i>
            <div class="toggle-track"><div class="toggle-thumb"></div></div>
            <span id="scoolaThemeLabel">Light</span>
        </button>

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
