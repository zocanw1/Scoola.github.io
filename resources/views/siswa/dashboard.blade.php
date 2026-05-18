@extends('layouts.siswa')

@section('content')

<div class="card" style="background: #ffffff; padding: 40px; margin-bottom: var(--spacing-md); border-radius: 12px; border: 1px solid var(--color-hairline);">
    <div class="editorial-header" style="margin: 0;">
        <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Panel Siswa</span>
        <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Presensi</h1>
        <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
            Konfirmasi kehadiran Anda dengan memasukkan kode unik dari pengajar. Pastikan akses lokasi aktif untuk verifikasi keamanan.
        </p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--spacing-xxl); align-items: start;">
    <!-- Main Interaction Column -->
    <div style="display: flex; flex-direction: column; gap: var(--spacing-xxl);">
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
            <div style="margin-bottom: 40px;">
                <h2 class="text-heading-sm" style="text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; font-weight: 700;">Masukkan Kode Sesi</h2>
                <div id="gpsStatus" style="display: flex; align-items: center; gap: 8px; margin-top: 12px;">
                    <div id="gpsIcon" style="width: 8px; height: 8px; border-radius: 50%; background: var(--color-stone);"></div>
                    <span id="gpsText" class="text-micro-caps" style="color: var(--color-stone); font-weight: 700;">Mendeteksi Lokasi...</span>
                </div>
            </div>

            <form action="{{ route('siswa.presensi.store') }}" method="POST" id="otpForm">
                @csrf
                <input type="hidden" name="kode_presensi" id="realKode">
                <input type="hidden" name="latitude" id="inputLat">
                <input type="hidden" name="longitude" id="inputLng">

                <div style="display: flex; gap: 16px; margin-bottom: 48px;" id="inputs">
                    @for($i=0; $i<6; $i++)
                    <input type="text" maxlength="1" autocomplete="off" class="form-field" 
                           style="width: 56px; height: 64px; text-align: center; font-size: 32px; font-weight: 600; border-radius: var(--rounded-none); border-width: 2px;" 
                           placeholder="·">
                    @endfor
                </div>

                <button type="submit" id="submitBtn" disabled class="btn-primary" style="width: 100%; height: 56px; font-size: 14px;">
                    Konfirmasi Kehadiran Sekarang
                </button>
            </form>
        </div>

        @if(session('success'))
        <div class="card" style="background: #ffffff; padding: 24px; border-left: 4px solid var(--color-primary); border-radius: 12px; border: 1px solid var(--color-hairline);">
            <p class="text-body-strong" style="margin: 0; color: var(--color-ink);">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="card" style="background: #ffffff; padding: 24px; border-left: 4px solid var(--color-graphite); border-radius: 12px; border: 1px solid var(--color-hairline);">
            <p class="text-body-strong" style="color: var(--color-ink); margin: 0;">{{ session('error') }}</p>
        </div>
        @endif
    </div>

    <!-- Info Column / Sidebar -->
    <div style="display: flex; flex-direction: column; gap: var(--spacing-xxl);">
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
            <span class="text-micro-caps" style="color: var(--color-stone); display: block; margin-bottom: 24px; font-weight: 700;">Identitas Siswa</span>
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 40px;">
                <div style="width: 64px; height: 64px; background: var(--color-hairline); display: grid; place-items: center; font-size: 24px; color: var(--color-stone);">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <p style="font-weight: 700; font-size: 18px; margin: 0; color: var(--color-ink);">{{ $siswa->nama_siswa ?? 'Student' }}</p>
                    <p style="color: var(--color-stone); font-size: 13px; margin: 4px 0 0 0;">{{ $siswa->kelas ?? '-' }} · {{ $siswa->NIS ?? '-' }}</p>
                </div>
            </div>

            @if($riwayat && $riwayat->count() > 0)
            <span class="text-micro-caps" style="color: var(--color-stone); display: block; margin-bottom: 24px; font-weight: 700;">Aktivitas Terakhir</span>
            <div style="display: flex; flex-direction: column;">
                @foreach($riwayat->take(5) as $r)
                <div style="padding: 20px 0; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-hairline);">
                    <div>
                        <p style="font-weight: 600; font-size: 14px; margin: 0; color: var(--color-ink);">{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d M Y') }}</p>
                        <p style="color: var(--color-stone); font-size: 12px; margin: 4px 0 0 0;">{{ $r->jam_masuk }}</p>
                    </div>
                    <span class="badge-status {{ $r->status == 'Hadir' ? 'bs-h' : 'bs-a' }}">
                        {{ $r->status }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <div class="card" style="background: #ffffff; padding: 40px; border: 1px solid var(--color-ink); border-radius: 12px;">
            <p style="font-size: 13px; line-height: 1.6; color: var(--color-graphite); margin: 0;">
                <b>Tips:</b> Gunakan kode 6 karakter yang ditampilkan oleh pengajar di layar proyektor atau papan tulis.
            </p>
        </div>
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
            updateGPSUI('error', 'GPS TIDAK DIDUKUNG');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                inputLat.value = pos.coords.latitude;
                inputLng.value = pos.coords.longitude;
                gpsReady = true;
                updateGPSUI('success', 'LOKASI TERDETEKSI');
                submitBtn.disabled = false;
            },
            (err) => {
                let msg = 'GAGAL DETEKSI LOKASI';
                if(err.code === 1) msg = 'AKSES LOKASI DITOLAK';
                updateGPSUI('error', msg);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    function updateGPSUI(status, msg) {
        gpsText.textContent = msg;
        gpsIcon.style.background = status === 'success' ? 'var(--color-ink)' : '#000';
        if(status === 'error') gpsIcon.style.opacity = '0.3';
    }

    initGPS();

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
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
            for(let i=0; i<data.length; i++) {
                if(inputs[i]) inputs[i].value = data[i];
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
