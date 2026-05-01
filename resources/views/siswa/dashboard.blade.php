@extends('layouts.siswa')

@section('content')

<style>
    .welcome-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: 14px;
        padding: 30px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -20px; right: -20px;
        width: 150px; height: 150px;
        background: var(--accent);
        opacity: 0.1;
        border-radius: 50%;
        filter: blur(40px);
    }

    .ws-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px; font-weight: 800;
        color: var(--text1);
        margin-bottom: 6px;
    }

    .ws-subtitle {
        font-size: 13px; color: var(--text2);
    }

    .code-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 40px;
        margin-bottom: 20px;
    }

    .otp-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text1);
        margin-bottom: 16px;
        letter-spacing: .02em;
    }

    .otp-inputs {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        direction: ltr;
    }

    .otp-inputs input {
        width: 50px;
        height: 60px;
        font-size: 28px;
        text-align: center;
        background: var(--navy3);
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        color: var(--accent);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        text-transform: uppercase;
        transition: all .2s;
        outline: none;
    }

    .otp-inputs input:focus {
        border-color: var(--accent);
        background: var(--navy2);
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(88, 166, 255, 0.15);
    }

    .btn-submit {
        padding: 12px 30px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        filter: brightness(1.15);
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(88, 166, 255, 0.3);
    }

    .btn-submit:active { transform: translateY(0); }

    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Custom Alerts */
    .alert {
        padding: 14px 20px;
        border-radius: 10px;
        margin-bottom: 24px;
        font-size: 13.5px;
        font-weight: 500;
        display: flex; align-items: flex-start; gap: 12px;
        animation: fi .4s ease;
    }
    .alert-success {
        background: rgba(63, 185, 80, 0.1);
        border: 1px solid rgba(63, 185, 80, 0.2);
        color: var(--green);
    }
    .alert-danger {
        background: rgba(248, 81, 73, 0.1);
        border: 1px solid rgba(248, 81, 73, 0.2);
        color: var(--red);
    }
    .alert i { font-size: 16px; margin-top: 1px; }

    /* GPS Status */
    .gps-status {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 13px;
        font-weight: 500;
        transition: all .3s ease;
    }

    .gps-status i { font-size: 16px; }

    .gps-status.detecting {
        background: rgba(88, 166, 255, 0.08);
        border: 1px solid rgba(88, 166, 255, 0.2);
        color: var(--accent);
    }

    .gps-status.success {
        background: rgba(63, 185, 80, 0.08);
        border: 1px solid rgba(63, 185, 80, 0.2);
        color: var(--green);
    }

    .gps-status.error {
        background: rgba(248, 81, 73, 0.08);
        border: 1px solid rgba(248, 81, 73, 0.2);
        color: var(--red);
    }

    .gps-status.warning {
        background: rgba(227, 179, 65, 0.08);
        border: 1px solid rgba(227, 179, 65, 0.2);
        color: var(--amber);
    }

    .gps-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .gps-dot.pulse { animation: pulse 1.5s infinite; }
    .gps-dot.detecting { background: var(--accent); }
    .gps-dot.success { background: var(--green); }
    .gps-dot.error { background: var(--red); }
    .gps-dot.warning { background: var(--amber); }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.4); }
    }

    .gps-loader {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .gps-loader .spinner {
        width: 14px; height: 14px;
        border: 2px solid rgba(88, 166, 255, 0.2);
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* Riwayat Absensi */
    .history-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: 14px;
        padding: 24px;
    }

    .h-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text1);
        margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }

    .h-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--glass-border);
    }
    .h-row:last-child { border-bottom: none; }
    
    .h-date { font-size: 13px; color: var(--text1); font-weight: 500; }
    .h-time { font-size: 11.5px; color: var(--text2); margin-top: 2px;}
    
    .s-badge {
        font-size: 10px; font-weight: 600; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: .06em;
    }
    .s-hadir { background: rgba(63, 185, 80, 0.15); color: var(--green); }
    .s-izin  { background: rgba(227, 179, 65, 0.15); color: var(--amber); }
    .s-ditolak { background: rgba(248, 81, 73, 0.15); color: var(--red); }
    .s-alfa { background: rgba(248, 81, 73, 0.15); color: var(--red); }
    .s-belum-hadir { background: rgba(139, 148, 158, 0.15); color: var(--text3); }

    .h-meta {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .loc-icon {
        font-size: 14px;
    }
    .loc-icon.in { color: var(--green); }
    .loc-icon.out { color: var(--red); }

    @keyframes fi { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
</style>

@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

<div class="welcome-card">
    <div class="ws-title">Halo, {{ $siswa ? $siswa->nama_siswa : auth()->user()->email }} 👋</div>
    <div class="ws-subtitle">{{ $siswa ? 'Kelas ' . $siswa->kelas . ' | NIS: ' . $siswa->NIS : 'Identitas siswa belum disetel.' }}</div>

    @if($siswa)
    <div class="code-container">
        <div class="otp-label">Masukkan Kode Presensi dari Papan Tulis</div>

        {{-- GPS Status Indicator --}}
        <div class="gps-status detecting" id="gpsStatus">
            <div class="gps-loader">
                <div class="spinner"></div>
            </div>
            <span id="gpsText">Mendeteksi lokasi GPS...</span>
        </div>
        
        <form action="{{ route('siswa.presensi.store') }}" method="POST" id="otpForm">
            @csrf
            <!-- Hidden real input -->
            <input type="hidden" name="kode_presensi" id="realKode">
            <input type="hidden" name="latitude" id="inputLat">
            <input type="hidden" name="longitude" id="inputLng">
            
            <div class="otp-inputs" id="inputs">
                <input type="text" maxlength="1" autocomplete="off" autofocus>
                <input type="text" maxlength="1" autocomplete="off">
                <input type="text" maxlength="1" autocomplete="off">
                <input type="text" maxlength="1" autocomplete="off">
                <input type="text" maxlength="1" autocomplete="off">
                <input type="text" maxlength="1" autocomplete="off">
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                    <i class="bi bi-geo-alt-fill"></i> <span id="btnText">Menunggu GPS...</span>
                </button>
            </div>
        </form>
    </div>
    @endif
</div>

@if($siswa && $riwayat->count() > 0)
<div class="history-card">
    <div class="h-title">
        <i class="bi bi-clock-history" style="color:var(--text2)"></i> Riwayat Presensi Terbaru
    </div>
    @foreach($riwayat as $r)
    <div class="h-row">
        <div>
            <div class="h-date">{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('l, d F Y') }}</div>
            <div class="h-time">Pukul {{ $r->jam_masuk }}</div>
        </div>
        <div class="h-meta">
            @if($r->is_dalam_radius !== null)
                @if($r->is_dalam_radius)
                    <i class="bi bi-geo-alt-fill loc-icon in" title="Dalam area sekolah"></i>
                @else
                    <i class="bi bi-geo-alt-fill loc-icon out" title="Di luar area sekolah"></i>
                @endif
            @endif
            @php
                $badgeClass = 's-izin';
                if ($r->status == 'Hadir') $badgeClass = 's-hadir';
                elseif ($r->status == 'Ditolak') $badgeClass = 's-ditolak';
                elseif ($r->status == 'Alfa' || $r->status == 'Alpa') $badgeClass = 's-alfa';
                elseif ($r->status == 'Belum Hadir') $badgeClass = 's-belum-hadir';
            @endphp
            <span class="s-badge {{ $badgeClass }}">{{ $r->status }}</span>
        </div>
    </div>
    @endforeach
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-inputs input');
    const realKode = document.getElementById('realKode');
    const form = document.getElementById('otpForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const inputLat = document.getElementById('inputLat');
    const inputLng = document.getElementById('inputLng');
    const gpsStatus = document.getElementById('gpsStatus');
    const gpsText = document.getElementById('gpsText');

    let gpsReady = false;

    // ===== GPS: Ambil lokasi =====
    function initGPS() {
        if (!navigator.geolocation) {
            setGPSState('error', 'Browser tidak mendukung GPS. Hubungi guru.');
            return;
        }

        // Langsung request GPS saat halaman load
        navigator.geolocation.getCurrentPosition(
            function(position) {
                inputLat.value = position.coords.latitude;
                inputLng.value = position.coords.longitude;
                gpsReady = true;

                setGPSState('success', 'Lokasi berhasil terdeteksi (' + 
                    position.coords.latitude.toFixed(5) + ', ' + 
                    position.coords.longitude.toFixed(5) + ')');

                submitBtn.disabled = false;
                btnText.textContent = 'Kirim Presensi';
                submitBtn.querySelector('i').className = 'bi bi-send-fill';
            },
            function(error) {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        setGPSState('error', 'Akses GPS ditolak! Izinkan akses lokasi di pengaturan browser.');
                        break;
                    case error.POSITION_UNAVAILABLE:
                        setGPSState('error', 'Lokasi tidak tersedia. Pastikan GPS aktif.');
                        break;
                    case error.TIMEOUT:
                        setGPSState('warning', 'Timeout mendeteksi GPS. Mencoba ulang...');
                        setTimeout(initGPS, 2000);
                        return;
                    default:
                        setGPSState('error', 'Gagal mengambil lokasi. Coba refresh halaman.');
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 30000
            }
        );
    }

    function setGPSState(state, message) {
        gpsStatus.className = 'gps-status ' + state;
        gpsText.textContent = message;

        let iconHTML = '';
        if (state === 'detecting') {
            iconHTML = '<div class="gps-loader"><div class="spinner"></div></div>';
        } else if (state === 'success') {
            iconHTML = '<span class="gps-dot success"></span>';
        } else if (state === 'error') {
            iconHTML = '<span class="gps-dot error"></span>';
        } else if (state === 'warning') {
            iconHTML = '<span class="gps-dot warning pulse"></span>';
        }

        // Replace the first child (icon area)
        const firstChild = gpsStatus.firstElementChild;
        if (firstChild) {
            firstChild.outerHTML = iconHTML;
        }
    }

    // Mulai deteksi GPS
    initGPS();

    // ===== OTP Input Logic =====
    inputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            
            if(this.value.length === 1) {
                if(index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }
            updateRealKode();
        });

        input.addEventListener('keydown', function(e) {
            if(e.key === 'Backspace' && this.value === '') {
                if(index > 0) {
                    inputs[index - 1].focus();
                }
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').toUpperCase().trim().substring(0, 6);
            if(pastedData) {
                for(let i = 0; i < pastedData.length; i++) {
                    if(i < inputs.length) {
                        inputs[i].value = pastedData[i];
                    }
                }
                if(pastedData.length < 6) {
                    inputs[pastedData.length].focus();
                } else {
                    inputs[5].focus();
                }
                updateRealKode();
            }
        });
    });

    function updateRealKode() {
        let code = '';
        inputs.forEach(input => {
            code += input.value;
        });
        realKode.value = code;
    }

    form.addEventListener('submit', function(e) {
        updateRealKode();

        if(realKode.value.length < 6) {
            e.preventDefault();
            alert('Kode presensi harus terdiri dari 6 karakter!');
            return;
        }

        if(!gpsReady) {
            e.preventDefault();
            alert('GPS belum siap. Pastikan izin lokasi sudah diberikan dan tunggu sampai GPS terdeteksi.');
            return;
        }

        // Disable button saat submit
        submitBtn.disabled = true;
        btnText.textContent = 'Mengirim...';
    });
});
</script>

@endsection
