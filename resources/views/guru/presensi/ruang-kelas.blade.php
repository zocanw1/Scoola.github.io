@extends('layouts.guru')

@section('content')

<style>
    .code-panel {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 24px;
        align-items: center;
    }

    .code-actions {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .presence-code {
        color: var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: clamp(42px, 8vw, 76px);
        line-height: 1;
        letter-spacing: .14em;
        text-shadow: 3px 3px 0 var(--cyber);
        word-break: break-all;
    }

    .status-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .status-actions .mp-btn-secondary {
        width: 38px;
        min-height: 38px;
        padding: 0;
        font-size: 12px;
    }

    .attendance-mobile-list {
        display: grid;
        gap: 14px;
    }

    .student-presence-card {
        display: grid;
        gap: 14px;
        padding: 18px;
        border: 3px solid var(--midnight);
        border-radius: 16px;
        background: var(--mochi);
        box-shadow: 4px 4px 0 var(--midnight);
    }

    .student-presence-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .presence-meta-grid {
        display: grid;
        gap: 10px;
    }

    .manual-action-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .manual-action-grid .mp-btn-secondary {
        width: 100%;
        min-height: 48px;
        font-size: 14px;
    }

    .mp-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(30, 27, 41, .55);
        backdrop-filter: blur(5px);
    }

    .mp-modal .mp-card {
        width: min(520px, 100%);
    }

    @media (max-width: 900px) {
        .code-panel { grid-template-columns: 1fr; }

        .code-panel > div:last-child {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .code-actions {
            width: 100%;
        }

        .code-actions .mp-btn,
        .code-actions .mp-btn-secondary {
            width: 100%;
        }
    }

    @media (max-width: 640px) {
        .student-presence-head {
            flex-direction: column;
        }

        .manual-action-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .mp-modal {
            align-items: flex-end;
            padding: 12px;
        }

        .mp-modal .mp-card {
            width: 100%;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            box-shadow: 0 -6px 0 var(--midnight);
        }
    }
</style>

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">Sesi Aktif</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-broadcast-pin"></i> Kelas {{ $sesi->kelas }}</span>
                <h1 class="mp-title">{{ $sesi->jadwal ? $sesi->jadwal->mapel->nama_mapel : 'Sesi Kelas' }}</h1>
                <p class="mp-description">
                    Guru: {{ auth()->user()->name }}. Verifikasi kehadiran siswa secara otomatis lewat kode, atau ubah status manual bila diperlukan.
                </p>
            </div>
        </section>
    </div>

    @if(session('success'))
        <div class="mp-alert">
            <strong>Berhasil.</strong> {{ session('success') }}
        </div>
    @endif

    @if($otherActiveSessions->count() > 0)
        <div class="mp-alert danger">
            <strong>Perhatian.</strong> Ada sesi lain yang aktif di kelas ini: {{ $otherActiveSessions->map(fn($s) => $s->guru->name ?? $s->guru->nama_guru ?? 'Guru lain')->join(', ') }}.
        </div>
    @endif

    <section class="mp-card">
        <div class="code-panel">
            <div>
                <span class="mp-label">Kode Presensi Digital</span>
                @if($sesi->kode_presensi)
                    <div class="presence-code">{{ $sesi->kode_presensi }}</div>
                    <div style="margin-top:18px;">
                        <span class="mp-badge" style="background:var(--cyber);">Aktif</span>
                        <span class="mp-badge" style="background:var(--gold); margin-left:8px;">Siswa dapat absen</span>
                    </div>
                @else
                    <h2 style="margin:12px 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:34px;">Presensi Ditutup</h2>
                    <p style="margin:0; font-weight:900;">Generate kode baru untuk membuka absensi mandiri lagi.</p>
                @endif
            </div>

            <div class="code-actions">
                @if($sesi->kode_presensi)
                    <a href="{{ route('guru.presensi.tampil', $sesi->id) }}" class="mp-btn-secondary" target="_blank">
                        <i class="bi bi-projector"></i> Mode Proyektor
                    </a>
                    <button type="button" class="mp-btn" onclick="openModal('modalStopCode')">
                        <i class="bi bi-pause-circle-fill"></i> Tutup Presensi
                    </button>
                @else
                    <form action="{{ route('guru.presensi.generate-kode', $sesi->id) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="mp-btn">
                            <i class="bi bi-arrow-clockwise"></i> Generate Kode
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>

    <section class="mp-card mp-card-gold" style="padding:22px;">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:18px; flex-wrap:wrap;">
            <div style="font-weight:900;">Akhiri sesi mengajar setelah semua status siswa diverifikasi.</div>
            <button type="button" class="mp-btn-secondary" onclick="openModal('modalEndClass')">
                <i class="bi bi-stop-circle-fill"></i> Akhiri Sesi Mengajar
            </button>
        </div>
    </section>

    <section class="mp-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:24px;">
            <div>
                <span class="mp-label">Daftar Siswa Terdaftar</span>
                <h2 style="margin:8px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:30px;">{{ $siswaKelas->count() }} Siswa</h2>
            </div>
            <span class="mp-badge" style="background:var(--cyber);">Kelas {{ $sesi->kelas }}</span>
        </div>

        <div class="mp-table-card mp-desktop-only">
            <div class="mp-table-wrap">
                <table class="mp-table data-table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Lokasi GPS</th>
                            <th>Status</th>
                            <th style="text-align:right;">Verifikasi Manual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaKelas as $siswa)
                            @php
                                $presensi = $presensiHariIni->get($siswa->NIS);
                                $status = $presensi ? $presensi->status : null;
                            @endphp
                            <tr>
                                <td data-label="Siswa">
                                    <div>{{ $siswa->nama_siswa }}</div>
                                    <div style="font-size:12px; color:var(--cosmo); margin-top:4px;">NIS: {{ $siswa->NIS }}</div>
                                </td>
                                <td data-label="Lokasi GPS">
                                    @if($presensi && $presensi->is_dalam_radius !== null)
                                        @if($presensi->is_dalam_radius)
                                            <span class="mp-badge" style="background:var(--cyber);">Dalam radius</span>
                                        @else
                                            <span class="mp-badge" style="background:var(--sakura);">Luar radius</span>
                                        @endif
                                    @else
                                        <span class="mp-badge" style="background:var(--white);">Belum ada</span>
                                    @endif
                                </td>
                                <td data-label="Status">
                                    <span class="mp-badge" style="background:{{ $status == 'Hadir' ? 'var(--cyber)' : ($status ? 'var(--sakura)' : 'var(--gold)') }};">
                                        {{ $status ?? 'Belum Absen' }}
                                    </span>
                                </td>
                                <td data-label="Verifikasi Manual" style="text-align:right;">
                                    <form action="{{ route('guru.presensi.update-status', [$sesi->id, $siswa->NIS]) }}" method="POST" class="status-actions" style="margin:0;">
                                        @csrf
                                        <button type="submit" name="status" value="Hadir" class="mp-btn-secondary" title="Hadir">H</button>
                                        <button type="submit" name="status" value="Izin" class="mp-btn-secondary" title="Izin">I</button>
                                        <button type="submit" name="status" value="Sakit" class="mp-btn-secondary" title="Sakit">S</button>
                                        <button type="submit" name="status" value="Alpa" class="mp-btn-secondary" title="Alpa">A</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:70px;">Tidak ada siswa ditemukan di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="attendance-mobile-list mp-mobile-only">
            @forelse($siswaKelas as $siswa)
                @php
                    $presensi = $presensiHariIni->get($siswa->NIS);
                    $status = $presensi ? $presensi->status : null;
                @endphp
                <section class="student-presence-card">
                    <div class="student-presence-head">
                        <div>
                            <div style="font-family:'Fredoka One', cursive; font-size:22px; color:var(--midnight); line-height:1.1;">{{ $siswa->nama_siswa }}</div>
                            <div style="margin-top:6px; font-size:12px; font-weight:900; color:var(--cosmo);">NIS {{ $siswa->NIS }}</div>
                        </div>
                        <span class="mp-badge" style="background:{{ $status == 'Hadir' ? 'var(--cyber)' : ($status ? 'var(--sakura)' : 'var(--gold)') }};">
                            {{ $status ?? 'Belum Absen' }}
                        </span>
                    </div>

                    <div class="presence-meta-grid">
                        <div style="display:flex; justify-content:space-between; gap:12px; align-items:center;">
                            <span class="mp-label" style="margin:0;">GPS</span>
                            @if($presensi && $presensi->is_dalam_radius !== null)
                                @if($presensi->is_dalam_radius)
                                    <span class="mp-badge" style="background:var(--cyber);">Dalam radius</span>
                                @else
                                    <span class="mp-badge" style="background:var(--sakura);">Luar radius</span>
                                @endif
                            @else
                                <span class="mp-badge" style="background:var(--white);">Belum ada</span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('guru.presensi.update-status', [$sesi->id, $siswa->NIS]) }}" method="POST" style="margin:0;">
                        @csrf
                        <div class="manual-action-grid">
                            <button type="submit" name="status" value="Hadir" class="mp-btn-secondary">Hadir</button>
                            <button type="submit" name="status" value="Izin" class="mp-btn-secondary">Izin</button>
                            <button type="submit" name="status" value="Sakit" class="mp-btn-secondary">Sakit</button>
                            <button type="submit" name="status" value="Alpa" class="mp-btn-secondary">Alpa</button>
                        </div>
                    </form>
                </section>
            @empty
                <div class="mp-empty-state">
                    <div style="font-family:'Fredoka One', cursive; font-size:24px; color:var(--midnight);">Belum ada siswa</div>
                    <p style="margin:10px 0 0; font-weight:800;">Tidak ada siswa ditemukan di kelas ini.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<div id="modalStopCode" class="mp-modal">
    <div class="mp-card">
        <span class="mp-badge" style="background:var(--gold);">Konfirmasi</span>
        <h2 style="margin:18px 0 12px; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:30px;">Tutup Presensi?</h2>
        <p style="margin:0 0 26px; font-weight:900; line-height:1.6;">Siswa tidak bisa menggunakan kode ini lagi. Anda masih bisa generate kode baru selama sesi kelas aktif.</p>
        <div class="mp-actions" style="margin-top:0;">
            <form action="{{ route('guru.presensi.akhiri', $sesi->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="mp-btn">Ya, Tutup</button>
            </form>
            <button onclick="closeModal('modalStopCode')" class="mp-btn-secondary">Batal</button>
        </div>
    </div>
</div>

<div id="modalEndClass" class="mp-modal">
    <div class="mp-card">
        <span class="mp-badge" style="background:var(--sakura);">Akhiri Kelas</span>
        <h2 style="margin:18px 0 12px; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:30px;">Akhiri Sesi Mengajar?</h2>
        <p style="margin:0 0 26px; font-weight:900; line-height:1.6;">Sesi mengajar akan selesai permanen untuk hari ini. Pastikan seluruh data presensi sudah benar.</p>
        <div class="mp-actions" style="margin-top:0;">
            <form action="{{ route('guru.presensi.akhiri-kelas', $sesi->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="mp-btn">Ya, Akhiri</button>
            </form>
            <button onclick="closeModal('modalEndClass')" class="mp-btn-secondary">Batal</button>
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
