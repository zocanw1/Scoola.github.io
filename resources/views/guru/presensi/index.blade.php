@extends('layouts.guru')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Log Akademik</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Data Presensi</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Riwayat kehadiran siswa pada sesi mata pelajaran Anda. Pantau dan verifikasi kehadiran secara real-time.
            </p>
        </div>
    </div>

    <!-- Actions & Stats Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid var(--color-hairline); display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 48px;">
            <div>
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 8px; font-weight: 700;">Total Entri</div>
                <div style="font-size: 40px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tight);">{{ $presensi->count() }}</div>
            </div>
        </div>
        <div style="display: flex; gap: 16px;">
            <a href="{{ route('guru.presensi.create') }}" class="btn-primary" style="text-decoration: none; height: 48px; padding: 0 32px; display: flex; align-items: center;">+ Tambah Presensi</a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="card" style="background: #ffffff; padding: 24px; border: 2px solid var(--color-ink); border-radius: 12px; font-size: 14px; color: var(--color-ink); font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table Card -->
    <div class="card" style="background: #ffffff; padding: 0; border-radius: 12px; border: 1px solid var(--color-hairline); overflow: hidden; margin-bottom: var(--spacing-section);">
        <table class="data-table" style="margin: 0;">
            <thead>
                <tr>
                    <th style="padding-left: 40px; width: 100px;">KODE</th>
                    <th style="width: 140px;">TANGGAL</th>
                    <th>JADWAL</th>
                    <th>SISWA</th>
                    <th style="width: 120px;">JAM</th>
                    <th style="width: 120px;">STATUS</th>
                    <th style="text-align: right; padding-right: 40px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensi as $p)
                <tr>
                    <td style="padding-left: 40px; color: var(--color-stone); font-family: monospace;">{{ $p->kd_presensi }}</td>
                    <td style="color: var(--color-slate);">{{ $p->tanggal }}</td>
                    <td style="font-weight: 600;">{{ $p->jadwal->kd_jp ?? '-' }}</td>
                    <td>
                        <div style="font-weight: 600; color: var(--color-ink);">{{ $p->siswa->nama_siswa ?? '-' }}</div>
                        <div style="font-size: 11px; color: var(--color-stone); margin-top: 4px;">NIS: {{ $p->siswa->NIS ?? '-' }}</div>
                    </td>
                    <td style="color: var(--color-slate);">{{ $p->jam_masuk }}</td>
                    <td>
                        <span class="badge-status {{ $p->status == 'Hadir' ? 'bs-h' : 'bs-a' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td style="text-align: right; padding-right: 40px;">
                        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('guru.presensi.edit', $p->kd_presensi) }}" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; text-decoration: none; display: inline-flex; align-items: center;">Edit</a>
                            <form action="{{ route('guru.presensi.destroy', $p->kd_presensi) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus?')" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; color: var(--color-primary);">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 120px; color: var(--color-stone);">Belum ada data presensi terekam.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
