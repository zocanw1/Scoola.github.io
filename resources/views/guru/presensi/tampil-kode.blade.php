@extends('layouts.guru')

@section('content')

<div class="editorial-header" style="display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <span class="eyebrow">Projector Mode</span>
        <h1 class="display-title">Presensi Kelas: {{ $sesi->kelas }}</h1>
    </div>
    <a href="{{ route('guru.presensi.ruang', $sesi->id) }}" class="text-link-sm" style="color: var(--color-ink); font-weight: 700; text-decoration: none;">&larr; KEMBALI KE PANEL</a>
</div>

<div style="margin-top: 64px; border: 1px solid var(--color-ink); padding: 80px 40px; text-align: center; background: #fff;">
    <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 24px; letter-spacing: 2px;">MASUKKAN KODE BERIKUT PADA PERANGKAT SISWA</div>
    
    <div style="font-size: 160px; font-weight: 800; color: var(--color-ink); letter-spacing: 12px; line-height: 1; margin: 48px 0; font-family: var(--font-family-base);">
        {{ $sesi->kode_presensi }}
    </div>

    <div style="display: flex; justify-content: center; gap: 48px; margin-top: 80px; padding-top: 48px; border-top: 1px solid var(--color-hairline);">
        <div>
            <div id="hour" style="font-size: 32px; font-weight: 700; color: var(--color-ink);">00</div>
            <div class="text-micro-caps" style="color: var(--color-stone); margin-top: 4px;">JAM</div>
        </div>
        <div>
            <div id="min" style="font-size: 32px; font-weight: 700; color: var(--color-ink);">00</div>
            <div class="text-micro-caps" style="color: var(--color-stone); margin-top: 4px;">MENIT</div>
        </div>
        <div>
            <div id="sec" style="font-size: 32px; font-weight: 700; color: var(--color-ink);">00</div>
            <div class="text-micro-caps" style="color: var(--color-stone); margin-top: 4px;">DETIK</div>
        </div>
    </div>
    
    <div class="text-meta" style="color: var(--color-slate); margin-top: 32px; text-transform: uppercase; letter-spacing: 1px;">Waktu Tersisa Sebelum Kode Kedaluwarsa</div>
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
