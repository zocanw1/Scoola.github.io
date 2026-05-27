@extends('layouts.guru')

@section('content')

<style>
    .schedule-option-list,
    .schedule-choice-grid {
        display: grid;
        gap: 18px;
        margin-top: 18px;
    }

    .schedule-choice-grid {
        margin-top: 22px;
    }

    .schedule-choice-card {
        display: block;
        cursor: pointer;
    }

    .schedule-choice-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .choice-shell {
        width: 100%;
        display: grid;
        gap: 14px;
        padding: 20px;
        border: 4px solid var(--midnight);
        border-radius: 18px;
        background: var(--white);
        box-shadow: 5px 5px 0 var(--midnight);
        transition: transform 0.16s ease, box-shadow 0.16s ease, background 0.16s ease;
    }

    .schedule-choice-card:hover .choice-shell {
        transform: translate(-2px, -2px);
        box-shadow: 7px 7px 0 var(--midnight);
    }

    .schedule-choice-card input:checked + .choice-shell {
        background: #ddfffb;
        transform: translate(-2px, -2px);
        box-shadow: 7px 7px 0 var(--midnight);
    }

    .choice-heading {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .choice-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .choice-check {
        min-width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--midnight);
        border-radius: 999px;
        background: var(--white);
        box-shadow: 3px 3px 0 var(--midnight);
        font-size: 18px;
        opacity: 0.65;
    }

    .schedule-choice-card input:checked + .choice-shell .choice-check {
        opacity: 1;
        background: var(--gold);
    }

    @media (max-width: 540px) {
        .choice-heading {
            flex-direction: column;
        }
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
                    <div class="schedule-choice-grid" role="radiogroup" aria-label="Pilih jadwal mengajar">
                        @foreach($jadwalHariIni as $j)
                            @php
                                $activeInThisClass = $allActiveSessions->get($j->kelas);
                            @endphp
                            <label class="schedule-choice-card">
                                <input type="radio" name="kd_jp" value="{{ $j->kd_jp }}" {{ $loop->first ? 'checked' : '' }} required>
                                <div class="choice-shell">
                                    <div class="choice-heading">
                                        <div>
                                            <div class="choice-meta">
                                                <span class="mp-badge" style="background:var(--cyber);">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</span>
                                                <span class="mp-badge" style="background:var(--white);">Kelas {{ $j->kelas }}</span>
                                            </div>
                                            <h3 style="margin:14px 0 6px; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:24px;">{{ $j->mapel->nama_mapel }}</h3>
                                            <div style="font-weight:900; color:var(--cosmo); text-transform:uppercase;">{{ $j->kd_jp }}</div>
                                        </div>
                                        <span class="choice-check"><i class="bi bi-check2"></i></span>
                                    </div>

                                    <div style="display:flex; flex-wrap:wrap; gap:10px;">
                                        @if($activeInThisClass)
                                            <span class="mp-badge" style="background:var(--gold);">Ada sesi aktif</span>
                                            <span class="mp-badge" style="background:var(--white);">{{ $activeInThisClass->map(fn($s) => $s->guru->nama_guru)->join(', ') }}</span>
                                        @else
                                            <span class="mp-badge" style="background:var(--white);">Siap dibuka</span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(!$jadwalHariIni->isEmpty())
                <div class="mp-sticky-action">
                    <button type="submit" id="startSessionBtn" class="mp-btn" style="width:100%;">
                        <i class="bi bi-play-circle-fill"></i> Mulai Sesi Kelas
                    </button>
                </div>
                <div class="mp-actions">
                    <a href="{{ route('guru.dashboard') }}" class="mp-btn-secondary">Batal</a>
                </div>
            @endif
        </form>
    </section>
</div>

@endsection
