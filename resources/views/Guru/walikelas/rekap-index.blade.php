@extends('layouts.guru')

@section('page-title', 'Rekap Kelas')
@section('breadcrumb', 'Wali Kelas / Rekap Per Sesi')

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
    .btn-filter-reset {
        background: none; border: none; color: var(--text3);
        font-size: 11px; cursor: pointer; text-decoration: underline;
        font-family: 'Inter', sans-serif;
    }
    .btn-filter-reset:hover { color: var(--red); }

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
        background: linear-gradient(135deg, var(--green), var(--accent));
        display: grid; place-items: center;
        font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .kelas-badge {
        display: inline-flex; align-items: center; gap: 4px;
        background: rgba(188,140,255,0.1); color: var(--purple);
        font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 5px;
    }
    .date-cell .date-main { font-weight: 600; color: var(--text1); font-size: 12.5px; }
    .date-cell .date-time { font-size: 10.5px; color: var(--text3); margin-top: 1px; }
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
    .btn-view:hover { background: rgba(88,166,255,0.18); border-color: rgba(88,166,255,0.4); color: var(--accent); }

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
</style>

{{-- HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Rekap Kelas {{ $namaKelas }}</div>
        <div class="page-subtitle">Data rekap presensi kelas yang Anda ampu sebagai wali kelas • {{ $totalSiswa }} siswa</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('guru.walikelas.index') }}" class="btn-outline active">
            <i class="bi bi-list-ul"></i> Per Sesi
        </a>
        <a href="{{ route('guru.walikelas.harian') }}" class="btn-outline">
            <i class="bi bi-calendar-day"></i> Harian
        </a>
        <a href="{{ route('guru.walikelas.bulanan') }}" class="btn-outline">
            <i class="bi bi-calendar-month"></i> Bulanan
        </a>
    </div>
</div>

{{-- STATS --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-journal-check"></i></div>
        <div>
            <div class="stat-label">Total Sesi</div>
            <div class="stat-value">{{ $totalSesi }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-value">{{ $totalSiswa }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
        <div>
            <div class="stat-label">Total Hadir</div>
            <div class="stat-value">{{ $totalHadir }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="bi bi-person-x-fill"></i></div>
        <div>
            <div class="stat-label">Total Alpa</div>
            <div class="stat-value">{{ $totalAlpa }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="bi bi-graph-up"></i></div>
        <div>
            <div class="stat-label">Kehadiran</div>
            <div class="stat-value">{{ $persentaseHadir }}%</div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('guru.walikelas.index') }}">
    <div class="filter-bar">
        <label><i class="bi bi-funnel" style="margin-right:3px"></i> Filter:</label>
        <input type="date" name="tanggal" class="filter-date"
               value="{{ request('tanggal') }}" onchange="this.form.submit()">
        @if(request()->hasAny(['tanggal']))
            <a href="{{ route('guru.walikelas.index') }}" class="btn-filter-reset">
                <i class="bi bi-x-circle"></i> Reset
            </a>
        @endif
    </div>
</form>

{{-- TABLE --}}
<div class="card">
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="card-label">Daftar Sesi Selesai — {{ $namaKelas }}</span>
            <span class="count-badge">{{ $sesiSelesai->count() }} sesi</span>
        </div>
    </div>

    <div class="tbl-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Tanggal</th>
                    <th>Guru Pengajar</th>
                    <th style="text-align:center">Hadir</th>
                    <th style="text-align:center">Alpa</th>
                    <th>Kehadiran</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sesiSelesai as $index => $sesi)
                @php
                    $siswaKelas = \App\Models\Siswa::where('kelas', $sesi->kelas)->count();
                    $hadirSesi  = \App\Models\Presensi::where('sesi_id', $sesi->id)->where('status', 'Hadir')->count();
                    $alpaSesi   = $siswaKelas - $hadirSesi;
                    $pctSesi    = $siswaKelas > 0 ? round(($hadirSesi / $siswaKelas) * 100, 1) : 0;
                    $barColor   = $pctSesi >= 80 ? 'var(--green)' : ($pctSesi >= 50 ? 'var(--amber)' : 'var(--red)');
                @endphp
                <tr>
                    <td style="color:var(--text3); font-size:11px">{{ $index + 1 }}</td>
                    <td>
                        <div class="date-cell">
                            <div class="date-main">{{ $sesi->created_at->format('d M Y') }}</div>
                            <div class="date-time">
                                <i class="bi bi-clock" style="font-size:9px"></i>
                                {{ $sesi->created_at->format('H:i') }} – {{ $sesi->updated_at->format('H:i') }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="cell-guru">
                            <div class="avatar-sm">{{ strtoupper(substr($sesi->guru->name ?? '?', 0, 1)) }}</div>
                            {{ $sesi->guru->name ?? '-' }}
                        </div>
                    </td>
                    <td style="text-align:center">
                        <span class="hadir-badge"><i class="bi bi-check2" style="font-size:10px"></i> {{ $hadirSesi }}</span>
                    </td>
                    <td style="text-align:center">
                        @if($alpaSesi > 0)
                            <span class="alpa-badge"><i class="bi bi-x-lg" style="font-size:9px"></i> {{ $alpaSesi }}</span>
                        @else
                            <span style="color:var(--text3); font-size:11px">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="persen-bar-wrap">
                            <div class="persen-bar">
                                <div class="persen-fill" style="width:{{ $pctSesi }}%; background:{{ $barColor }}"></div>
                            </div>
                            <span class="persen-text" style="color:{{ $barColor }}">{{ $pctSesi }}%</span>
                        </div>
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('guru.walikelas.show', $sesi->id) }}" class="btn-view">
                            <i class="bi bi-eye-fill"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-journal-x"></i></div>
                            <div class="empty-title">Belum ada sesi yang selesai</div>
                            <div class="empty-desc">Data akan muncul setelah guru mengakhiri sesi kelas untuk {{ $namaKelas }}.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
