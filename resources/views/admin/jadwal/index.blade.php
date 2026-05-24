@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">TIMETABLE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-calendar3"></i> Manajemen Akademik</span>
                <h1 class="mp-title">Kurikulum</h1>
                <p class="mp-description">
                    Visualisasi dan pengaturan jadwal mata pelajaran lintas kelas dan tingkatan secara real-time.
                </p>
            </div>
        </section>
    </div>

    <section class="mp-card">
        <div class="mp-tabs">
            <a href="{{ route('jadwal.index') }}" class="mp-tab {{ !isset($kelas) ? 'active' : '' }}">Semua Unit</a>
            @foreach($allClasses as $c)
                <a href="{{ route('jadwal.kelas', $c) }}" class="mp-tab {{ (isset($kelas) && $kelas == $c) ? 'active' : '' }}">{{ $c }}</a>
            @endforeach
        </div>
    </section>

    @if(!isset($kelas))
        <section class="mp-selection-grid">
            @foreach($allClasses as $c)
                @php $count = $countsByClass[$c] ?? 0; @endphp
                <a href="{{ route('jadwal.kelas', $c) }}" class="mp-select-card mp-hover">
                    <span class="mp-badge">Kelas</span>
                    <div class="mp-select-title">{{ $c }}</div>
                    <div class="mp-small">{{ $count }} mata pelajaran terdaftar</div>
                    <div style="margin-top: 24px; font-weight: 900;">Buka Jadwal <i class="bi bi-arrow-right"></i></div>
                </a>
            @endforeach
        </section>
    @endif

    @if(isset($kelas) && !isset($hari))
        @php $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; @endphp
        <section class="mp-selection-grid">
            @foreach($daysList as $d)
                @php $countDay = $countsByDay[$d] ?? 0; @endphp
                <a href="{{ route('jadwal.kelas', ['kelas' => $kelas, 'hari' => $d]) }}" class="mp-select-card mp-hover">
                    <span class="mp-badge">{{ $kelas }}</span>
                    <div class="mp-select-title">{{ $d }}</div>
                    <div class="mp-small">{{ $countDay }} sesi belajar</div>
                    <div style="margin-top: 24px; font-weight: 900;">Lihat Hari <i class="bi bi-arrow-right"></i></div>
                </a>
            @endforeach
        </section>
    @endif

    @if(isset($kelas) && isset($hari))
        <section class="mp-table-card">
            <div style="padding: 28px 30px; border-bottom: 4px solid var(--midnight); background: var(--cyber); display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; align-items: center;">
                <div>
                    <div class="mp-label" style="margin-bottom: 6px;">Jadwal Aktif</div>
                    <div style="font-family: 'Fredoka One', cursive; font-size: 28px; color: var(--midnight);">{{ $kelas }} - {{ $hari }}</div>
                </div>
                <span class="mp-badge" style="background: var(--white);">Semester Gasal 2026</span>
            </div>

            <div class="mp-table-wrap">
                <table class="mp-table" style="min-width: 1180px;">
                    <thead>
                        <tr>
                            <th style="width: 120px;" class="mp-center">Jam</th>
                            @for($i=1; $i<=12; $i++)
                                <th class="mp-center">{{ $i }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="mp-center">{{ $hari }}</td>
                            @php
                                $currentJadwal = $jadwal->where('hari', $hari);
                                $occupiedUntil = 0;
                            @endphp

                            @for($i=1; $i<=12; $i++)
                                @if($i <= $occupiedUntil)
                                    @continue
                                @endif

                                @php $lesson = $currentJadwal->where('jam_mulai', $i)->first(); @endphp

                                @if($lesson)
                                    @php
                                        $span = ($lesson->jam_selesai - $lesson->jam_mulai) + 1;
                                        $occupiedUntil = $lesson->jam_selesai;
                                    @endphp
                                    <td colspan="{{ $span }}" style="padding: 8px; min-width: 120px;">
                                        <a href="{{ route('jadwal.edit', $lesson->kd_jp) }}" class="mp-select-card mp-hover" style="padding: 18px; box-shadow: 4px 4px 0 var(--midnight); min-height: 120px;">
                                            <div style="font-family: 'Fredoka One', cursive; font-size: 16px; line-height: 1.25; color: var(--midnight);">{{ $lesson->mapel->nama_mapel ?? $lesson->kd_mapel }}</div>
                                            <div style="margin-top: 10px; font-size: 12px; font-weight: 900; color: var(--midnight);">{{ $lesson->guru->nama_guru ?? '-' }}</div>
                                            @if($lesson->ruangan)
                                                <span class="mp-badge" style="margin-top: 12px;">{{ $lesson->ruangan }}</span>
                                            @endif
                                        </a>
                                    </td>
                                @else
                                    <td style="background: var(--mochi);"></td>
                                @endif
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    @php
        $createUrlParams = [];
        if(isset($kelas)) $createUrlParams['kelas'] = $kelas;
        if(isset($hari)) $createUrlParams['hari'] = $hari;
    @endphp
</div>

<a href="{{ route('jadwal.create', $createUrlParams) }}" class="mp-fab" title="Tambah Jadwal">
    <i class="bi bi-plus-lg"></i>
</a>

@endsection
