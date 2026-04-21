@extends('layouts.guru')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

<style>

/* ── Welcome strip ── */
.welcome-strip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 10px;
}

.ws-left h2 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 20px; font-weight: 800;
    color: var(--text1);
    letter-spacing: -0.02em;
}

.ws-left p { font-size: 12px; color: var(--text2); margin-top: 2px; }

.ws-date {
    text-align: right;
    font-size: 11px;
    color: var(--text2);
}

.ws-date strong {
    display: block;
    font-size: 13px;
    color: var(--text1);
    font-weight: 600;
}

.ws-time {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 28px; font-weight: 800;
    color: var(--text1);
    letter-spacing: -0.03em;
    line-height: 1;
}

/* ── CARD ── */
.card {
    background: var(--navy2);
    border: 1px solid var(--gb);
    border-radius: 10px;
}

/* ── Stat mini row ── */
.stat-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 14px; }

.stat-mini {
    background: var(--navy2);
    border: 1px solid var(--gb);
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: border-color .2s;
}

.stat-mini:hover { border-color: rgba(88,166,255,.3); }

.stat-mini-icon {
    width: 36px; height: 36px;
    border-radius: 8px;
    display: grid; place-items: center;
    font-size: 16px;
    flex-shrink: 0;
}

.ic-blue   { background: rgba(88,166,255,.12);  color: var(--accent); }
.ic-green  { background: rgba(63,185,80,.12);   color: var(--green); }
.ic-amber  { background: rgba(227,179,65,.12);  color: var(--amber); }
.ic-red    { background: rgba(248,81,73,.12);   color: var(--red); }
.ic-purple { background: rgba(188,140,255,.12); color: var(--purple); }

.stat-mini-val {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 22px; font-weight: 800;
    color: var(--text1);
    line-height: 1;
}

.stat-mini-lbl { font-size: 11px; color: var(--text2); margin-top: 2px; }

.stat-trend { font-size: 10.5px; margin-top: 4px; display: flex; align-items: center; gap: 3px; }
.up { color: var(--green); } .dn { color: var(--red); } .nt { color: var(--text3); }

/* ── GRID LAYOUT ── */
.dash-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 300px;
    grid-template-rows: auto auto;
    gap: 14px;
}

.card-inner { padding: 16px 18px; }

.card-title {
    font-size: 12px; font-weight: 600;
    color: var(--text1);
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 14px;
}

.card-title-icon { font-size: 13px; }
.card-title-action {
    margin-left: auto;
    font-size: 11px;
    color: var(--accent);
    cursor: pointer;
    font-weight: 400;
    text-decoration: none;
}

/* ── Absensi Hari Ini table ── */
.abs-table { width: 100%; border-collapse: collapse; }
.abs-table th {
    font-size: 10px; letter-spacing: .06em; text-transform: uppercase;
    color: var(--text3); font-weight: 600;
    padding: 0 8px 8px;
    border-bottom: 1px solid var(--gb);
    text-align: left;
}

.abs-table td {
    padding: 8px 8px;
    font-size: 12px;
    color: var(--text2);
    border-bottom: 1px solid rgba(255,255,255,0.04);
}

.abs-table tr:last-child td { border-bottom: none; }
.abs-table tr:hover td { background: var(--gh); }

.abs-table td:first-child { color: var(--text1); font-weight: 500; }

.badge-status {
    font-size: 10px; font-weight: 600;
    padding: 2px 8px; border-radius: 20px;
    display: inline-block;
}

.bs-h { background: rgba(63,185,80,.12);  color: var(--green); }
.bs-i { background: rgba(227,179,65,.12); color: var(--amber); }
.bs-a { background: rgba(248,81,73,.12);  color: var(--red); }
.bs-s { background: rgba(188,140,255,.12);color: var(--purple); }

/* ── Bar Chart ── */
.chart-wrap {
    height: 130px;
    display: flex;
    align-items: flex-end;
    gap: 8px;
    padding-bottom: 2px;
}

.bar-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    height: 100%;
    justify-content: flex-end;
}

.bar {
    width: 100%;
    border-radius: 4px 4px 0 0;
    min-height: 4px;
    transition: opacity .2s;
    cursor: pointer;
}

.bar:hover { opacity: .75; }
.bar-hadir { background: var(--accent); }
.bar-izin  { background: var(--amber); }
.bar-alpha { background: var(--red); }

.bar-lbl { font-size: 9.5px; color: var(--text3); }

.chart-legend {
    display: flex; gap: 14px;
    margin-top: 12px;
}

.leg { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text2); }
.leg-dot { width: 7px; height: 7px; border-radius: 50%; }

/* ── Donut ── */
.donut-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.donut-svg { width: 110px; height: 110px; }

.donut-center {
    text-align: center;
}

.d-pct {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px; font-weight: 800;
    color: var(--text1); line-height: 1;
}

.d-lbl { font-size: 10px; color: var(--text2); text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; }

.d-legend { width: 100%; }

.d-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 0;
    border-bottom: 1px solid rgba(255,255,255,.04);
    font-size: 11.5px;
}

.d-row:last-child { border-bottom: none; }
.d-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.d-name { color: var(--text2); flex: 1; }
.d-val { font-weight: 600; color: var(--text1); }
.d-pct2 { color: var(--text3); font-size: 10px; min-width: 32px; text-align: right; }

/* ── Calendar ── */
.cal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.cal-nav {
    width: 24px; height: 24px;
    border-radius: 5px;
    background: var(--glass);
    border: 1px solid var(--gb);
    color: var(--text2);
    display: grid; place-items: center;
    cursor: pointer;
    font-size: 11px;
}

.cal-month {
    font-size: 12px; font-weight: 600;
    color: var(--text1);
}

.cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 3px;
}

.cal-day-name {
    font-size: 9px;
    color: var(--text3);
    text-align: center;
    padding: 3px 0;
    font-weight: 600;
    letter-spacing: .05em;
}

.cal-day {
    aspect-ratio: 1;
    display: grid;
    place-items: center;
    font-size: 11px;
    color: var(--text2);
    border-radius: 5px;
    cursor: pointer;
    transition: all .15s;
}

.cal-day:hover { background: var(--gh); color: var(--text1); }
.cal-day.today { background: var(--accent); color: var(--navy); font-weight: 700; }
.cal-day.has-event { position: relative; }

.cal-day.has-event::after {
    content: '';
    position: absolute;
    bottom: 3px;
    width: 4px; height: 4px;
    background: var(--green);
    border-radius: 50%;
}

.cal-day.empty { color: transparent; cursor: default; }
.cal-day.other-month { color: var(--text3); }

/* ── Jadwal Hari Ini ── */
.jadwal-list { display: flex; flex-direction: column; gap: 8px; }

.jadwal-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: var(--navy3);
    border-radius: 8px;
    border: 1px solid var(--gb);
    transition: border-color .15s;
}

.jadwal-item:hover { border-color: rgba(88,166,255,.25); }

.jadwal-time {
    font-size: 10.5px;
    color: var(--text2);
    min-width: 72px;
    font-weight: 500;
}

.jadwal-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.jadwal-info { flex: 1; }
.jadwal-mapel { font-size: 12.5px; font-weight: 600; color: var(--text1); }
.jadwal-kelas { font-size: 10.5px; color: var(--text2); margin-top: 1px; }
.jadwal-guru  { font-size: 10.5px; color: var(--text3); }

/* ── Quick Alerts ── */
.alert-list { display: flex; flex-direction: column; gap: 8px; }

.alert-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid;
}

.alert-item.warn { background: rgba(227,179,65,.06); border-color: rgba(227,179,65,.2); }
.alert-item.danger { background: rgba(248,81,73,.06); border-color: rgba(248,81,73,.2); }
.alert-item.info { background: rgba(88,166,255,.06); border-color: rgba(88,166,255,.2); }

.alert-icon { font-size: 14px; margin-top: 1px; flex-shrink: 0; }
.alert-icon.wi { color: var(--amber); }
.alert-icon.di { color: var(--red); }
.alert-icon.ii { color: var(--accent); }

/* ── Rekap kelas ── */
.kelas-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 0;
    border-bottom: 1px solid rgba(255,255,255,.04);
    font-size: 12px;
}

.kelas-row:last-child { border-bottom: none; }
.kelas-name { color: var(--text1); font-weight: 500; min-width: 55px; }
.kelas-bar-wrap { flex: 1; height: 5px; background: var(--navy4); border-radius: 10px; overflow: hidden; }
.kelas-bar-fill { height: 100%; border-radius: 10px; background: var(--accent); }
.kelas-pct { color: var(--text2); min-width: 36px; text-align: right; font-size: 11px; }

/* ── Fade in ── */
@keyframes fi { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.fi { animation: fi .4s ease both; }
.d1{animation-delay:.05s} .d2{animation-delay:.10s} .d3{animation-delay:.15s}
.d4{animation-delay:.20s} .d5{animation-delay:.25s} .d6{animation-delay:.30s}
.d7{animation-delay:.35s} .d8{animation-delay:.40s}
</style>

<!-- Welcome Strip -->
<div class="welcome-strip fi d1">
    <div class="ws-left">
        <h2>Selamat pagi, Guru 👋</h2>
        <p>Ringkasan jadwal dan aktivitas Anda hari ini</p>
    </div>
    <div style="text-align:right">
        <div class="ws-time" id="clock">--:--</div>
        <div class="ws-date"><strong id="tgl">—</strong><span id="hari">—</span></div>
    </div>
</div>

<!-- Stat Row -->
<div class="stat-row fi d2">
    <div class="stat-mini">
        <div class="stat-mini-icon ic-blue"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="stat-mini-val">{{ $totalKelasDiajar }}</div>
            <div class="stat-mini-lbl">Kelas Diajar</div>
            <div class="stat-trend"><span class="nt">hari ini</span></div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon ic-green"><i class="bi bi-check-circle-fill"></i></div>
        <div>
            <div class="stat-mini-val">{{ $hadirHariIni }}</div>
            <div class="stat-mini-lbl">Siswa Hadir</div>
            <div class="stat-trend"><span class="up">{{ $persentaseHadir }}% kehadiran</span></div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon ic-amber"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="stat-mini-val">{{ $izinSakitHariIni }}</div>
            <div class="stat-mini-lbl">Izin / Sakit</div>
            <div class="stat-trend"><span class="nt">Di kelas Anda</span></div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon ic-red"><i class="bi bi-x-circle-fill"></i></div>
        <div>
            <div class="stat-mini-val">{{ $alpaHariIni }}</div>
            <div class="stat-mini-lbl">Alpha</div>
            @if($alpaHariIni > 0)
                <div class="stat-trend"><span class="dn">▲ perlu tindak lanjut</span></div>
            @else
                <div class="stat-trend"><span class="nt">tidak ada alpha</span></div>
            @endif
        </div>
    </div>
</div>

<!-- Main Grid -->
<div class="dash-grid">

    <!-- Col 1: Absensi hari ini -->
    <div class="card fi d3" style="grid-row: span 1;">
        <div class="card-inner">
            <div class="card-title">
                <i class="bi bi-clipboard2-check card-title-icon" style="color:var(--green)"></i>
                Log Absensi Kelas Anda
                <a href="{{ route('guru.presensi.index') }}" class="card-title-action">Lihat Semua →</a>
            </div>
            <table class="abs-table">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiTerbaru as $absen)
                        @php
                            $bdgClass = 'bs-h';
                            if($absen->status == 'Izin') $bdgClass = 'bs-i';
                            elseif($absen->status == 'Sakit') $bdgClass = 'bs-s';
                            elseif($absen->status == 'Alpa') $bdgClass = 'bs-a';
                        @endphp
                        <tr>
                            <td>{{ $absen->siswa->nama_siswa ?? '-' }}</td>
                            <td>{{ $absen->sesi->kelas ?? '-' }}</td>
                            <td>{{ $absen->jam_masuk ?? '—' }}</td>
                            <td><span class="badge-status {{ $bdgClass }}">{{ $absen->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding: 15px">Anda belum mencatat absensi hari ini. Buka sesi untuk memulai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Col 2: Chart kehadiran mingguan -->
    <div class="card fi d4">
        <div class="card-inner">
            <div class="card-title">
                <i class="bi bi-bar-chart-fill card-title-icon" style="color:var(--accent)"></i>
                Tren Kehadiran Siswa (7 Hari)
            </div>
            <div class="chart-wrap">
                @foreach($trendData as $trend)
                    @if($trend->is_empty)
                        <div class="bar-group">
                            <div class="bar" style="height:0%; background:var(--navy3)"></div>
                            <div class="bar-lbl">{{ substr($trend->hari, 0, 3) }}</div>
                        </div>
                    @else
                        @php
                            $dominantClass = 'bar-hadir';
                            $dominantPct = $trend->pct_hadir;
                            if($trend->pct_izin > $dominantPct) { $dominantClass = 'bar-izin'; $dominantPct = $trend->pct_izin; }
                            if($trend->pct_alpa > $dominantPct) { $dominantClass = 'bar-alpha'; $dominantPct = $trend->pct_alpa; }
                        @endphp
                        <div class="bar-group">
                            <div class="bar {{ $dominantClass }}" style="height:{{ $dominantPct }}%"></div>
                            <div class="bar-lbl">{{ substr($trend->hari, 0, 3) }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="chart-legend">
                <div class="leg"><div class="leg-dot" style="background:var(--accent)"></div>Hadir</div>
                <div class="leg"><div class="leg-dot" style="background:var(--amber)"></div>Izin</div>
                <div class="leg"><div class="leg-dot" style="background:var(--red)"></div>Alpha</div>
            </div>
        </div>

        <!-- Rekap per kelas -->
        <div style="border-top:1px solid var(--gb);padding:14px 18px 16px;">
            <div class="card-title" style="margin-bottom:10px">
                <i class="bi bi-building card-title-icon" style="color:var(--purple)"></i>
                Kehadiran Per Kelas Ajar
            </div>
            <div>
                @forelse($kelasBreakdown as $kb)
                    @php
                        $bg = 'var(--accent)';
                        if ($kb->persentase >= 80) $bg = 'var(--green)';
                        elseif ($kb->persentase >= 50) $bg = 'var(--amber)';
                        else $bg = 'var(--red)';
                    @endphp
                    <div class="kelas-row">
                        <span class="kelas-name">{{ $kb->nama }}</span>
                        <div class="kelas-bar-wrap">
                            <div class="kelas-bar-fill" style="width:{{ $kb->persentase }}%; background:{{ $bg }}"></div>
                        </div>
                        <span class="kelas-pct">{{ $kb->persentase }}%</span>
                    </div>
                @empty
                    <div style="font-size:12px; color:var(--text3); text-align:center; padding-top:10px;">Belum ada sesi di kelas Anda hari ini.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Col 3: Right panel -->
    <div style="display:flex;flex-direction:column;gap:14px;grid-row:span 2;">

        <!-- Donut proporsi -->
        <div class="card fi d3">
            <div class="card-inner">
                <div class="card-title">
                    <i class="bi bi-pie-chart-fill card-title-icon" style="color:var(--amber)"></i>
                    Proporsi Hari Ini
                </div>
                <div class="donut-section">
                    @php
                        $totPie = $hadirHariIni + $izinSakitHariIni + $alpaHariIni;
                        $totNum = $totPie > 0 ? $totPie : 1;
                        $pHadir = round(($hadirHariIni / $totNum) * 100, 1);
                        $pIzin = round(($izinSakitHariIni / $totNum) * 100, 1);
                        $pAlpa = round(($alpaHariIni / $totNum) * 100, 1);
                    @endphp
                    <div style="position:relative;display:inline-block;">
                        <svg class="donut-svg" viewBox="0 0 42 42" fill="none">
                            <circle cx="21" cy="21" r="15.91" stroke="#21262d" stroke-width="5.5"/>
                            <circle cx="21" cy="21" r="15.91" stroke="#f85149" stroke-width="5.5"
                                stroke-dasharray="{{ $pAlpa }} {{ 100 - $pAlpa }}" stroke-dashoffset="0" stroke-linecap="round"/>
                            <circle cx="21" cy="21" r="15.91" stroke="#e3b341" stroke-width="5.5"
                                stroke-dasharray="{{ $pIzin }} {{ 100 - $pIzin }}" stroke-dashoffset="-{{ $pAlpa }}" stroke-linecap="round"/>
                            <circle cx="21" cy="21" r="15.91" stroke="#58a6ff" stroke-width="5.5"
                                stroke-dasharray="{{ $pHadir }} {{ 100 - $pHadir }}" stroke-dashoffset="-{{ $pAlpa + $pIzin }}" stroke-linecap="round"/>
                        </svg>
                        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">
                            <div class="d-pct">{{ $persentaseHadir }}%</div>
                            <div style="font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.05em">Hadir</div>
                        </div>
                    </div>
                    <div class="d-legend" style="width:100%">
                        <div class="d-row">
                            <div class="d-dot" style="background:var(--accent)"></div>
                            <span class="d-name">Hadir</span>
                            <span class="d-val">{{ $hadirHariIni }}</span>
                            <span class="d-pct2">{{ $pHadir }}%</span>
                        </div>
                        <div class="d-row">
                            <div class="d-dot" style="background:var(--amber)"></div>
                            <span class="d-name">Izin / Sakit</span>
                            <span class="d-val">{{ $izinSakitHariIni }}</span>
                            <span class="d-pct2">{{ $pIzin }}%</span>
                        </div>
                        <div class="d-row">
                            <div class="d-dot" style="background:var(--red)"></div>
                            <span class="d-name">Alpha</span>
                            <span class="d-val">{{ $alpaHariIni }}</span>
                            <span class="d-pct2">{{ $pAlpa }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <div class="card fi d5">
            <div class="card-inner">
                <div class="card-title">
                    <i class="bi bi-bell-fill card-title-icon" style="color:var(--red)"></i>
                    Berita & Info
                </div>
                <div class="alert-list">
                    <div class="alert-item warn">
                        <i class="bi bi-clock-fill alert-icon wi"></i>
                        <div>
                            <div class="alert-title">Jangan Lupa Presensi</div>
                            <div class="alert-desc">Isi absensi siswa tepat waktu saat mengajar.</div>
                        </div>
                    </div>
                    <div class="alert-item info">
                        <i class="bi bi-info-circle-fill alert-icon ii"></i>
                        <div>
                            <div class="alert-title">Rapat Guru</div>
                            <div class="alert-desc">Besok pukul 14:00 ada evaluasi bulanan di ruang guru.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2 Col 1–2: Jadwal Hari Ini -->
    <div class="card fi d6" style="grid-column: span 2;">
        <div class="card-inner">
            <div class="card-title">
                <i class="bi bi-calendar-check-fill card-title-icon" style="color:var(--purple)"></i>
                Jadwal Mengajar Anda Hari Ini
                <a href="{{ route('guru.presensi.index') }}" class="card-title-action">Mulai Mengajar →</a>
            </div>
            <div class="jadwal-list">
                <div class="jadwal-item">
                    <div class="jadwal-time">07:00 – 08:30</div>
                    <div class="jadwal-dot" style="background:var(--accent)"></div>
                    <div class="jadwal-info">
                        <div class="jadwal-mapel">Matematika</div>
                        <div class="jadwal-kelas">Kelas X-A · Ruang 101</div>
                    </div>
                    <div class="jadwal-guru">Anda</div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">08:30 – 10:00</div>
                    <div class="jadwal-dot" style="background:var(--green)"></div>
                    <div class="jadwal-info">
                        <div class="jadwal-mapel">Matematika</div>
                        <div class="jadwal-kelas">Kelas XI-B · Ruang 203</div>
                    </div>
                    <div class="jadwal-guru">Anda</div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">12:30 – 14:00</div>
                    <div class="jadwal-dot" style="background:var(--purple)"></div>
                    <div class="jadwal-info">
                        <div class="jadwal-mapel">Matematika</div>
                        <div class="jadwal-kelas">Kelas X-B · Ruang 105</div>
                    </div>
                    <div class="jadwal-guru">Anda</div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Clock
function tick(){
    const n=new Date();
    const pad=v=>String(v).padStart(2,'0');
    const days=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    document.getElementById('clock').textContent=pad(n.getHours())+':'+pad(n.getMinutes())+':'+pad(n.getSeconds());
    document.getElementById('tgl').textContent=n.getDate()+' '+months[n.getMonth()]+' '+n.getFullYear();
    document.getElementById('hari').textContent=days[n.getDay()];
}
tick(); setInterval(tick,1000);
</script>

@endsection