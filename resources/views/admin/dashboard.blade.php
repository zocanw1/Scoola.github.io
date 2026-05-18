@extends('layouts.admin')

@section('content')

<div class="card" style="background: #ffffff; padding: 32px; margin-bottom: var(--spacing-md); border-radius: 16px; border: 1px solid var(--color-hairline);">
    <div class="editorial-header" style="margin: 0;">
        <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Dashboard Utama</span>
        <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Overview</h1>
        <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
            Ringkasan ekosistem Scoola hari ini. Pantau kehadiran siswa dan efektivitas pengajaran secara real-time.
        </p>
    </div>
</div>

<!-- Stats Strip -->
@php 
    $stats = [
        ['Total Siswa', $totalSiswa, "$totalKelasAktif unit kelas aktif", false],
        ['Kehadiran', $persentaseHadir . '%', "$hadirHariIni siswa hadir", false],
        ['Izin / Sakit', $izinSakitHariIni, "siswa berhalangan", false],
        ['Absensi Alpha', $alpaHariIni, $alpaHariIni > 0 ? 'Perlu tindakan segera' : 'Data aman', $alpaHariIni > 0]
    ];
    $statCount = count($stats);
@endphp
<div class="stats-grid" style="margin-bottom: var(--spacing-xxl);">
    @foreach($stats as $index => $s)
        <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
            <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px;">{{ $s[0] }}</div>
            <div style="font-size: 40px; font-weight: 400; line-height: 1; color: var(--color-ink); letter-spacing: var(--tracking-tight);">{{ $s[1] }}</div>
            <div class="text-meta" style="color: {{ $s[3] ? 'var(--color-primary)' : 'var(--color-slate)' }}; margin-top: 16px; font-size: 12px;">{{ $s[2] }}</div>
        </div>
    @endforeach
</div>

<style>
    .dashboard-layout {
        display: grid; 
        grid-template-columns: 2fr 1fr; 
        gap: var(--spacing-xxl); 
        align-items: start;
    }
    @media (max-width: 1024px) {
        .dashboard-layout {
            grid-template-columns: 1fr !important;
            gap: 32px !important;
        }
    }
</style>

<div class="dashboard-layout">
    
    <!-- Main Column -->
    <div style="display: flex; flex-direction: column; gap: var(--spacing-xxl);">
        
        <!-- Absensi Terbaru -->
        <div class="card table-container-card" style="background: #ffffff; padding: 40px; border-radius: 16px; border: 1px solid var(--color-hairline);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700;">Absensi Masuk Terbaru</h2>
                <a href="/admin/absensi" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; text-decoration: none; display: inline-flex; align-items: center;">Lihat Semua</a>
            </div>
            
            <table class="data-table responsive-table">
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
                            <td data-label="Kelas">{{ $absen->siswa->kelas ?? '-' }}</td>
                            <td data-label="Waktu">{{ $absen->jam_masuk ?? '—' }}</td>
                            <td data-label="Status">
                                <span class="badge-status {{ $absen->status == 'Hadir' ? 'bs-h' : 'bs-a' }}">
                                    {{ $absen->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding: 64px; color: var(--color-stone);">Belum ada aktivitas terekam hari ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Jadwal Pelajaran -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 16px; border: 1px solid var(--color-hairline);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700;">Agenda Belajar Hari Ini</h2>
                <a href="/admin/jadwal" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; text-decoration: none; display: inline-flex; align-items: center;">Kelola</a>
            </div>
            
            <div style="display: flex; flex-direction: column;">
                @foreach([['07:00','Matematika','X-A','Pak Hendra'],['08:30','B. Indonesia','XI-B','Bu Dewi'],['10:15','Fisika','XII-C','Pak Rizal']] as $j)
                <div style="padding: 24px 0; display: flex; align-items: center; gap: var(--spacing-xl); border-bottom: 1px solid var(--color-hairline);">
                    <div style="color: var(--color-stone); width: 100px; font-size: 13px; font-weight: 600;">{{ $j[0] }}</div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 16px; color: var(--color-ink);">{{ $j[1] }}</div>
                        <div style="color: var(--color-slate); font-size: 12px; text-transform: uppercase; margin-top: 4px;">{{ $j[2] }}</div>
                    </div>
                    <div style="color: var(--color-graphite); font-size: 14px; text-align: right;">{{ $j[3] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div style="display: flex; flex-direction: column; gap: var(--spacing-xxl);">
        
        <!-- Tren Kehadiran -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 16px; border: 1px solid var(--color-hairline);">
            <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700; margin-bottom: 40px;">Analitik Kehadiran</h2>
            
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

        <!-- Kehadiran Per Kelas -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 16px; border: 1px solid var(--color-hairline);">
            <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700; margin-bottom: 40px;">Data Per Kelas</h2>
            <div style="display: flex; flex-direction: column; gap: 24px;">
                @forelse($kelasBreakdown as $kb)
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <span style="font-weight: 600; min-width: 50px;">{{ $kb->nama }}</span>
                        <div style="flex: 1; height: 2px; background: var(--color-hairline);">
                            <div style="height: 100%; width: {{ $kb->persentase }}%; background: var(--color-ink);"></div>
                        </div>
                        <span style="font-size: 12px; color: var(--color-stone);">{{ $kb->persentase }}%</span>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--color-stone); font-size: 13px;">Menunggu data...</div>
                @endforelse
            </div>
        </div>

        <!-- Alerts -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 16px; border: 1px solid var(--color-ink);">
            <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700; margin-bottom: 32px;">Laporan Kritikal</h2>
            <div style="display: flex; flex-direction: column; gap: 32px;">
                <div>
                    <div style="font-weight: 700; font-size: 15px; margin-bottom: 4px;">Anomali Kehadiran</div>
                    <div style="color: var(--color-slate); font-size: 13px; line-height: 1.5;">Terdapat 5 siswa dengan status Alpha berulang minggu ini.</div>
                    <a href="#" style="color: var(--color-ink); font-size: 11px; text-transform: uppercase; font-weight: 700; text-decoration: none; display: block; margin-top: 12px;">Tindak Lanjut →</a>
                </div>
                <div style="padding-top: 32px; border-top: 1px solid var(--color-hairline);">
                    <div style="font-weight: 700; font-size: 15px; margin-bottom: 4px;">Sesi Belum Lengkap</div>
                    <div style="color: var(--color-slate); font-size: 13px; line-height: 1.5;">Kelas XII-A belum mengunggah rekap presensi jam ke-4.</div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
