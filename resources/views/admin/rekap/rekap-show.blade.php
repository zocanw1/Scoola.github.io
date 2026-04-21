@extends('layouts.admin')

@section('content')

<style>
    /* ── BACK BTN ── */
    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--text3); font-size: 12px;
        text-decoration: none; margin-bottom: 16px;
        transition: color .2s; font-weight: 500;
    }

    .btn-back:hover { color: var(--accent); }

    /* ── PAGE HEADER ── */
    .page-header {
        display: flex; justify-content: space-between;
        align-items: flex-start; margin-bottom: 20px;
        flex-wrap: wrap; gap: 12px;
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

    .btn-print {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(88,166,255,0.08);
        border: 1px solid rgba(88,166,255,0.15);
        color: var(--accent); border-radius: 8px;
        padding: 8px 16px; font-size: 12px; font-weight: 700;
        cursor: pointer; transition: all .2s;
        font-family: 'Inter', sans-serif;
    }

    .btn-print:hover {
        background: var(--accent); color: #fff;
        border-color: var(--accent);
    }

    /* ── INFO GRID ── */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(155px, 1fr));
        gap: 12px; margin-bottom: 20px;
    }

    .info-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 16px 18px;
        transition: border-color .2s;
    }

    .info-card:hover { border-color: rgba(88,166,255,0.25); }

    .info-label {
        font-size: 10.5px; font-weight: 600; color: var(--text2);
        text-transform: uppercase; letter-spacing: .06em;
        margin-bottom: 6px;
    }

    .info-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 18px; font-weight: 800;
        color: var(--text1); line-height: 1.1;
    }

    .info-icon {
        font-size: 14px; margin-right: 4px;
    }

    /* ── DONUT CHART ── */
    .donut-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 20px;
        display: flex; align-items: center; gap: 20px;
        margin-bottom: 20px;
    }

    .donut-container {
        width: 100px; height: 100px;
        position: relative; flex-shrink: 0;
    }

    .donut-center {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
    }

    .donut-pct {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 22px; font-weight: 800;
        color: var(--text1); line-height: 1;
    }

    .donut-lbl { font-size: 9px; color: var(--text3); text-transform: uppercase; margin-top: 2px; }

    .donut-legend { display: flex; flex-direction: column; gap: 8px; }

    .legend-item { display: flex; align-items: center; gap: 8px; font-size: 12px; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .legend-count { font-weight: 700; color: var(--text1); }
    .legend-label { color: var(--text2); }

    /* ── TABLE CARD ── */
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

    .toolbar-right { display: flex; align-items: center; gap: 8px; }

    .filter-select {
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 7px; padding: 6px 11px;
        color: var(--text2); font-size: 12px;
        font-family: 'Inter', sans-serif;
        outline: none; cursor: pointer;
    }

    .filter-select:focus { border-color: rgba(88,166,255,.4); color: var(--text1); }

    /* ── TABLE ── */
    .tbl-wrap { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; }

    .data-table th {
        padding: 10px 16px; text-align: left;
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .1em;
        color: var(--text3);
        border-bottom: 1px solid var(--glass-border);
        background: var(--navy2); white-space: nowrap;
    }

    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        font-size: 12.5px; color: var(--text2);
        vertical-align: middle;
    }

    .data-table tbody tr { transition: background .15s; }
    .data-table tbody tr:hover td { background: var(--glass-hover); color: var(--text1); }
    .data-table tbody tr:last-child td { border-bottom: none; }

    /* ── CELLS ── */
    .cell-name {
        font-weight: 600; color: var(--text1);
        display: flex; align-items: center; gap: 9px;
    }

    .avatar-sm {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--purple));
        display: grid; place-items: center;
        font-size: 10px; font-weight: 700;
        color: #fff; flex-shrink: 0;
    }

    .nis-badge {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px; font-weight: 700;
        color: var(--accent);
        background: rgba(88,166,255,0.08);
        padding: 3px 8px; border-radius: 5px;
        display: inline-block; letter-spacing: .03em;
    }

    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .02em;
    }

    .sb-hadir {
        background: rgba(63,185,80,0.1); color: var(--green);
        border: 1px solid rgba(63,185,80,0.2);
    }

    .sb-alpa {
        background: rgba(248,81,73,0.1); color: var(--red);
        border: 1px solid rgba(248,81,73,0.2);
    }

    .time-cell {
        font-family: 'Plus Jakarta Sans', monospace;
        font-size: 12px; color: var(--text3);
        display: inline-flex; align-items: center; gap: 5px;
    }

    .time-cell i { font-size: 11px; }

    /* ── EMPTY ── */
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
    .data-table tbody tr:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 25 }}ms; }
    @endfor

    /* ── PRINT ── */
    @media print {
        .btn-back, .btn-print, .sidebar, .topbar, .hamburger-btn,
        .sidebar-overlay, .filter-select, .toolbar-right { display: none !important; }
        .main-wrapper { margin-left: 0 !important; }
        .card, .info-card, .donut-card { border: 1px solid #ddd; background: #fff; }
        .data-table th, .data-table td { color: #333 !important; border-color: #ddd; }
        .info-value, .donut-pct, .page-title, .card-label, .cell-name,
        .legend-count { color: #333 !important; }
        body { background: #fff; color: #333; }
    }
</style>

{{-- BACK --}}
<a href="{{ route('admin.rekap.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sesi
</a>

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Rekap Sesi — {{ $kelas }}</div>
        <div class="page-subtitle">
            <i class="bi bi-calendar3" style="margin-right:3px"></i>
            {{ \Carbon\Carbon::parse($sesi->created_at)->translatedFormat('l, d F Y') }} •
            <i class="bi bi-clock" style="margin-right:2px"></i>
            {{ $sesi->created_at->format('H:i') }} – {{ $sesi->updated_at->format('H:i') }} WIB
        </div>
    </div>
    <div class="header-actions">
        <button onclick="window.print()" class="btn-print">
            <i class="bi bi-printer-fill"></i> Cetak Rekap
        </button>
    </div>
</div>

{{-- INFO GRID + DONUT --}}
<div style="display:grid; grid-template-columns: 1fr auto; gap:16px; margin-bottom:20px; align-items:start;">
    <div class="info-grid" style="margin-bottom:0">
        <div class="info-card">
            <div class="info-label"><i class="bi bi-person-badge info-icon" style="color:var(--accent)"></i> Guru Pengajar</div>
            <div class="info-value" style="font-size:15px">{{ $sesi->guru->name ?? '-' }}</div>
        </div>
        <div class="info-card">
            <div class="info-label"><i class="bi bi-building info-icon" style="color:var(--purple)"></i> Kelas</div>
            <div class="info-value" style="color:var(--purple)">{{ $kelas }}</div>
        </div>
        <div class="info-card">
            <div class="info-label"><i class="bi bi-people info-icon" style="color:var(--accent)"></i> Total Siswa</div>
            <div class="info-value">{{ $totalSiswa }}</div>
        </div>
        <div class="info-card">
            <div class="info-label"><i class="bi bi-person-check info-icon" style="color:var(--green)"></i> Hadir</div>
            <div class="info-value" style="color:var(--green)">{{ $totalHadir }}</div>
        </div>
        <div class="info-card">
            <div class="info-label"><i class="bi bi-person-x info-icon" style="color:var(--red)"></i> Alpa</div>
            <div class="info-value" style="color:var(--red)">{{ $totalAlpa }}</div>
        </div>
    </div>

    {{-- DONUT CHART --}}
    <div class="donut-card">
        @php
            $hadirDeg = $totalSiswa > 0 ? ($totalHadir / $totalSiswa) * 360 : 0;
        @endphp
        <div class="donut-container">
            <svg viewBox="0 0 36 36" style="width:100%; height:100%; transform:rotate(-90deg)">
                <circle cx="18" cy="18" r="15.5" fill="none"
                    stroke="var(--navy3)" stroke-width="3.5" />
                <circle cx="18" cy="18" r="15.5" fill="none"
                    stroke="{{ $persentase >= 80 ? 'var(--green)' : ($persentase >= 50 ? 'var(--amber)' : 'var(--red)') }}"
                    stroke-width="3.5"
                    stroke-dasharray="{{ $persentase }} {{ 100 - $persentase }}"
                    stroke-linecap="round" />
            </svg>
            <div class="donut-center">
                <div class="donut-pct" style="color:{{ $persentase >= 80 ? 'var(--green)' : ($persentase >= 50 ? 'var(--amber)' : 'var(--red)') }}">
                    {{ $persentase }}%
                </div>
                <div class="donut-lbl">Kehadiran</div>
            </div>
        </div>
        <div class="donut-legend">
            <div class="legend-item">
                <div class="legend-dot" style="background:var(--green)"></div>
                <span class="legend-count">{{ $totalHadir }}</span>
                <span class="legend-label">Hadir</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background:var(--red)"></div>
                <span class="legend-count">{{ $totalAlpa }}</span>
                <span class="legend-label">Alpa</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background:var(--accent)"></div>
                <span class="legend-count">{{ $totalSiswa }}</span>
                <span class="legend-label">Total</span>
            </div>
        </div>
    </div>
</div>

{{-- TABLE CARD --}}
<div class="card">
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="card-label">Daftar Kehadiran</span>
            <span class="count-badge">{{ $totalSiswa }} siswa</span>
        </div>
        <div class="toolbar-right">
            <select class="filter-select" id="statusFilter">
                <option value="">Semua Status</option>
                <option value="hadir">Hadir</option>
                <option value="alpa">Alpa</option>
            </select>
        </div>
    </div>

    <div class="tbl-wrap">
        <table class="data-table" id="rekapTable">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th style="text-align:center">Status</th>
                    <th>Jam Absen</th>
                </tr>
            </thead>
            <tbody id="rekapBody">
                @forelse($rekapData as $index => $data)
                <tr data-status="{{ strtolower($data->status) }}">
                    <td style="color:var(--text3); font-size:11px">{{ $index + 1 }}</td>
                    <td><span class="nis-badge">{{ $data->NIS }}</span></td>
                    <td>
                        <div class="cell-name">
                            <div class="avatar-sm">{{ strtoupper(substr($data->nama_siswa, 0, 1)) }}</div>
                            {{ $data->nama_siswa }}
                        </div>
                    </td>
                    <td style="text-align:center">
                        @if($data->status == 'Hadir')
                            <span class="status-badge sb-hadir"><i class="bi bi-check-circle-fill"></i> Hadir</span>
                        @else
                            <span class="status-badge sb-alpa"><i class="bi bi-x-circle-fill"></i> Alpa</span>
                        @endif
                    </td>
                    <td>
                        @if($data->jam_masuk !== '-')
                            <span class="time-cell">
                                <i class="bi bi-clock-fill" style="color:var(--green)"></i>
                                {{ $data->jam_masuk }}
                            </span>
                        @else
                            <span style="color:var(--text3); font-size:11px">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-people"></i></div>
                            <div class="empty-title">Data siswa tidak ditemukan</div>
                            <div class="empty-desc">Tidak ada siswa yang terdaftar di kelas ini.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#rekapBody tr[data-status]');

    statusFilter.addEventListener('change', function() {
        const val = this.value;
        rows.forEach(row => {
            if (!val || row.dataset.status === val) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection
