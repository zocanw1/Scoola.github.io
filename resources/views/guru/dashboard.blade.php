@extends('layouts.guru')

@section('content')

<style>
    .teacher-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(320px, .95fr);
        gap: 32px;
        align-items: start;
    }

    .trend-bars {
        height: 210px;
        display: flex;
        align-items: flex-end;
        gap: 12px;
        padding: 18px 14px 16px;
        margin: 24px 0 14px;
        border: 4px solid var(--midnight);
        border-radius: 16px;
        background: var(--mochi);
        box-shadow: 5px 5px 0 var(--midnight);
    }

    .trend-bar {
        flex: 1;
        min-height: 10px;
        border: 3px solid var(--midnight);
        border-radius: 10px 10px 0 0;
        background: var(--cosmo);
        box-shadow: 2px 0 0 var(--midnight);
    }

    .agenda-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 18px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--mochi);
        box-shadow: 4px 4px 0 var(--midnight);
    }

    @media (max-width: 1080px) {
        .teacher-layout { grid-template-columns: 1fr; }
    }
</style>

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">Panel Guru</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-lightning-charge-fill"></i> Dashboard Pengajar</span>
                <h1 class="mp-title">Aktivitas Mengajar</h1>
                <p class="mp-description">
                    Pantau jadwal mengajar, presensi terbaru, dan kondisi kehadiran kelas hari ini dari satu panel.
                </p>
            </div>
        </section>
    </div>

    @php
        $stats = [
            ['Kelas Diajar', $totalKelasDiajar, 'Sesi aktif hari ini', 'var(--cyber)', 'bi-building-fill'],
            ['Siswa Hadir', $hadirHariIni, $persentaseHadir . '% dari total siswa', 'var(--sakura)', 'bi-person-check-fill'],
            ['Izin / Sakit', $izinSakitHariIni, 'Konfirmasi diterima', 'var(--white)', 'bi-chat-dots-fill'],
            ['Alpha', $alpaHariIni, $alpaHariIni > 0 ? 'Perlu pengecekan' : 'Sesi terekam baik', 'var(--gold)', 'bi-exclamation-triangle-fill'],
        ];
    @endphp

    <section class="mp-stats-grid">
        @foreach($stats as $s)
            <div class="mp-stat-card mp-hover" style="background:{{ $s[3] }};">
                <div class="mp-stat-icon"><i class="bi {{ $s[4] }}"></i></div>
                <div>
                    <div class="mp-stat-label">{{ $s[0] }}</div>
                    <div class="mp-stat-value">{{ $s[1] }}</div>
                    <div style="margin-top:8px; font-size:12px; font-weight:900; text-transform:uppercase;">{{ $s[2] }}</div>
                </div>
            </div>
        @endforeach
    </section>

    <div class="teacher-layout">
        <div style="display:flex; flex-direction:column; gap:32px;">
            <section class="mp-card">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:24px;">
                    <h2 style="margin:0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:28px;">Log Absensi Kelas</h2>
                    <a href="{{ route('guru.presensi.index') }}" class="mp-btn-secondary">Lihat Semua</a>
                </div>

                <div class="mp-table-card">
                    <div class="mp-table-wrap">
                        <table class="mp-table data-table">
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
                                        <td data-label="Siswa">{{ $absen->siswa->nama_siswa ?? '-' }}</td>
                                        <td data-label="Kelas">{{ $absen->sesi->kelas ?? '-' }}</td>
                                        <td data-label="Waktu" class="mp-mono">{{ $absen->jam_masuk ?? '-' }}</td>
                                        <td data-label="Status">
                                            <span class="mp-badge" style="background:{{ $absen->status == 'Hadir' ? 'var(--cyber)' : 'var(--sakura)' }};">
                                                {{ $absen->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align:center; padding:60px;">Anda belum mencatat absensi hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class="mp-card">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:24px;">
                    <h2 style="margin:0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:28px;">Agenda Mengajar</h2>
                    <a href="{{ route('guru.presensi.index') }}" class="mp-btn">Mulai Sesi</a>
                </div>

                <div style="display:flex; flex-direction:column; gap:16px;">
                    @forelse($agendaMengajar as $agenda)
                        <div class="agenda-card">
                            <div style="display:flex; align-items:center; gap:16px;">
                                <span class="mp-badge" style="background:var(--cyber);">{{ $agenda->jam_label }}</span>
                                <div>
                                    <div style="font-family:'Fredoka One', cursive; font-size:20px; color:var(--midnight);">{{ $agenda->mapel }}</div>
                                    <div style="font-size:12px; font-weight:900; color:var(--cosmo); text-transform:uppercase;">Kelas {{ $agenda->kelas }}</div>
                                </div>
                            </div>
                            <span class="mp-badge" style="background:{{ $agenda->status_color }};">{{ $agenda->status_label }}</span>
                        </div>
                    @empty
                        <div class="agenda-card" style="justify-content:center; color:var(--midnight); font-weight:900;">
                            Belum ada jadwal mengajar untuk hari ini.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside style="display:flex; flex-direction:column; gap:32px;">
            <section class="mp-card">
                <span class="mp-label">Grafik Kehadiran</span>
                <h2 style="margin:8px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:28px;">Tren 7 Hari</h2>

                <div class="trend-bars">
                    @foreach($trendData as $trend)
                        <div class="trend-bar"
                             title="{{ $trend->is_empty ? '0' : round($trend->pct_hadir) }}%"
                             style="height: {{ $trend->is_empty ? '10px' : max(10, $trend->pct_hadir) . '%' }}; background:{{ $loop->iteration % 2 === 0 ? 'var(--cyber)' : 'var(--cosmo)' }};">
                        </div>
                    @endforeach
                </div>

                <div style="display:flex; justify-content:space-between; gap:8px;">
                    @foreach($trendData as $trend)
                        <div style="width:100%; text-align:center; color:var(--midnight); font-size:11px; font-weight:900; text-transform:uppercase;">{{ substr($trend->hari, 0, 3) }}</div>
                    @endforeach
                </div>
            </section>

            <section class="mp-card mp-card-cyber">
                <span class="mp-label">Data Per Kelas</span>
                <div style="display:flex; flex-direction:column; gap:16px; margin-top:18px;">
                    @forelse($kelasBreakdown as $kb)
                        <div>
                            <div style="display:flex; justify-content:space-between; gap:12px; margin-bottom:8px; font-weight:900;">
                                <span>{{ $kb->nama }}</span>
                                <span>{{ $kb->persentase }}%</span>
                            </div>
                            <div style="height:18px; overflow:hidden; border:3px solid var(--midnight); border-radius:999px; background:var(--white); box-shadow:3px 3px 0 var(--midnight);">
                                <div style="width:{{ $kb->persentase }}%; height:100%; background:var(--cosmo); border-right:3px solid var(--midnight);"></div>
                            </div>
                        </div>
                    @empty
                        <div style="font-weight:900;">Menunggu data kelas hari ini.</div>
                    @endforelse
                </div>
            </section>

            <section class="mp-card mp-card-sakura">
                <span class="mp-badge" style="background:var(--gold);">Status Sesi Hari Ini</span>
                <div style="display:flex; flex-direction:column; gap:18px; margin-top:22px;">
                    @foreach($statusSesiHariIni as $statusSesi)
                        <div style="padding:18px; border:3px solid var(--midnight); border-radius:14px; background:var(--white); color:var(--midnight); box-shadow:4px 4px 0 var(--midnight);">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                                <strong>{{ $statusSesi->title }}</strong>
                                <span class="mp-badge" style="background:var(--cyber);">{{ $statusSesi->value }}</span>
                            </div>
                            <p style="margin:8px 0 0; font-weight:800;">{{ $statusSesi->description }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </aside>
    </div>
</div>

@endsection
