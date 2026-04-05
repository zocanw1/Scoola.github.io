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
        color: var(--navy);
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
        background: #79baff;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(88, 166, 255, 0.3);
    }

    .btn-submit:active { transform: translateY(0); }

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
        
        <form action="{{ route('siswa.presensi.store') }}" method="POST" id="otpForm">
            @csrf
            <!-- Hidden real input -->
            <input type="hidden" name="kode_presensi" id="realKode">
            
            <div class="otp-inputs" id="inputs">
                <input type="text" maxlength="1" autocomplete="off" autofocus>
                <input type="text" maxlength="1" autocomplete="off">
                <input type="text" maxlength="1" autocomplete="off">
                <input type="text" maxlength="1" autocomplete="off">
                <input type="text" maxlength="1" autocomplete="off">
                <input type="text" maxlength="1" autocomplete="off">
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="bi bi-send-fill"></i> Kirim Presensi
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
        <div>
            <span class="s-badge {{ $r->status == 'Hadir' ? 's-hadir' : 's-izin' }}">{{ $r->status }}</span>
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

    inputs.forEach((input, index) => {
        // Auto convert to uppercase
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            
            if(this.value.length === 1) {
                if(index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }
            updateRealKode();
        });

        // Handle Backspace
        input.addEventListener('keydown', function(e) {
            if(e.key === 'Backspace' && this.value === '') {
                if(index > 0) {
                    inputs[index - 1].focus();
                }
            }
        });

        // Handle Paste
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
        }
    });
});
</script>

@endsection