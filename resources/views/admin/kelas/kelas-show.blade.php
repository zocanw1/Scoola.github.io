@extends('layouts.admin')

@section('content')

<style>
    /* ── PAGE HEADER ─────────────────────────── */
    .page-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .btn-back {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        display: grid; place-items: center;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: var(--glass-hover);
        color: var(--accent);
        border-color: var(--accent);
    }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
    }

    /* ── CARD ─────────────────────────── */
    .card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        overflow: hidden;
    }

    .card-toolbar {
        padding: 16px 20px;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-label {
        font-size: 13px;
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
        padding: 12px 20px;
        text-align: left;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--text3);
        border-bottom: 1px solid var(--glass-border);
        background: var(--navy2);
    }

    .data-table td {
        padding: 14px 20px;
        border-bottom: 1px solid var(--glass-border);
        font-size: 13px;
        color: var(--text2);
        vertical-align: middle;
    }

    .data-table tbody tr:hover td {
        background: var(--glass-hover);
        color: var(--text1);
    }

    .cell-name {
        font-weight: 600;
        color: var(--text1);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar-sm {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--purple));
        display: grid; place-items: center;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .nis-badge {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: var(--accent);
        background: rgba(88,166,255,0.08);
        padding: 3px 8px;
        border-radius: 5px;
    }

    /* ── EMPTY STATE ─────────────────────────── */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: var(--text3);
    }

    .empty-state i {
        font-size: 40px;
        margin-bottom: 12px;
        display: block;
    }
</style>

<div class="page-header">
    <a href="{{ route('admin.kelas.index') }}" class="btn-back">
        <i class="bi bi-chevron-left"></i>
    </a>
    <div>
        <div class="page-title">Siswa Kelas: {{ $kelas }}</div>
    </div>
</div>

<div class="card">
    <div class="card-toolbar">
        <div class="card-label">Daftar Siswa</div>
        <span class="count-badge">{{ $siswa->count() }} orang</span>
    </div>

    <div class="tbl-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th style="text-align:center">Aksi (Siswa)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa as $index => $s)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="nis-badge">{{ $s->NIS }}</span></td>
                    <td>
                        <div class="cell-name">
                            <div class="avatar-sm">{{ strtoupper(substr($s->nama_siswa, 0, 1)) }}</div>
                            {{ $s->nama_siswa }}
                        </div>
                    </td>
                    <td>{{ $s->user->email ?? '-' }}</td>
                    <td style="text-align:center">
                        <a href="{{ route('siswa.edit', $s->NIS) }}" class="btn-back" style="display:inline-grid; border-radius:8px; width:auto; padding:0 12px; font-size:11px; height:28px">
                            Detail Siswa
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="bi bi-people"></i>
                        Belum ada siswa yang terdaftar di kelas {{ $kelas }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
