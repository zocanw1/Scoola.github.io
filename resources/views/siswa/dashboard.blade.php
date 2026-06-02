@extends('layouts.siswa')

@section('content')

<style>
    .student-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.65fr) minmax(320px, .95fr);
        gap: 32px;
        align-items: start;
    }

    .hero-topline {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .otp-row {
        display: grid;
        grid-template-columns: repeat(6, minmax(42px, 1fr));
        gap: 12px;
        margin: 26px 0 18px;
    }

    .mp-page .otp-input {
        width: 100% !important;
        aspect-ratio: 1 / 1.08;
        min-height: 58px;
        padding: 0 !important;
        text-align: center;
        border: 4px solid var(--midnight) !important;
        border-radius: 14px !important;
        background: var(--mochi) !important;
        box-shadow: 5px 5px 0 var(--midnight) !important;
        color: var(--midnight) !important;
        font-family: 'Fredoka One', cursive !important;
        font-size: clamp(24px, 4vw, 34px) !important;
        line-height: 1;
        text-transform: uppercase;
    }

    .mp-page .otp-input:focus {
        background: var(--white) !important;
        border-color: var(--cosmo) !important;
        transform: translate(-2px, -2px) !important;
        box-shadow: 7px 7px 0 var(--midnight) !important;
    }

    .gps-shell {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        margin-top: 18px;
        border: 3px solid var(--midnight);
        border-radius: 16px;
        background: var(--mochi);
        box-shadow: 4px 4px 0 var(--midnight);
    }

    .gps-shell[data-gps-state="success"] {
        background: #ddfffb;
    }

    .gps-shell[data-gps-state="error"],
    .gps-shell[data-gps-state="denied"],
    .gps-shell[data-gps-state="timeout"] {
        background: #fff0ef;
    }

    .gps-pill {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        border: 3px solid var(--midnight);
        border-radius: 999px;
        background: var(--gold);
        box-shadow: 3px 3px 0 var(--midnight);
        color: var(--midnight);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .gps-dot {
        width: 12px;
        height: 12px;
        border: 2px solid var(--midnight);
        border-radius: 999px;
        background: var(--sakura);
    }

    .gps-copy {
        display: grid;
        gap: 6px;
        min-width: 0;
    }

    .gps-copy strong {
        color: var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: 18px;
        line-height: 1.1;
    }

    .gps-copy p {
        margin: 0;
        color: var(--midnight);
        font-size: 13px;
        font-weight: 800;
        line-height: 1.45;
    }

    .retry-gps-btn {
        min-width: 132px;
        min-height: 46px;
        border: 3px solid var(--midnight);
        border-radius: 12px;
        background: var(--white);
        box-shadow: 4px 4px 0 var(--midnight);
        color: var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: 12px;
        cursor: pointer;
    }

    .retry-gps-btn[hidden] {
        display: none;
    }

    .gps-permission-backdrop {
        position: fixed;
        inset: 0;
        z-index: 90;
        display: grid;
        place-items: center;
        padding: 22px;
        background: rgba(30, 27, 41, .58);
        backdrop-filter: blur(8px);
    }

    .gps-permission-backdrop[hidden] {
        display: none;
    }

    .gps-permission-dialog {
        width: min(100%, 430px);
        border: 4px solid var(--midnight);
        border-radius: 22px;
        background: var(--white);
        box-shadow: 8px 8px 0 var(--midnight);
        color: var(--midnight);
        overflow: hidden;
    }

    .gps-permission-head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px;
        border-bottom: 3px solid var(--midnight);
        background: var(--gold);
    }

    .gps-permission-icon {
        width: 54px;
        height: 54px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        border: 3px solid var(--midnight);
        border-radius: 16px;
        background: var(--cyber);
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 25px;
    }

    .gps-permission-head h3 {
        margin: 0;
        font-family: 'Fredoka One', cursive;
        font-size: 22px;
        line-height: 1.12;
    }

    .gps-permission-body {
        display: grid;
        gap: 16px;
        padding: 20px;
    }

    .gps-permission-body p {
        margin: 0;
        font-weight: 850;
        line-height: 1.55;
    }

    .gps-permission-actions {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .gps-permission-help {
        display: grid;
        gap: 8px;
        padding: 14px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--mochi);
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 13px;
        font-weight: 850;
        line-height: 1.45;
    }

    .gps-permission-help[hidden] {
        display: none;
    }

    .gps-permission-help ol {
        margin: 0;
        padding-left: 20px;
    }

    .gps-permission-help li + li {
        margin-top: 4px;
    }

    .gps-permission-btn {
        min-height: 48px;
        border: 3px solid var(--midnight);
        border-radius: 13px;
        background: var(--white);
        box-shadow: 4px 4px 0 var(--midnight);
        color: var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: 12px;
        cursor: pointer;
    }

    .gps-permission-btn.primary {
        background: var(--sakura);
    }

    .gps-permission-btn:disabled {
        opacity: .7;
        cursor: wait;
        transform: none;
    }

    .submit-shell {
        margin-top: 18px;
    }

    .student-profile {
        display: flex;
        align-items: center;
        gap: 18px;
        padding-bottom: 26px;
        margin-bottom: 26px;
        border-bottom: 3px dashed var(--midnight);
    }

    .student-avatar {
        width: 70px;
        height: 70px;
        flex: 0 0 auto;
        display: grid;
        place-items: center;
        border: 4px solid var(--midnight);
        border-radius: 18px;
        background: var(--cyber);
        box-shadow: 5px 5px 0 var(--midnight);
        color: var(--midnight);
        font-size: 30px;
    }

    .history-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 18px 0;
        border-bottom: 3px dashed rgba(30, 27, 41, .18);
    }

    .history-item:last-child { border-bottom: 0; }

    .history-stack {
        display: grid;
        gap: 12px;
        margin-top: 14px;
    }

    .history-item {
        padding: 16px 18px;
        border: 3px solid var(--midnight);
        border-radius: 16px;
        background: var(--mochi);
        box-shadow: 4px 4px 0 var(--midnight);
    }

    @media (max-width: 980px) {
        .student-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 540px) {
        .hero-topline,
        .gps-shell {
            flex-direction: column;
        }

        .otp-row {
            gap: 8px;
            margin-bottom: 14px;
        }

        .student-profile {
            align-items: flex-start;
            flex-direction: column;
        }

        .retry-gps-btn {
            width: 100%;
        }

        .gps-permission-dialog {
            border-radius: 18px;
            box-shadow: 6px 6px 0 var(--midnight);
        }

        .gps-permission-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">( &gt;= siap hadir )</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <div class="hero-topline">
                    <div>
                        <span class="mp-kicker"><i class="bi bi-stars"></i> Panel Siswa</span>
                        <h1 class="mp-title">Presensi Harian</h1>
                    </div>
                    <span class="mp-badge" style="background:var(--gold);">Android friendly</span>
                </div>
                <p class="mp-description">Masukkan kode 6 karakter dari pengajar. Sistem akan mengecek lokasi perangkat sebelum menyimpan kehadiran kamu.</p>
            </div>
        </section>
    </div>

    <div class="student-grid">
        <div style="display:flex; flex-direction:column; gap:28px;">
            @if(session('success'))
                <div class="mp-alert">
                    <strong>Berhasil.</strong> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mp-alert danger">
                    <strong>Perlu dicek.</strong> {{ session('error') }}
                </div>
            @endif

            <section class="mp-form-card" id="kode-presensi">
                <div style="display:flex; justify-content:space-between; gap:20px; align-items:flex-start; flex-wrap:wrap;">
                    <div>
                        <span class="mp-label">Kode Sesi</span>
                        <h2 style="margin:8px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:32px; line-height:1.1;">Masukkan Kode</h2>
                    </div>
                </div>

                <div id="gpsShell" class="gps-shell" data-gps-state="loading">
                    <div class="gps-copy">
                        <div id="gpsStatus" class="gps-pill">
                            <span id="gpsIcon" class="gps-dot"></span>
                            <span id="gpsText">Meminta lokasi</span>
                        </div>
                        <strong id="gpsTitle">Cek posisi perangkat</strong>
                        <p id="gpsHint">Izinkan akses lokasi browser agar presensi bisa dikirim tanpa hambatan.</p>
                    </div>
                    <button type="button" id="retryGpsBtn" class="retry-gps-btn" hidden>Coba Lagi</button>
                </div>

                <form action="{{ route('siswa.presensi.store') }}" method="POST" id="otpForm">
                    @csrf
                    <input type="hidden" name="kode_presensi" id="realKode">
                    <input type="hidden" name="latitude" id="inputLat">
                    <input type="hidden" name="longitude" id="inputLng">
                    <input type="hidden" name="accuracy" id="inputAccuracy">

                    <div class="otp-row" id="inputs">
                        @for($i=0; $i<6; $i++)
                            <input type="text" maxlength="1" autocomplete="one-time-code" inputmode="text" autocapitalize="characters" spellcheck="false" pattern="[A-Za-z0-9]*" enterkeyhint="done" class="otp-input" placeholder="-">
                        @endfor
                    </div>

                    <div class="submit-shell mp-sticky-action">
                        <button type="submit" id="submitBtn" disabled class="mp-btn" style="width:100%; min-height:58px; font-size:15px;">
                            <i class="bi bi-check2-circle"></i> Konfirmasi Kehadiran
                        </button>
                    </div>
                </form>
            </section>

            <section class="mp-card mp-card-gold">
                <span class="mp-badge" style="background:var(--white);">Catatan</span>
                <p style="margin:18px 0 0; font-weight:900; line-height:1.65;">
                    Gunakan kode yang sedang aktif di layar guru. Jika lokasi gagal terdeteksi, izinkan akses lokasi dari browser lalu coba lagi.
                </p>
            </section>
        </div>

        <aside class="mp-card" id="aktivitas-terakhir">
            <div class="student-profile">
                <div class="student-avatar"><i class="bi bi-person-fill"></i></div>
                <div style="min-width:0;">
                    <span class="mp-label">Identitas Siswa</span>
                    <h2 style="margin:8px 0 6px; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:26px; line-height:1.15;">{{ $siswa->nama_siswa ?? 'Siswa' }}</h2>
                    <div style="font-weight:900; color:var(--cosmo);">{{ $siswa->kelas ?? '-' }} / {{ $siswa->NIS ?? '-' }}</div>
                </div>
            </div>

            <span class="mp-label">Aktivitas Terakhir</span>

            @if($riwayat && $riwayat->count() > 0)
                <div class="history-stack">
                    @foreach($riwayat->take(5) as $r)
                        <div class="history-item">
                            <div>
                                <div style="font-weight:900; color:var(--midnight);">{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}</div>
                                <div style="font-size:12px; font-weight:900; color:var(--cosmo); margin-top:3px;">{{ $r->jam_masuk }}</div>
                            </div>
                            <span class="mp-badge" style="background:{{ $r->status == 'Hadir' ? 'var(--cyber)' : 'var(--sakura)' }};">
                                {{ $r->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mp-empty-state" style="margin-top:18px; box-shadow:5px 5px 0 var(--midnight);">
                    <div style="font-family:'Fredoka One', cursive; font-size:22px; color:var(--midnight);">Belum ada riwayat</div>
                    <p style="margin:10px 0 0; font-weight:800;">Presensi pertama kamu akan tampil di sini.</p>
                </div>
            @endif
        </aside>
    </div>

    <div id="gpsPermissionBackdrop" class="gps-permission-backdrop" hidden>
        <div class="gps-permission-dialog" role="dialog" aria-modal="true" aria-labelledby="gpsPermissionTitle" aria-describedby="gpsPermissionMessage">
            <div class="gps-permission-head">
                <div class="gps-permission-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <h3 id="gpsPermissionTitle">Aktifkan GPS dulu</h3>
            </div>
            <div class="gps-permission-body">
                <p id="gpsPermissionMessage">Presensi butuh izin lokasi supaya sistem bisa mengecek posisi perangkat kamu.</p>
                <div id="gpsPermissionHelp" class="gps-permission-help" hidden>
                    <strong>Izin lokasi bisa diaktifkan lagi dari browser.</strong>
                    <ol>
                        <li>Tekan ikon gembok atau setelan situs di samping alamat web.</li>
                        <li>Pilih Lokasi, lalu ubah menjadi Izinkan.</li>
                        <li>Kembali ke halaman ini dan tekan Cek Lagi.</li>
                    </ol>
                </div>
                <div class="gps-permission-actions">
                    <button type="button" id="grantGpsBtn" class="gps-permission-btn primary">Izinkan GPS</button>
                    <button type="button" id="openGpsSettingsBtn" class="gps-permission-btn">Cara Aktifkan</button>
                    <button type="button" id="dismissGpsBtn" class="gps-permission-btn">Nanti Dulu</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('#inputs input');
    const realKode = document.getElementById('realKode');
    const submitBtn = document.getElementById('submitBtn');
    const gpsShell = document.getElementById('gpsShell');
    const gpsText = document.getElementById('gpsText');
    const gpsTitle = document.getElementById('gpsTitle');
    const gpsHint = document.getElementById('gpsHint');
    const gpsIcon = document.getElementById('gpsIcon');
    const retryGpsBtn = document.getElementById('retryGpsBtn');
    const inputLat = document.getElementById('inputLat');
    const inputLng = document.getElementById('inputLng');
    const inputAccuracy = document.getElementById('inputAccuracy');
    const gpsPermissionBackdrop = document.getElementById('gpsPermissionBackdrop');
    const gpsPermissionTitle = document.getElementById('gpsPermissionTitle');
    const gpsPermissionMessage = document.getElementById('gpsPermissionMessage');
    const gpsPermissionHelp = document.getElementById('gpsPermissionHelp');
    const grantGpsBtn = document.getElementById('grantGpsBtn');
    const openGpsSettingsBtn = document.getElementById('openGpsSettingsBtn');
    const dismissGpsBtn = document.getElementById('dismissGpsBtn');

    let gpsReady = false;
    let gpsRequesting = false;
    let permissionStatus = null;

    function updateSubmitState() {
        submitBtn.disabled = !gpsReady || realKode.value.length < inputs.length;
    }

    function updateGPSUI(status, title, message) {
        gpsShell.dataset.gpsState = status;
        gpsTitle.textContent = title;
        gpsHint.textContent = message;
        gpsText.textContent = title;
        retryGpsBtn.hidden = status !== 'error' && status !== 'denied' && status !== 'timeout';

        if (status === 'success') {
            gpsIcon.style.background = 'var(--cyber)';
        } else if (status === 'loading') {
            gpsIcon.style.background = 'var(--gold)';
        } else {
            gpsIcon.style.background = 'var(--sakura)';
        }

        updateSubmitState();
    }

    function showGPSPermission(title = 'Aktifkan GPS dulu', message = 'Presensi butuh izin lokasi supaya sistem bisa mengecek posisi perangkat kamu.', options = {}) {
        gpsPermissionTitle.textContent = title;
        gpsPermissionMessage.textContent = message;
        gpsPermissionHelp.hidden = !options.showHelp;
        gpsPermissionBackdrop.hidden = false;
        grantGpsBtn.disabled = false;
        grantGpsBtn.textContent = options.primaryText || 'Izinkan GPS';
        window.setTimeout(() => grantGpsBtn.focus(), 0);
    }

    function hideGPSPermission() {
        gpsPermissionBackdrop.hidden = true;
    }

    function showDeniedRecovery() {
        showGPSPermission(
            'Izin lokasi diblokir',
            'Kalau tadi tidak sengaja menolak GPS, aktifkan lagi izin lokasi dari pengaturan situs browser lalu tekan Cek Lagi.',
            { showHelp: true, primaryText: 'Cek Lagi' }
        );
        updateGPSUI('denied', 'Izin lokasi diblokir', 'Aktifkan lagi izin lokasi dari pengaturan situs browser, lalu tekan Coba Lagi.');
    }

    async function refreshPermissionState() {
        if (!navigator.permissions || !navigator.permissions.query) {
            showGPSPermission();
            return;
        }

        try {
            permissionStatus = await navigator.permissions.query({ name: 'geolocation' });
            permissionStatus.onchange = () => {
                if (permissionStatus.state === 'denied') {
                    showDeniedRecovery();
                    return;
                }

                showGPSPermission('GPS bisa diaktifkan lagi', 'Tekan Izinkan GPS untuk membaca ulang lokasi perangkat.');
            };

            if (permissionStatus.state === 'denied') {
                showDeniedRecovery();
                return;
            }
        } catch (error) {
            permissionStatus = null;
        }

        showGPSPermission();
    }

    function initGPS() {
        if (!navigator.geolocation) {
            gpsReady = false;
            updateGPSUI('error', 'GPS tidak didukung', 'Browser Android ini tidak menyediakan geolokasi untuk presensi.');
            return;
        }

        gpsReady = false;
        gpsRequesting = true;
        inputAccuracy.value = '';
        grantGpsBtn.disabled = true;
        grantGpsBtn.textContent = 'Menunggu...';
        gpsPermissionHelp.hidden = true;
        gpsPermissionTitle.textContent = 'Menunggu persetujuan GPS';
        gpsPermissionMessage.textContent = 'Pilih Izinkan pada permintaan lokasi browser, lalu tunggu sampai posisi terbaca.';
        updateGPSUI('loading', 'Meminta lokasi', 'Tunggu sebentar. Kami sedang membaca lokasi perangkat kamu.');

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                inputLat.value = pos.coords.latitude;
                inputLng.value = pos.coords.longitude;
                inputAccuracy.value = Number.isFinite(pos.coords.accuracy) ? pos.coords.accuracy : '';
                gpsReady = true;
                gpsRequesting = false;
                hideGPSPermission();
                const accuracyText = inputAccuracy.value ? ` Akurasi GPS sekitar ${Math.round(pos.coords.accuracy)}m.` : '';
                updateGPSUI('success', 'Lokasi siap', `Posisi perangkat sudah terdeteksi.${accuracyText} Kamu bisa lanjut kirim presensi.`);
            },
            (err) => {
                gpsReady = false;
                gpsRequesting = false;
                let status = 'error';
                let title = 'Lokasi belum siap';
                let message = 'Kami belum bisa membaca lokasi perangkat kamu.';

                if (err.code === 1) {
                    status = 'denied';
                    title = 'Izin lokasi ditolak';
                    message = 'Aktifkan lagi izin lokasi dari pengaturan situs browser, lalu tekan Coba Lagi.';
                } else if (err.code === 3) {
                    status = 'timeout';
                    title = 'Pencarian lokasi terlalu lama';
                    message = 'Sinyal GPS lambat. Coba lagi saat koneksi dan lokasi perangkat lebih stabil.';
                }

                hideGPSPermission();
                updateGPSUI(status, title, message);
                if (status === 'denied') {
                    showDeniedRecovery();
                }
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    refreshPermissionState();
    grantGpsBtn.addEventListener('click', initGPS);
    openGpsSettingsBtn.addEventListener('click', () => {
        gpsPermissionHelp.hidden = false;
        grantGpsBtn.textContent = 'Cek Lagi';
    });
    dismissGpsBtn.addEventListener('click', hideGPSPermission);
    retryGpsBtn.addEventListener('click', () => {
        if (permissionStatus && permissionStatus.state === 'denied') {
            showDeniedRecovery();
            return;
        }

        showGPSPermission('Coba izinkan GPS lagi', 'Tekan tombol izinkan, lalu beri akses lokasi dari browser agar presensi bisa dikirim.');
    });

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            input.value = input.value.toUpperCase();
            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateCode();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const data = e.clipboardData.getData('text').toUpperCase().substring(0, 6);
            for (let i = 0; i < data.length; i++) {
                if (inputs[i]) inputs[i].value = data[i];
            }
            updateCode();
            if (data.length === inputs.length) {
                inputs[inputs.length - 1].focus();
            }
        });
    });

    function updateCode() {
        let code = "";
        inputs.forEach(i => code += i.value);
        realKode.value = code;
        updateSubmitState();
    }

    document.getElementById('otpForm').addEventListener('submit', (e) => {
        if (realKode.value.length < 6) {
            e.preventDefault();
            alert('Kode presensi harus 6 karakter!');
        }
        if (!gpsReady) {
            e.preventDefault();
            if (!gpsRequesting) {
                showGPSPermission('GPS belum aktif', 'Izinkan lokasi terlebih dahulu agar tombol presensi bisa digunakan.');
            }
        }
    });
});
</script>

@endsection
