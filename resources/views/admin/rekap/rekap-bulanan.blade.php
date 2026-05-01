@extends('layouts.admin')

@section('content')

<style>
    /* ── PAGE HEADER ── */
    .page-header {
        display: flex; align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 20px; gap: 16px; flex-wrap: wrap;
    }

    .page-header-left .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px; font-weight: 800;
        color: var(--text1); line-height: 1.2;
    }

    .page-header-left .page-subtitle {
        font-size: 12px; color: var(--text2); margin-top: 3px;
    }

    .header-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    .btn-outline {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        border-radius: 8px;
        font-size: 12px; font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        transition: all .2s;
    }

    .btn-outline:hover { border-color: rgba(88,166,255,.4); color: var(--accent); }

    .btn-outline.active {
        background: rgba(88,166,255,.1);
        border-color: rgba(88,166,255,.3);
        color: var(--accent);
    }

    /* ── MONTH PICKER BAR ── */
    .month-picker-bar {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 20px; flex-wrap: wrap;
    }

    .month-nav {
        display: inline-flex; align-items: center; gap: 0;
    }

    .month-nav-btn {
        width: 32px; height: 32px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        display: grid; place-items: center;
        font-size: 14px; cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }

    .month-nav-btn:first-child { border-radius: 8px 0 0 8px; }
    .month-nav-btn:last-child  { border-radius: 0 8px 8px 0; border-left: none; }
    .month-nav-btn:hover { background: var(--glass-hover); color: var(--accent); }

    .month-display {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px; font-weight: 700;
        color: var(--text1);
    }

    .month-input {
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 8px;
        padding: 8px 14px;
        color: var(--text1);
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        outline: none; cursor: pointer;
        transition: border-color .2s;
    }

    .month-input:focus { border-color: rgba(88,166,255,.4); }

    .btn-now {
        padding: 6px 12px;
        background: rgba(88,166,255,.08);
        border: 1px solid rgba(88,166,255,.15);
        color: var(--accent);
        border-radius: 7px;
        font-size: 11px; font-weight: 600;
        cursor: pointer; font-family: 'Inter', sans-serif;
        transition: all .2s; text-decoration: none;
    }

    .btn-now:hover { background: rgba(88,166,255,.18); border-color: rgba(88,166,255,.4); }

    /* ── KELAS SELECTOR ── */
    .kelas-selector {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 20px; flex-wrap: wrap;
    }

    .kelas-selector label {
        font-size: 11px; font-weight: 600; color: var(--text3);
        text-transform: uppercase; letter-spacing: .05em;
    }

    .kelas-select {
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 7px;
        padding: 7px 12px;
        color: var(--text1);
        font-size: 12px;
        font-family: 'Inter', sans-serif;
        outline: none; cursor: pointer;
        transition: border-color .2s;
    }

    .kelas-select:focus { border-color: rgba(88,166,255,.4); }

    /* ── STATS ROW ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 16px 18px;
        display: flex; align-items: center; gap: 14px;
        transition: border-color .2s;
    }

    .stat-card:hover { border-color: rgba(88,166,255,0.25); }

    .stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: grid; place-items: center;
        font-size: 18px; flex-shrink: 0;
    }

    .stat-icon.blue    { background: rgba(88,166,255,.12);  color: var(--accent); }
    .stat-icon.green   { background: rgba(63,185,80,.12);   color: var(--green); }
    .stat-icon.red     { background: rgba(248,81,73,.12);   color: var(--red); }
    .stat-icon.purple  { background: rgba(188,140,255,.12); color: var(--purple); }
    .stat-icon.amber   { background: rgba(227,179,65,.12);  color: var(--amber); }

    .stat-label {
        font-size: 10.5px; color: var(--text2);
        text-transform: uppercase; letter-spacing: .06em; font-weight: 600;
    }

    .stat-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 22px; font-weight: 800;
        color: var(--text1); line-height: 1.1;
    }

    .stat-sub {
        font-size: 10px; color: var(--text3);
        margin-top: 1px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 140px;
    }

    /* ── CARD ── */
    .card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        overflow: hidden;
    }

    .card-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid var(--glass-border);
        gap: 12px; flex-wrap: wrap;
    }

    .card-toolbar-left { display: flex; align-items: center; gap: 8px; }

    .card-label { font-size: 12.5px; font-weight: 600; color: var(--text1); }

    .count-badge {
        font-size: 10px; font-weight: 700;
        background: rgba(88,166,255,.12); color: var(--accent);
        padding: 2px 8px; border-radius: 10px;
    }

    /* ── TABLE ── */
    .tbl-wrap { overflow-x: auto; }

    .data-table { width: 100%; border-collapse: collapse; }

    .data-table th {
        padding: 10px 16px;
        text-align: left;
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .1em;
        color: var(--text3);
        border-bottom: 1px solid var(--glass-border);
        background: var(--navy2);
        white-space: nowrap;
    }

    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--glass-border);
        font-size: 12.5px; color: var(--text2);
        vertical-align: middle;
    }

    .data-table tbody tr { transition: background .15s; }
    .data-table tbody tr:hover td { background: var(--glass-hover); color: var(--text1); }
    .data-table tbody tr:last-child td { border-bottom: none; }

    /* ── CELLS ── */
    .cell-siswa {
        display: flex; align-items: center; gap: 9px;
        font-weight: 600; color: var(--text1);
    }

    .avatar-sm {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--purple), var(--accent));
        display: grid; place-items: center;
        font-size: 10px; font-weight: 700;
        color: #fff; flex-shrink: 0;
    }

    .nis-text {
        font-size: 10px; color: var(--text3); margin-top: 1px;
    }

    .hadir-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(63,185,80,0.1); color: var(--green);
        font-size: 11px; font-weight: 700;
        padding: 3px 9px; border-radius: 5px;
    }

    .alpa-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(248,81,73,0.1); color: var(--red);
        font-size: 11px; font-weight: 700;
        padding: 3px 9px; border-radius: 5px;
    }

    .persen-bar-wrap {
        display: flex; align-items: center; gap: 8px;
    }

    .persen-bar {
        width: 80px; height: 6px;
        background: var(--navy3); border-radius: 4px;
        overflow: hidden;
    }

    .persen-fill {
        height: 100%; border-radius: 4px;
        transition: width .5s ease;
    }

    .persen-text { font-size: 11px; font-weight: 700; color: var(--text1); min-width: 36px; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 10px; font-weight: 700;
        padding: 3px 10px; border-radius: 5px;
        text-transform: uppercase; letter-spacing: .04em;
    }

    .status-badge.sangat-baik { background: rgba(63,185,80,0.1); color: var(--green); }
    .status-badge.baik        { background: rgba(88,166,255,0.1); color: var(--accent); }
    .status-badge.perhatian    { background: rgba(227,179,65,0.12); color: var(--amber); }
    .status-badge.kritis       { background: rgba(248,81,73,0.1); color: var(--red); }

    /* ── EMPTY STATE ── */
    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-icon { font-size: 40px; color: var(--text3); margin-bottom: 12px; }
    .empty-title { font-size: 14px; font-weight: 600; color: var(--text2); margin-bottom: 5px; }
    .empty-desc { font-size: 12px; color: var(--text3); }

    /* ── ANIMATION ── */
    .data-table tbody tr { animation: rowIn .25s ease both; }

    @keyframes rowIn {
        from { opacity: 0; transform: translateX(-6px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    @for ($i = 1; $i <= 40; $i++)
    .data-table tbody tr:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 30 }}ms; }
    @endfor

    .search-box {
        display: flex; align-items: center; gap: 7px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 7px; padding: 6px 11px;
        transition: border-color .2s;
    }

    .search-box:focus-within { border-color: rgba(88,166,255,.4); }
    .search-box i { font-size: 12px; color: var(--text3); }

    .search-box input {
        background: none; border: none; outline: none;
        color: var(--text1); font-size: 12px;
        font-family: 'Inter', sans-serif; width: 160px;
    }

    .search-box input::placeholder { color: var(--text3); }

    /* ── PRINT STYLES ── */
    @media print {
        @page { size: portrait; margin: 15mm; }
        body { background: #fff !important; color: #000 !important; }
        .sidebar, .topbar, .header-actions, .month-picker-bar, .kelas-selector, .search-box, .btn-print, .theme-toggle, .hamburger-btn, .top-logout {
            display: none !important;
        }
        .main-wrapper { margin-left: 0 !important; padding: 0 !important; width: 100% !important; background: #fff !important; }
        .page-body { padding: 0 !important; margin: 0 !important; }
        .page-title { color: #000 !important; font-size: 24px !important; margin-bottom: 5px !important; }
        .page-subtitle { color: #444 !important; }

        .card { border: none !important; box-shadow: none !important; background: #fff !important; margin-top: 20px;}
        .card-toolbar { border-bottom: 2px solid #000 !important; padding-left: 0; padding-right: 0; }
        .card-label { color: #000 !important; font-size: 16px !important; }
        .count-badge { background: #eee !important; color: #000 !important; border: 1px solid #ccc; }
        
        .data-table th { background: #f0f0f0 !important; color: #000 !important; border-bottom: 2px solid #000 !important; font-weight: bold !important; font-size: 12px !important; }
        .data-table td { color: #000 !important; border-bottom: 1px solid #ddd !important; font-size: 12px !important;}
        .status-badge { background: none !important; border: 1px solid #ccc; color: #000 !important; font-size: 10px !important; }
        .hadir-badge, .alpa-badge { background: none !important; color: #000 !important; border: 1px solid #ccc; }
        
        .persen-bar { border: 1px solid #ddd; background: #fff !important; }
        .persen-fill { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .persen-text { color: #000 !important; }

        .stats-row { display: flex; gap: 10px; margin-bottom: 20px; break-inside: avoid; }
        .stat-card { background: #fff !important; border: 1px solid #ccc !important; padding: 10px; flex: 1; min-width: 0; }
        .stat-icon { background: #f0f0f0 !important; color: #000 !important; box-shadow: inset 0 0 0 1px #ddd;}
        .stat-value { color: #000 !important; }
        
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

@php
    $prevBulan = $bulan->copy()->subMonth()->format('Y-m');
    $nextBulan = $bulan->copy()->addMonth()->format('Y-m');
    $bulanIni  = \Carbon\Carbon::today()->format('Y-m');
    $bulanDisplay = $bulan->format('Y-m');
@endphp

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Rekap Bulanan</div>
        <div class="page-subtitle">Ringkasan kehadiran per siswa dalam satu bulan</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.rekap.index') }}" class="btn-outline">
            <i class="bi bi-list-ul"></i> Per Sesi
        </a>
        <a href="{{ route('admin.rekap.harian') }}" class="btn-outline">
            <i class="bi bi-calendar-day"></i> Rekap Harian
        </a>
        <a href="{{ route('admin.rekap.bulanan') }}" class="btn-outline active">
            <i class="bi bi-calendar-month"></i> Rekap Bulanan
        </a>
    </div>
</div>

{{-- MONTH PICKER BAR --}}
<div class="month-picker-bar">
    <div class="month-nav">
        <a href="{{ route('admin.rekap.bulanan', ['bulan' => $prevBulan, 'kelas' => $selectedKelas]) }}" class="month-nav-btn">
            <i class="bi bi-chevron-left"></i>
        </a>
        <a href="{{ route('admin.rekap.bulanan', ['bulan' => $nextBulan, 'kelas' => $selectedKelas]) }}" class="month-nav-btn">
            <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <div class="month-display">
        <i class="bi bi-calendar3" style="color:var(--accent); font-size:13px; margin-right:4px"></i>
        {{ $bulan->translatedFormat('F Y') }}
    </div>

    <form method="GET" action="{{ route('admin.rekap.bulanan') }}" style="display:flex; gap:8px; align-items:center">
        <input type="month" name="bulan" class="month-input" value="{{ $bulanDisplay }}" onchange="this.form.submit()">
        @if($selectedKelas)
            <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
        @endif
    </form>

    @if($bulanDisplay !== $bulanIni)
        <a href="{{ route('admin.rekap.bulanan', ['kelas' => $selectedKelas]) }}" class="btn-now">
            <i class="bi bi-arrow-counterclockwise" style="font-size:10px"></i> Bulan Ini
        </a>
    @endif
</div>

{{-- KELAS SELECTOR --}}
@if($kelasList->isNotEmpty())
<div class="kelas-selector">
    <label><i class="bi bi-funnel" style="margin-right:3px"></i> Kelas:</label>
    <form method="GET" action="{{ route('admin.rekap.bulanan') }}" id="kelasForm">
        <input type="hidden" name="bulan" value="{{ $bulanDisplay }}">
        <select name="kelas" class="kelas-select" onchange="this.form.submit()">
            @foreach ($kelasList as $k)
                <option value="{{ $k }}" @selected($selectedKelas == $k)>{{ $k }}</option>
            @endforeach
        </select>
    </form>
</div>
@endif

{{-- STATS ROW --}}
@if($rekapSiswa->isNotEmpty())
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-journal-check"></i></div>
        <div>
            <div class="stat-label">Total Sesi</div>
            <div class="stat-value">{{ $totalSesi }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="bi bi-graph-up"></i></div>
        <div>
            <div class="stat-label">Rata-rata Kehadiran</div>
            <div class="stat-value">{{ $ringkasan->rata_rata }}%</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-trophy-fill"></i></div>
        <div>
            <div class="stat-label">Siswa Terbaik</div>
            <div class="stat-value" style="font-size:16px">{{ $ringkasan->siswa_terbaik->persentase ?? 0 }}%</div>
            <div class="stat-sub">{{ $ringkasan->siswa_terbaik->nama_siswa ?? '-' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div>
            <div class="stat-label">Perlu Perhatian</div>
            <div class="stat-value" style="font-size:16px">{{ $ringkasan->siswa_terendah->persentase ?? 0 }}%</div>
            <div class="stat-sub">{{ $ringkasan->siswa_terendah->nama_siswa ?? '-' }}</div>
        </div>
    </div>
</div>
@endif

{{-- TABLE CARD --}}
<div class="card">
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="card-label">Rekap Kehadiran Siswa</span>
            <span class="count-badge">{{ $rekapSiswa->count() }} siswa</span>
        </div>
        <div style="display:flex; gap:10px; align-items:center;">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama siswa...">
            </div>
            @if($rekapSiswa->isNotEmpty())
            <button onclick="window.print()" class="btn-outline btn-print" style="margin:0; border-color:var(--accent); color:var(--accent); cursor:pointer;">
                <i class="bi bi-printer"></i> Cetak PDF
            </button>
            @endif
        </div>
    </div>

    <div class="tbl-wrap">
        <table class="data-table" id="rekapTable">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Siswa</th>
                    <th style="text-align:center">Hadir</th>
                    <th style="text-align:center">Alpa</th>
                    <th>Kehadiran</th>
                    <th style="text-align:center">Status</th>
                </tr>
            </thead>
            <tbody id="rekapBody">
                @forelse($rekapSiswa as $index => $siswa)
                @php
                    $barColor = $siswa->persentase >= 80 ? 'var(--green)' : ($siswa->persentase >= 50 ? 'var(--amber)' : 'var(--red)');

                    if ($siswa->persentase >= 90) {
                        $statusLabel = 'Sangat Baik';
                        $statusClass = 'sangat-baik';
                    } elseif ($siswa->persentase >= 75) {
                        $statusLabel = 'Baik';
                        $statusClass = 'baik';
                    } elseif ($siswa->persentase >= 50) {
                        $statusLabel = 'Perlu Perhatian';
                        $statusClass = 'perhatian';
                    } else {
                        $statusLabel = 'Kritis';
                        $statusClass = 'kritis';
                    }
                @endphp
                <tr data-nama="{{ strtolower($siswa->nama_siswa) }}">
                    <td style="color:var(--text3); font-size:11px">{{ $index + 1 }}</td>
                    <td>
                        <div class="cell-siswa">
                            <div class="avatar-sm">{{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}</div>
                            <div>
                                {{ $siswa->nama_siswa }}
                                <div class="nis-text">{{ $siswa->NIS }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center">
                        <span class="hadir-badge"><i class="bi bi-check2" style="font-size:10px"></i> {{ $siswa->total_hadir }}</span>
                    </td>
                    <td style="text-align:center">
                        @if($siswa->total_alpa > 0)
                            <span class="alpa-badge"><i class="bi bi-x-lg" style="font-size:9px"></i> {{ $siswa->total_alpa }}</span>
                        @else
                            <span style="color:var(--text3); font-size:11px">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="persen-bar-wrap">
                            <div class="persen-bar">
                                <div class="persen-fill" style="width:{{ $siswa->persentase }}%; background:{{ $barColor }}"></div>
                            </div>
                            <span class="persen-text" style="color:{{ $barColor }}">{{ $siswa->persentase }}%</span>
                        </div>
                    </td>
                    <td style="text-align:center">
                        <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                            <div class="empty-title">Belum ada data presensi</div>
                            <div class="empty-desc">
                                @if($kelasList->isEmpty())
                                    Tidak ada sesi kelas yang selesai di bulan {{ $bulan->translatedFormat('F Y') }}.
                                @else
                                    Pilih kelas untuk melihat rekap bulanan siswa.
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div id="noResult" style="display:none">
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-search"></i></div>
                <div class="empty-title">Tidak ada hasil</div>
                <div class="empty-desc">Coba kata kunci yang berbeda.</div>
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const rows        = document.querySelectorAll('#rekapBody tr[data-nama]');
    const noResult    = document.getElementById('noResult');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            let visible = 0;

            rows.forEach(row => {
                const nama = row.dataset.nama;
                const match = !q || nama.includes(q);

                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            noResult.style.display = visible === 0 ? 'flex' : 'none';
        });
    }
</script>

@endsection
