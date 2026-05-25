@extends('layouts.siswa')

@section('content')

<style>
    .student-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.65fr) minmax(320px, .95fr);
        gap: 32px;
        align-items: start;
    }

    .otp-row {
        display: grid;
        grid-template-columns: repeat(6, minmax(42px, 1fr));
        gap: 14px;
        margin: 34px 0 42px;
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

    @media (max-width: 980px) {
        .student-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 540px) {
        .otp-row { gap: 8px; }

        .student-profile {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">( &gt;= siap hadir )</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-stars"></i> Panel Siswa</span>
                <h1 class="mp-title">Presensi Harian</h1>
                <p class="mp-description">
                    Masukkan kode 6 karakter dari pengajar. Sistem akan mengecek lokasi perangkat sebelum menyimpan kehadiran kamu.
                </p>
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

            <section class="mp-form-card">
                <div style="display:flex; justify-content:space-between; gap:20px; align-items:flex-start; flex-wrap:wrap;">
                    <div>
                        <span class="mp-label">Kode Sesi</span>
                        <h2 style="margin:8px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:32px; line-height:1.1;">Masukkan Kode</h2>
                    </div>
                    <div id="gpsStatus" class="gps-pill">
                        <span id="gpsIcon" class="gps-dot"></span>
                        <span id="gpsText">Mendeteksi lokasi</span>
                    </div>
                </div>

                <form action="{{ route('siswa.presensi.store') }}" method="POST" id="otpForm">
                    @csrf
                    <input type="hidden" name="kode_presensi" id="realKode">
                    <input type="hidden" name="latitude" id="inputLat">
                    <input type="hidden" name="longitude" id="inputLng">

                    <div class="otp-row" id="inputs">
                        @for($i=0; $i<6; $i++)
                            <input type="text" maxlength="1" autocomplete="off" class="otp-input" placeholder="-">
                        @endfor
                    </div>

                    <button type="submit" id="submitBtn" disabled class="mp-btn" style="width:100%; min-height:58px; font-size:15px;">
                        <i class="bi bi-check2-circle"></i> Konfirmasi Kehadiran
                    </button>
                </form>
            </section>

            <section class="mp-card mp-card-gold">
                <span class="mp-badge" style="background:var(--white);">Catatan</span>
                <p style="margin:18px 0 0; font-weight:900; line-height:1.65;">
                    Gunakan kode yang sedang aktif di layar guru. Jika lokasi gagal terdeteksi, izinkan akses lokasi dari browser lalu coba lagi.
                </p>
            </section>
        </div>

        <aside class="mp-card">
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
                <div style="margin-top:12px;">
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('#inputs input');
    const realKode = document.getElementById('realKode');
    const submitBtn = document.getElementById('submitBtn');
    const gpsText = document.getElementById('gpsText');
    const gpsIcon = document.getElementById('gpsIcon');
    const inputLat = document.getElementById('inputLat');
    const inputLng = document.getElementById('inputLng');

    let gpsReady = false;

    function initGPS() {
        if (!navigator.geolocation) {
            updateGPSUI('error', 'GPS tidak didukung');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                inputLat.value = pos.coords.latitude;
                inputLng.value = pos.coords.longitude;
                gpsReady = true;
                updateGPSUI('success', 'Lokasi terdeteksi');
                submitBtn.disabled = false;
            },
            (err) => {
                let msg = 'Gagal deteksi lokasi';
                if (err.code === 1) msg = 'Akses lokasi ditolak';
                updateGPSUI('error', msg);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    function updateGPSUI(status, msg) {
        gpsText.textContent = msg;
        gpsIcon.style.background = status === 'success' ? 'var(--cyber)' : 'var(--sakura)';
        gpsIcon.style.opacity = status === 'error' ? '0.85' : '1';
    }

    initGPS();

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
        });
    });

    function updateCode() {
        let code = "";
        inputs.forEach(i => code += i.value);
        realKode.value = code;
    }

    document.getElementById('otpForm').addEventListener('submit', (e) => {
        if (realKode.value.length < 6) {
            e.preventDefault();
            alert('Kode presensi harus 6 karakter!');
        }
        if (!gpsReady) {
            e.preventDefault();
            alert('Tunggu sampai GPS terdeteksi!');
        }
    });
});
</script>

@endsection
