@extends('layouts.guru')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Sesi Pengajaran Aktif</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">{{ $sesi->jadwal ? $sesi->jadwal->mapel->nama_mapel : 'Sesi Kelas' }}</h1>
            <div style="display: flex; gap: 24px; align-items: center; margin-top: 16px;">
                <div class="text-meta" style="color: var(--color-ink); font-weight: 700;">KELAS {{ $sesi->kelas }}</div>
                <div class="text-meta" style="color: var(--color-stone); font-weight: 700; text-transform: uppercase;">GURU: {{ auth()->user()->name }}</div>
            </div>
        </div>
    </div>

    <!-- Presence Code Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 700;">Kode Presensi Digital</div>
            @if($sesi->kode_presensi)
                <div style="font-size: 64px; font-weight: 800; letter-spacing: 8px; color: var(--color-ink); line-height: 1;">{{ $sesi->kode_presensi }}</div>
                <div class="text-meta" style="color: var(--color-ink); margin-top: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Status: Aktif &bull; Siswa dapat melakukan scan sekarang</div>
            @else
                <div style="font-size: 32px; font-weight: 400; color: var(--color-stone); font-style: italic; text-transform: uppercase; letter-spacing: var(--tracking-tight);">Sesi Presensi Ditutup</div>
                <div class="text-meta" style="color: var(--color-slate); margin-top: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Silakan generate kode untuk memulai absensi mandiri</div>
            @endif
        </div>
        <div style="display: flex; gap: 16px;">
            @if($sesi->kode_presensi)
                <a href="{{ route('guru.presensi.tampil', $sesi->id) }}" class="btn-ghost" target="_blank" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Mode Proyektor</a>
                <button type="button" class="btn-primary" onclick="openModal('modalStopCode')" style="height: 56px; padding: 0 32px; font-size: 13px;">Tutup Presensi</button>
            @else
                <form action="{{ route('guru.presensi.generate-kode', $sesi->id) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 13px;">Generate Kode</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Danger Zone Card -->
    <div class="card" style="background: #ffffff; padding: 24px 40px; border-radius: 12px; border: 1px solid var(--color-hairline); display: flex; justify-content: flex-end; align-items: center;">
        <button type="button" class="btn-ghost" onclick="openModal('modalEndClass')" style="color: var(--color-primary); font-weight: 700; font-size: 11px; padding: 0 24px; height: 40px;">AKHIRI SESI MENGAJAR &rarr;</button>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="card" style="background: #ffffff; padding: 24px; border: 2px solid var(--color-ink); border-radius: 12px; font-size: 14px; color: var(--color-ink); font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Student Table Card -->
    <div class="card" style="background: #ffffff; padding: 0; border-radius: 12px; border: 1px solid var(--color-hairline); overflow: hidden; margin-bottom: var(--spacing-section);">
        <div style="padding: 32px 40px; border-bottom: 1px solid var(--color-hairline); background: var(--color-surface); display: flex; justify-content: space-between; align-items: center;">
            <div class="text-micro-caps" style="color: var(--color-ink); font-weight: 700;">Daftar Siswa Terdaftar [{{ $siswaKelas->count() }}]</div>
        </div>
        <table class="data-table" style="margin: 0;">
            <thead>
                <tr>
                    <th style="padding-left: 40px;">SISWA</th>
                    <th style="width: 180px;">LOKASI GPS</th>
                    <th style="width: 160px;">STATUS</th>
                    <th style="text-align: right; padding-right: 40px;">VERIFIKASI MANUAL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaKelas as $siswa)
                    @php
                        $presensi = $presensiHariIni->get($siswa->NIS);
                        $status = $presensi ? $presensi->status : null;
                    @endphp
                    <tr>
                        <td style="padding-left: 40px;">
                            <div style="font-weight: 600; color: var(--color-ink);">{{ $siswa->nama_siswa }}</div>
                            <div style="font-size: 11px; color: var(--color-stone); margin-top: 4px;">NIS: {{ $siswa->NIS }}</div>
                        </td>
                        <td>
                            @if($presensi && $presensi->is_dalam_radius !== null)
                                @if($presensi->is_dalam_radius)
                                    <span class="text-meta" style="color: #10b981; font-weight: 700;">&bull; DALAM RADIUS</span>
                                @else
                                    <span class="text-meta" style="color: #ef4444; font-weight: 700;">&bull; LUAR RADIUS</span>
                                @endif
                            @else
                                <span class="text-meta" style="color: var(--color-stone);">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-status {{ $status == 'Hadir' ? 'bs-h' : ($status ? 'bs-a' : '') }}">
                                {{ $status ?? 'BELUM ABSEN' }}
                            </span>
                        </td>
                        <td style="text-align: right; padding-right: 40px;">
                            <form action="{{ route('guru.presensi.update-status', [$sesi->id, $siswa->NIS]) }}" method="POST" style="margin:0; display:flex; gap:8px; justify-content: flex-end;">
                                @csrf
                                <button type="submit" name="status" value="Hadir" class="btn-ghost" style="width: 32px; height: 32px; padding: 0; font-weight: 700; color: #10b981;" title="Hadir">H</button>
                                <button type="submit" name="status" value="Izin" class="btn-ghost" style="width: 32px; height: 32px; padding: 0; font-weight: 700; color: #f59e0b;" title="Izin">I</button>
                                <button type="submit" name="status" value="Sakit" class="btn-ghost" style="width: 32px; height: 32px; padding: 0; font-weight: 700; color: #3b82f6;" title="Sakit">S</button>
                                <button type="submit" name="status" value="Alpa" class="btn-ghost" style="width: 32px; height: 32px; padding: 0; font-weight: 700; color: #ef4444;" title="Alpa">A</button>
                            </form>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 120px; color: var(--color-stone);">Tidak ada siswa ditemukan di kelas ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- Custom Modals -->
<div id="modalStopCode" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card" style="background: #ffffff; padding: 48px; max-width: 480px; width: 90%; border-radius: 12px; border: 1px solid var(--color-ink);">
        <h2 style="font-size: 24px; margin-bottom: 16px; font-weight: 600; letter-spacing: var(--tracking-tight);">Tutup Presensi?</h2>
        <p style="margin-bottom: 32px; color: var(--color-graphite); line-height: 1.5;">Siswa tidak akan bisa melakukan scan kode lagi. Anda dapat membuka kembali sesi presensi dengan generate kode baru.</p>
        <div style="display: flex; gap: 16px;">
            <form action="{{ route('guru.presensi.akhiri', $sesi->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-primary" style="height: 48px; padding: 0 32px;">Ya, Tutup Sesi</button>
            </form>
            <button onclick="closeModal('modalStopCode')" class="btn-ghost" style="height: 48px; padding: 0 32px;">Batal</button>
        </div>
    </div>
</div>

<div id="modalEndClass" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card" style="background: #ffffff; padding: 48px; max-width: 480px; width: 90%; border-radius: 12px; border: 1px solid var(--color-primary);">
        <h2 style="font-size: 24px; margin-bottom: 16px; font-weight: 600; letter-spacing: var(--tracking-tight); color: var(--color-primary);">Akhiri Sesi Mengajar?</h2>
        <p style="margin-bottom: 32px; color: var(--color-graphite); line-height: 1.5;">Sesi mengajar akan berakhir secara permanen untuk hari ini. Pastikan seluruh data presensi telah diverifikasi.</p>
        <div style="display: flex; gap: 16px;">
            <form action="{{ route('guru.presensi.akhiri-kelas', $sesi->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-primary" style="background: var(--color-primary); height: 48px; padding: 0 32px;">Ya, Akhiri Sesi</button>
            </form>
            <button onclick="closeModal('modalEndClass')" class="btn-ghost" style="height: 48px; padding: 0 32px;">Batal</button>
        </div>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('modalStopCode');
            closeModal('modalEndClass');
        }
    });
</script>

@endsection
