@extends('layouts.admin')

@section('content')

<style>
    /* ── PAGE HEADER ─────────────────────────── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header-left .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
    }

    .page-header-left .page-subtitle {
        font-size: 12px;
        color: var(--text2);
        margin-top: 3px;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        background: var(--accent);
        color: var(--navy);
        border: none;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }

    .btn-primary:hover {
        background: #79baff;
        color: var(--navy);
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(88,166,255,0.3);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }

    .btn-secondary:hover {
        background: var(--glass-hover);
        color: var(--text1);
        border-color: var(--text3);
    }

    /* ── CARD ─────────────────────────── */
    .card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        overflow: hidden;
    }

    .card-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid var(--glass-border);
        gap: 12px;
        flex-wrap: wrap;
    }

    .card-toolbar-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-label {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text1);
    }

    .count-badge {
        font-size: 10px;
        font-weight: 700;
        background: rgba(88,166,255,.12);
        color: var(--accent);
        padding: 2px 8px;
        border-radius: 10px;
    }

    /* ── TABLE ─────────────────────────── */
    .tbl-wrap {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 10px 16px;
        text-align: left;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--text3);
        border-bottom: 1px solid var(--glass-border);
        white-space: nowrap;
        background: var(--navy2);
    }

    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        font-size: 12.5px;
        color: var(--text2);
        vertical-align: middle;
    }

    .data-table tbody tr {
        transition: background .15s;
    }

    .data-table tbody tr:hover td {
        background: var(--glass-hover);
        color: var(--text1);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ── CELLS ─────────────────────────── */
    .badge-hari {
        display: inline-block;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: var(--accent);
        background: rgba(88,166,255,0.08);
        padding: 3px 8px;
        border-radius: 5px;
        letter-spacing: .03em;
    }

    .jam-box {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--green);
        background: rgba(63,185,80,.08);
        padding: 3px 8px;
        border-radius: 5px;
        display: inline-block;
    }

    .cell-mapel {
        font-weight: 600;
        color: var(--text1);
    }

    .cell-guru {
        color: var(--text2);
        font-size: 12px;
    }

    /* ── ACTION BUTTONS ─────────────────────────── */
    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        background: rgba(88,166,255,0.08);
        border: 1px solid rgba(88,166,255,0.15);
        color: var(--accent);
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 500;
        text-decoration: none;
        transition: all .15s;
        font-family: 'Inter', sans-serif;
    }

    .btn-edit:hover {
        background: rgba(88,166,255,0.18);
        border-color: rgba(88,166,255,0.4);
        color: var(--accent);
    }



    /* ── EMPTY STATE ─────────────────────────── */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        font-size: 40px;
        color: var(--text3);
        margin-bottom: 12px;
    }

    .empty-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text2);
        margin-bottom: 5px;
    }

    .empty-desc {
        font-size: 12px;
        color: var(--text3);
    }

    /* ── ALERT ─────────────────────────── */
    .alert-success {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        background: rgba(63,185,80,0.1);
        border: 1px solid rgba(63,185,80,0.2);
        border-radius: 8px;
        color: var(--green);
        font-size: 12.5px;
        font-weight: 500;
        margin-bottom: 16px;
    }
</style>

@if (session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">
            Jadwal Pelajaran
            @isset($kelas)
                <span style="color:var(--text3); font-size:16px;">({{ $kelas }})</span>
            @endisset
        </div>
        <div class="page-subtitle">Kelola jadwal pelajaran secara modern</div>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('jadwal.index') }}" class="btn-secondary">
            <i class="bi bi-book"></i> Daftar Kelas
        </a>
        <a href="{{ route('jadwal.create') }}" class="btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Jadwal
        </a>
    </div>
</div>

<div class="card">
    <div class="card-toolbar">
        <div class="card-toolbar-left">
            <span class="card-label">Daftar Jadwal</span>
            <span class="count-badge">{{ count($jadwal) }} data</span>
        </div>
    </div>

    <div class="tbl-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Waktu (Jam Ke)</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th style="padding-left:0;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal as $j)
                <tr>
                    <td>
                        <span class="badge-hari">{{ $j->hari }}</span>
                    </td>
                    <td>
                        <span class="jam-box">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</span>
                    </td>
                    <td>
                        <div class="cell-mapel">{{ $j->mapel->nama_mapel ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="cell-guru">
                            <i class="bi bi-person-fill" style="margin-right:4px;"></i>
                            {{ $j->guru->nama_guru ?? '-' }}
                        </div>
                    </td>
                    <td style="padding-left:0;">
                        <div class="action-group">
                            <a href="{{ route('jadwal.edit', $j->kd_jp) }}" class="btn-edit">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:0; border:none">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                            <div class="empty-title">Belum ada jadwal</div>
                            <div class="empty-desc">Silakan tambahkan jadwal baru untuk kelas ini.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection