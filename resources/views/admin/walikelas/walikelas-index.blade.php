@extends('layouts.admin')

@section('page-title', 'Wali Kelas')
@section('breadcrumb', 'Wali Kelas')

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
    .btn-add {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 18px; background: var(--accent); color: #fff;
        border-radius: 8px; font-size: 12px; font-weight: 700;
        text-decoration: none; transition: all .2s;
        font-family: 'Inter', sans-serif; border: none; cursor: pointer;
    }
    .btn-add:hover { filter: brightness(1.15); transform: translateY(-1px); color: #fff; }

    /* Stats */
    .stats-row {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
    .stat-icon.purple { background: rgba(188,140,255,.12); color: var(--purple); }
    .stat-icon.blue   { background: rgba(88,166,255,.12);  color: var(--accent); }
    .stat-icon.green  { background: rgba(63,185,80,.12);   color: var(--green); }
    .stat-label {
        font-size: 10.5px; color: var(--text2);
        text-transform: uppercase; letter-spacing: .06em; font-weight: 600;
    }
    .stat-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 22px; font-weight: 800; color: var(--text1); line-height: 1.1;
    }

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
        background: linear-gradient(135deg, var(--purple), var(--accent));
        display: grid; place-items: center;
        font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .kelas-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(188,140,255,0.1); color: var(--purple);
        font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 5px;
    }
    .empty-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(248,81,73,0.08); color: var(--red);
        font-size: 10.5px; font-weight: 600; padding: 3px 9px; border-radius: 5px;
    }
    .siswa-count {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px; font-weight: 700; color: var(--accent);
    }
    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 11px; background: rgba(88,166,255,0.08);
        border: 1px solid rgba(88,166,255,0.15);
        color: var(--accent); border-radius: 6px;
        font-size: 11.5px; font-weight: 500; text-decoration: none;
        transition: all .15s; font-family: 'Inter', sans-serif;
    }
    .btn-edit:hover { background: rgba(88,166,255,0.18); border-color: rgba(88,166,255,0.4); color: var(--accent); }
    .action-btns { display: flex; gap: 6px; justify-content: center; }

    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-icon { font-size: 40px; color: var(--text3); margin-bottom: 12px; }
    .empty-title { font-size: 14px; font-weight: 600; color: var(--text2); margin-bottom: 5px; }
    .empty-desc { font-size: 12px; color: var(--text3); }

    .data-table tbody tr { animation: rowIn .25s ease both; }
    @keyframes rowIn {
        from { opacity: 0; transform: translateX(-6px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @for ($i = 1; $i <= 20; $i++)
    .data-table tbody tr:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 30 }}ms; }
    @endfor

    .alert-success {
        background: rgba(63,185,80,0.08); border: 1px solid rgba(63,185,80,0.25);
        color: var(--green); padding: 10px 16px; border-radius: 8px;
        font-size: 12px; font-weight: 500; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .alert-error {
        background: rgba(248,81,73,0.08); border: 1px solid rgba(248,81,73,0.25);
        color: var(--red); padding: 10px 16px; border-radius: 8px;
        font-size: 12px; font-weight: 500; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
</style>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-error"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
@endif

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Wali Kelas</div>
        <div class="page-subtitle">Kelola penugasan guru sebagai wali kelas</div>
    </div>
    <a href="{{ route('admin.walikelas.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah Wali Kelas
    </a>
</div>

{{-- STATS --}}
@php
    $totalKelas = $kelasList->count();
    $terisi = $kelasList->whereNotNull('wali_kelas_nip')->count();
    $kosong = $totalKelas - $terisi;
@endphp
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-building"></i></div>
        <div>
            <div class="stat-label">Total Kelas</div>
            <div class="stat-value">{{ $totalKelas }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-mortarboard-fill"></i></div>
        <div>
            <div class="stat-label">Sudah Ada Wali</div>
            <div class="stat-value">{{ $terisi }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-question-circle"></i></div>
        <div>
            <div class="stat-label">Belum Ada Wali</div>
            <div class="stat-value">{{ $kosong }}</div>
        </div>
    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="card-label">Daftar Kelas & Wali Kelas</span>
            <span class="count-badge">{{ $totalKelas }} kelas</span>
        </div>
        <div class="search-box" style="width: 250px;">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari kelas atau guru...">
        </div>
    </div>

    <div class="tbl-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Kelas</th>
                    <th>Wali Kelas</th>
                    <th>NIP</th>
                    <th style="text-align:center">Jumlah Siswa</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelasList as $index => $kls)
                <tr>
                    <td style="color:var(--text3); font-size:11px">{{ $index + 1 }}</td>
                    <td><span class="kelas-badge"><i class="bi bi-building" style="font-size:10px"></i> {{ $kls->nama_kelas }}</span></td>
                    <td>
                        @if($kls->waliKelas)
                            <div class="cell-guru">
                                <div class="avatar-sm">{{ strtoupper(substr($kls->waliKelas->nama_guru, 0, 1)) }}</div>
                                {{ $kls->waliKelas->nama_guru }}
                            </div>
                        @else
                            <span class="empty-badge"><i class="bi bi-dash-circle" style="font-size:10px"></i> Belum ditentukan</span>
                        @endif
                    </td>
                    <td>
                        @if($kls->waliKelas)
                            <span style="color:var(--text2); font-size:12px; font-family:'Plus Jakarta Sans',monospace;">{{ $kls->wali_kelas_nip }}</span>
                        @else
                            <span style="color:var(--text3); font-size:11px">—</span>
                        @endif
                    </td>
                    <td style="text-align:center">
                        <span class="siswa-count">{{ $kls->siswa->count() }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            @if($kls->waliKelas)
                                <a href="{{ route('admin.walikelas.edit', $kls->id) }}" class="btn-edit">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            @else
                                <a href="{{ route('admin.walikelas.create') }}?kelas_id={{ $kls->id }}" class="btn-add" style="font-size:11px; padding:5px 12px;">
                                    <i class="bi bi-plus"></i> Assign
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state-row">
                    <td colspan="6" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-building"></i></div>
                            <div class="empty-title">Belum ada data kelas</div>
                            <div class="empty-desc">Tambahkan siswa terlebih dahulu untuk membuat data kelas.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
                <tr id="noResults" style="display: none;">
                    <td colspan="6" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-search"></i></div>
                            <div class="empty-title">Pencarian tidak ditemukan</div>
                            <div class="empty-desc">Tidak ada kelas atau guru yang cocok dengan kata kunci Anda.</div>
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
