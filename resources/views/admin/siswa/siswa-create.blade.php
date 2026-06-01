@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">NEW STUDENT</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-person-plus-fill"></i> Manajemen Data</span>
                <h1 class="mp-title">Pendaftaran Siswa</h1>
                <p class="mp-description">
                    Daftarkan siswa baru ke sistem akademik Scoola. Data akun dan kelas akan langsung terhubung ke modul presensi.
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
        <form method="POST" action="{{ route('siswa.store') }}">
            @csrf

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">NIS (Nomor Induk Siswa)</label>
                    <input type="text" name="nis" class="mp-input" placeholder="Contoh: 12345678" required value="{{ old('nis') }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Nama Lengkap Siswa</label>
                    <input type="text" name="nama" class="mp-input" placeholder="Masukkan nama lengkap siswa" required value="{{ old('nama') }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Penempatan Kelas</label>
                    <select name="kelas" class="mp-input" required>
                        <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>PILIH KELAS</option>
                        <option value="XI-SIJA 1" {{ old('kelas') == 'XI-SIJA 1' ? 'selected' : '' }}>XI-SIJA 1</option>
                        <option value="XI-SIJA 2" {{ old('kelas') == 'XI-SIJA 2' ? 'selected' : '' }}>XI-SIJA 2</option>
                    </select>
                </div>

                <div class="mp-field">
                    <label class="mp-label">L / P</label>
                    <select name="jenis_kelamin" class="mp-input" required>
                        <option value="L" {{ old('jenis_kelamin', 'L') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Alamat Email Institusi</label>
                    <input type="email" name="email" class="mp-input" placeholder="nama@sekolah.com" required value="{{ old('email') }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Password Sementara</label>
                    <input type="password" name="password" class="mp-input" placeholder="Minimal 6 karakter" required>
                    <small class="mp-hint">Digunakan siswa untuk login pertama</small>
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-save2"></i> Simpan Data Siswa</button>
                <a href="{{ route('siswa.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
