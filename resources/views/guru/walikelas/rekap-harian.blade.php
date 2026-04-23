@extends('layouts.guru')

@section('page-title', 'Rekap Harian')
@section('breadcrumb', 'Wali Kelas / Rekap Harian')

@section('content')

<style>
    .page-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        margin-bottom: 20px; gap: 16px; flex-wrap: wrap;
    }
    .page-header-left .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px; font-weight: 800; color: var(--text1); line-height: 1.2;
    }
    .page-header-left .page-subtitle {
        font-size: 12px; color: var(--text2); margin-top: 3px;
    }
    .header-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-outline {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; background: var(--navy3);
        border: 1px solid var(--glass-border); color: var(--text2);
        border-radius: 8px; font-size: 12px; font-weight: 600;
        font-family: 'Inter', sans-serif; text-decoration: none;
        transition: all .2s; cursor: pointer;
    }
    .btn-outline:hover { border-color: rgba(88,166,255,.4); color: var(--accent); }
    .btn-outline.active {
        background: rgba(88,166,255,.1); border-color: rgba(88,166,255,.3); color: var(--accent);
    }

    /* Filter */
    .filter-bar {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 16px; flex-wrap: wrap;
    }
    .filter-bar label {
        font-size: 11px; font-weight: 600; color: var(--text3);
        text-transform: uppercase; letter-spacing: .05em;
    }
    .filter-date {
        background: var(--navy3); border: 1px solid var(--glass-border);
        border-radius: 7px; padding: 7px 12px; color: var(--text1);
        font-size: 12px; font-family: 'Inter', sans-serif;
        outline: none; cursor: pointer; transition: border-color .2s;
    }
    .filter-date:focus { border-color: rgba(88,166,255,.4); }

    /* Stats */
    .stats-row {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(155px, 1fr));
        gap: 12px; margin-bottom: 20px;
    }
    .stat-card {
        background: var(--navy2); border: 1px solid var(--glass-border);
        border-radius: var(--r); padding: 16px 18px;
        display: flex; align-items: center; gap: 14px; transition: border-color .2s;
    }
    .stat-card:hover { border-color: rgba(88,166,255,0.25); }
    .stat-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: grid; place-items: center; font-size: 18px; flex-shrink: 0;
    }
    .stat-icon.blue   { background: rgba(88,166,255,.12);  color: var(--accent); }
    .stat-icon.green  { background: rgba(63,185,80,.12);   color: var(--green); }
    .stat-icon.red    { background: rgba(248,81,73,.12);   color: var(--red); }
    .stat-icon.purple { background: rgba(188,140,255,.12); color: var(--purple); }
    .stat-icon.amber  { background: rgba(227,179,65,.12);  color: var(--amber); }
    .stat-label {
        font-size: 10.5px; color: var(--text2);
        text-transform: uppercase; letter-spacing: .06em; font-weight: 600;
    }
    .stat-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 22px; font-weight: 800; color: var(--text1); line-height: 1.1;
    }

    /* Table card */
    .card {
        background: var(--navy2); border: 1px solid var(--glass-border);
        border-radius: var(--r); overflow: hidden;
    }
    .card-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 16px; border-bottom: 1px solid var(--glass-border);
        gap: 12px; flex-wrap: wrap;
    }
    .card-toolbar-left { display: flex; align-items: center; gap: 8px; }
    .card-label { font-size: 12.5px; font-weight: 600; color: var(--text1); }
    .count-badge {
        font-size: 10px; font-weight: 700;
        background: rgba(88,166,255,.12); color: var(--accent);
        padding: 2px 8px; border-radius: 10px;
    }
    .btn-print {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(88,166,255,0.08); border: 1px solid rgba(88,166,255,0.15);
        color: var(--accent); border-radius: 8px; padding: 7px 14px;
        font-size: 11px; font-weight: 700; cursor: pointer; transition: all .2s;
        font-family: 'Inter', sans-serif;
    }
    .btn-print:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

    .tbl-wrap { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
        padding: 10px 16px; text-align: left;
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .1em; color: var(--text3);
        border-bottom: 1px solid var(--glass-border);
        background: var(--navy2); white-space: nowrap;
    }
    .data-table td {
        padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.04);
        font-size: 12.5px; color: var(--text2); vertical-align: middle;
    }
    .data-table tbody tr { transition: background .15s; }
    .data-table tbody tr:hover td { background: var(--glass-hover); color: var(--text1); }
    .data-table tbody tr:last-child td { border-bottom: none; }

    .cell-guru {
        display: flex; align-items: center; gap: 9px;
        font-weight: 600; color: var(--text1);
    }
    .avatar-sm {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, var(--green), var(--accent));
        display: grid; place-items: center;
        font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .hadir-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(63,185,80,0.1); color: var(--green);
        font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 5px;
    }
    .alpa-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(248,81,73,0.1); color: var(--red);
        font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 5px;
    }
    .persen-bar-wrap { display: flex; align-items: center; gap: 8px; }
    .persen-bar {
        width: 60px; height: 6px; background: var(--navy3);
        border-radius: 4px; overflow: hidden;
    }
    .persen-fill { height: 100%; border-radius: 4px; transition: width .3s ease; }
    .persen-text { font-size: 11px; font-weight: 700; color: var(--text1); min-width: 36px; }

    .btn-view {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 11px; background: rgba(88,166,255,0.08);
        border: 1px solid rgba(88,166,255,0.15);
        color: var(--accent); border-radius: 6px;
        font-size: 11.5px; font-weight: 500; text-decoration: none;
        transition: all .15s; font-family: 'Inter', sans-serif;
    }
    .btn-view:hover { background: rgba(88,166,255,0.18); border-color: rgba(88,166,255,0.4); }

    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-icon { font-size: 40px; color: var(--text3); margin-bottom: 12px; }
    .empty-title { font-size: 14px; font-weight: 600; color: var(--text2); margin-bottom: 5px; }
    .empty-desc { font-size: 12px; color: var(--text3); }

    @media print {
        .btn-back, .btn-print, .sidebar, .topbar, .hamburger-btn,
        .sidebar-overlay, .header-actions, .filter-bar { display: none !important; }
        .main-wrapper { margin-left: 0 !important; }
        .card, .stat-card { border: 1px solid #ddd; background: #fff; }
        .data-table th, .data-table td { color: #333 !important; border-color: #ddd; }
        .stat-value, .page-title, .card-label { color: #333 !important; }
        body { background: #fff; color: #333; }
    }
</style>

{{-- HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Rekap Harian — {{ $namaKelas }}</div>
        <div class="page-subtitle">
            Ringkasan kehadiran kelas Anda pada
            {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
        </div>
    </div>
    <div class="header-actions">
        <a href="{{ route('guru.walikelas.index') }}" class="btn-outline">
            <i class="bi bi-list-ul"></i> Per Sesi
        </a>
        <a href="{{ route('guru.walikelas.harian') }}" class="btn-outline active">
            <i class="bi bi-calendar-day"></i> Harian
        </a>
        <a href="{{ route('guru.walikelas.bulanan') }}" class="btn-outline">
            <i class="bi bi-calendar-month"></i> Bulanan
        </a>
    </div>
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('guru.walikelas.harian') }}">
    <div class="filter-bar">
        <label><i class="bi bi-calendar3" style="margin-right:3px"></i> Tanggal:</label>
        <input type="date" name="tanggal" class="filter-date"
               value="{{ $tanggal }}" onchange="this.form.submit()">
    </div>
</form>

{{-- STATS --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-value">{{ $ringkasan->total_siswa }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
        <div>
            <div class="stat-label">Hadir</div>
            <div class="stat-value" style="color:var(--green)">{{ $ringkasan->hadir }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-person-x-fill"></i></div>
        <div>
            <div class="stat-label">Alpa</div>
            <div class="stat-value" style="color:var(--red)">{{ $ringkasan->alpa }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="bi bi-graph-up"></i></div>
        <div>
            <div class="stat-label">Kehadiran</div>
            <div class="stat-value">{{ $ringkasan->persentase }}%</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-journal-check"></i></div>
        <div>
            <div class="stat-label">Sesi Hari Ini</div>
            <div class="stat-value">{{ $ringkasan->sesi_count }}</div>
        </div>
    </div>
</div>

{{-- SESI TABLE --}}
<div class="card">
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="card-label">Sesi Kelas {{ $namaKelas }} — {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
            <span class="count-badge">{{ $sesiHari->count() }} sesi</span>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <div class="search-box" style="width: 250px;">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari guru atau waktu...">
            </div>
            <button onclick="window.print()" class="btn-print">
                <i class="bi bi-printer-fill"></i> Cetak
            </button>
        </div>
    </div>

    <div class="tbl-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Waktu</th>
                    <th>Guru Pengajar</th>
                    <th style="text-align:center">Hadir</th>
                    <th style="text-align:center">Alpa</th>
                    <th>Kehadiran</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sesiHari as $index => $sesi)
                @php
                    $ts = \App\Models\Siswa::where('kelas', $sesi->kelas)->count();
                    $hd = \App\Models\Presensi::where('sesi_id', $sesi->id)->where('status', 'Hadir')->count();
                    $al = $ts - $hd;
                    $pct = $ts > 0 ? round(($hd / $ts) * 100, 1) : 0;
                    $bc = $pct >= 80 ? 'var(--green)' : ($pct >= 50 ? 'var(--amber)' : 'var(--red)');
                @endphp
                <tr>
                    <td style="color:var(--text3); font-size:11px">{{ $index + 1 }}</td>
                    <td style="font-weight:600; color:var(--text1)">
                        {{ $sesi->created_at->format('H:i') }} – {{ $sesi->updated_at->format('H:i') }}
                    </td>
                    <td>
                        <div class="cell-guru">
                            <div class="avatar-sm">{{ strtoupper(substr($sesi->guru->name ?? '?', 0, 1)) }}</div>
                            {{ $sesi->guru->name ?? '-' }}
                        </div>
                    </td>
                    <td style="text-align:center">
                        <span class="hadir-badge"><i class="bi bi-check2" style="font-size:10px"></i> {{ $hd }}</span>
                    </td>
                    <td style="text-align:center">
                        @if($al > 0)
                            <span class="alpa-badge"><i class="bi bi-x-lg" style="font-size:9px"></i> {{ $al }}</span>
                        @else
                            <span style="color:var(--text3); font-size:11px">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="persen-bar-wrap">
                            <div class="persen-bar">
                                <div class="persen-fill" style="width:{{ $pct }}%; background:{{ $bc }}"></div>
                            </div>
                            <span class="persen-text" style="color:{{ $bc }}">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('guru.walikelas.show', $sesi->id) }}" class="btn-view">
                            <i class="bi bi-eye-fill"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr class="empty-state-row">
                    <td colspan="7" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                            <div class="empty-title">Tidak ada sesi pada tanggal ini</div>
                            <div class="empty-desc">Pilih tanggal lain untuk melihat rekap.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
                <tr id="noResults" style="display: none;">
                    <td colspan="7" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-search"></i></div>
                            <div class="empty-title">Pencarian tidak ditemukan</div>
                            <div class="empty-desc">Tidak ada sesi yang cocok dengan kata kunci Anda.</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.querySelector('.data-table');
        const rows = table.querySelectorAll('tbody tr:not(.empty-state-row):not(#noResults)');
        const noResults = document.getElementById('noResults');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (visibleCount === 0 && rows.length > 0) {
            noResults.style.display = '';
        } else {
            noResults.style.display = 'none';
        }
    });
</script>
@endsection
