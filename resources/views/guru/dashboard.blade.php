@extends('layouts.guru')

@section('content')

<div class="card" style="background: #ffffff; padding: 40px; margin-bottom: var(--spacing-md); border-radius: 12px; border: 1px solid var(--color-hairline);">
    <div class="editorial-header" style="margin: 0;">
        <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Dashboard Pengajar</span>
        <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Aktivitas</h1>
        <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
            Selamat datang kembali. Berikut adalah jadwal mengajar dan ringkasan kehadiran siswa di kelas Anda hari ini.
        </p>
    </div>
</div>

<!-- Stats Strip -->
@php 
    $stats = [
        ['Kelas Diajar', $totalKelasDiajar, "Sesi aktif hari ini", false],
        ['Siswa Hadir', $hadirHariIni, $persentaseHadir . "% dari total siswa", false],
        ['Izin / Sakit', $izinSakitHariIni, "Konfirmasi diterima", false],
        ['Alpha', $alpaHariIni, $alpaHariIni > 0 ? 'Perlu pengecekan' : 'Sesi terekam baik', $alpaHariIni > 0]
    ];
    $statCount = count($stats);
@endphp
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--spacing-md); margin-bottom: var(--spacing-xxl);">
    @foreach($stats as $index => $s)
        @php 
            $isFullWidth = ($statCount % 2 !== 0 && $index === 0) || $statCount === 1;
        @endphp
        <div class="card" style="background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid var(--color-hairline); @if($isFullWidth) grid-column: 1 / -1; @endif">
            <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px;">{{ $s[0] }}</div>
            <div style="font-size: 40px; font-weight: 400; line-height: 1; color: var(--color-ink); letter-spacing: var(--tracking-tight);">{{ $s[1] }}</div>
            <div class="text-meta" style="color: {{ $s[3] ? 'var(--color-primary)' : 'var(--color-slate)' }}; margin-top: 16px; font-size: 12px;">{{ $s[2] }}</div>
        </div>
    @endforeach
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--spacing-xxl); align-items: start;">
    
    <!-- Main Column -->
    <div style="display: flex; flex-direction: column; gap: var(--spacing-xxl);">
        
        <!-- Absensi Terbaru -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700;">Log Absensi Kelas</h2>
                <a href="{{ route('guru.presensi.index') }}" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px;">Lihat Semua</a>
            </div>
            
            <table class="data-table">
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
                            <td data-label="Siswa" style="font-weight: 600;">{{ $absen->siswa->nama_siswa ?? '-' }}</td>
                            <td data-label="Kelas">{{ $absen->sesi->kelas ?? '-' }}</td>
                            <td data-label="Waktu">{{ $absen->jam_masuk ?? '—' }}</td>
                            <td data-label="Status">
                                <span class="badge-status {{ $absen->status == 'Hadir' ? 'bs-h' : 'bs-a' }}">
                                    {{ $absen->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding: 64px; color: var(--color-stone);">Anda belum mencatat absensi hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Jadwal Pelajaran -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700;">Agenda Mengajar</h2>
                <a href="{{ route('guru.presensi.index') }}" class="btn-primary" style="height: 32px; font-size: 11px; padding: 0 16px;">Mulai Sesi</a>
            </div>
            
            <div class="agenda-list">
                @foreach([['07:00','Matematika','X-A'],['08:30','Matematika','XI-B'],['12:30','Matematika','X-B']] as $j)
                <div class="agenda-list-item">
                    <div class="agenda-time">{{ $j[0] }}</div>
                    <div class="agenda-details">
                        <div class="agenda-subject">{{ $j[1] }}</div>
                        <div class="agenda-class">{{ $j[2] }}</div>
                    </div>
                    <div class="agenda-teacher">Aktif</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div style="display: flex; flex-direction: column; gap: var(--spacing-xxl);">
        
        <!-- Tren Kehadiran -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
            <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700; margin-bottom: 40px;">Grafik Kehadiran</h2>
            
            <div style="height: 200px; display: flex; align-items: flex-end; gap: 12px; border-bottom: 2px solid var(--color-ink); padding-bottom: 12px; margin-bottom: 20px;">
                @foreach($trendData as $trend)
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%;">
                        <div style="width: 100%; height: {{ $trend->is_empty ? '2px' : $trend->pct_hadir . '%' }}; background: {{ $trend->is_empty ? 'var(--color-hairline)' : 'var(--color-ink)' }};"></div>
                    </div>
                @endforeach
            </div>
            <div style="display: flex; justify-content: space-between;">
                @foreach($trendData as $trend)
                    <div class="text-micro-caps" style="color: var(--color-stone); width: 100%; text-align: center;">{{ substr($trend->hari, 0, 3) }}</div>
                @endforeach
            </div>
        </div>

        <!-- Info Card -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
            <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700; margin-bottom: 32px; color: var(--color-surface);">Pusat Informasi</h2>
            <div style="display: flex; flex-direction: column; gap: 32px;">
                <div>
                    <div style="font-weight: 700; font-size: 15px; margin-bottom: 8px;">Pemberitahuan Presensi</div>
                    <div style="color: var(--color-stone); font-size: 13px; line-height: 1.5;">Mohon pastikan semua data presensi jam pertama diunggah sebelum pukul 09:00.</div>
                </div>
                <div style="padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <div style="font-weight: 700; font-size: 15px; margin-bottom: 8px;">Rapat Evaluasi</div>
                    <div style="color: var(--color-stone); font-size: 13px; line-height: 1.5;">Pertemuan bulanan guru akan diadakan besok di Aula Utama.</div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
