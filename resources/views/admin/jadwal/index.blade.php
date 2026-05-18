@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Akademik</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Kurikulum</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Visualisasi dan pengaturan jadwal mata pelajaran lintas kelas dan tingkatan secara real-time.
            </p>
        </div>
    </div>


    <!-- Actions & Selectors Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div style="display: flex; gap: 32px; flex-wrap: wrap;">
            <a href="{{ route('jadwal.index') }}" style="text-decoration: none; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; color: {{ !isset($kelas) ? 'var(--color-ink)' : 'var(--color-stone)' }}; font-weight: 700; border-bottom: {{ !isset($kelas) ? '2px solid var(--color-ink)' : 'none' }}; padding-bottom: 8px;">Semua Unit</a>
            @php
                $allClasses = \App\Models\JadwalPelajaran::distinct()->pluck('kelas')->sort();
            @endphp
            @foreach($allClasses as $c)
                <a href="{{ route('jadwal.kelas', $c) }}" style="text-decoration: none; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; color: {{ (isset($kelas) && $kelas == $c) ? 'var(--color-ink)' : 'var(--color-stone)' }}; font-weight: 700; border-bottom: {{ (isset($kelas) && $kelas == $c) ? '2px solid var(--color-ink)' : 'none' }}; padding-bottom: 8px;">{{ $c }}</a>
            @endforeach
        </div>
    </div>

    @if(!isset($kelas))
        <!-- Dashboard: Grid of Classes -->
        @php $classCount = $allClasses->count(); @endphp
        <div class="responsive-card-grid">
            @foreach($allClasses as $c)
                @php 
                    $count = \App\Models\JadwalPelajaran::where('kelas', $c)->count();
                    $isFullWidth = ($classCount % 2 !== 0 && $loop->first) || $classCount === 1;
                @endphp
                <a href="{{ route('jadwal.kelas', $c) }}" class="card selection-card" 
                   style="background: #ffffff; text-decoration: none; padding: 48px; color: var(--color-ink); display: block; border-radius: 16px; border: 1px solid var(--color-hairline); 
                   @if($isFullWidth) grid-column: 1 / -1; @endif">
                    <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 16px; font-weight: 700;">VOL. 0{{ $loop->iteration }}</div>
                    <div class="display-text" style="font-size: 32px; font-weight: 400; margin-bottom: 24px; letter-spacing: var(--tracking-tighter); text-transform: uppercase;">Kelas {{ $c }}</div>
                    <div class="text-meta" style="color: var(--color-slate); font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">{{ $count }} Mata Pelajaran Terdaftar</div>
                </a>
            @endforeach
        </div>
    @endif

    @if(isset($kelas) && !isset($hari))
        <!-- Dashboard: Choose Day -->
        @php 
            $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; 
            $daysCount = count($daysList);
        @endphp
        <div class="responsive-card-grid">
            @foreach($daysList as $d)
                @php 
                    $countDay = \App\Models\JadwalPelajaran::where('kelas', $kelas)->where('hari', $d)->count();
                    $isFullWidth = ($daysCount % 2 !== 0 && $loop->first) || $daysCount === 1;
                @endphp
                <a href="{{ route('jadwal.kelas', ['kelas' => $kelas, 'hari' => $d]) }}" class="card selection-card" 
                   style="background: #ffffff; text-decoration: none; padding: 48px; color: var(--color-ink); display: block; border-radius: 16px; border: 1px solid var(--color-hairline);
                   @if($isFullWidth) grid-column: 1 / -1; @endif">
                    <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 16px; font-weight: 700;">{{ $kelas }}</div>
                    <div class="display-text" style="font-size: 32px; font-weight: 400; margin-bottom: 24px; letter-spacing: var(--tracking-tighter); text-transform: uppercase;">{{ $d }}</div>
                    <div class="text-meta" style="color: var(--color-slate); font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">{{ $countDay }} Sesi Belajar</div>
                </a>
            @endforeach
        </div>
    @endif

    @if(isset($kelas) && isset($hari))
        <!-- Visual Grid Card -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); margin-bottom: var(--spacing-section);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border-bottom: 1px solid var(--color-ink); padding-bottom: 24px;">
                <div style="font-size: 24px; font-weight: 400; letter-spacing: var(--tracking-tight);">{{ $kelas }} — {{ $hari }}</div>
                <div class="text-micro-caps" style="color: var(--color-stone); letter-spacing: 0.1em; font-weight: 700;">Gasal 2026 / Editorial System</div>
            </div>
            
            <div style="overflow-x: auto; border: 1px solid var(--color-hairline);">
                <table style="width: 100%; border-collapse: collapse; min-width: 1200px;">
                    <thead>
                        <tr>
                            <th class="text-micro-caps" style="padding: 24px; border-bottom: 2px solid var(--color-ink); border-right: 1px solid var(--color-hairline); background: var(--color-surface); width: 120px; font-weight: 700;">JAM</th>
                            @for($i=1; $i<=12; $i++)
                                <th class="text-micro-caps" style="padding: 24px; border-bottom: 2px solid var(--color-ink); text-align: center; font-weight: 700; background: var(--color-surface);">{{ $i }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background: #FFFFFF;">
                            <td class="text-body-strong" style="padding: 40px 24px; border-right: 1px solid var(--color-hairline); text-align: center; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.1em;">{{ $hari }}</td>
                            @php
                                $currentJadwal = $jadwal->where('hari', $hari);
                                $occupiedUntil = 0;
                            @endphp

                            @for($i=1; $i<=12; $i++)
                                @if($i <= $occupiedUntil)
                                    @continue
                                @endif

                                @php
                                    $lesson = $currentJadwal->where('jam_mulai', $i)->first();
                                @endphp

                                @if($lesson)
                                    @php
                                        $span = ($lesson->jam_selesai - $lesson->jam_mulai) + 1;
                                        $occupiedUntil = $lesson->jam_selesai;
                                    @endphp
                                    <td colspan="{{ $span }}" style="padding: 4px;">
                                        <div style="background: var(--color-ink); color: #FFFFFF; padding: 24px; min-height: 140px; display: flex; flex-direction: column; justify-content: center; cursor: pointer; transition: opacity 0.2s;" onclick="window.location='{{ route('jadwal.edit', $lesson->kd_jp) }}'">
                                            <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">{{ $lesson->mapel->nama_mapel ?? $lesson->kd_mapel }}</div>
                                            <div style="color: var(--color-stone); font-size: 12px; font-weight: 500;">{{ $lesson->guru->nama_guru ?? '-' }}</div>
                                            @if($lesson->ruangan)
                                                <div style="font-size: 10px; margin-top: 16px; opacity: 0.5; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700;">{{ $lesson->ruangan }}</div>
                                            @endif
                                        </div>
                                    </td>
                                @else
                                    <td style="border-right: 1px solid var(--color-hairline);"></td>
                                @endif
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- FAB Action -->
    <div class="fab-container">
        @php
            $createUrlParams = [];
            if(isset($kelas)) $createUrlParams['kelas'] = $kelas;
            if(isset($hari)) $createUrlParams['hari'] = $hari;
        @endphp
        <a href="{{ route('jadwal.create', $createUrlParams) }}" class="btn-fab" title="Tambah Jadwal">
            <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
            <span class="fab-label">Tambah Jadwal Baru</span>
        </a>
    </div>

</div>

@endsection
