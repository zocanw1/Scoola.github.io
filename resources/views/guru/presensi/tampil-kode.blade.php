@extends('layouts.guru')

@section('content')

<style>
    .projector-stage {
        min-height: calc(100vh - 170px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 34px;
    }

    .projector-card {
        position: relative;
        overflow: hidden;
        padding: clamp(34px, 6vw, 76px);
        border: 5px solid var(--midnight);
        border-radius: 22px;
        background: var(--cosmo);
        color: var(--white);
        box-shadow: 12px 12px 0 var(--midnight);
        text-align: center;
    }

    .projector-card::before {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        top: -70px;
        right: -50px;
        border: 5px solid var(--midnight);
        border-radius: 999px;
        background: var(--gold);
        box-shadow: 8px 8px 0 var(--midnight);
    }

    .projector-card::after {
        content: 'SCOOLA';
        position: absolute;
        left: 28px;
        bottom: 12px;
        color: rgba(255,255,255,.12);
        font-family: 'Fredoka One', cursive;
        font-size: clamp(48px, 12vw, 132px);
        line-height: 1;
        pointer-events: none;
    }

    .projector-content {
        position: relative;
        z-index: 1;
    }

    .projector-code {
        margin: clamp(28px, 5vw, 54px) 0;
        color: var(--white);
        font-family: 'Fredoka One', cursive;
        font-size: clamp(64px, 16vw, 170px);
        line-height: 1;
        letter-spacing: .12em;
        text-shadow: 7px 7px 0 var(--midnight);
        -webkit-text-stroke: 3px var(--midnight);
        word-break: break-all;
    }

    .timer-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(110px, 1fr));
        gap: 18px;
        max-width: 620px;
        margin: 0 auto;
    }

    .timer-box {
        padding: 18px;
        border: 4px solid var(--midnight);
        border-radius: 16px;
        background: var(--white);
        color: var(--midnight);
        box-shadow: 6px 6px 0 var(--midnight);
    }

    .timer-number {
        color: var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: clamp(32px, 5vw, 54px);
        line-height: 1;
    }

    @media (max-width: 640px) {
        .timer-grid { grid-template-columns: 1fr; }

        .projector-code { letter-spacing: .05em; }
    }
</style>

<div class="mp-page projector-stage">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; gap:18px; flex-wrap:wrap;">
        <div>
            <span class="mp-badge" style="background:var(--cyber);">Projector Mode</span>
            <h1 style="margin:16px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:clamp(30px, 5vw, 48px); line-height:1.1;">Presensi Kelas {{ $sesi->kelas }}</h1>
        </div>
        <a href="{{ route('guru.presensi.ruang', $sesi->id) }}" class="mp-btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Panel
        </a>
    </div>

    <section class="projector-card">
        <div class="projector-content">
            <span class="mp-badge" style="background:var(--gold);">Masukkan kode ini di perangkat siswa</span>
            <div class="projector-code">{{ $sesi->kode_presensi }}</div>

            <div class="timer-grid">
                <div class="timer-box">
                    <div id="hour" class="timer-number">00</div>
                    <div class="mp-label" style="margin-top:8px;">Jam</div>
                </div>
                <div class="timer-box">
                    <div id="min" class="timer-number">00</div>
                    <div class="mp-label" style="margin-top:8px;">Menit</div>
                </div>
                <div class="timer-box">
                    <div id="sec" class="timer-number">00</div>
                    <div class="mp-label" style="margin-top:8px;">Detik</div>
                </div>
            </div>

            <p style="margin:28px 0 0; color:var(--white); font-weight:900; text-shadow:2px 2px 0 var(--midnight);">
                Waktu tersisa sebelum kode kedaluwarsa
            </p>
        </div>
    </section>
</div>

<script>
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
