@extends('layouts.admin')

@section('content')

<style>
    /* ── PAGE HEADER ─────────────────────────── */
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: 12px;
        color: var(--text2);
        margin-top: 4px;
    }

    /* ── FORM CARD ─────────────────────────── */
    .form-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 24px;
        max-width: 600px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--text2);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .form-control {
        width: 100%;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 8px;
        padding: 10px 14px;
        color: var(--text1);
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        transition: all .2s;
        outline: none;
    }

    .form-control:focus {
        border-color: rgba(88,166,255,.4);
        box-shadow: 0 0 0 3px rgba(88,166,255,.1);
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--glass-border);
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 18px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        border-radius: 8px;
        font-size: 12.5px;
        text-decoration: none;
    }

    .alert-danger {
        background: rgba(248,81,73,0.1);
        border: 1px solid rgba(248,81,73,0.2);
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 24px;
        max-width: 600px;
        color: var(--red);
    }
</style>

<div class="page-header">
    <div class="page-title">Tambah Administrator Baru</div>
    <div class="page-subtitle">Pemberian akses penuh ke dashboard admin</div>
</div>

@if ($errors->any())
    <div class="alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-card">
    <form method="POST" action="{{ route('admin.akun.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" placeholder="Masukkan nama" required value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="admin@scoola.id" required value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Password Sementara</label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Simpan Admin</button>
            <a href="{{ route('admin.akun.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

@endsection
