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
        max-width: 800px;
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

    .form-control::placeholder {
        color: var(--text3);
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%238b949e' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 12px 12px;
        padding-right: 40px;
    }
    
    [data-theme="light"] select.form-control {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2357606a' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
    }

    /* ── ACTION BUTTONS ─────────────────────────── */
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
        color: var(--navy);
        border: none;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-submit:hover {
        background: #79baff;
        color: var(--navy);
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(88,166,255,0.3);
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
        font-weight: 500;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        transition: all .2s;
    }

    .btn-cancel:hover {
        background: var(--glass-hover);
        color: var(--text1);
        border-color: var(--text3);
    }

    /* ── ALERT ─────────────────────────── */
    .alert-danger {
        background: rgba(248,81,73,0.1);
        border: 1px solid rgba(248,81,73,0.2);
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 24px;
        max-width: 800px;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
        color: var(--red);
        font-size: 12.5px;
    }

    .grid-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 20px;
    }
</style>

<div class="page-header">
    <div class="page-title">Tambah Jadwal Pelajaran</div>
    <div class="page-subtitle">Pilih kelas, mata pelajaran, guru, dan waktu belajar</div>
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

@if (session('confirm_replace'))
    <div class="alert-warning" style="background: rgba(227, 179, 65, 0.1); border: 1px solid rgba(227, 179, 65, 0.3); border-radius: 8px; padding: 16px 20px; margin-bottom: 24px; max-width: 800px;">
        <div style="color: var(--yellow); font-weight: 700; margin-bottom: 8px; font-size: 14px; display:flex; align-items:center; gap:6px;">
            <i class="bi bi-exclamation-triangle-fill"></i> Peringatan Jadwal Bentrok
        </div>
        <div style="color: var(--text2); font-size: 13px; margin-bottom: 16px;">
            {{ session('confirm_replace') }}
        </div>
        <form action="{{ route('jadwal.store') }}" method="POST" style="display: inline-block;">
            @csrf
            <input type="hidden" name="hari" value="{{ old('hari') }}">
            <input type="hidden" name="kelas" value="{{ old('kelas') }}">
            <input type="hidden" name="jam_mulai" value="{{ old('jam_mulai') }}">
            <input type="hidden" name="jam_selesai" value="{{ old('jam_selesai') }}">
            <input type="hidden" name="kd_mapel" value="{{ old('kd_mapel') }}">
            <input type="hidden" name="NIP" value="{{ old('NIP') }}">
            <input type="hidden" name="force" value="1">
            <button type="submit" style="background: var(--yellow); color: var(--navy); border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; transition: 0.2s;">
                <i class="bi bi-check-circle-fill" style="margin-right: 4px;"></i> Ya, Tetap Simpan & Ganti
            </button>
        </form>
    </div>
@endif

<div class="form-card">
    <form action="{{ route('jadwal.store') }}" method="POST">
        @csrf

        <div class="grid-form">
            <div class="form-group">
                <label class="form-label">Hari</label>
                <select name="hari" class="form-control" required>
                    <option value="" disabled {{ old('hari') ? '' : 'selected' }}>-- Pilih Hari --</option>
                    @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                        <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>
                            {{ $hari }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-control" required>
                    <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>-- Pilih Kelas --</option>

                    @php
                        $tingkatan = ['X', 'XI', 'XII'];
                        $jurusan = 'SIJA';
                        $rombel = [1, 2];
                    @endphp

                    @foreach ($tingkatan as $t)
                        @foreach ($rombel as $r)
                            @php $kls = "$t-$jurusan $r"; @endphp
                            <option value="{{ $kls }}" {{ old('kelas') == $kls ? 'selected' : '' }}>
                                {{ $kls }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Jam Ke (Mulai)</label>
                <input type="number" name="jam_mulai" class="form-control" min="1" max="12" value="{{ old('jam_mulai') }}" required placeholder="Misal: 1">
            </div>

            <div class="form-group">
                <label class="form-label">Jam Ke (Selesai)</label>
                <input type="number" name="jam_selesai" class="form-control" min="1" max="12" value="{{ old('jam_selesai') }}" required placeholder="Misal: 3">
            </div>

            <div class="form-group">
                <label class="form-label">Mata Pelajaran</label>
                <select name="kd_mapel" class="form-control" required>
                    <option value="" disabled {{ old('kd_mapel') ? '' : 'selected' }}>-- Pilih Mata Pelajaran --</option>
                    @foreach ($mapel as $m)
                        <option value="{{ $m->kd_mapel }}" {{ old('kd_mapel') == $m->kd_mapel ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Guru Pengajar</label>
                <select name="NIP" class="form-control" required>
                    <option value="" disabled {{ old('NIP') ? '' : 'selected' }}>-- Pilih Guru --</option>
                    @foreach ($guru as $g)
                        <option value="{{ $g->NIP }}" {{ old('NIP') == $g->NIP ? 'selected' : '' }}>
                            {{ $g->nama_guru }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="bi bi-save"></i> Simpan Jadwal
            </button>
            <a href="{{ route('jadwal.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

@endsection
