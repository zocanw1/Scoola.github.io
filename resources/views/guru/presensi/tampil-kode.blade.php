@extends('layouts.guru')

@section('content')

<style>
    .page-header {
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    .hero-card {
        background: var(--navy2);
        border: 1px solid var(--accent);
        border-radius: 16px;
        padding: 60px 40px;
        text-align: center;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(88, 166, 255, 0.08);
    }

    .hero-card::before {
        content: '';
        position: absolute;
        top: -50px; left: -50px;
        width: 150px; height: 150px;
        background: var(--accent);
        opacity: 0.1;
        border-radius: 50%;
        filter: blur(40px);
    }

    .hero-card::after {
        content: '';
        position: absolute;
        bottom: -50px; right: -50px;
        width: 150px; height: 150px;
        background: var(--purple);
        opacity: 0.1;
        border-radius: 50%;
        filter: blur(40px);
    }

    .class-badge {
        display: inline-block;
        background: rgba(88, 166, 255, 0.15);
        color: var(--accent);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .05em;
        margin-bottom: 20px;
    }

    .kode-text {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 80px;
        font-weight: 800;
        color: var(--text1);
        letter-spacing: 0.15em;
        line-height: 1;
        text-shadow: 0 4px 12px rgba(0,0,0,0.2);
        margin: 20px 0;
    }

    .kode-instruction {
        font-size: 15px;
        color: var(--text2);
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.5;
    }

    .countdown-wrap {
        display: flex;
        justify-content: center;
        gap: 16px;
        margin-top: 40px;
    }

    .time-box {
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 14px 20px;
        min-width: 90px;
    }

    .time-val {
        font-size: 24px;
        font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text1);
    }

    .time-lbl {
        font-size: 10px;
        color: var(--text2);
        text-transform: uppercase;
        letter-spacing: .1em;
        margin-top: 4px;
    }

    .btn-finish {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        background: var(--red);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }

    .btn-finish:hover {
        filter: brightness(1.2);
        transform: translateY(-1px);
        color: #fff;
    }
</style>

<div class="page-header">
    <div>
        <div class="page-title">Sesi Presensi Aktif</div>
        <div class="page-subtitle">Siswa sekarang dapat melakukan presensi melalui perangkat mereka</div>
    </div>
    <a href="{{ route('guru.presensi.ruang', $sesi->id) }}" class="btn-finish">
        <i class="bi bi-box-arrow-left"></i> Tutup Proyektor
    </a>
</div>

<div class="hero-card fi">
    <div class="class-badge">KELAS: {{ $sesi->kelas }}</div>
    
    <div class="kode-instruction">Minta siswa untuk login dan memasukkan kode presensi di bawah ini:</div>
    
    <div class="kode-text">{{ $sesi->kode_presensi }}</div>
    
    <div class="countdown-wrap">
        <div class="time-box">
            <div class="time-val" id="hour">01</div>
            <div class="time-lbl">JAM</div>
        </div>
        <div class="time-box">
            <div class="time-val" id="min">59</div>
            <div class="time-lbl">MENIT</div>
        </div>
        <div class="time-box">
            <div class="time-val" id="sec">59</div>
            <div class="time-lbl">DETIK</div>
        </div>
    </div>
</div>

<script>
    // Countdown Timer Logic
    const endTime = new Date("{{ $sesi->waktu_berlaku->toIso8601String() }}").getTime();

    const interval = setInterval(function() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            clearInterval(interval);
            document.getElementById("hour").innerText = "00";
            document.getElementById("min").innerText = "00";
            document.getElementById("sec").innerText = "00";
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("hour").innerText = hours.toString().padStart(2, '0');
        document.getElementById("min").innerText = minutes.toString().padStart(2, '0');
        document.getElementById("sec").innerText = seconds.toString().padStart(2, '0');
    }, 1000);
</script>

@endsection
