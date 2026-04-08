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

    /* ── DATE PICKER ── */
    .date-picker-bar {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 20px; flex-wrap: wrap;
    }

    .date-picker-bar label {
        font-size: 12px; font-weight: 600; color: var(--text2);
    }

    .date-input {
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

    .date-input:focus { border-color: rgba(88,166,255,.4); }

    .date-nav {
        display: inline-flex; align-items: center; gap: 0;
    }

    .date-nav-btn {
        width: 32px; height: 32px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        display: grid; place-items: center;
        font-size: 14px; cursor: pointer;
        transition: all .15s;
    }

    .date-nav-btn:first-child { border-radius: 8px 0 0 8px; }
    .date-nav-btn:last-child  { border-radius: 0 8px 8px 0; border-left: none; }
    .date-nav-btn:hover { background: var(--glass-hover); color: var(--accent); }

    .date-display {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px; font-weight: 700;
        color: var(--text1);
    }

    .btn-today {
        padding: 6px 12px;
        background: rgba(88,166,255,.08);
        border: 1px solid rgba(88,166,255,.15);
        color: var(--accent);
        border-radius: 7px;
        font-size: 11px; font-weight: 600;
        cursor: pointer; font-family: 'Inter', sans-serif;
        transition: all .2s; text-decoration: none;
    }

    .btn-today:hover { background: rgba(88,166,255,.18); border-color: rgba(88,166,255,.4); }

    /* ── SUMMARY BAR ── */
    .summary-bar {
        display: flex; align-items: center; gap: 16px;
        margin-bottom: 20px; flex-wrap: wrap;
    }

    .summary-item {
        display: flex; align-items: center; gap: 6px;
        font-size: 12.5px; font-weight: 600;
    }

    .summary-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
    }

    /* ── KELAS CARDS ── */
    .kelas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
    }

    .kelas-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        overflow: hidden;
        transition: all .2s;
        animation: cardIn .3s ease both;
    }

    .kelas-card:hover {
        border-color: rgba(88,166,255,0.25);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .kelas-card-header {
        padding: 16px 18px;
        border-bottom: 1px solid var(--glass-border);
        display: flex; align-items: center; justify-content: space-between;
    }

    .kelas-card-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px; font-weight: 800;
        color: var(--text1);
        display: flex; align-items: center; gap: 8px;
    }

    .kelas-card-title i {
        color: var(--purple); font-size: 14px;
    }

    .kelas-card-sesi {
        font-size: 10px; font-weight: 700;
        background: rgba(188,140,255,.12);
        color: var(--purple);
        padding: 3px 8px; border-radius: 10px;
    }

    .kelas-card-body { padding: 16px 18px; }

    .kelas-guru {
        font-size: 12px; color: var(--text2); margin-bottom: 14px;
        display: flex; align-items: center; gap: 6px;
    }

    .kelas-guru i { color: var(--text3); font-size: 12px; }

    /* ── PROGRESS BAR ── */
    .progress-section { margin-bottom: 12px; }

    .progress-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 6px;
    }

    .progress-label { font-size: 11px; font-weight: 600; color: var(--text2); }
    .progress-pct {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px; font-weight: 800;
    }

    .progress-bar-bg {
        width: 100%; height: 8px;
        background: var(--navy3); border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%; border-radius: 4px;
        transition: width .5s ease;
    }

    /* ── STAT ROW ── */
    .kelas-stats {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 8px; margin-top: 12px;
    }

    .ks-item {
        text-align: center;
        padding: 8px;
        background: var(--glass);
        border-radius: 8px;
    }

    .ks-val {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px; font-weight: 800;
        line-height: 1;
    }

    .ks-lbl { font-size: 9px; color: var(--text3); text-transform: uppercase; margin-top: 2px; }

    /* ── EMPTY STATE ── */
    .empty-state {
        padding: 60px 20px; text-align: center;
        grid-column: 1 / -1;
    }

    .empty-icon { font-size: 40px; color: var(--text3); margin-bottom: 12px; }
    .empty-title { font-size: 14px; font-weight: 600; color: var(--text2); margin-bottom: 5px; }
    .empty-desc { font-size: 12px; color: var(--text3); }

    @for ($i = 0; $i < 12; $i++)
    .kelas-card:nth-child({{ $i + 1 }}) { animation-delay: {{ $i * 60 }}ms; }
    @endfor
</style>

@php
    $tgl = \Carbon\Carbon::parse($tanggal);
    $prev = $tgl->copy()->subDay()->format('Y-m-d');
    $next = $tgl->copy()->addDay()->format('Y-m-d');
    $today = \Carbon\Carbon::today()->format('Y-m-d');

    $totalSiswaHari = $kelasBreakdown->sum('total_siswa');
    $totalHadirHari = $kelasBreakdown->sum('hadir');
    $totalAlpaHari  = $kelasBreakdown->sum('alpa');
    $avgPct = $totalSiswaHari > 0 ? round(($totalHadirHari / $totalSiswaHari) * 100, 1) : 0;
@endphp

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Rekap Harian</div>
        <div class="page-subtitle">Ringkasan kehadiran per kelas berdasarkan tanggal</div>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.rekap.index') }}" class="btn-outline">
            <i class="bi bi-list-ul"></i> Per Sesi
        </a>
        <a href="{{ route('admin.rekap.harian') }}" class="btn-outline active">
            <i class="bi bi-calendar-day"></i> Rekap Harian
        </a>
        <a href="{{ route('admin.rekap.bulanan') }}" class="btn-outline">
            <i class="bi bi-calendar-month"></i> Rekap Bulanan
        </a>
    </div>
</div>

{{-- DATE PICKER BAR --}}
<div class="date-picker-bar">
    <div class="date-nav">
        <a href="{{ route('admin.rekap.harian', ['tanggal' => $prev]) }}" class="date-nav-btn">
            <i class="bi bi-chevron-left"></i>
        </a>
        <a href="{{ route('admin.rekap.harian', ['tanggal' => $next]) }}" class="date-nav-btn">
            <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <div class="date-display">
        <i class="bi bi-calendar3" style="color:var(--accent); font-size:13px; margin-right:4px"></i>
        {{ $tgl->translatedFormat('l, d F Y') }}
    </div>

    <form method="GET" action="{{ route('admin.rekap.harian') }}" style="display:flex; gap:8px; align-items:center">
        <input type="date" name="tanggal" class="date-input" value="{{ $tanggal }}" onchange="this.form.submit()">
    </form>

    @if($tanggal !== $today)
        <a href="{{ route('admin.rekap.harian') }}" class="btn-today">
            <i class="bi bi-arrow-counterclockwise" style="font-size:10px"></i> Hari Ini
        </a>
    @endif
</div>

{{-- SUMMARY BAR --}}
@if($kelasBreakdown->isNotEmpty())
<div class="summary-bar">
    <div class="summary-item">
        <div class="summary-dot" style="background:var(--accent)"></div>
        <span style="color:var(--text1)">{{ $sesiHari->count() }}</span>
        <span style="color:var(--text3)">Sesi</span>
    </div>
    <div class="summary-item">
        <div class="summary-dot" style="background:var(--purple)"></div>
        <span style="color:var(--text1)">{{ $kelasBreakdown->count() }}</span>
        <span style="color:var(--text3)">Kelas</span>
    </div>
    <div class="summary-item">
        <div class="summary-dot" style="background:var(--green)"></div>
        <span style="color:var(--green)">{{ $totalHadirHari }}</span>
        <span style="color:var(--text3)">Hadir</span>
    </div>
    <div class="summary-item">
        <div class="summary-dot" style="background:var(--red)"></div>
        <span style="color:var(--red)">{{ $totalAlpaHari }}</span>
        <span style="color:var(--text3)">Alpa</span>
    </div>
    <div class="summary-item">
        <div class="summary-dot" style="background:var(--amber)"></div>
        <span style="color:var(--text1)">{{ $avgPct }}%</span>
        <span style="color:var(--text3)">Rata-rata</span>
    </div>
</div>
@endif

{{-- KELAS CARDS GRID --}}
<div class="kelas-grid">
    @forelse($kelasBreakdown as $kb)
    @php
        $barColor = $kb->persentase >= 80 ? 'var(--green)' : ($kb->persentase >= 50 ? 'var(--amber)' : 'var(--red)');
    @endphp
    <div class="kelas-card">
        <div class="kelas-card-header">
            <div class="kelas-card-title">
                <i class="bi bi-building"></i>
                {{ $kb->kelas }}
            </div>
            <span class="kelas-card-sesi">{{ $kb->sesi_count }} sesi</span>
        </div>
        <div class="kelas-card-body">
            <div class="kelas-guru">
                <i class="bi bi-person-badge"></i>
                {{ $kb->guru_list }}
            </div>

            <div class="progress-section">
                <div class="progress-header">
                    <span class="progress-label">Tingkat Kehadiran</span>
                    <span class="progress-pct" style="color:{{ $barColor }}">{{ $kb->persentase }}%</span>
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" style="width:{{ $kb->persentase }}%; background:{{ $barColor }}"></div>
                </div>
            </div>

            <div class="kelas-stats">
                <div class="ks-item">
                    <div class="ks-val" style="color:var(--accent)">{{ $kb->total_siswa }}</div>
                    <div class="ks-lbl">Siswa</div>
                </div>
                <div class="ks-item">
                    <div class="ks-val" style="color:var(--green)">{{ $kb->hadir }}</div>
                    <div class="ks-lbl">Hadir</div>
                </div>
                <div class="ks-item">
                    <div class="ks-val" style="color:var(--red)">{{ $kb->alpa }}</div>
                    <div class="ks-lbl">Alpa</div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
        <div class="empty-title">Tidak ada sesi di tanggal ini</div>
        <div class="empty-desc">Pilih tanggal lain atau kembali ke hari ini.</div>
    </div>
    @endforelse
</div>

@endsection
