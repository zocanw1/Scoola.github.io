<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola — Admin</title>
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
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>


        <div class="sb-section" style="margin-top:6px">Data</div>
        <a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Siswa
            <span class="nav-badge nb-blue">{{ App\Models\Siswa::count() }}</span>
        </a>
        <a href="{{ route('guru.index') }}" class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill"></i> Guru
            <span class="nav-badge nb-blue">{{ App\Models\Guru::count() }}</span>
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

        <div class="sb-section" style="margin-top:6px">Laporan</div>
        <a href="{{ route('admin.rekap.index') }}" class="nav-link {{ request()->routeIs('admin.rekap.index') || request()->routeIs('admin.rekap.show') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i> Rekap Presensi
        </a>
        <a href="{{ route('admin.rekap.harian') }}" class="nav-link {{ request()->routeIs('admin.rekap.harian') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i> Rekap Harian
        </a>
        <a href="{{ route('admin.rekap.bulanan') }}" class="nav-link {{ request()->routeIs('admin.rekap.bulanan') ? 'active' : '' }}">
            <i class="bi bi-calendar-month-fill"></i> Rekap Bulanan
        </a>
        <a href="#" class="nav-link disabled" title="Segera hadir">
            <i class="bi bi-download"></i> Ekspor Data
            <span class="nav-badge nb-amber">Soon</span>
        </a>

        <div class="sb-section" style="margin-top:6px">Sistem</div>
        <a href="{{ route('admin.akun.index') }}" class="nav-link {{ request()->routeIs('admin.akun.*') ? 'active' : '' }}">
            <i class="bi bi-shield-plus"></i> Kelola Admin
        </a>
        <a href="#" class="nav-link disabled" title="Segera hadir">
            <i class="bi bi-gear-fill"></i> Pengaturan
            <span class="nav-badge nb-amber">Soon</span>
        </a>
    </div>

    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div style="overflow:hidden">
                <div class="sb-uname">{{ auth()->user()->name ?? auth()->user()->email }}</div>
                <div class="sb-urole">{{ auth()->user()->role }}</div>
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
            <div class="top-breadcrumb">Admin <span>/ @yield('breadcrumb', 'Overview')</span></div>
        </div>

        <div class="top-spacer"></div>

        <div class="top-search">
            <i class="bi bi-search" style="font-size:12px"></i>
            <input type="text" placeholder="Cari siswa, kelas, guru...">
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