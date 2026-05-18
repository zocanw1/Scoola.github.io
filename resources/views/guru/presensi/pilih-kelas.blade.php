@extends('layouts.guru')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Aktivitas Mengajar</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Buka Ruang Kelas</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Pilih jadwal pelajaran aktif untuk memulai sesi pengajaran dan mengaktifkan sistem presensi digital.
            </p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card" style="background: #ffffff; padding: 48px; border-radius: 12px; border: 1px solid var(--color-hairline); margin-bottom: var(--spacing-section);">
        <form method="POST" action="{{ route('guru.presensi.buka') }}" style="max-width: 720px;">
            @csrf

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Jadwal Pelajaran Hari Ini</label>
                
                @if($jadwalHariIni->isEmpty())
                    <div style="border: 1px solid var(--color-hairline); padding: 48px; text-align: center; background: var(--color-surface); border-radius: 8px;">
                        <div class="text-body" style="color: var(--color-stone);">Anda tidak memiliki jadwal mengajar terdaftar untuk hari ini.</div>
                    </div>
                @else
                    <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                        <select name="kd_jp" style="width: 100%; border: none; padding: 16px 0; font-family: var(--font-family-base); font-size: 18px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink); font-weight: 500;" required>
                            <option value="" disabled selected>PILIH JADWAL MENGAJAR</option>
                            @foreach($jadwalHariIni as $j)
                                @php
                                    $activeInThisClass = $allActiveSessions->get($j->kelas);
                                @endphp
                                <option value="{{ $j->kd_jp }}">
                                    JAM {{ $j->jam_mulai }} – {{ $j->jam_selesai }} | {{ $j->mapel->nama_mapel }} [{{ $j->kelas }}]
                                    @if($activeInThisClass)
                                        (SESI AKTIF: {{ $activeInThisClass->map(fn($s) => $s->guru->nama_guru)->join(', ') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                    </div>
                @endif
            </div>

            @if(!$jadwalHariIni->isEmpty())
            <div style="display: flex; gap: 32px; align-items: center; margin-top: 64px; padding-top: 48px; border-top: 1px solid var(--color-hairline);">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Mulai Sesi Kelas</button>
                <a href="{{ route('guru.dashboard') }}" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; font-size: 13px; display: inline-flex; align-items: center;">Batal</a>
            </div>
            @endif
        </form>
    </div>

</div>

@endsection
