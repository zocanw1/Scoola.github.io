@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">NEW TEACHER</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
            <span class="mp-kicker"><i class="bi bi-person-plus"></i> Manajemen Data</span>
            <h1 class="mp-title">Pendaftaran Guru</h1>
            <p class="mp-description">
                Tambahkan tenaga pengajar baru ke sistem Scoola lengkap dengan akun akses dan spesialisasi mata pelajaran.
            </p>
            </div>
        </section>
    </div>

    @if ($errors->any())
        <div class="mp-alert danger">
            <div class="mp-label" style="margin-bottom: 10px;">Ditemukan Kesalahan Validasi</div>
            <ul style="margin: 0; padding-left: 20px; color: var(--midnight); font-weight: 800;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="mp-form-card">
        <form method="POST" action="{{ route('guru.store') }}">
            @csrf

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">NIP / Identitas Pegawai</label>
                    <input type="text" name="nip" class="mp-input" placeholder="19XXXXXXXXXXXXXX" required value="{{ old('nip') }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" class="mp-input" placeholder="Masukkan nama lengkap guru" required value="{{ old('nama') }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Alamat Email Resmi</label>
                    <input type="email" name="email" class="mp-input" placeholder="nama@sekolah.com" required value="{{ old('email') }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Kredensial Akses</label>
                    <input type="password" name="password" class="mp-input" placeholder="Minimal 6 karakter" required>
                    <small class="mp-hint">Password sementara untuk akun guru</small>
                </div>
            </div>

            <div class="mp-field">
                <label class="mp-label">Spesialisasi Mata Pelajaran</label>
                <div class="mp-checkbox-grid">
                    @foreach ($mapel as $m)
                        <label class="mp-check">
                            <input type="checkbox" name="kd_mapel[]" value="{{ $m->kd_mapel }}" @checked(is_array(old('kd_mapel')) && in_array($m->kd_mapel, old('kd_mapel')))>
                            <span>{{ $m->nama_mapel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-save2"></i> Simpan Data Guru</button>
                <a href="{{ route('guru.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
