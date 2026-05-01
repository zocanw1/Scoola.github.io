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
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-primary:hover {
        filter: brightness(1.15);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(88,166,255,0.3);
    }

    /* ── DATA CARD ─────────────────────────── */
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
        margin-left: 8px;
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
        background: var(--navy2);
    }

    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        font-size: 12.5px;
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
        gap: 9px;
    }

    .avatar-sm {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--purple));
        display: grid; place-items: center;
        font-size: 10px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .role-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        background: rgba(88,166,255,0.1);
        color: var(--accent);
        text-transform: uppercase;
    }

    /* ── ACTION BUTTONS ─────────────────────────── */
    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit {
        padding: 5px 11px;
        background: rgba(88,166,255,0.08);
        border: 1px solid rgba(88,166,255,0.15);
        color: var(--accent);
        border-radius: 6px;
        font-size: 11.5px;
        text-decoration: none;
    }



    .alert-success {
        padding: 11px 16px;
        background: rgba(63,185,80,0.1);
        border: 1px solid rgba(63,185,80,0.2);
        border-radius: 8px;
        color: var(--green);
        margin-bottom: 16px;
    }
</style>

@if(session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">Kelola Administrator</div>
        <div class="page-subtitle">Daftar akun yang memiliki akses penuh ke dashboard admin</div>
    </div>
    <a href="{{ route('admin.akun.create') }}" class="btn-primary">
        <i class="bi bi-person-plus-fill"></i> Tambah Admin
    </a>
</div>

<div class="card">
    <div class="card-toolbar">
        <div class="card-label">Daftar Admin <span class="count-badge">{{ $admins->count() }} data</span></div>
    </div>
    <div class="tbl-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $index => $admin)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="cell-name">
                            <div class="avatar-sm">{{ strtoupper(substr($admin->name, 0, 1)) }}</div>
                            {{ $admin->name }}
                        </div>
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td><span class="role-badge">{{ $admin->role }}</span></td>
                    <td>
                        <div class="action-group" style="justify-content:center">
                            <a href="{{ route('admin.akun.edit', $admin->id) }}" class="btn-edit">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
