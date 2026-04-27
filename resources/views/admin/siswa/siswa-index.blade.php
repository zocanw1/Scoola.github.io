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
        color: var(--navy);
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
        background: #79baff;
        color: var(--navy);
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(88,166,255,0.3);
    }

    /* ── STATS ROW ─────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
    .stat-icon.amber  { background: rgba(227,179,65,.12);  color: var(--amber); }
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

    .card-label {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text1);
    }

    .count-badge {
        font-size: 10px;
        font-weight: 700;
        background: rgba(88,166,255,.12);
        color: var(--accent);
        padding: 2px 8px;
        border-radius: 10px;
    }

    /* ── SEARCH & FILTER ─────────────────────────── */
    .toolbar-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }

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
    .tbl-wrap {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 10px 16px;
        text-align: left;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--text3);
        border-bottom: 1px solid var(--glass-border);
        white-space: nowrap;
        background: var(--navy2);
    }

    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        font-size: 12.5px;
        color: var(--text2);
        vertical-align: middle;
    }

    .data-table tbody tr {
        transition: background .15s;
    }

    .data-table tbody tr:hover td {
        background: var(--glass-hover);
        color: var(--text1);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ── CELLS ─────────────────────────── */
    .cell-nis {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px;
        font-weight: 700;
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
        background: linear-gradient(135deg, var(--accent), var(--purple));
        display: grid; place-items: center;
        font-size: 10px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .kelas-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(188,140,255,0.1);
        color: var(--purple);
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 5px;
    }

    .email-text {
        font-size: 12px;
        color: var(--text2);
    }

    /* ── ACTION BUTTONS ─────────────────────────── */
    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        background: rgba(88,166,255,0.08);
        border: 1px solid rgba(88,166,255,0.15);
        color: var(--accent);
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 500;
        text-decoration: none;
        transition: all .15s;
        font-family: 'Inter', sans-serif;
    }

    .btn-edit:hover {
        background: rgba(88,166,255,0.18);
        border-color: rgba(88,166,255,0.4);
        color: var(--accent);
    }



    /* ── EMPTY STATE ─────────────────────────── */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        font-size: 40px;
        color: var(--text3);
        margin-bottom: 12px;
    }

    .empty-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text2);
        margin-bottom: 5px;
    }

    .empty-desc {
        font-size: 12px;
        color: var(--text3);
    }

    /* ── ALERT ─────────────────────────── */
    .alert-success {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        background: rgba(63,185,80,0.1);
        border: 1px solid rgba(63,185,80,0.2);
        border-radius: 8px;
        color: var(--green);
        font-size: 12.5px;
        font-weight: 500;
        margin-bottom: 16px;
        animation: slideIn .3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── ROW ENTER ANIMATION ─────────────────────────── */
    .data-table tbody tr {
        animation: rowIn .25s ease both;
    }

    @keyframes rowIn {
        from { opacity: 0; transform: translateX(-6px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    @for ($i = 1; $i <= 20; $i++)
    .data-table tbody tr:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 30 }}ms; }
    @endfor
</style>

{{-- ALERT success --}}
@if (session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Data Siswa</div>
        <div class="page-subtitle">Kelola seluruh data siswa yang terdaftar di sekolah</div>
    </div>
    <a href="{{ route('siswa.create') }}" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Siswa
    </a>
</div>

{{-- STATS ROW --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-value">{{ $siswa->count() }}</div>
        </div>
    </div>

    @php
        $kelasList = $siswa->groupBy('kelas');
    @endphp

    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-building"></i></div>
        <div>
            <div class="stat-label">Total Kelas</div>
            <div class="stat-value">{{ $kelasList->count() }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
        <div>
            <div class="stat-label">Akun Aktif</div>
            <div class="stat-value">{{ $siswa->count() }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber"><i class="bi bi-calendar-check"></i></div>
        <div>
            <div class="stat-label">Tahun Ajar</div>
            <div class="stat-value" style="font-size:13px">2025/2026</div>
        </div>
    </div>
</div>

{{-- DATA CARD --}}
<div class="card">
    {{-- TOOLBAR --}}
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="card-label">Daftar Siswa</span>
            <span class="count-badge">{{ $siswa->count() }} data</span>
        </div>
        <div class="toolbar-right">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama atau NIS...">
            </div>
            <select class="filter-select" id="kelasFilter">
                <option value="">Semua Kelas</option>
                @foreach ($kelasList->keys()->sort() as $kelas)
                    <option value="{{ $kelas }}">{{ $kelas }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="tbl-wrap">
        <table class="data-table" id="siswaTable">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Email</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody id="siswaBody">
                @forelse ($siswa as $index => $s)
                <tr data-name="{{ strtolower($s->nama_siswa) }}"
                    data-nis="{{ strtolower($s->NIS) }}"
                    data-kelas="{{ $s->kelas }}">
                    <td style="color:var(--text3); font-size:11px">{{ $index + 1 }}</td>
                    <td><span class="cell-nis">{{ $s->NIS }}</span></td>
                    <td>
                        <div class="cell-name">
                            <div class="avatar-sm">{{ strtoupper(substr($s->nama_siswa, 0, 1)) }}</div>
                            {{ $s->nama_siswa }}
                        </div>
                    </td>
                    <td>
                        <span class="kelas-badge">
                            <i class="bi bi-building" style="font-size:10px"></i>
                            {{ $s->kelas }}
                        </span>
                    </td>
                    <td>
                        <span class="email-text">
                            <i class="bi bi-envelope" style="font-size:11px; margin-right:4px; color:var(--text3)"></i>
                            {{ $s->user->email ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group" style="justify-content:center">
                            <a href="{{ route('siswa.edit', $s->NIS) }}" class="btn-edit">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-people"></i></div>
                            <div class="empty-title">Belum ada data siswa</div>
                            <div class="empty-desc">Klik tombol "Tambah Siswa" untuk menambahkan data pertama.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Empty search result --}}
        <div id="noResult" style="display:none">
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-search"></i></div>
                <div class="empty-title">Tidak ada hasil</div>
                <div class="empty-desc">Coba kata kunci atau filter kelas yang berbeda.</div>
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const kelasFilter = document.getElementById('kelasFilter');
    const rows        = document.querySelectorAll('#siswaBody tr[data-name]');
    const noResult    = document.getElementById('noResult');

    function filterTable() {
        const q     = searchInput.value.toLowerCase().trim();
        const kelas = kelasFilter.value;
        let visible = 0;

        rows.forEach(row => {
            const name  = row.dataset.name || '';
            const nis   = row.dataset.nis || '';
            const rowK  = row.dataset.kelas;

            const matchQ = !q || name.includes(q) || nis.includes(q);
            const matchK = !kelas || rowK === kelas;

            if (matchQ && matchK) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        noResult.style.display = (visible === 0 && rows.length > 0) ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterTable);
    kelasFilter.addEventListener('change', filterTable);
</script>

@endsection
