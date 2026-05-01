@extends('layouts.admin')

@section('page-title', 'Tambah Wali Kelas')
@section('breadcrumb', 'Wali Kelas / Tambah')

@section('content')

<style>
    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        color: var(--text3); font-size: 12px; text-decoration: none;
        margin-bottom: 16px; transition: color .2s; font-weight: 500;
    }
    .btn-back:hover { color: var(--accent); }

    .page-header .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px; font-weight: 800; color: var(--text1); line-height: 1.2;
    }
    .page-header .page-subtitle {
        font-size: 12px; color: var(--text2); margin-top: 3px;
    }

    .form-card {
        background: var(--navy2); border: 1px solid var(--glass-border);
        border-radius: var(--r); padding: 24px 28px; max-width: 560px;
    }
    .form-group { margin-bottom: 18px; }
    .form-label {
        display: block; font-size: 11px; font-weight: 600; color: var(--text2);
        text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px;
    }
    .form-select {
        width: 100%; background: var(--navy3);
        border: 1px solid var(--glass-border); border-radius: 8px;
        padding: 10px 14px; color: var(--text1);
        font-size: 13px; font-family: 'Inter', sans-serif;
        outline: none; cursor: pointer; transition: border-color .2s;
    }
    .form-select:focus { border-color: rgba(88,166,255,.5); }
    .form-select option { background: var(--navy2); color: var(--text1); }

    .btn-submit {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 22px; background: var(--accent); color: #fff;
        border-radius: 8px; font-size: 13px; font-weight: 700;
        border: none; cursor: pointer; transition: all .2s;
        font-family: 'Inter', sans-serif;
    }
    .btn-submit:hover { filter: brightness(1.15); transform: translateY(-1px); }

    .btn-cancel {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 22px; background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2); border-radius: 8px; font-size: 13px; font-weight: 600;
        text-decoration: none; transition: all .2s; font-family: 'Inter', sans-serif;
    }
    .btn-cancel:hover { border-color: rgba(88,166,255,.3); color: var(--text1); }

    .form-actions { display: flex; gap: 10px; margin-top: 24px; }

    .form-hint { font-size: 11px; color: var(--text3); margin-top: 4px; }

    .alert-error {
        background: rgba(248,81,73,0.08); border: 1px solid rgba(248,81,73,0.25);
        color: var(--red); padding: 10px 16px; border-radius: 8px;
        font-size: 12px; font-weight: 500; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }

    .validation-error { color: var(--red); font-size: 11px; margin-top: 4px; }
</style>

<a href="{{ route('admin.walikelas.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Wali Kelas
</a>

<div class="page-header" style="margin-bottom:20px">
    <div>
        <div class="page-title">Tambah Wali Kelas</div>
        <div class="page-subtitle">Pilih guru dan kelas untuk ditugaskan sebagai wali kelas</div>
    </div>
</div>

@if(session('error'))
    <div class="alert-error"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
@endif

<div class="form-card">
    <form action="{{ route('admin.walikelas.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label"><i class="bi bi-building" style="margin-right:4px"></i> Pilih Kelas</label>
            <select name="kelas_id" class="form-select" required>
                <option value="">— Pilih Kelas —</option>
                @foreach($kelasAvailable as $kls)
                    <option value="{{ $kls->id }}" @selected(old('kelas_id', request('kelas_id')) == $kls->id)>
                        {{ $kls->nama_kelas }} ({{ $kls->siswa->count() }} siswa)
                    </option>
                @endforeach
            </select>
            <div class="form-hint">Hanya menampilkan kelas yang belum memiliki wali kelas</div>
            @error('kelas_id') <div class="validation-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label"><i class="bi bi-person-badge" style="margin-right:4px"></i> Pilih Guru</label>
            <select name="guru_nip" class="form-select" required>
                <option value="">— Pilih Guru —</option>
                @foreach($guruList as $guru)
                    <option value="{{ $guru->NIP }}" @selected(old('guru_nip') == $guru->NIP)>
                        {{ $guru->nama_guru }} — {{ $guru->NIP }}
                        @if($guru->mapel) ({{ $guru->mapel->nama_mapel }}) @endif
                    </option>
                @endforeach
            </select>
            <div class="form-hint">Hanya menampilkan guru yang belum menjadi wali kelas</div>
            @error('guru_nip') <div class="validation-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="bi bi-check-lg"></i> Simpan Wali Kelas
            </button>
            <a href="{{ route('admin.walikelas.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

@endsection
