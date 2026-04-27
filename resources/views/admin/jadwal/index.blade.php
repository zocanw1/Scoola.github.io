@extends('layouts.admin')

@section('page-title', 'Jadwal Pelajaran')
@section('breadcrumb', 'Jadwal')

@section('content')

<style>
    /* ── PAGE HEADER ─────────────────────────── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header-left .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
    }

    .page-header-left .page-subtitle {
        font-size: 13px;
        color: var(--text2);
        margin-top: 4px;
    }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; background: var(--accent); color: var(--navy);
        border: none; border-radius: 10px; font-size: 13px; font-weight: 700;
        text-decoration: none; transition: all .2s; white-space: nowrap;
    }

    .btn-primary:hover { background: #79baff; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(88,166,255,0.4); }

    /* ── SCHEDULE GRID ─────────────────────────── */
    .schedule-container {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .schedule-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255,255,255,0.03);
    }

    .schedule-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text1);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .schedule-title i { color: var(--accent); font-size: 18px; }

    .grid-wrap {
        overflow-x: auto;
        padding: 24px;
        scrollbar-width: thin;
        scrollbar-color: var(--glass-border) transparent;
    }

    .visual-grid {
        width: 100%;
        min-width: 1200px;
        border-collapse: separate;
        border-spacing: 8px;
        table-layout: fixed;
    }

    .visual-grid th {
        padding: 12px;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .visual-grid td {
        height: 90px;
        vertical-align: top;
        position: relative;
    }

    .day-cell {
        width: 110px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: var(--text1);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px;
        box-shadow: inset 0 0 15px rgba(0,0,0,0.1);
    }

    .lesson-card {
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        padding: 12px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 5px solid var(--accent);
        cursor: pointer;
    }

    .lesson-card:hover {
        background: var(--glass-hover);
        border-color: rgba(88,166,255,0.5);
        transform: scale(1.02);
        z-index: 10;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }

    .mapel-name {
        font-weight: 700;
        font-size: 13px;
        color: var(--text1);
        line-height: 1.3;
        margin-bottom: 6px;
    }

    .guru-name {
        font-size: 11px;
        color: var(--text2);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ruangan-tag {
        font-size: 10px;
        background: rgba(88,166,255,0.12);
        color: var(--accent);
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 700;
        margin-top: 8px;
        align-self: flex-start;
        border: 1px solid rgba(88,166,255,0.1);
    }

    .empty-slot {
        background: rgba(255,255,255,0.015);
        border: 1px dashed rgba(255,255,255,0.08);
        border-radius: 10px;
        height: 100%;
    }

    /* Color variations */
    .lesson-card.color-1 { border-left-color: #58a6ff; }
    .lesson-card.color-2 { border-left-color: #bc8cff; }
    .lesson-card.color-3 { border-left-color: #3fb950; }
    .lesson-card.color-4 { border-left-color: #e3b341; }
    .lesson-card.color-5 { border-left-color: #f78166; }

    /* ── CLASS SELECTOR ─────────────────────────── */
    .class-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .class-btn {
        padding: 8px 18px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        color: var(--text2);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .class-btn.active {
        background: var(--accent);
        color: var(--navy);
        border-color: var(--accent);
        box-shadow: 0 4px 12px rgba(88,166,255,0.3);
    }

    /* ── LIST VIEW CARD ─────────────────────────── */
    .list-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        overflow: hidden;
        margin-top: 40px;
    }

    .list-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--glass-border);
        background: rgba(255,255,255,0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 14px 20px;
        background: rgba(0,0,0,0.1);
        font-size: 11px;
        font-weight: 700;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        text-align: left;
    }

    .data-table td {
        padding: 14px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        color: var(--text2);
        font-size: 13px;
    }

    .data-table tr:hover td {
        background: rgba(255,255,255,0.02);
        color: var(--text1);
    }

    .jam-badge {
        background: rgba(63,185,80,0.1);
        color: var(--green);
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12px;
        border: 1px solid rgba(63,185,80,0.15);
    }

    .hari-badge {
        background: rgba(88,166,255,0.1);
        color: var(--accent);
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12px;
        border: 1px solid rgba(88,166,255,0.15);
    }
</style>

@if (session('success'))
    <div style="display:flex; align-items:center; gap:12px; padding:14px 20px; background:rgba(63,185,80,0.12); border:1px solid rgba(63,185,80,0.25); border-radius:12px; color:var(--green); font-size:14px; margin-bottom:24px; animation: slideIn 0.3s ease;">
        <i class="bi bi-check-circle-fill" style="font-size:18px"></i>
        <span style="font-weight:600">{{ session('success') }}</span>
    </div>
@endif

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Jadwal Pelajaran</div>
        <div class="page-subtitle">Kelola dan visualisasikan jadwal pelajaran sekolah secara terorganisir</div>
    </div>
    <a href="{{ route('jadwal.create') }}" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Jadwal Baru
    </a>
</div>

{{-- CLASS SELECTOR --}}
<div class="class-selector">
    <a href="{{ route('jadwal.index') }}" class="class-btn {{ !isset($kelas) ? 'active' : '' }}">Semua Kelas</a>
    @php
        $allClasses = \App\Models\JadwalPelajaran::distinct()->pluck('kelas')->sort();
    @endphp
    @foreach($allClasses as $c)
        <a href="{{ route('jadwal.kelas', $c) }}" class="class-btn {{ (isset($kelas) && $kelas == $c) ? 'active' : '' }}">{{ $c }}</a>
    @endforeach
</div>

@if(!isset($kelas))
    {{-- DASHBOARD VIEW: CHOOSE CLASS --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px;">
        @foreach($allClasses as $c)
            @php
                $count = \App\Models\JadwalPelajaran::where('kelas', $c)->count();
            @endphp
            <a href="{{ route('jadwal.kelas', $c) }}" class="schedule-container" style="text-decoration:none; padding:24px; display:flex; flex-direction:column; align-items:center; gap:16px; transition:all 0.3s; margin-bottom:0; cursor:pointer; background: var(--navy3);">
                <div style="width:64px; height:64px; border-radius:16px; background:rgba(88,166,255,0.1); color:var(--accent); display:grid; place-items:center; font-size:28px; transition:all 0.3s;" class="class-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div style="text-align:center">
                    <div style="font-size:18px; font-weight:800; color:var(--text1)">{{ $c }}</div>
                    <div style="font-size:12px; color:var(--text3); text-transform:uppercase; margin-top:4px; letter-spacing:0.05em">{{ $count }} Mata Pelajaran</div>
                </div>
                <div style="margin-top:8px; font-size:11px; font-weight:700; color:var(--accent); background:rgba(88,166,255,0.1); padding:4px 12px; border-radius:20px;">LIHAT JADWAL <i class="bi bi-arrow-right" style="margin-left:4px"></i></div>
            </a>
        @endforeach
    </div>
@endif

@if(isset($kelas) && !isset($hari))
    {{-- DASHBOARD VIEW: CHOOSE DAY --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        @php
            $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        @endphp
        @foreach($daysList as $d)
            @php
                $countDay = \App\Models\JadwalPelajaran::where('kelas', $kelas)->where('hari', $d)->count();
            @endphp
            <a href="{{ route('jadwal.kelas', ['kelas' => $kelas, 'hari' => $d]) }}" class="schedule-container" style="text-decoration:none; padding:24px; display:flex; flex-direction:column; align-items:center; gap:16px; transition:all 0.3s; margin-bottom:0; cursor:pointer; background: var(--navy3);">
                <div style="width:64px; height:64px; border-radius:16px; background:rgba(88,166,255,0.1); color:var(--accent); display:grid; place-items:center; font-size:28px; transition:all 0.3s;" class="class-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div style="text-align:center">
                    <div style="font-size:18px; font-weight:800; color:var(--text1)">{{ $d }}</div>
                    <div style="font-size:12px; color:var(--text3); text-transform:uppercase; margin-top:4px; letter-spacing:0.05em">{{ $countDay }} Mata Pelajaran</div>
                </div>
                <div style="margin-top:8px; font-size:11px; font-weight:700; color:var(--accent); background:rgba(88,166,255,0.1); padding:4px 12px; border-radius:20px;">LIHAT JADWAL HARI INI <i class="bi bi-arrow-right" style="margin-left:4px"></i></div>
            </a>
        @endforeach
    </div>
@endif

@if(isset($kelas) && isset($hari))
    <div style="margin-bottom: 20px;">
        <a href="{{ route('jadwal.kelas', $kelas) }}" style="color:var(--text2); text-decoration:none; font-size:14px; display:inline-flex; align-items:center; gap:6px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Pilihan Hari
        </a>
    </div>

    {{-- VISUAL GRID FOR SPECIFIC CLASS AND DAY --}}
    <div class="schedule-container">
        <div class="schedule-header">
            <div class="schedule-title">
                <i class="bi bi-calendar3"></i>
                Visualisasi Jadwal: <span style="color:var(--accent); margin-left:4px">{{ $kelas }} - Hari {{ $hari }}</span>
            </div>
            <div style="font-size: 11px; color: var(--text3); font-weight: 700; letter-spacing:0.1em">
                GASAL 2025/2026
            </div>
        </div>

        <div class="grid-wrap">
            <table class="visual-grid">
                <thead>
                    <tr>
                        <th style="width: 110px;">HARI</th>
                        @for($i=1; $i<=12; $i++)
                            <th>JAM {{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @php
                        $days = [$hari];
                        $jadwalByDay = $jadwal->groupBy('hari');
                    @endphp

                    @foreach($days as $day)
                        <tr>
                            <td class="day-cell">{{ $day }}</td>
                            @php
                                $currentJadwal = $jadwalByDay->get($day, collect());
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
                                        $colorIdx = (crc32($lesson->kd_mapel) % 5) + 1;
                                    @endphp
                                    <td colspan="{{ $span }}">
                                        <div class="lesson-card color-{{ $colorIdx }}" onclick="window.location='{{ route('jadwal.edit', $lesson->kd_jp) }}'">
                                            <div class="mapel-name">{{ $lesson->mapel->nama_mapel ?? $lesson->kd_mapel }}</div>
                                            <div class="guru-name">
                                                <i class="bi bi-person-fill"></i>
                                                {{ $lesson->guru->nama_guru ?? '-' }}
                                            </div>
                                            @if($lesson->ruangan)
                                                <div class="ruangan-tag"><i class="bi bi-geo-alt-fill" style="margin-right:3px"></i>{{ $lesson->ruangan }}</div>
                                            @endif
                                            
                                            <div style="position:absolute; top:8px; right:10px; display:flex; gap:6px; opacity:0.3" class="card-actions">
                                                <i class="bi bi-pencil-square"></i>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td><div class="empty-slot"></div></td>
                                @endif
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif



@endsection
