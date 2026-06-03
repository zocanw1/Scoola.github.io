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
        ['label' => 'Total Siswa', 'value' => $totalSiswa, 'hint' => "{$totalKelasAktif} unit kelas aktif", 'icon' => 'bi-people'],
        ['label' => 'Kehadiran', 'value' => $persentaseHadir . '%', 'hint' => "{$hadirHariIni} siswa hadir", 'icon' => 'bi-graph-up-arrow'],
        ['label' => 'Izin / Sakit', 'value' => $izinSakitHariIni, 'hint' => 'siswa berhalangan', 'icon' => 'bi-heart-pulse'],
        ['label' => 'Absensi Alpha', 'value' => $alpaHariIni, 'hint' => $alpaHariIni > 0 ? 'Perlu tindak lanjut' : 'Data aman', 'icon' => 'bi-exclamation-circle'],
    ];

    $trendCount = max(count($trendData ?? []), 1);
@endphp

<style>
    .admin-dashboard {
        display: grid;
        gap: 28px;
        color: var(--admin-dark);
        font-family: var(--font-sans);
    }

    .dashboard-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
        gap: 34px;
        align-items: stretch;
        min-height: 320px;
        padding: 42px;
        background:
            radial-gradient(circle at 82% 18%, rgba(37, 99, 235, 0.10), transparent 28%),
            linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid var(--admin-line);
        border-radius: 28px;
        box-shadow: var(--admin-shadow-md);
        overflow: hidden;
    }

    .hero-copy {
        display: flex;
        flex-direction: column;
        justify-content: center;
        max-width: 680px;
    }

    .hero-kicker,
    .section-kicker,
    .metric-label,
    .table-status,
    .soft-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: fit-content;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }

    .hero-kicker,
    .soft-pill {
        padding: 8px 13px;
        border: 1px solid var(--admin-blue-line);
        background: var(--admin-soft-blue);
        color: var(--admin-blue);
    }

    .hero-title {
        max-width: 640px;
        margin: 20px 0 18px;
        color: var(--admin-dark);
        font-size: clamp(40px, 5vw, 64px);
        font-weight: 850;
        line-height: 1;
        letter-spacing: 0;
    }

    .hero-description {
        max-width: 600px;
        margin: 0;
        color: var(--admin-muted);
        font-size: 17px;
        font-weight: 500;
        line-height: 1.75;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    .clean-btn,
    .clean-btn-secondary {
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 0 18px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none !important;
        transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
    }

    .clean-btn {
        border: 1px solid var(--admin-blue);
        background: var(--admin-blue);
        color: #ffffff !important;
        box-shadow: 0 16px 34px rgba(37, 99, 235, 0.18);
    }

    .clean-btn:hover {
        background: var(--admin-blue-dark);
        transform: translateY(-2px);
        box-shadow: 0 20px 44px rgba(37, 99, 235, 0.22);
    }

    .clean-btn-secondary {
        border: 1px solid var(--admin-line);
        background: var(--admin-white);
        color: var(--admin-text) !important;
        box-shadow: 0 10px 26px rgba(16, 24, 40, 0.05);
    }

    .hero-preview {
        position: relative;
        display: grid;
        align-content: center;
        gap: 16px;
        min-height: 260px;
    }

    .preview-panel {
        position: relative;
        display: grid;
        gap: 16px;
        padding: 22px;
        border: 1px solid var(--admin-line);
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.86);
        box-shadow: var(--admin-shadow-lg);
        backdrop-filter: blur(16px);
    }

    .preview-row {
        display: grid;
        grid-template-columns: 44px 1fr auto;
        gap: 12px;
        align-items: center;
        padding: 12px;
        border: 1px solid var(--admin-line-soft);
        border-radius: 18px;
        background: #ffffff;
    }

    .preview-icon,
    .metric-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--admin-blue);
        background: var(--admin-soft-blue);
        border: 1px solid var(--admin-blue-line);
    }

    .preview-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        font-size: 18px;
    }

    .preview-line {
        height: 9px;
        border-radius: 999px;
        background: #eef2ff;
    }

    .preview-line.short { width: 72%; }
    .preview-line.long { width: 100%; margin-top: 8px; }

    .preview-live {
        color: var(--admin-green);
        background: var(--admin-green-soft);
        border: 1px solid #bbf7d0;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 800;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .metric-card,
    .dashboard-card,
    .dashboard-table-card,
    .report-card {
        border: 1px solid var(--admin-line);
        border-radius: 24px;
        background: var(--admin-white);
        box-shadow: var(--admin-shadow-sm);
    }

    .metric-card {
        min-height: 150px;
        display: grid;
        gap: 16px;
        padding: 22px;
    }

    .metric-top {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
    }

    .metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        font-size: 20px;
    }

    .metric-label {
        color: var(--admin-muted);
    }

    .metric-value {
        color: var(--admin-dark);
        font-size: 38px;
        font-weight: 850;
        line-height: 1;
    }

    .metric-hint {
        color: var(--admin-muted);
        font-size: 13px;
        font-weight: 700;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(320px, 0.65fr);
        gap: 24px;
        align-items: start;
    }

    .dashboard-column {
        display: grid;
        gap: 24px;
    }

    .dashboard-card,
    .dashboard-table-card {
        overflow: hidden;
    }

    .dashboard-card {
        padding: 26px;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
    }

    .section-kicker {
        color: var(--admin-blue);
    }

    .section-title {
        margin: 6px 0 0;
        color: var(--admin-dark);
        font-size: 22px;
        font-weight: 850;
        line-height: 1.2;
    }

    .clean-table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .clean-table {
        width: 100%;
        min-width: 720px;
        border-collapse: collapse;
    }

    .clean-table th,
    .clean-table td {
        padding: 17px 22px;
        text-align: left;
        border-bottom: 1px solid var(--admin-line);
    }

    .clean-table th {
        background: #f8fbff;
        color: var(--admin-muted);
        font-size: 12px;
        font-weight: 850;
        text-transform: uppercase;
    }

    .clean-table td {
        color: var(--admin-text);
        font-size: 14px;
        font-weight: 700;
    }

    .clean-table tbody tr:hover td {
        background: #fbfcfe;
    }

    .table-status {
        padding: 6px 11px;
        border: 1px solid var(--admin-blue-line);
        background: var(--admin-soft-blue);
        color: var(--admin-blue);
    }

    .agenda-list,
    .report-list,
    .class-list,
    .composition-list {
        display: grid;
        gap: 14px;
    }

    .agenda-item,
    .composition-item,
    .class-item,
    .report-card {
        padding: 16px;
        border: 1px solid var(--admin-line);
        border-radius: 18px;
        background: #ffffff;
    }

    .agenda-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .agenda-time {
        min-width: 92px;
        color: var(--admin-blue);
        background: var(--admin-soft-blue);
        border: 1px solid var(--admin-blue-line);
        border-radius: 999px;
        padding: 7px 11px;
        font-size: 12px;
        font-weight: 850;
        text-align: center;
    }

    .agenda-title,
    .report-title {
        margin: 0;
        color: var(--admin-dark);
        font-size: 15px;
        font-weight: 850;
    }

    .agenda-meta,
    .report-summary,
    .empty-copy {
        margin: 6px 0 0;
        color: var(--admin-muted);
        font-size: 13px;
        font-weight: 650;
        line-height: 1.55;
    }

    .chart-shell {
        display: flex;
        align-items: end;
        gap: 12px;
        height: 210px;
        padding: 18px 14px 12px;
        border: 1px solid var(--admin-line);
        border-radius: 20px;
        background: #f8fbff;
    }

    .chart-bar {
        position: relative;
        flex: 1;
        min-width: 22px;
        display: flex;
        align-items: end;
        justify-content: center;
        height: 100%;
    }

    .chart-fill {
        width: 100%;
        min-height: 8px;
        border-radius: 999px 999px 8px 8px;
        background: linear-gradient(180deg, #60a5fa 0%, var(--admin-blue) 100%);
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.18);
    }

    .chart-tooltip {
        position: absolute;
        top: -10px;
        transform: translateY(-100%);
        opacity: 0;
        color: #ffffff;
        background: var(--admin-dark);
        border-radius: 999px;
        padding: 5px 9px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
        transition: opacity 0.18s ease;
    }

    .chart-bar:hover .chart-tooltip {
        opacity: 1;
    }

    .chart-labels {
        display: grid;
        grid-template-columns: repeat({{ $trendCount }}, minmax(0, 1fr));
        gap: 12px;
        margin-top: 12px;
        color: var(--admin-muted);
        font-size: 12px;
        font-weight: 800;
        text-align: center;
    }

    .composition-shell {
        display: grid;
        gap: 18px;
    }

    .composition-chart {
        width: min(220px, 100%);
        aspect-ratio: 1;
        margin: 0 auto;
        display: grid;
        place-items: center;
        border-radius: 50%;
        box-shadow: 0 22px 52px rgba(16, 24, 40, 0.10);
    }

    .composition-center {
        width: 58%;
        aspect-ratio: 1;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: #ffffff;
        text-align: center;
    }

    .composition-number {
        color: var(--admin-dark);
        font-size: 34px;
        font-weight: 850;
        line-height: 1;
    }

    .composition-item,
    .class-item {
        display: grid;
        gap: 8px;
    }

    .item-head {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        color: var(--admin-dark);
        font-size: 14px;
        font-weight: 850;
    }

    .progress-track {
        height: 10px;
        overflow: hidden;
        border-radius: 999px;
        background: #eef2ff;
    }

    .progress-fill {
        height: 100%;
        border-radius: inherit;
        background: var(--admin-blue);
    }

    .report-card {
        box-shadow: none;
    }

    .report-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 12px;
        color: var(--admin-blue);
        font-size: 13px;
        font-weight: 850;
        text-decoration: none;
    }

    .empty-state {
        padding: 34px 24px;
        border: 1px dashed var(--admin-line);
        border-radius: 20px;
        background: #fbfcfe;
        text-align: center;
    }

    @media (max-width: 1180px) {
        .dashboard-hero,
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .metrics-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .dashboard-hero {
            padding: 28px 22px;
            border-radius: 22px;
        }

        .hero-title {
            font-size: 38px;
        }

        .metrics-grid {
            grid-template-columns: 1fr;
        }

        .section-head,
        .agenda-item {
            align-items: stretch;
            flex-direction: column;
        }

        .agenda-time {
            width: fit-content;
        }
    }
</style>

<div class="admin-dashboard">
    <section class="dashboard-hero">
        <div class="hero-copy">
            <span class="hero-kicker"><i class="bi bi-speedometer2"></i> Dashboard Utama</span>
            <h1 class="hero-title">Overview Scoola</h1>
            <p class="hero-description">
                Ringkasan operasional sekolah hari ini dalam tampilan ringan, rapi, dan mudah dipindai.
                Pantau kehadiran, agenda belajar, dan laporan penting tanpa kehilangan konteks data.
            </p>
            <div class="hero-actions">
                <a href="{{ route('admin.rekap.index') }}" class="clean-btn">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Lihat Rekap
                </a>
                <a href="{{ route('jadwal.index') }}" class="clean-btn-secondary">
                    <i class="bi bi-calendar3"></i> Kelola Jadwal
                </a>
            </div>
        </div>

        <div class="hero-preview" aria-hidden="true">
            <div class="preview-panel">
                <span class="soft-pill"><i class="bi bi-broadcast"></i> Live Preview</span>
                <div class="preview-row">
                    <span class="preview-icon"><i class="bi bi-people"></i></span>
                    <div>
                        <div class="preview-line short"></div>
                        <div class="preview-line long"></div>
                    </div>
                    <span class="preview-live">{{ $persentaseHadir }}%</span>
                </div>
                <div class="preview-row">
                    <span class="preview-icon"><i class="bi bi-calendar-check"></i></span>
                    <div>
                        <div class="preview-line long"></div>
                        <div class="preview-line short"></div>
                    </div>
                    <span class="preview-live">{{ $hadirHariIni }}</span>
                </div>
                <div class="preview-row">
                    <span class="preview-icon"><i class="bi bi-building"></i></span>
                    <div>
                        <div class="preview-line short"></div>
                        <div class="preview-line long"></div>
                    </div>
                    <span class="preview-live">{{ $totalKelasAktif }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="metrics-grid">
        @foreach($stats as $stat)
            <article class="metric-card">
                <div class="metric-top">
                    <span class="metric-label">{{ $stat['label'] }}</span>
                    <span class="metric-icon"><i class="bi {{ $stat['icon'] }}"></i></span>
                </div>
                <div>
                    <div class="metric-value">{{ $stat['value'] }}</div>
                    <div class="metric-hint">{{ $stat['hint'] }}</div>
                </div>
            </article>
        @endforeach
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-column">
            <article class="dashboard-table-card">
                <div class="section-head" style="padding: 26px 26px 0;">
                    <div>
                        <span class="section-kicker"><i class="bi bi-clock-history"></i> Aktivitas Presensi</span>
                        <h2 class="section-title">Absensi Masuk Terbaru</h2>
                    </div>
                    <a href="{{ route('admin.rekap.index') }}" class="clean-btn-secondary">Lihat Semua</a>
                </div>
                <div class="clean-table-wrap">
                    <table class="clean-table">
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
                                    <td>{{ $absen->siswa->kelas ?? '-' }}</td>
                                    <td>{{ $absen->jam_masuk ?? '-' }}</td>
                                    <td><span class="table-status">{{ $absen->status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div class="empty-copy">Belum ada aktivitas terekam hari ini.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="dashboard-card">
                <div class="section-head">
                    <div>
                        <span class="section-kicker"><i class="bi bi-calendar-week"></i> Agenda</span>
                        <h2 class="section-title">Agenda Belajar Hari Ini</h2>
                    </div>
                    <a href="{{ route('jadwal.index') }}" class="clean-btn-secondary">Kelola</a>
                </div>

                <div class="agenda-list">
                    @forelse($agendaHariIni as $agenda)
                        <div class="agenda-item">
                            <span class="agenda-time">{{ $formatAgendaLabel($agenda->jam_mulai, $agenda->jam_selesai) }}</span>
                            <div style="flex: 1;">
                                <h3 class="agenda-title">{{ $agenda->mapel?->nama_mapel ?? $agenda->kd_jp }}</h3>
                                <p class="agenda-meta">Kelas {{ $agenda->kelas }} dengan {{ $agenda->guru?->nama_guru ?? '-' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-copy">Belum ada jadwal pelajaran yang tercatat untuk hari ini.</div>
                        </div>
                    @endforelse
                </div>
            </article>
        </div>

        <aside class="dashboard-column">
            <article class="dashboard-card">
                <div class="section-head">
                    <div>
                        <span class="section-kicker"><i class="bi bi-bar-chart-line"></i> Analitik</span>
                        <h2 class="section-title">Tren Kehadiran</h2>
                    </div>
                </div>
                <div class="chart-shell">
                    @foreach($trendData as $trend)
                        <div class="chart-bar">
                            <span class="chart-tooltip">{{ $trend->is_empty ? '0' : $trend->pct_hadir }}%</span>
                            <div class="chart-fill" style="height: {{ $trend->is_empty ? '8px' : ($trend->pct_hadir . '%') }};"></div>
                        </div>
                    @endforeach
                </div>
                <div class="chart-labels">
                    @foreach($trendData as $trend)
                        <span>{{ substr($trend->hari, 0, 3) }}</span>
                    @endforeach
                </div>
            </article>

            <article class="dashboard-card">
                <div class="section-head">
                    <div>
                        <span class="section-kicker"><i class="bi bi-pie-chart"></i> Siswa</span>
                        <h2 class="section-title">Komposisi Siswa</h2>
                    </div>
                </div>

                @php
                    $firstSegment = $studentComposition[0] ?? null;
                    $secondSegment = $studentComposition[1] ?? null;
                    $firstStop = $firstSegment?->percentage ?? 0;
                    $pieBackground = $studentCompositionTotal > 0
                        ? "conic-gradient(#2563eb 0 {$firstStop}%, #93c5fd {$firstStop}% 100%)"
                        : 'conic-gradient(#eff6ff 0 100%)';
                @endphp

                @if($studentCompositionTotal > 0)
                    <div class="composition-shell">
                        <div class="composition-chart" style="background: {{ $pieBackground }};">
                            <div class="composition-center">
                                <div>
                                    <div class="composition-number">{{ $studentCompositionTotal }}</div>
                                    <div class="metric-hint">Siswa</div>
                                </div>
                            </div>
                        </div>

                        <div class="composition-list">
                            @foreach($studentComposition as $segment)
                                <div class="composition-item">
                                    <div class="item-head">
                                        <span>{{ $segment->label }}</span>
                                        <span>{{ rtrim(rtrim(number_format($segment->percentage, 1), '0'), '.') }}%</span>
                                    </div>
                                    <div class="metric-hint">{{ $segment->total }} siswa dari {{ $studentCompositionTotal }} total</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-copy">Data siswa untuk diagram belum tersedia.</div>
                    </div>
                @endif
            </article>

            <article class="dashboard-card">
                <div class="section-head">
                    <div>
                        <span class="section-kicker"><i class="bi bi-building"></i> Kelas</span>
                        <h2 class="section-title">Data Per Kelas</h2>
                    </div>
                </div>

                <div class="class-list">
                    @forelse($kelasBreakdown as $kb)
                        <div class="class-item">
                            <div class="item-head">
                                <span>{{ $kb->nama }}</span>
                                <span>{{ $kb->persentase }}%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $kb->persentase }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-copy">Menunggu data kelas.</div>
                        </div>
                    @endforelse
                </div>
            </article>

            <article class="dashboard-card">
                <div class="section-head">
                    <div>
                        <span class="section-kicker"><i class="bi bi-shield-check"></i> Monitoring</span>
                        <h2 class="section-title">Laporan Kritikal</h2>
                    </div>
                </div>

                <div class="report-list">
                    @forelse($criticalReports as $report)
                        <div class="report-card">
                            <h3 class="report-title">{{ $report->title }}</h3>
                            <p class="report-summary">{{ $report->summary }}</p>

                            @if(collect($report->items ?? [])->isNotEmpty())
                                <ul style="margin: 12px 0 0; padding-left: 18px; color: var(--admin-text); font-size: 13px; font-weight: 700; line-height: 1.6;">
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
                        <div class="report-card">
                            <h3 class="report-title">Tidak Ada Alert</h3>
                            <p class="report-summary">Belum ada anomali presensi atau sesi yang perlu ditindaklanjuti hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </article>
        </aside>
    </section>
</div>
@endsection
