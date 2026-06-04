@extends('layouts.admin')

@section('content')
@php
    $formatAgendaLabel = static function ($jamMulai, $jamSelesai): string {
        $mulai = trim((string) $jamMulai);
        $selesai = trim((string) $jamSelesai);

        if (is_numeric($mulai) && is_numeric($selesai)) {
            return "Jam {$mulai}-{$selesai}";
        }

        if ($selesai === '' || $selesai === $mulai) {
            return $mulai !== '' ? $mulai : '-';
        }

        return trim($mulai . ' - ' . $selesai);
    };

    $stats = [
        ['label' => 'Total Siswa', 'value' => $totalSiswa, 'hint' => "{$totalKelasAktif} unit kelas aktif", 'background' => '#00CEC9', 'accent' => '#1E1B29', 'icon' => 'bi-mortarboard-fill'],
        ['label' => 'Kehadiran', 'value' => $persentaseHadir . '%', 'hint' => "{$hadirHariIni} siswa hadir", 'background' => '#FF7675', 'accent' => '#FFFFFF', 'icon' => 'bi-stars'],
        ['label' => 'Izin / Sakit', 'value' => $izinSakitHariIni, 'hint' => 'siswa berhalangan', 'background' => '#FFFFFF', 'accent' => '#1E1B29', 'icon' => 'bi-chat-heart-fill'],
        ['label' => 'Absensi Alpha', 'value' => $alpaHariIni, 'hint' => $alpaHariIni > 0 ? 'Perlu tindakan segera' : 'Data aman', 'background' => '#FDCB6E', 'accent' => '#1E1B29', 'icon' => 'bi-exclamation-triangle-fill'],
    ];

    $firstSegment = $studentComposition[0] ?? null;
    $secondSegment = $studentComposition[1] ?? null;
    $firstStop = $firstSegment?->percentage ?? 0;
    $firstColor = $firstSegment?->color ?? '#00CEC9';
    $secondColor = $secondSegment?->color ?? '#FF7675';
    $pieBackground = $studentCompositionTotal > 0
        ? "conic-gradient({$firstColor} 0 {$firstStop}%, {$secondColor} {$firstStop}% 100%)"
        : 'conic-gradient(#FAF9FF 0 100%)';
@endphp

<style>
    .font-anime-header {
        font-family: 'Fredoka One', cursive, sans-serif !important;
    }

    .font-anime-body {
        font-family: 'Nunito', sans-serif !important;
    }

    .admin-dashboard-manga {
        min-height: 100vh;
        width: 100%;
        margin: -24px;
        padding: 24px;
        color: #1E1B29;
        font-family: 'Nunito', sans-serif;
    }

    .admin-dashboard-manga * {
        box-sizing: border-box;
    }

    .pattern-dot {
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.65) 2px, transparent 2px);
        background-size: 18px 18px;
    }

    .neo-card {
        position: relative;
        border: 3px solid #1E1B29 !important;
        border-radius: 18px !important;
        background: #FFFFFF;
        box-shadow: 6px 6px 0 0 #1E1B29 !important;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .neo-card:hover {
        transform: translate(-2px, -2px);
        box-shadow: 8px 8px 0 0 #1E1B29 !important;
    }

    .neo-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border: 3px solid #1E1B29 !important;
        border-radius: 12px;
        box-shadow: 3px 3px 0 0 #1E1B29 !important;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 12px;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        text-decoration: none !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .neo-btn:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0 0 #1E1B29 !important;
    }

    .hero-panel {
        margin-bottom: 32px;
        padding: 32px;
        overflow: hidden;
        background: #6C5CE7;
        color: #FFFFFF;
    }

    .hero-sticker {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border: 2px solid #1E1B29;
        border-radius: 12px;
        background: #FDCB6E;
        color: #1E1B29;
        box-shadow: 3px 3px 0 0 #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 12px;
        transform: rotate(3deg);
    }

    .hero-badge {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border: 2px solid #1E1B29;
        border-radius: 999px;
        background: #00CEC9;
        color: #1E1B29;
        box-shadow: 2px 2px 0 0 #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 12px;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .hero-title {
        position: relative;
        z-index: 1;
        margin: 16px 0 12px;
        color: #FFFFFF;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: clamp(38px, 5vw, 54px);
        letter-spacing: 0.04em;
        line-height: 1.15;
        text-shadow: 3px 3px 0 #1E1B29;
        -webkit-text-stroke: 1.5px #1E1B29;
    }

    .hero-copy {
        position: relative;
        z-index: 1;
        max-width: 720px;
        margin: 0;
        color: rgba(255, 255, 255, 0.96);
        font-size: 16px;
        font-weight: 800;
        line-height: 1.7;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 24px;
    }

    .stat-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .stat-label {
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 12px;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        opacity: 0.82;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #1E1B29;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.35);
        box-shadow: 2px 2px 0 0 #1E1B29;
        font-size: 18px;
    }

    .stat-value {
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 40px;
        line-height: 1.1;
        text-shadow: 2px 2px 0 #FFFFFF, -1px -1px 0 #1E1B29;
        -webkit-text-stroke: 1px #1E1B29;
    }

    .stat-hint {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 2px dashed #1E1B29;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .neo-layout {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
        gap: 32px;
        align-items: start;
    }

    .stack-8 {
        display: grid;
        gap: 32px;
    }

    .section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .section-title h2 {
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 22px;
    }

    .neo-table-wrap {
        overflow-x: auto;
        border: 3px solid #1E1B29;
        border-radius: 16px;
        background: #FFFFFF;
    }

    .neo-table {
        width: 100%;
        min-width: 720px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .neo-table th {
        padding: 16px;
        border-bottom: 3px solid #1E1B29;
        background: #FAF9FF;
        color: #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 13px;
        letter-spacing: 0.04em;
        text-align: left;
        text-transform: uppercase;
    }

    .neo-table td {
        padding: 16px;
        border-bottom: 2px solid #1E1B29;
        color: #1E1B29;
        font-size: 14px;
        font-weight: 800;
    }

    .neo-table tbody tr:last-child td {
        border-bottom: none;
    }

    .student-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border: 2px solid #1E1B29;
        border-radius: 10px;
        background: #FAF9FF;
        font-size: 12px;
        font-weight: 900;
    }

    .badge-anime {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border: 2px solid #1E1B29;
        border-radius: 10px;
        box-shadow: 2px 2px 0 0 #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 11px;
        text-transform: uppercase;
    }

    .badge-anime-hadir {
        background: #00CEC9;
        color: #1E1B29;
    }

    .badge-anime-absen {
        background: #FF7675;
        color: #FFFFFF;
    }

    .agenda-card {
        padding: 24px;
    }

    .agenda-list,
    .class-list,
    .report-list {
        display: grid;
        gap: 16px;
    }

    .agenda-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #FAF9FF;
        border-left: 8px solid #00CEC9 !important;
    }

    .agenda-main {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .agenda-time {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 10px;
        background: #1E1B29;
        color: #FFFFFF;
        box-shadow: 2px 2px 0 0 #6C5CE7;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 14px;
        white-space: nowrap;
    }

    .agenda-subject {
        margin: 0 0 6px;
        color: #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 18px;
        line-height: 1.15;
    }

    .agenda-class {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 8px;
        background: #1E1B29;
        color: #FFFFFF;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .agenda-teacher {
        color: #6C5CE7;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 14px;
        white-space: nowrap;
    }

    .empty-neo {
        padding: 20px;
        background: #FAF9FF;
        color: #6B7280;
        font-size: 14px;
        font-style: italic;
        font-weight: 800;
        text-align: center;
    }

    .trend-card,
    .composition-card,
    .class-card,
    .critical-card {
        padding: 24px;
    }

    .trend-shell {
        height: 192px;
        display: flex;
        align-items: flex-end;
        gap: 12px;
        padding: 16px 10px;
        margin-bottom: 14px;
        border: 1px solid #D1D5DB;
        border-bottom: 4px solid #1E1B29;
        border-radius: 16px;
        background: #FAF9FF;
    }

    .trend-bar {
        position: relative;
        flex: 1;
        height: 100%;
        display: flex;
        align-items: flex-end;
    }

    .trend-tooltip {
        position: absolute;
        top: -36px;
        left: 50%;
        transform: translateX(-50%);
        padding: 5px 8px;
        border-radius: 8px;
        background: #1E1B29;
        color: #FFFFFF;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 10px;
        opacity: 0;
        transition: opacity 0.18s ease;
        white-space: nowrap;
    }

    .trend-bar:hover .trend-tooltip {
        opacity: 1;
    }

    .trend-fill {
        width: 100%;
        min-height: 10px;
        border: 2px solid #1E1B29;
        border-radius: 10px 10px 0 0;
        box-shadow: 2px 0 0 0 #1E1B29;
    }

    .trend-labels {
        display: flex;
        gap: 12px;
    }

    .trend-labels span {
        flex: 1;
        color: #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 12px;
        letter-spacing: 0.05em;
        text-align: center;
        text-transform: uppercase;
    }

    .student-pie-shell {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .student-pie-chart {
        position: relative;
        width: 168px;
        height: 168px;
        flex-shrink: 0;
        border: 4px solid #1E1B29;
        border-radius: 50%;
        box-shadow: 6px 6px 0 0 #1E1B29;
    }

    .student-pie-hole {
        position: absolute;
        inset: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        border: 3px solid #1E1B29;
        border-radius: 50%;
        background: #FFFFFF;
        text-align: center;
    }

    .student-pie-hole strong {
        display: block;
        color: #6C5CE7;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 12px;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .student-pie-hole span {
        display: block;
        color: #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 38px;
        line-height: 1;
    }

    .student-pie-hole small {
        display: block;
        color: #1E1B29;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .composition-list {
        flex: 1;
        display: grid;
        gap: 12px;
    }

    .composition-item {
        padding: 16px;
        background: #FAF9FF;
    }

    .composition-head,
    .class-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .legend-dot {
        width: 16px;
        height: 16px;
        display: inline-block;
        border: 2px solid #1E1B29;
        border-radius: 50%;
    }

    .composition-name {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 14px;
    }

    .mini-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border: 2px solid #1E1B29;
        border-radius: 8px;
        background: #FDCB6E;
        box-shadow: 1.5px 1.5px 0 0 #1E1B29;
        color: #1E1B29;
        font-size: 11px;
        font-weight: 900;
    }

    .composition-meta {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        color: #1E1B29;
        font-size: 14px;
        font-weight: 900;
    }

    .composition-meta span:last-child {
        color: #6C5CE7;
        text-transform: uppercase;
    }

    .progress-track {
        width: 100%;
        height: 16px;
        overflow: hidden;
        border: 2px solid #1E1B29;
        border-radius: 999px;
        background: #FAF9FF;
    }

    .progress-fill {
        height: 100%;
        border-right: 2px solid #1E1B29;
        background: #6C5CE7;
    }

    .critical-card {
        overflow: hidden;
        background: #FF7675;
    }

    .critical-card .section-title h2 {
        color: #FFFFFF;
        text-shadow: 2px 2px 0 #1E1B29;
        -webkit-text-stroke: 1px #1E1B29;
    }

    .report-item {
        padding: 16px;
        border: 2px solid #1E1B29;
        border-radius: 14px;
        background: #FFFFFF;
        box-shadow: 3px 3px 0 0 #1E1B29;
    }

    .report-item h4 {
        margin: 0 0 6px;
        color: #1E1B29;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 16px;
    }

    .report-item p {
        margin: 0;
        color: #374151;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.7;
    }

    .report-item ul {
        margin: 12px 0 0;
        padding-left: 18px;
        color: #1E1B29;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.7;
    }

    .report-link {
        display: inline-block;
        margin-top: 12px;
        color: #6C5CE7;
        font-family: 'Fredoka One', cursive, sans-serif;
        font-size: 12px;
        text-decoration: none;
    }

    @media (max-width: 1200px) {
        .stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .neo-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 720px) {
        .admin-dashboard-manga {
            margin: -16px;
            padding: 16px;
        }

        .hero-panel,
        .agenda-card,
        .trend-card,
        .composition-card,
        .class-card,
        .critical-card {
            padding: 20px;
        }

        .hero-sticker {
            position: static;
            margin-bottom: 16px;
            transform: rotate(0deg);
        }

        .stat-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .student-pie-shell,
        .agenda-item,
        .agenda-main {
            flex-direction: column;
            align-items: stretch;
        }

        .agenda-teacher {
            white-space: normal;
        }

        .student-pie-chart {
            margin: 0 auto;
        }
    }
</style>

<div class="admin-dashboard-manga">
    <div class="neo-card hero-panel">
        <div class="pattern-dot" style="position:absolute;inset:0;opacity:.18;pointer-events:none;"></div>
        <span class="hero-sticker">
            <i class="bi bi-stars"></i>
            ( ≧◡≦ ) HELLO!
        </span>

        <span class="hero-badge">
            <i class="bi bi-speedometer2"></i>
            Dashboard Utama
        </span>
        <h1 class="hero-title">OVERVIEW</h1>
        <p class="hero-copy">
            Ringkasan ekosistem Scoola hari ini. Pantau kehadiran siswa, agenda belajar, dan alert operasional
            dengan tampilan Manga-Pop yang kembali penuh warna.
        </p>
    </div>

    <div class="stat-grid">
        @foreach($stats as $stat)
            <div class="neo-card stat-card" style="background-color: {{ $stat['background'] }}; color: {{ $stat['accent'] }};">
                <div>
                    <div class="stat-head">
                        <span class="stat-label">{{ $stat['label'] }}</span>
                        <span class="stat-icon"><i class="bi {{ $stat['icon'] }}"></i></span>
                    </div>
                    <div class="stat-value">{{ $stat['value'] }}</div>
                </div>
                <div class="stat-hint">{{ $stat['hint'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="neo-layout">
        <div class="stack-8">
            <div>
                <div class="section-title">
                    <h2><span>📝</span> Absensi Masuk Terbaru</h2>
                    <a href="{{ route('admin.rekap.index') }}" class="neo-btn" style="background:#00CEC9;color:#1E1B29;">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="neo-table-wrap">
                    <table class="neo-table">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensiTerbaru as $absen)
                                <tr>
                                    <td>{{ $absen->siswa->nama_siswa ?? '-' }}</td>
                                    <td>
                                        <span class="student-chip">{{ $absen->siswa->kelas ?? '-' }}</span>
                                    </td>
                                    <td>{{ $absen->jam_masuk ?? '-' }}</td>
                                    <td>
                                        <span class="badge-anime {{ $absen->status === 'Hadir' ? 'badge-anime-hadir' : 'badge-anime-absen' }}">
                                            {{ $absen->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-neo">Belum ada aktivitas terekam hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="neo-card agenda-card">
                <div class="section-title">
                    <h2><span>⚡</span> Agenda Belajar Hari Ini</h2>
                    <a href="{{ route('jadwal.index') }}" class="neo-btn" style="background:#FF7675;color:#FFFFFF;">
                        Kelola
                    </a>
                </div>

                <div class="agenda-list">
                    @forelse($agendaHariIni as $agenda)
                        <div class="neo-card agenda-item">
                            <div class="agenda-main">
                                <span class="agenda-time">{{ $formatAgendaLabel($agenda->jam_mulai, $agenda->jam_selesai) }}</span>
                                <div>
                                    <h4 class="agenda-subject">{{ $agenda->mapel?->nama_mapel ?? $agenda->kd_jp }}</h4>
                                    <span class="agenda-class">Kelas {{ $agenda->kelas }}</span>
                                </div>
                            </div>
                            <div class="agenda-teacher">
                                <span>👤</span> {{ $agenda->guru?->nama_guru ?? '-' }}
                            </div>
                        </div>
                    @empty
                        <div class="neo-card empty-neo">Belum ada jadwal pelajaran yang tercatat untuk hari ini.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="stack-8">
            <div class="neo-card trend-card">
                <div class="section-title">
                    <h2><span>📊</span> Analitik Kehadiran</h2>
                </div>

                <div class="trend-shell">
                    @foreach($trendData as $trend)
                        <div class="trend-bar">
                            <span class="trend-tooltip">{{ $trend->is_empty ? '0' : rtrim(rtrim(number_format($trend->pct_hadir, 1), '0'), '.') }}%</span>
                            <div
                                class="trend-fill"
                                style="height: {{ $trend->is_empty ? '10px' : ($trend->pct_hadir . '%') }}; background-color: {{ $trend->is_empty ? '#FAF9FF' : ($loop->iteration % 2 === 0 ? '#00CEC9' : '#6C5CE7') }};"
                            ></div>
                        </div>
                    @endforeach
                </div>

                <div class="trend-labels">
                    @foreach($trendData as $trend)
                        <span>{{ substr($trend->hari, 0, 3) }}</span>
                    @endforeach
                </div>
            </div>

            <div class="neo-card composition-card">
                <div class="section-title">
                    <h2><span>🥧</span> Komposisi Siswa</h2>
                </div>

                @if($studentCompositionTotal > 0)
                    <div class="student-pie-shell">
                        <div class="student-pie-chart" style="background: {{ $pieBackground }};">
                            <div class="student-pie-hole">
                                <div>
                                    <strong>Total</strong>
                                    <span>{{ $studentCompositionTotal }}</span>
                                    <small>Siswa</small>
                                </div>
                            </div>
                        </div>

                        <div class="composition-list">
                            @foreach($studentComposition as $segment)
                                <div class="neo-card composition-item">
                                    <div class="composition-head">
                                        <span class="composition-name">
                                            <span class="legend-dot" style="background-color: {{ $segment->color }};"></span>
                                            {{ $segment->label }}
                                        </span>
                                        <span class="mini-badge">{{ rtrim(rtrim(number_format($segment->percentage, 1), '0'), '.') }}%</span>
                                    </div>
                                    <div class="composition-meta">
                                        <span>{{ $segment->total }} siswa</span>
                                        <span>{{ $studentCompositionTotal }} total</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="empty-neo">Data siswa untuk diagram belum tersedia.</div>
                @endif
            </div>

            <div class="neo-card class-card">
                <div class="section-title">
                    <h2><span>🏫</span> Data Per Kelas</h2>
                </div>

                <div class="class-list">
                    @forelse($kelasBreakdown as $kb)
                        <div>
                            <div class="class-head">
                                <span class="font-anime-header" style="font-size:14px;color:#1E1B29;">{{ $kb->nama }}</span>
                                <span class="mini-badge">{{ $kb->persentase }}%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $kb->persentase }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-neo">Menunggu data kelas.</div>
                    @endforelse
                </div>
            </div>

            <div class="neo-card critical-card">
                <div class="pattern-dot" style="position:absolute;inset:0;opacity:.1;pointer-events:none;"></div>

                <div class="section-title" style="position:relative;z-index:1;">
                    <h2><span>🚨</span> Laporan Kritikal</h2>
                </div>

                <div class="report-list" style="position:relative;z-index:1;">
                    @forelse($criticalReports as $report)
                        <div class="report-item">
                            <h4>{{ $report->title }}</h4>
                            <p>{{ $report->summary }}</p>

                            @if(collect($report->items ?? [])->isNotEmpty())
                                <ul>
                                    @foreach($report->items as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if(!empty($report->action_url))
                                <a href="{{ $report->action_url }}" class="report-link">
                                    {{ $report->action_label ?? 'Buka Detail' }} <i class="bi bi-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="report-item">
                            <h4>Tidak Ada Alert</h4>
                            <p>Belum ada anomali presensi atau sesi yang perlu ditindaklanjuti hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
