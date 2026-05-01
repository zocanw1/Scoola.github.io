@extends('layouts.admin')

@section('content')

<style>
    .page-header { margin-bottom: 24px; }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px; font-weight: 800;
        color: var(--text1); line-height: 1.2;
    }

    .page-subtitle { font-size: 12px; color: var(--text2); margin-top: 4px; }

    .form-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 24px;
        max-width: 600px;
    }

    .form-group { margin-bottom: 20px; }

    .form-label {
        display: block;
        font-size: 11.5px; font-weight: 600;
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

    .form-control::placeholder { color: var(--text3); }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%238b949e' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 12px 12px;
        padding-right: 40px;
        cursor: pointer;
    }

    [data-theme="light"] select.form-control {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2357606a' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
    }

    .nis-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(88,166,255,0.08);
        border: 1px solid rgba(88,166,255,0.15);
        color: var(--accent);
        padding: 8px 14px;
        border-radius: 8px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px; font-weight: 700;
        letter-spacing: .03em;
    }

    .form-actions {
        display: flex; gap: 12px;
        margin-top: 28px; padding-top: 20px;
        border-top: 1px solid var(--glass-border);
    }

    .btn-submit {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px;
        background: var(--accent); color: #fff;
        border: none; border-radius: 8px;
        font-size: 12.5px; font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer; transition: all .2s;
    }

    .btn-submit:hover {
        filter: brightness(1.15); color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(88,166,255,0.3);
    }

    .btn-cancel {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 9px 18px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        border-radius: 8px;
        font-size: 12.5px; font-weight: 500;
        font-family: 'Inter', sans-serif;
        text-decoration: none; transition: all .2s;
    }

    .btn-cancel:hover {
        background: var(--glass-hover);
        color: var(--text1);
        border-color: var(--text3);
    }

    .alert-danger {
        background: rgba(248,81,73,0.1);
        border: 1px solid rgba(248,81,73,0.2);
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 24px;
        max-width: 600px;
    }

    .alert-danger ul {
        margin: 0; padding-left: 20px;
        color: var(--red); font-size: 12.5px;
    }
</style>

<div class="page-header">
    <div class="page-title">Edit Data Siswa</div>
    <div class="page-subtitle">Perbarui informasi siswa yang terdaftar di sistem</div>
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
    <form method="POST" action="{{ route('siswa.update', $siswa->NIS) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">NIS (Nomor Induk Siswa)</label>
            <div class="nis-badge">
                <i class="bi bi-fingerprint"></i>
                {{ $siswa->NIS }}
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required value="{{ old('nama', $siswa->nama_siswa) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Kelas</label>
            <select name="kelas" class="form-control" required>
                <option value="" disabled>-- Pilih Kelas --</option>
                <option value="XI-SIJA 1" @selected(old('kelas', $siswa->kelas) == 'XI-SIJA 1')>XI-SIJA 1</option>
                <option value="XI-SIJA 2" @selected(old('kelas', $siswa->kelas) == 'XI-SIJA 2')>XI-SIJA 2</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" class="form-control" placeholder="nama@sekolah.com" required value="{{ old('email', $siswa->user->email) }}">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
            <a href="{{ route('siswa.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

@endsection

