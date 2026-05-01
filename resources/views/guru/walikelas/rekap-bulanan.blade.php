@extends('layouts.guru')

@section('page-title', 'Rekap Bulanan')
@section('breadcrumb', 'Wali Kelas / Rekap Bulanan')

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
    .filter-month {
        background: var(--navy3); border: 1px solid var(--glass-border);
        border-radius: 7px; padding: 7px 12px; color: var(--text1);
        font-size: 12px; font-family: 'Inter', sans-serif;
        outline: none; cursor: pointer; transition: border-color .2s;
    }
    .filter-month:focus { border-color: rgba(88,166,255,.4); }

    /* Ringkasan cards */
    .ringkasan-row {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px; margin-bottom: 20px;
    }
    .ring-card {
        background: var(--navy2); border: 1px solid var(--glass-border);
        border-radius: var(--r); padding: 18px 20px; transition: border-color .2s;
    }
    .ring-card:hover { border-color: rgba(88,166,255,0.25); }
    .ring-label {
        font-size: 10.5px; font-weight: 600; color: var(--text2);
        text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px;
    }
    .ring-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px; font-weight: 800; color: var(--text1); line-height: 1.1;
    }
    .ring-sub { font-size: 11px; color: var(--text3); margin-top: 4px; }

    /* Card / Table */
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
    .search-box {
        display: flex; align-items: center; gap: 7px;
        background: var(--navy3); border: 1px solid var(--glass-border);
        border-radius: 7px; padding: 6px 11px; transition: border-color .2s;
    }
    .search-box:focus-within { border-color: rgba(88,166,255,.4); }
    .search-box i { font-size: 12px; color: var(--text3); }
    .search-box input {
        background: none; border: none; outline: none; color: var(--text1);
        font-size: 12px; font-family: 'Inter', sans-serif; width: 160px;
    }
    .search-box input::placeholder { color: var(--text3); }
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
        padding: 12px 16px; border-bottom: 1px solid var(--glass-border);
        font-size: 12.5px; color: var(--text2); vertical-align: middle;
    }
    .data-table tbody tr { transition: background .15s; }
    .data-table tbody tr:hover td { background: var(--glass-hover); color: var(--text1); }
    .data-table tbody tr:last-child td { border-bottom: none; }

    .cell-name {
        font-weight: 600; color: var(--text1);
        display: flex; align-items: center; gap: 9px;
    }
    .avatar-sm {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--purple));
        display: grid; place-items: center;
        font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .nis-badge {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px; font-weight: 700; color: var(--accent);
        background: rgba(88,166,255,0.08); padding: 3px 8px;
        border-radius: 5px; display: inline-block; letter-spacing: .03em;
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
        width: 70px; height: 6px; background: var(--navy3);
        border-radius: 4px; overflow: hidden;
    }
    .persen-fill { height: 100%; border-radius: 4px; transition: width .3s ease; }
    .persen-text { font-size: 11px; font-weight: 700; color: var(--text1); min-width: 36px; }

    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-icon { font-size: 40px; color: var(--text3); margin-bottom: 12px; }
    .empty-title { font-size: 14px; font-weight: 600; color: var(--text2); margin-bottom: 5px; }
    .empty-desc { font-size: 12px; color: var(--text3); }

    .data-table tbody tr { animation: rowIn .25s ease both; }
    @keyframes rowIn {
        from { opacity: 0; transform: translateX(-6px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @for ($i = 1; $i <= 40; $i++)
    .data-table tbody tr:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 25 }}ms; }
    @endfor

    @media print {
        .btn-back, .btn-print, .sidebar, .topbar, .hamburger-btn,
        .sidebar-overlay, .header-actions, .filter-bar { display: none !important; }
        .main-wrapper { margin-left: 0 !important; }
        .card, .ring-card { border: 1px solid #ddd; background: #fff; }
        .data-table th, .data-table td { color: #333 !important; border-color: #ddd; }
        .ring-value, .page-title, .card-label, .cell-name { color: #333 !important; }
        body { background: #fff; color: #333; }
    }
</style>

{{-- HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Rekap Bulanan — {{ $namaKelas }}</div>
        <div class="page-subtitle">
            Ringkasan kehadiran siswa bulan {{ $bulan->translatedFormat('F Y') }}
        </div>
    </div>
    <div class="header-actions">
        <a href="{{ route('guru.walikelas.index') }}" class="btn-outline">
            <i class="bi bi-list-ul"></i> Per Sesi
        </a>
        <a href="{{ route('guru.walikelas.harian') }}" class="btn-outline">
            <i class="bi bi-calendar-day"></i> Harian
        </a>
        <a href="{{ route('guru.walikelas.bulanan') }}" class="btn-outline active">
            <i class="bi bi-calendar-month"></i> Bulanan
        </a>
    </div>
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('guru.walikelas.bulanan') }}">
    <div class="filter-bar">
        <label><i class="bi bi-calendar-month" style="margin-right:3px"></i> Bulan:</label>
        <input type="month" name="bulan" class="filter-month"
               value="{{ $bulan->format('Y-m') }}" onchange="this.form.submit()">
    </div>
</form>

{{-- RINGKASAN --}}
<div class="ringkasan-row">
    <div class="ring-card">
        <div class="ring-label"><i class="bi bi-journal-check" style="margin-right:4px; color:var(--accent)"></i> Total Sesi</div>
        <div class="ring-value">{{ $totalSesi }}</div>
        <div class="ring-sub">Sesi kelas pada bulan ini</div>
    </div>
    <div class="ring-card">
        <div class="ring-label"><i class="bi bi-graph-up" style="margin-right:4px; color:var(--green)"></i> Rata-rata Kehadiran</div>
        <div class="ring-value" style="color:{{ $ringkasan->rata_rata >= 80 ? 'var(--green)' : ($ringkasan->rata_rata >= 50 ? 'var(--amber)' : 'var(--red)') }}">
            {{ $ringkasan->rata_rata }}%
        </div>
        <div class="ring-sub">Dari seluruh siswa kelas</div>
    </div>
    @if($ringkasan->siswa_terbaik)
    <div class="ring-card">
        <div class="ring-label"><i class="bi bi-trophy-fill" style="margin-right:4px; color:var(--amber)"></i> Siswa Terbaik</div>
        <div class="ring-value" style="font-size:16px">{{ $ringkasan->siswa_terbaik->nama_siswa }}</div>
        <div class="ring-sub">{{ $ringkasan->siswa_terbaik->persentase }}% kehadiran</div>
    </div>
    @endif
    @if($ringkasan->siswa_terendah)
    <div class="ring-card">
        <div class="ring-label"><i class="bi bi-exclamation-triangle-fill" style="margin-right:4px; color:var(--red)"></i> Perlu Perhatian</div>
        <div class="ring-value" style="font-size:16px; color:var(--red)">{{ $ringkasan->siswa_terendah->nama_siswa }}</div>
        <div class="ring-sub">{{ $ringkasan->siswa_terendah->persentase }}% kehadiran</div>
    </div>
    @endif
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="card-label">Rekap Per Siswa — {{ $namaKelas }}</span>
            <span class="count-badge">{{ $rekapSiswa->count() }} siswa</span>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama atau NIS...">
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
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th style="text-align:center">Hadir</th>
                    <th style="text-align:center">Alpa</th>
                    <th>Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapSiswa as $index => $siswa)
                @php
                    $barColor = $siswa->persentase >= 80 ? 'var(--green)' : ($siswa->persentase >= 50 ? 'var(--amber)' : 'var(--red)');
                @endphp
                <tr data-name="{{ strtolower($siswa->nama_siswa) }}" data-nis="{{ strtolower($siswa->NIS) }}">
                    <td style="color:var(--text3); font-size:11px">{{ $index + 1 }}</td>
                    <td><span class="nis-badge">{{ $siswa->NIS }}</span></td>
                    <td>
                        <div class="cell-name">
                            <div class="avatar-sm">{{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}</div>
                            {{ $siswa->nama_siswa }}
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
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                            <div class="empty-title">Belum ada data</div>
                            <div class="empty-desc">Tidak ada sesi kelas pada bulan ini.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="noResult" style="display:none">
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-search"></i></div>
        <div class="empty-title">Tidak ada hasil</div>
        <div class="empty-desc">Coba kata kunci yang berbeda.</div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const rows        = document.querySelectorAll('.data-table tbody tr[data-name]');
    const noResult    = document.getElementById('noResult');

    function filterTable() {
        const q = searchInput.value.toLowerCase().trim();
        let visible = 0;

        rows.forEach(row => {
            const name = row.dataset.name || '';
            const nis  = row.dataset.nis || '';

            if (!q || name.includes(q) || nis.includes(q)) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        noResult.style.display = visible === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterTable);
</script>

@endsection
