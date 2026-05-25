@extends('layouts.guru')

@section('content')

<style>
    .schedule-option-list {
        display: grid;
        gap: 18px;
        margin-top: 18px;
    }

    .select-panel {
        position: relative;
        max-width: 820px;
    }

    .select-panel select {
        appearance: none;
        width: 100%;
        min-height: 64px;
        padding: 16px 56px 16px 18px !important;
        border: 4px solid var(--midnight) !important;
        border-radius: 14px !important;
        background: var(--mochi) !important;
        color: var(--midnight) !important;
        box-shadow: 5px 5px 0 var(--midnight) !important;
        font-family: 'Nunito', sans-serif !important;
        font-size: 16px !important;
        font-weight: 900 !important;
        outline: none !important;
    }

    .select-panel::after {
        content: '\F282';
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--midnight);
        font-family: bootstrap-icons;
        font-size: 18px;
        pointer-events: none;
    }
</style>

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">Mulai Kelas</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-journal-plus"></i> Aktivitas Mengajar</span>
                <h1 class="mp-title">Buka Ruang Kelas</h1>
                <p class="mp-description">
                    Pilih jadwal mengajar hari ini untuk membuat sesi kelas dan mengaktifkan kode presensi digital.
                </p>
            </div>
        </section>
    </div>

    <section class="mp-form-card">
        <form method="POST" action="{{ route('guru.presensi.buka') }}">
            @csrf

            <div class="mp-field">
                <label class="mp-label">Jadwal Pelajaran Hari Ini</label>

                @if($jadwalHariIni->isEmpty())
                    <div class="mp-empty-state">
                        <div style="font-family:'Fredoka One', cursive; font-size:28px; color:var(--midnight);">Tidak ada jadwal</div>
                        <p style="margin:12px 0 0; font-weight:900;">Anda tidak memiliki jadwal mengajar terdaftar untuk hari ini.</p>
                    </div>
                @else
                    <div class="select-panel">
                        <select name="kd_jp" required>
                            <option value="" disabled selected>Pilih jadwal mengajar</option>
                            @foreach($jadwalHariIni as $j)
                                @php
                                    $activeInThisClass = $allActiveSessions->get($j->kelas);
                                @endphp
                                <option value="{{ $j->kd_jp }}">
                                    Jam {{ $j->jam_mulai }} - {{ $j->jam_selesai }} | {{ $j->mapel->nama_mapel }} [{{ $j->kelas }}]
                                    @if($activeInThisClass)
                                        (Sesi aktif: {{ $activeInThisClass->map(fn($s) => $s->guru->nama_guru)->join(', ') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="schedule-option-list">
                        @foreach($jadwalHariIni as $j)
                            @php
                                $activeInThisClass = $allActiveSessions->get($j->kelas);
                            @endphp
                            <div class="mp-card" style="padding:20px; box-shadow:5px 5px 0 var(--midnight);">
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;">
                                    <div>
                                        <span class="mp-badge" style="background:var(--cyber);">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</span>
                                        <h3 style="margin:14px 0 6px; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:24px;">{{ $j->mapel->nama_mapel }}</h3>
                                        <div style="font-weight:900; color:var(--cosmo); text-transform:uppercase;">Kelas {{ $j->kelas }}</div>
                                    </div>
                                    @if($activeInThisClass)
                                        <span class="mp-badge" style="background:var(--gold);">Ada sesi aktif</span>
                                    @else
                                        <span class="mp-badge" style="background:var(--white);">Siap dibuka</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(!$jadwalHariIni->isEmpty())
                <div class="mp-actions">
                    <button type="submit" class="mp-btn">
                        <i class="bi bi-play-circle-fill"></i> Mulai Sesi Kelas
                    </button>
                    <a href="{{ route('guru.dashboard') }}" class="mp-btn-secondary">Batal</a>
                </div>
            @endif
        </form>
    </section>
</div>

@endsection
