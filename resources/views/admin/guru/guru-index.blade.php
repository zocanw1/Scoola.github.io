@extends('layouts.admin')

@section('content')

<style>
    /* ── PAGE HEADER ─────────────────────────── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header-left .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
    }

    .page-header-left .page-subtitle {
        font-size: 12px;
        color: var(--text2);
        margin-top: 3px;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }

    .btn-primary:hover {
        filter: brightness(1.15);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(88,166,255,0.3);
    }

    /* ── STATS ROW ─────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: border-color .2s;
    }

    .stat-card:hover { border-color: rgba(88,166,255,0.25); }

    .stat-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        display: grid; place-items: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .stat-icon.blue   { background: rgba(88,166,255,.12);  color: var(--accent); }
    .stat-icon.green  { background: rgba(63,185,80,.12);   color: var(--green); }
    .stat-icon.purple { background: rgba(188,140,255,.12); color: var(--purple); }

    .stat-label {
        font-size: 10.5px;
        color: var(--text2);
        text-transform: uppercase;
        letter-spacing: .06em;
        font-weight: 600;
    }

    .stat-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.1;
    }

    /* ── CARD ─────────────────────────── */
    .card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        overflow: hidden;
    }

    .card-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid var(--glass-border);
        gap: 12px;
        flex-wrap: wrap;
    }

    .card-toolbar-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-label { font-size: 12.5px; font-weight: 600; color: var(--text1); }

    .count-badge {
        font-size: 10px; font-weight: 700;
        background: rgba(88,166,255,.12);
        color: var(--accent);
        padding: 2px 8px;
        border-radius: 10px;
    }

    .toolbar-right { display: flex; align-items: center; gap: 8px; }

    .search-box {
        display: flex;
        align-items: center;
        gap: 7px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 7px;
        padding: 6px 11px;
        transition: border-color .2s;
    }

    .search-box:focus-within { border-color: rgba(88,166,255,.4); }
    .search-box i { font-size: 12px; color: var(--text3); }

    .search-box input {
        background: none; border: none; outline: none;
        color: var(--text1);
        font-size: 12px;
        font-family: 'Inter', sans-serif;
        width: 180px;
    }

    .search-box input::placeholder { color: var(--text3); }

    .filter-select {
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 7px;
        padding: 6px 11px;
        color: var(--text2);
        font-size: 12px;
        font-family: 'Inter', sans-serif;
        outline: none;
        cursor: pointer;
        transition: border-color .2s;
    }

    .filter-select:focus { border-color: rgba(88,166,255,.4); color: var(--text1); }

    /* ── TABLE ─────────────────────────── */
    .tbl-wrap { overflow-x: auto; }

    .data-table { width: 100%; border-collapse: collapse; }

    .data-table th {
        padding: 10px 16px;
        text-align: left;
        font-size: 10px; font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--text3);
        border-bottom: 1px solid var(--glass-border);
        white-space: nowrap;
        background: var(--navy2);
    }

    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--glass-border);
        font-size: 12.5px;
        color: var(--text2);
        vertical-align: middle;
    }

    .data-table tbody tr { transition: background .15s; }
    .data-table tbody tr:hover td { background: var(--glass-hover); color: var(--text1); }
    .data-table tbody tr:last-child td { border-bottom: none; }

    /* ── CELLS ─────────────────────────── */
    .cell-nip {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px; font-weight: 700;
        color: var(--accent);
        background: rgba(88,166,255,0.08);
        padding: 3px 8px;
        border-radius: 5px;
        display: inline-block;
        letter-spacing: .03em;
    }

    .cell-name {
        font-weight: 600;
        color: var(--text1);
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .avatar-sm {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--green), var(--accent));
        display: grid; place-items: center;
        font-size: 10px; font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .mapel-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(188,140,255,0.1);
        color: var(--purple);
        font-size: 11px; font-weight: 600;
        padding: 3px 9px;
        border-radius: 5px;
    }

    .email-text { font-size: 12px; color: var(--text2); }

    /* ── ACTION BUTTONS ─────────────────────────── */
    .action-group { display: flex; align-items: center; gap: 6px; }

    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 11px;
        background: rgba(88,166,255,0.08);
        border: 1px solid rgba(88,166,255,0.15);
        color: var(--accent);
        border-radius: 6px;
        font-size: 11.5px; font-weight: 500;
        text-decoration: none;
        transition: all .15s;
        font-family: 'Inter', sans-serif;
    }

    .btn-edit:hover {
        background: rgba(88,166,255,0.18);
        border-color: rgba(88,166,255,0.4);
        color: var(--accent);
    }



    /* ── EMPTY / ALERT ─────────────────────────── */
    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-icon { font-size: 40px; color: var(--text3); margin-bottom: 12px; }
    .empty-title { font-size: 14px; font-weight: 600; color: var(--text2); margin-bottom: 5px; }
    .empty-desc { font-size: 12px; color: var(--text3); }

    .alert-success {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 16px;
        background: rgba(63,185,80,0.1);
        border: 1px solid rgba(63,185,80,0.2);
        border-radius: 8px;
        color: var(--green);
        font-size: 12.5px; font-weight: 500;
        margin-bottom: 16px;
        animation: slideIn .3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .data-table tbody tr { animation: rowIn .25s ease both; }

    @keyframes rowIn {
        from { opacity: 0; transform: translateX(-6px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    @for ($i = 1; $i <= 20; $i++)
    .data-table tbody tr:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 30 }}ms; }
    @endfor
</style>

{{-- ALERT --}}
@if (session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Data Guru</div>
        <div class="page-subtitle">Kelola seluruh data guru yang terdaftar di sekolah</div>
    </div>
    <a href="{{ route('guru.create') }}" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Guru
    </a>
</div>

{{-- STATS --}}
@php
    $mapelGroups = $guru->groupBy('kd_mapel');
@endphp
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
        <div>
            <div class="stat-label">Total Guru</div>
            <div class="stat-value">{{ $guru->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-book-fill"></i></div>
        <div>
            <div class="stat-label">Mapel Diajar</div>
            <div class="stat-value">{{ $mapelGroups->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
        <div>
            <div class="stat-label">Akun Aktif</div>
            <div class="stat-value">{{ $guru->count() }}</div>
        </div>
    </div>
</div>

{{-- DATA CARD --}}
<div class="card">
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="card-label">Daftar Guru</span>
            <span class="count-badge">{{ $guru->count() }} data</span>
        </div>
        <div class="toolbar-right">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama atau NIP...">
            </div>
            <select class="filter-select" id="mapelFilter">
                <option value="">Semua Mapel</option>
                @foreach ($mapelGroups->keys()->sort() as $kd)
                    @php $mapelObj = $guru->firstWhere('kd_mapel', $kd); @endphp
                    <option value="{{ $kd }}">{{ $mapelObj->mapel->nama_mapel ?? $kd }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="tbl-wrap">
        <table class="data-table" id="guruTable">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Mata Pelajaran</th>
                    <th>Email</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody id="guruBody">
                @forelse ($guru as $index => $g)
                <tr data-name="{{ strtolower($g->nama_guru) }}"
                    data-nip="{{ strtolower($g->NIP) }}"
                    data-mapel="{{ $g->kd_mapel }}">
                    <td style="color:var(--text3); font-size:11px">{{ $index + 1 }}</td>
                    <td><span class="cell-nip">{{ $g->NIP }}</span></td>
                    <td>
                        <div class="cell-name">
                            <div class="avatar-sm">{{ strtoupper(substr($g->nama_guru, 0, 1)) }}</div>
                            {{ $g->nama_guru }}
                        </div>
                    </td>
                    <td>
                        <span class="mapel-badge">
                            <i class="bi bi-book" style="font-size:10px"></i>
                            {{ $g->mapel->nama_mapel ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="email-text">
                            <i class="bi bi-envelope" style="font-size:11px; margin-right:4px; color:var(--text3)"></i>
                            {{ $g->user->email ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group" style="justify-content:center">
                            <a href="{{ route('guru.edit', $g->NIP) }}" class="btn-edit">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-person-badge"></i></div>
                            <div class="empty-title">Belum ada data guru</div>
                            <div class="empty-desc">Klik tombol "Tambah Guru" untuk menambahkan data pertama.</div>
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
                <div class="empty-desc">Coba kata kunci atau filter mapel yang berbeda.</div>
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const mapelFilter = document.getElementById('mapelFilter');
    const rows        = document.querySelectorAll('#guruBody tr[data-name]');
    const noResult    = document.getElementById('noResult');

    function filterTable() {
        const q     = searchInput.value.toLowerCase().trim();
        const mapel = mapelFilter.value;
        let visible = 0;

        rows.forEach(row => {
            const name  = row.dataset.name || '';
            const nip   = row.dataset.nip || '';
            const rowM  = row.dataset.mapel;

            const matchQ = !q || name.includes(q) || nip.includes(q);
            const matchM = !mapel || rowM === mapel;

            if (matchQ && matchM) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        noResult.style.display = (visible === 0 && rows.length > 0) ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterTable);
    mapelFilter.addEventListener('change', filterTable);
</script>

@endsection
