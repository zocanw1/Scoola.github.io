@extends('layouts.admin')

@section('page-title', 'Jadwal Pelajaran')
@section('breadcrumb', 'Jadwal')

@section('content')

<style>
    /* ── PAGE HEADER ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header-left .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .page-header-left .page-subtitle {
        font-size: 13px;
        color: var(--text2);
        margin-top: 4px;
    }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 11px 22px;
        background: var(--gradient-accent, linear-gradient(135deg, #60a5fa, #818cf8));
        color: #fff;
        border: none; border-radius: 12px;
        font-size: 13px; font-weight: 700;
        text-decoration: none; transition: all .25s;
        white-space: nowrap;
        box-shadow: 0 2px 10px rgba(96,165,250,0.25);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(96,165,250,0.4);
        color: #fff;
    }

    /* ── CLASS / DAY SELECTOR ── */
    .selector-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .selector-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 24px 20px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .selector-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: var(--gradient-accent, linear-gradient(135deg, #60a5fa, #818cf8));
        opacity: 0;
        transition: opacity .3s;
    }

    .selector-card:hover {
        border-color: rgba(96,165,250,0.3);
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.2);
    }

    .selector-card:hover::before { opacity: 1; }

    .selector-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        display: grid; place-items: center;
        font-size: 24px;
        transition: all .3s;
    }

    .selector-icon.blue {
        background: var(--accent-soft, rgba(96,165,250,0.1));
        color: var(--accent);
    }

    .selector-icon.purple {
        background: var(--purple-soft, rgba(188,140,255,0.1));
        color: var(--purple);
    }

    .selector-card:hover .selector-icon {
        transform: scale(1.08);
    }

    .selector-name {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 17px; font-weight: 800;
        color: var(--text1);
        text-align: center;
    }

    .selector-count {
        font-size: 11.5px;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 600;
    }

    .selector-cta {
        font-size: 11px; font-weight: 700;
        color: var(--accent);
        background: var(--accent-soft, rgba(96,165,250,0.08));
        padding: 5px 14px;
        border-radius: 20px;
        display: flex; align-items: center; gap: 5px;
        transition: all .2s;
    }

    .selector-card:hover .selector-cta {
        background: var(--accent);
        color: #fff;
    }

    /* ── CLASS SELECTOR TABS ── */
    .class-selector {
        display: flex;
        gap: 8px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .class-btn {
        padding: 8px 20px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        color: var(--text2);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .class-btn:hover {
        border-color: rgba(96,165,250,0.3);
        color: var(--text1);
        background: var(--accent-soft, rgba(96,165,250,0.08));
    }

    .class-btn.active {
        background: var(--gradient-accent, linear-gradient(135deg, #60a5fa, #818cf8));
        color: #fff;
        border-color: transparent;
        box-shadow: 0 3px 12px rgba(96,165,250,0.3);
    }

    /* ── SCHEDULE CONTAINER ── */
    .schedule-container {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 28px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }

    .schedule-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--glass);
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

    .semester-badge {
        font-size: 11px;
        color: var(--text3);
        font-weight: 700;
        letter-spacing: 0.1em;
        background: var(--navy3);
        padding: 4px 12px;
        border-radius: 8px;
        border: 1px solid var(--glass-border);
    }

    .grid-wrap {
        overflow-x: auto;
        padding: 20px;
        scrollbar-width: thin;
        scrollbar-color: var(--glass-border) transparent;
    }

    .visual-grid {
        width: 100%;
        min-width: 1100px;
        border-collapse: separate;
        border-spacing: 6px;
        table-layout: fixed;
    }

    .visual-grid th {
        padding: 10px;
        text-align: center;
        font-size: 10.5px;
        font-weight: 700;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .visual-grid td {
        height: 85px;
        vertical-align: top;
        position: relative;
    }

    .day-cell {
        width: 100px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: var(--text1);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        box-shadow: inset 0 0 12px rgba(0,0,0,0.08);
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
        border-left: 4px solid var(--accent);
        cursor: pointer;
        position: relative;
    }

    .lesson-card:hover {
        background: var(--glass-hover);
        border-color: rgba(96,165,250,0.4);
        transform: scale(1.03);
        z-index: 10;
        box-shadow: 0 8px 28px rgba(0,0,0,0.25);
    }

    .mapel-name {
        font-weight: 700;
        font-size: 13px;
        color: var(--text1);
        line-height: 1.3;
        margin-bottom: 5px;
    }

    .guru-name {
        font-size: 11.5px;
        color: var(--text2);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .ruangan-tag {
        font-size: 10px;
        background: var(--accent-soft, rgba(96,165,250,0.08));
        color: var(--accent);
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 700;
        margin-top: 6px;
        align-self: flex-start;
    }

    .empty-slot {
        background: var(--glass);
        border: 1px dashed var(--glass-border);
        border-radius: 10px;
        height: 100%;
    }

    [data-theme="light"] .empty-slot {
        background: rgba(0,0,0,0.015);
        border-color: rgba(0,0,0,0.06);
    }

    .lesson-card.color-1 { border-left-color: #60a5fa; }
    .lesson-card.color-2 { border-left-color: #a78bfa; }
    .lesson-card.color-3 { border-left-color: #34d399; }
    .lesson-card.color-4 { border-left-color: #fbbf24; }
    .lesson-card.color-5 { border-left-color: #f87171; }

    /* ── BACK LINK ── */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text3);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 20px;
        transition: color .2s;
        padding: 6px 0;
    }

    .back-link:hover { color: var(--accent); }

    /* ── FLASH SUCCESS ── */
    .flash-success {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        background: var(--green-soft, rgba(52,211,153,0.08));
        border: 1px solid rgba(52,211,153,0.2);
        border-radius: 12px;
        color: var(--green);
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 24px;
        animation: fadeInUp .3s ease;
    }

    .flash-success i { font-size: 18px; }

    /* ── MOBILE ── */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 14px;
        }
        .btn-primary {
            width: 100%;
            justify-content: center;
        }
        .selector-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
        }
        .class-selector {
            overflow-x: auto;
            flex-wrap: nowrap;
            scrollbar-width: none;
            padding-bottom: 4px;
        }
        .class-selector::-webkit-scrollbar { display: none; }
        .class-btn { flex-shrink: 0; }
    }

    /* ── ANIMATION ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fi { animation: fadeInUp .35s ease both; }
    .fi.d1 { animation-delay: .05s; }
    .fi.d2 { animation-delay: .1s; }
    .fi.d3 { animation-delay: .15s; }

    .selector-card {
        animation: fadeInUp .4s ease both;
    }
    @for ($i = 1; $i <= 10; $i++)
    .selector-card:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 60 }}ms; }
    @endfor
</style>

{{-- FLASH SUCCESS --}}
@if (session('success'))
    <div class="flash-success">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- PAGE HEADER --}}
<div class="page-header fi">
    <div class="page-header-left">
        <div class="page-title">
            <i class="bi bi-calendar3" style="color:var(--accent); margin-right:4px"></i>
            Jadwal Pelajaran
        </div>
        <div class="page-subtitle">Kelola dan visualisasikan jadwal pelajaran sekolah secara terorganisir</div>
    </div>
    @php
        $createUrlParams = [];
        if(isset($kelas)) $createUrlParams['kelas'] = $kelas;
        if(isset($hari)) $createUrlParams['hari'] = $hari;
    @endphp
    <a href="{{ route('jadwal.create', $createUrlParams) }}" class="btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Jadwal Baru
    </a>
</div>

{{-- CLASS SELECTOR TABS --}}
<div class="class-selector fi d1">
    <a href="{{ route('jadwal.index') }}" class="class-btn {{ !isset($kelas) ? 'active' : '' }}">
        <i class="bi bi-grid-3x3-gap" style="margin-right:4px"></i> Semua Kelas
    </a>
    @php
        $allClasses = \App\Models\JadwalPelajaran::distinct()->pluck('kelas')->sort();
    @endphp
    @foreach($allClasses as $c)
        <a href="{{ route('jadwal.kelas', $c) }}" class="class-btn {{ (isset($kelas) && $kelas == $c) ? 'active' : '' }}">
            {{ $c }}
        </a>
    @endforeach
</div>

{{-- ═══════════ VIEW 1: ALL CLASSES DASHBOARD ═══════════ --}}
@if(!isset($kelas))
    <div class="selector-grid fi d2">
        @foreach($allClasses as $c)
            @php
                $count = \App\Models\JadwalPelajaran::where('kelas', $c)->count();
            @endphp
            <a href="{{ route('jadwal.kelas', $c) }}" class="selector-card">
                <div class="selector-icon blue">
                    <i class="bi bi-building"></i>
                </div>
                <div class="selector-name">{{ $c }}</div>
                <div class="selector-count">{{ $count }} Mata Pelajaran</div>
                <div class="selector-cta">
                    Lihat Jadwal <i class="bi bi-arrow-right"></i>
                </div>
            </a>
        @endforeach
    </div>
@endif

{{-- ═══════════ VIEW 2: CHOOSE DAY ═══════════ --}}
@if(isset($kelas) && !isset($hari))
    <div class="back-link fi">
        <a href="{{ route('jadwal.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Semua Kelas
        </a>
    </div>

    <div class="selector-grid fi d2">
        @php
            $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            $dayIcons = ['bi-1-circle', 'bi-2-circle', 'bi-3-circle', 'bi-4-circle', 'bi-5-circle'];
        @endphp
        @foreach($daysList as $idx => $d)
            @php
                $countDay = \App\Models\JadwalPelajaran::where('kelas', $kelas)->where('hari', $d)->count();
            @endphp
            <a href="{{ route('jadwal.kelas', ['kelas' => $kelas, 'hari' => $d]) }}" class="selector-card">
                <div class="selector-icon purple">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="selector-name">{{ $d }}</div>
                <div class="selector-count">{{ $countDay }} Mata Pelajaran</div>
                <div class="selector-cta">
                    Lihat Jadwal <i class="bi bi-arrow-right"></i>
                </div>
            </a>
        @endforeach
    </div>
@endif

{{-- ═══════════ VIEW 3: SPECIFIC DAY VISUAL GRID ═══════════ --}}
@if(isset($kelas) && isset($hari))
    <a href="{{ route('jadwal.kelas', $kelas) }}" class="back-link fi">
        <i class="bi bi-arrow-left"></i> Kembali ke Pilihan Hari
    </a>

    <div class="schedule-container fi d1">
        <div class="schedule-header">
            <div class="schedule-title">
                <i class="bi bi-calendar3"></i>
                Jadwal: <span style="color:var(--accent); margin-left:4px">{{ $kelas }} — {{ $hari }}</span>
            </div>
            <div class="semester-badge">
                GASAL 2025/2026
            </div>
        </div>

        <div class="grid-wrap">
            <table class="visual-grid">
                <thead>
                    <tr>
                        <th style="width: 100px;">HARI</th>
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
