@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

<style>
/* Dashboard-specific aliases for legacy var names */
:root { --gb: var(--glass-border); --gh: var(--glass-hover); }

/* ── Absensi table ── */
.abs-table { width: 100%; border-collapse: collapse; }
.abs-table th {
    font-size: 10px; letter-spacing: .06em; text-transform: uppercase;
    color: var(--text3); font-weight: 600; padding: 0 8px 10px;
    border-bottom: 1px solid var(--glass-border); text-align: left;
}
.abs-table td {
    padding: 10px 8px; font-size: 12.5px; color: var(--text2);
    border-bottom: 1px solid rgba(255,255,255,0.03);
}
.abs-table tr:last-child td { border-bottom: none; }
.abs-table tr:hover td { background: var(--glass-hover); }
.abs-table td:first-child { color: var(--text1); font-weight: 500; }

/* ── Bar Chart ── */
.chart-wrap { height: 140px; display: flex; align-items: flex-end; gap: 8px; padding-bottom: 2px; }
.bar-group { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 5px; height: 100%; justify-content: flex-end; }
.bar { width: 100%; border-radius: 5px 5px 0 0; min-height: 4px; transition: opacity .2s; cursor: pointer; }
.bar:hover { opacity: .75; }
.bar-hadir { background: var(--accent); }
.bar-izin  { background: var(--amber); }
.bar-alpha { background: var(--red); }
.bar-lbl { font-size: 10px; color: var(--text3); }
.chart-legend { display: flex; gap: 14px; margin-top: 14px; }
.leg { display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: var(--text2); }
.leg-dot { width: 8px; height: 8px; border-radius: 50%; }

/* ── Donut ── */
.donut-section { display: flex; flex-direction: column; align-items: center; gap: 14px; }
.donut-svg { width: 120px; height: 120px; }
.d-pct { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 26px; font-weight: 800; color: var(--text1); line-height: 1; }
.d-lbl { font-size: 10px; color: var(--text2); text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; }
.d-legend { width: 100%; }
.d-row { display: flex; align-items: center; gap: 8px; padding: 7px 0; border-bottom: 1px solid rgba(255,255,255,.04); font-size: 12px; }
.d-row:last-child { border-bottom: none; }
.d-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.d-name { color: var(--text2); flex: 1; }
.d-val { font-weight: 600; color: var(--text1); }
.d-pct2 { color: var(--text3); font-size: 10.5px; min-width: 32px; text-align: right; }

/* ── Calendar ── */
.cal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
.cal-nav { width: 28px; height: 28px; border-radius: 7px; background: var(--glass); border: 1px solid var(--glass-border); color: var(--text2); display: grid; place-items: center; cursor: pointer; font-size: 12px; transition: all var(--transition); }
.cal-nav:hover { background: var(--accent-soft); color: var(--accent); }
.cal-month { font-size: 13px; font-weight: 700; color: var(--text1); }
.cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 3px; }
.cal-day-name { font-size: 9.5px; color: var(--text3); text-align: center; padding: 4px 0; font-weight: 700; letter-spacing: .05em; }
.cal-day { aspect-ratio: 1; display: grid; place-items: center; font-size: 11.5px; color: var(--text2); border-radius: 6px; cursor: pointer; transition: all .15s; }
.cal-day:hover { background: var(--glass-hover); color: var(--text1); }
.cal-day.today { background: var(--accent); color: #fff; font-weight: 700; border-radius: 8px; }
.cal-day.has-event { position: relative; }
.cal-day.has-event::after { content: ''; position: absolute; bottom: 3px; width: 4px; height: 4px; background: var(--green); border-radius: 50%; }
.cal-day.empty { color: transparent; cursor: default; }

/* ── Jadwal Hari Ini ── */
.jadwal-list { display: flex; flex-direction: column; gap: 8px; }
.jadwal-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; background: var(--navy3); border-radius: var(--r-sm); border: 1px solid var(--glass-border); transition: all var(--transition); }
.jadwal-item:hover { border-color: var(--accent-glow); transform: translateX(4px); }
.jadwal-time { font-size: 11px; color: var(--text2); min-width: 72px; font-weight: 600; }
.jadwal-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.jadwal-info { flex: 1; }
.jadwal-mapel { font-size: 13px; font-weight: 600; color: var(--text1); }
.jadwal-kelas { font-size: 11px; color: var(--text2); margin-top: 2px; }
.jadwal-guru  { font-size: 11px; color: var(--text3); }

/* ── Quick Alerts ── */
.alert-list { display: flex; flex-direction: column; gap: 8px; }
.alert-item { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; border-radius: var(--r-sm); border: 1px solid; transition: all var(--transition); }
.alert-item:hover { transform: translateX(3px); }
.alert-item.warn   { background: var(--amber-soft); border-color: rgba(251,191,36,.2); }
.alert-item.danger  { background: var(--red-soft); border-color: rgba(248,113,113,.2); }
.alert-item.info    { background: var(--accent-soft); border-color: rgba(96,165,250,.2); }
.alert-icon { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
.alert-icon.wi { color: var(--amber); }
.alert-icon.di { color: var(--red); }
.alert-icon.ii { color: var(--accent); }
.alert-title { font-size: 12.5px; font-weight: 600; color: var(--text1); }
.alert-desc  { font-size: 11.5px; color: var(--text2); margin-top: 3px; line-height: 1.4; }

/* ── Rekap kelas ── */
.kelas-row { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,.04); font-size: 12.5px; }
.kelas-row:last-child { border-bottom: none; }
.kelas-name { color: var(--text1); font-weight: 600; min-width: 60px; }
.kelas-bar-wrap { flex: 1; height: 6px; background: var(--navy4); border-radius: 10px; overflow: hidden; }
.kelas-bar-fill { height: 100%; border-radius: 10px; background: var(--accent); transition: width .6s ease; }
.kelas-pct { color: var(--text2); min-width: 36px; text-align: right; font-size: 11.5px; font-weight: 600; }

/* ── Dashboard Mobile ── */
@media (max-width: 768px) {
    .jadwal-item { flex-wrap: wrap; gap: 8px; }
    .jadwal-time { min-width: auto; }
    .abs-table { display: block; overflow-x: auto; }
}
</style>

<!-- Welcome Strip -->
<div class="welcome-strip fi d1">
    <div class="ws-left">
        <h2>Selamat pagi, Admin 👋</h2>
        <p>Berikut ringkasan aktivitas sekolah hari ini</p>
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
            <div class="stat-mini-val">{{ $totalSiswa }}</div>
            <div class="stat-mini-lbl">Total Siswa</div>
            <div class="stat-trend"><span class="nt">{{ $totalKelasAktif }} kelas aktif</span></div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon ic-green"><i class="bi bi-check-circle-fill"></i></div>
        <div>
            <div class="stat-mini-val">{{ $hadirHariIni }}</div>
            <div class="stat-mini-lbl">Hadir Hari Ini</div>
            <div class="stat-trend"><span class="up">{{ $persentaseHadir }}% kehadiran</span></div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-icon ic-amber"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="stat-mini-val">{{ $izinSakitHariIni }}</div>
            <div class="stat-mini-lbl">Izin / Sakit</div>
            <div class="stat-trend"><span class="nt">hari ini</span></div>
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
                Absensi Masuk Terbaru
                <a href="/admin/absensi" class="card-title-action">Lihat Semua →</a>
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
                            <td>{{ $absen->siswa->kelas ?? '-' }}</td>
                            <td>{{ $absen->jam_masuk ?? '—' }}</td>
                            <td><span class="badge-status {{ $bdgClass }}">{{ $absen->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding: 15px">Belum ada data absensi hari ini</td>
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
                Tren Kehadiran 7 Hari
                <a href="/admin/laporan" class="card-title-action">Rekap →</a>
            </div>
            <div class="chart-wrap">
                @foreach($trendData as $trend)
                    @if($trend->is_empty)
                        <div class="bar-group">
                            <div class="bar" style="height:0%; background:var(--navy3)"></div>
                            <div class="bar-lbl">{{ substr($trend->hari, 0, 3) }}</div>
                        </div>
                    @else
                        <!-- the dominant stat gets the bar -->
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
                Kehadiran Per Kelas
            </div>
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
                    <div style="font-size:12px; color:var(--text3); text-align:center; padding-top:10px;">Belum ada data sesi hari ini.</div>
                @endforelse
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
                        // Make sure percentages sum up to exactly 100 if tot > 0 to avoid gaps
                        if ($totPie > 0 && ($pHadir + $pIzin + $pAlpa != 100)) {
                            // Let's just adjust hadir to fill the gap so it doesn't break visually
                        }
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

        <!-- Kalender -->
        <div class="card fi d4">
            <div class="card-inner">
                <div class="card-title">
                    <i class="bi bi-calendar3 card-title-icon" style="color:var(--green)"></i>
                    Kalender
                </div>
                <div class="cal-header">
                    <div class="cal-nav" onclick="prevMonth()">‹</div>
                    <div class="cal-month" id="cal-title">—</div>
                    <div class="cal-nav" onclick="nextMonth()">›</div>
                </div>
                <div class="cal-grid" id="cal-grid">
                    <div class="cal-day-name">Min</div>
                    <div class="cal-day-name">Sen</div>
                    <div class="cal-day-name">Sel</div>
                    <div class="cal-day-name">Rab</div>
                    <div class="cal-day-name">Kam</div>
                    <div class="cal-day-name">Jum</div>
                    <div class="cal-day-name">Sab</div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <div class="card fi d5">
            <div class="card-inner">
                <div class="card-title">
                    <i class="bi bi-bell-fill card-title-icon" style="color:var(--red)"></i>
                    Perlu Perhatian
                </div>
                <div class="alert-list">
                    <div class="alert-item danger">
                        <i class="bi bi-exclamation-triangle-fill alert-icon di"></i>
                        <div>
                            <div class="alert-title">5 Siswa Alpha Berulang</div>
                            <div class="alert-desc">Lebih dari 3x dalam sepekan. Perlu konfirmasi wali murid.</div>
                        </div>
                    </div>
                    <div class="alert-item warn">
                        <i class="bi bi-clock-fill alert-icon wi"></i>
                        <div>
                            <div class="alert-title">Absensi XII-A Belum Lengkap</div>
                            <div class="alert-desc">Guru belum mengisi absensi jam ke-3 & ke-4.</div>
                        </div>
                    </div>
                    <div class="alert-item info">
                        <i class="bi bi-info-circle-fill alert-icon ii"></i>
                        <div>
                            <div class="alert-title">Rekap Bulan Ini Siap</div>
                            <div class="alert-desc">Data November sudah bisa diekspor ke PDF/Excel.</div>
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
                Jadwal Pelajaran Hari Ini
                <a href="/admin/jadwal" class="card-title-action">Kelola Jadwal →</a>
            </div>
            <div class="jadwal-list">
                <div class="jadwal-item">
                    <div class="jadwal-time">07:00 – 08:30</div>
                    <div class="jadwal-dot" style="background:var(--accent)"></div>
                    <div class="jadwal-info">
                        <div class="jadwal-mapel">Matematika</div>
                        <div class="jadwal-kelas">Kelas X-A · Ruang 101</div>
                    </div>
                    <div class="jadwal-guru">Pak Hendra</div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">08:30 – 10:00</div>
                    <div class="jadwal-dot" style="background:var(--green)"></div>
                    <div class="jadwal-info">
                        <div class="jadwal-mapel">Bahasa Indonesia</div>
                        <div class="jadwal-kelas">Kelas XI-B · Ruang 203</div>
                    </div>
                    <div class="jadwal-guru">Bu Dewi</div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">10:15 – 11:45</div>
                    <div class="jadwal-dot" style="background:var(--amber)"></div>
                    <div class="jadwal-info">
                        <div class="jadwal-mapel">Fisika</div>
                        <div class="jadwal-kelas">Kelas XII-C · Lab IPA</div>
                    </div>
                    <div class="jadwal-guru">Pak Rizal</div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">12:30 – 14:00</div>
                    <div class="jadwal-dot" style="background:var(--purple)"></div>
                    <div class="jadwal-info">
                        <div class="jadwal-mapel">Sejarah Indonesia</div>
                        <div class="jadwal-kelas">Kelas X-B · Ruang 105</div>
                    </div>
                    <div class="jadwal-guru">Bu Sari</div>
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

// Calendar
let calYear, calMonth;
const today = new Date();
calYear = today.getFullYear(); calMonth = today.getMonth();

const eventDays = [3,7,12,15,20,23,27];

function buildCal(){
    const months=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    document.getElementById('cal-title').textContent=months[calMonth]+' '+calYear;
    const grid=document.getElementById('cal-grid');
    // remove days (keep headers)
    while(grid.children.length>7) grid.removeChild(grid.lastChild);
    const first=new Date(calYear,calMonth,1).getDay();
    const total=new Date(calYear,calMonth+1,0).getDate();
    for(let i=0;i<first;i++){
        const d=document.createElement('div');
        d.className='cal-day empty'; d.textContent='·';
        grid.appendChild(d);
    }
    for(let d=1;d<=total;d++){
        const el=document.createElement('div');
        el.className='cal-day';
        if(d===today.getDate()&&calMonth===today.getMonth()&&calYear===today.getFullYear()) el.classList.add('today');
        if(eventDays.includes(d)) el.classList.add('has-event');
        el.textContent=d;
        grid.appendChild(el);
    }
}

function prevMonth(){ calMonth--; if(calMonth<0){calMonth=11;calYear--;} buildCal(); }
function nextMonth(){ calMonth++; if(calMonth>11){calMonth=0;calYear++;} buildCal(); }

buildCal();
</script>

@endsection