@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Akademik</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Tambah Mata Pelajaran</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Definisikan mata pelajaran baru untuk kurikulum sekolah. Pastikan kode unik belum terdaftar.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="card" style="background: #ffffff; padding: 24px; border: 2px solid var(--color-ink); border-radius: 12px;">
            <ul style="margin: 0; padding-left: 20px; color: var(--color-ink); font-size: 14px; font-weight: 500;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card" style="background: #ffffff; padding: 48px; border-radius: 16px; border: 1px solid var(--color-hairline); margin-bottom: var(--spacing-section);">
        <form action="{{ route('mapel.store') }}" method="POST" style="max-width: 720px;">
            @csrf
            
            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Kode Mata Pelajaran</label>
                <input type="text" name="kd_mapel" class="form-field" placeholder="MP-XXX" value="{{ old('kd_mapel') }}" required autofocus>
                <small style="color: var(--color-stone); margin-top: 12px; display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">
                    IDENTIFIKASI UNIK KURIKULUM
                </small>
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Nama Lengkap Mata Pelajaran</label>
                <input type="text" name="nama_mapel" class="form-field" placeholder="Masukkan nama mata pelajaran" value="{{ old('nama_mapel') }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Simpan Mata Pelajaran</button>
                <a href="{{ route('mapel.index') }}" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Batal</a>
            </div>
        </form>
    </div>

</div>

@endsection
