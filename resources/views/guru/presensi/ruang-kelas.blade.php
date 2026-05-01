@extends('layouts.guru')

@section('content')

<style>
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 14px;
    }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: 12px;
        color: var(--text2);
        margin-top: 4px;
    }

    .btn-projector {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: rgba(88, 166, 255, 0.15);
        border: 1px solid rgba(88, 166, 255, 0.3);
        color: var(--accent);
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        transition: all .2s;
    }

    .btn-projector:hover {
        background: var(--accent);
        color: var(--navy);
        box-shadow: 0 4px 14px rgba(88, 166, 255, 0.3);
    }

    .btn-generate {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: rgba(63, 185, 80, 0.1);
        border: 1px solid rgba(63, 185, 80, 0.25);
        color: var(--green);
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-generate:hover {
        background: var(--green);
        color: var(--navy);
        box-shadow: 0 4px 14px rgba(63, 185, 80, 0.3);
    }

    .btn-stop-code {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: rgba(227, 179, 65, 0.1);
        border: 1px solid rgba(227, 179, 65, 0.25);
        color: var(--amber);
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-stop-code:hover {
        background: var(--amber);
        color: var(--navy);
        box-shadow: 0 4px 14px rgba(227, 179, 65, 0.3);
    }

    .btn-end-class {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        background: rgba(248, 81, 73, 0.1);
        border: 1px solid rgba(248, 81, 73, 0.2);
        color: var(--red);
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-end-class:hover {
        background: var(--red);
        color: #fff;
        box-shadow: 0 4px 14px rgba(248, 81, 73, 0.3);
    }

    /* ── Status Banner ── */
    .code-status-banner {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .code-status-banner.active {
        background: rgba(63, 185, 80, 0.06);
        border-color: rgba(63, 185, 80, 0.2);
    }

    .code-status-banner.inactive {
        background: rgba(227, 179, 65, 0.06);
        border-color: rgba(227, 179, 65, 0.2);
    }

    .code-status-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .code-status-icon.active {
        background: rgba(63, 185, 80, 0.15);
        color: var(--green);
    }

    .code-status-icon.inactive {
        background: rgba(227, 179, 65, 0.15);
        color: var(--amber);
    }

    .code-status-info { flex: 1; }

    .code-status-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text1);
        margin-bottom: 2px;
    }

    .code-status-desc {
        font-size: 11.5px;
        color: var(--text2);
    }

    .code-display {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: 0.12em;
        color: var(--green);
        background: rgba(63, 185, 80, 0.1);
        padding: 6px 16px;
        border-radius: 8px;
    }

    /* ── Action Bar ── */
    .action-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .action-bar-separator {
        width: 1px;
        background: var(--glass-border);
        margin: 4px 4px;
    }

    /* ── Flash Messages ── */
    .flash-msg {
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 18px;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: fi .3s ease;
    }

    .flash-msg.success {
        background: rgba(63, 185, 80, 0.1);
        border: 1px solid rgba(63, 185, 80, 0.25);
        color: var(--green);
    }

    .flash-msg.error {
        background: rgba(248, 81, 73, 0.1);
        border: 1px solid rgba(248, 81, 73, 0.25);
        color: var(--red);
    }

    /* ── Table ── */
    .card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        overflow: hidden;
    }

    .table-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text1);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table th {
        font-size: 10.5px;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text3);
        font-weight: 600;
        padding: 12px 20px;
        text-align: left;
        border-bottom: 1px solid var(--glass-border);
        background: var(--navy3);
    }

    .custom-table td {
        padding: 14px 20px;
        font-size: 13px;
        border-bottom: 1px solid var(--glass-border);
        vertical-align: middle;
    }

    .custom-table tr:hover td {
        background: var(--glass-hover);
    }

    .student-name {
        color: var(--text1);
        font-weight: 600;
        margin-bottom: 2px;
    }

    .student-nis {
        font-size: 11px;
        color: var(--text2);
    }

    .status-badge {
        display: inline-block;
        font-size: 10.5px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .sb-hadir { background: rgba(63, 185, 80, 0.15); color: var(--green); }
    .sb-izin  { background: rgba(227, 179, 65, 0.15); color: var(--amber); }
    .sb-sakit { background: rgba(188, 140, 255, 0.15); color: var(--purple); }
    .sb-alpha { background: rgba(248, 81, 73, 0.15); color: var(--red); }
    .sb-none  { background: var(--navy4); color: var(--text2); }
    .sb-ditolak { background: rgba(248, 81, 73, 0.15); color: var(--red); }
    .sb-belum-hadir { background: rgba(139, 148, 158, 0.15); color: var(--text3); }

    .gps-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .gps-badge.in {
        background: rgba(63, 185, 80, 0.1);
        color: var(--green);
    }

    .gps-badge.out {
        background: rgba(248, 81, 73, 0.1);
        color: var(--red);
    }

    .gps-badge.na {
        color: var(--text3);
        font-weight: 400;
    }

    .action-group {
        display: flex;
        gap: 6px;
    }

    .btn-act {
        border: 1px solid var(--glass-border);
        background: var(--navy3);
        color: var(--text2);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
    }

    .btn-act:hover {
        background: var(--glass-hover);
        color: var(--text1);
    }

    .btn-act.h:hover { border-color: var(--green); color: var(--green); }
    .btn-act.i:hover { border-color: var(--amber); color: var(--amber); }
    .btn-act.s:hover { border-color: var(--purple); color: var(--purple); }
    .btn-act.a:hover { border-color: var(--red); color: var(--red); }

    /* ── Modal ── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 999;
        place-items: center;
        animation: fadeIn .2s ease;
    }

    .modal-overlay.show {
        display: grid;
    }

    .modal-box {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 28px 32px;
        max-width: 420px;
        width: 90%;
        text-align: center;
        animation: slideUp .25s ease;
    }

    .modal-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        font-size: 22px;
        margin: 0 auto 16px;
    }

    .modal-icon.warn {
        background: rgba(227, 179, 65, 0.15);
        color: var(--amber);
    }

    .modal-icon.danger {
        background: rgba(248, 81, 73, 0.15);
        color: var(--red);
    }

    .modal-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px;
        font-weight: 800;
        color: var(--text1);
        margin-bottom: 6px;
    }

    .modal-desc {
        font-size: 13px;
        color: var(--text2);
        line-height: 1.5;
        margin-bottom: 24px;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .modal-btn {
        padding: 9px 22px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .2s;
        border: none;
    }

    .modal-btn.cancel {
        background: var(--navy3);
        color: var(--text2);
        border: 1px solid var(--glass-border);
    }

    .modal-btn.cancel:hover {
        background: var(--glass-hover);
        color: var(--text1);
    }

    .modal-btn.confirm-warn {
        background: var(--amber);
        color: var(--navy);
    }

    .modal-btn.confirm-warn:hover {
        box-shadow: 0 4px 14px rgba(227, 179, 65, 0.3);
    }

    .modal-btn.confirm-danger {
        background: var(--red);
        color: #fff;
    }

    .modal-btn.confirm-danger:hover {
        box-shadow: 0 4px 14px rgba(248, 81, 73, 0.3);
    }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="flash-msg success fi">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="flash-msg error fi">
        <i class="bi bi-x-circle-fill"></i>
        {{ session('error') }}
    </div>
@endif

{{-- Header --}}
<div class="page-header fi">
    <div>
        <div class="page-title">Ruang Kelas: {{ $sesi->jadwal ? $sesi->jadwal->mapel->nama_mapel . ' (' . $sesi->kelas . ')' : $sesi->kelas }}</div>
        <div class="page-subtitle">Sesi Aktif | Guru: {{ auth()->user()->name }}</div>
    </div>

    @if($sesi->kode_presensi)
        <a href="{{ route('guru.presensi.tampil', $sesi->id) }}" class="btn-projector" target="_blank">
            <i class="bi bi-projector"></i> Tampilkan Kode Presensi
        </a>
    @endif
</div>

{{-- Code Status Banner --}}
@if($sesi->kode_presensi)
    <div class="code-status-banner active fi d1">
        <div class="code-status-icon active">
            <i class="bi bi-qr-code"></i>
        </div>
        <div class="code-status-info">
            <div class="code-status-title">Kode Presensi Aktif</div>
            <div class="code-status-desc">Siswa dapat menggunakan kode ini untuk melakukan presensi mandiri</div>
        </div>
        <div class="code-display">{{ $sesi->kode_presensi }}</div>
    </div>
@else
    <div class="code-status-banner inactive fi d1">
        <div class="code-status-icon inactive">
            <i class="bi bi-slash-circle"></i>
        </div>
        <div class="code-status-info">
            <div class="code-status-title">Kode Presensi Tidak Aktif</div>
            <div class="code-status-desc">Sesi presensi ditutup. Generate kode baru agar siswa bisa absen kembali.</div>
        </div>
    </div>
@endif

{{-- Action Bar --}}
<div class="action-bar fi d2">
    @if($sesi->kode_presensi)
        {{-- Kode aktif: bisa tutup sesi presensi --}}
        <button type="button" class="btn-stop-code" onclick="openModal('modalStopCode')">
            <i class="bi bi-pause-circle"></i> Tutup Sesi Presensi
        </button>
    @else
        {{-- Kode tidak aktif: bisa generate baru --}}
        <form action="{{ route('guru.presensi.generate-kode', $sesi->id) }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn-generate">
                <i class="bi bi-arrow-repeat"></i> Generate Kode Baru
            </button>
        </form>
    @endif

    <div class="action-bar-separator"></div>

    {{-- Akhiri kelas (selalu tersedia) --}}
    <button type="button" class="btn-end-class" onclick="openModal('modalEndClass')">
        <i class="bi bi-power"></i> Akhiri Kelas
    </button>
</div>

{{-- Student Table --}}
<div class="card fi d3">
    <div class="table-header">
        <div class="table-title">
            <i class="bi bi-people-fill" style="color:var(--accent)"></i> Daftar Siswa
        </div>
    </div>
    <table class="custom-table">
        <thead>
            <tr>
                <th>Siswa</th>
                <th>Lokasi GPS</th>
                <th>Status Saat Ini</th>
                <th>Set Manual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswaKelas as $siswa)
                @php
                    $presensi = $presensiHariIni->get($siswa->NIS);
                    $status = $presensi ? $presensi->status : null;
                @endphp
                <tr>
                    <td>
                        <div class="student-name">{{ $siswa->nama_siswa }}</div>
                        <div class="student-nis">NIS: {{ $siswa->NIS }}</div>
                    </td>
                    <td>
                        @if($presensi && $presensi->is_dalam_radius !== null)
                            @if($presensi->is_dalam_radius)
                                <span class="gps-badge in"><i class="bi bi-geo-alt-fill"></i> Dalam Area</span>
                            @else
                                <span class="gps-badge out" title="Lat: {{ $presensi->latitude }}, Lng: {{ $presensi->longitude }}">
                                    <i class="bi bi-geo-alt-fill"></i> Luar Area
                                </span>
                            @endif
                        @else
                            <span class="gps-badge na">—</span>
                        @endif
                    </td>
                    <td>
                        @if($status == 'Hadir')
                            <span class="status-badge sb-hadir">Hadir</span>
                        @elseif($status == 'Izin')
                            <span class="status-badge sb-izin">Izin</span>
                        @elseif($status == 'Sakit')
                            <span class="status-badge sb-sakit">Sakit</span>
                        @elseif($status == 'Alpa' || $status == 'Alfa' || $status == 'Alpha')
                            <span class="status-badge sb-alpha">Alpha</span>
                        @elseif($status == 'Ditolak')
                            <span class="status-badge sb-ditolak"><i class="bi bi-geo-alt-fill" style="font-size:10px"></i> Ditolak (Lokasi)</span>
                        @elseif($status == 'Belum Hadir')
                            <span class="status-badge sb-belum-hadir">Belum Hadir</span>
                        @else
                            <span class="status-badge sb-none">Belum Absen</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-group">
                            <form action="{{ route('guru.presensi.update-status', [$sesi->id, $siswa->NIS]) }}" method="POST" style="margin:0; display:flex; gap:6px;">
                                @csrf
                                <button type="submit" name="status" value="Hadir" class="btn-act h" title="Set Hadir">Hadir</button>
                                <button type="submit" name="status" value="Izin" class="btn-act i" title="Set Izin">Izin</button>
                                <button type="submit" name="status" value="Sakit" class="btn-act s" title="Set Sakit">Sakit</button>
                                <button type="submit" name="status" value="Alpa" class="btn-act a" title="Set Alpha">Alpha</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding: 40px; color: var(--text3);">
                        Tidak ada siswa yang terdata di kelas ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal: Tutup Sesi Presensi --}}
<div class="modal-overlay" id="modalStopCode">
    <div class="modal-box">
        <div class="modal-icon warn">
            <i class="bi bi-pause-circle-fill"></i>
        </div>
        <div class="modal-title">Tutup Sesi Presensi?</div>
        <div class="modal-desc">
            Kode presensi saat ini akan dinonaktifkan dan tidak bisa digunakan lagi oleh siswa.
            <br><strong>Kelas tetap aktif</strong> — Anda bisa generate kode baru kapan saja.
        </div>
        <div class="modal-actions">
            <button class="modal-btn cancel" onclick="closeModal('modalStopCode')">Batal</button>
            <form action="{{ route('guru.presensi.akhiri', $sesi->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="modal-btn confirm-warn">Ya, Tutup Sesi</button>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Akhiri Kelas --}}
<div class="modal-overlay" id="modalEndClass">
    <div class="modal-box">
        <div class="modal-icon danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="modal-title">Akhiri Sesi Mengajar?</div>
        <div class="modal-desc">
            Tindakan ini akan <strong>mengakhiri sesi mengajar Anda</strong> dan <strong>menghapus data presensi</strong> khusus untuk sesi ini pada kelas {{ $sesi->kelas }}.
            <br><br>Data presensi pada jam pelajaran lain (guru lain) tidak akan terpengaruh.
        </div>
        <div class="modal-actions">
            <button class="modal-btn cancel" onclick="closeModal('modalEndClass')">Batal</button>
            <form action="{{ route('guru.presensi.akhiri-kelas', $sesi->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="modal-btn confirm-danger">Ya, Akhiri Kelas</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    // Close modal on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.show').forEach(m => m.classList.remove('show'));
        }
    });
</script>

@endsection
