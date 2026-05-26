@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">EDIT MODE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
            <span class="mp-kicker"><i class="bi bi-person-badge"></i> Manajemen Data</span>
            <h1 class="mp-title">Edit Guru</h1>
            <p class="mp-description">
                Perbarui profil pengajar dan penugasan mata pelajaran untuk {{ $guru->nama_guru }}.
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
        <form method="POST" action="{{ route('guru.update', $guru->NIP) }}">
            @csrf
            @method('PUT')

            <div class="mp-field">
                <label class="mp-label">NIP (Nomor Induk Pegawai)</label>
                <input type="text" name="nip" class="mp-input" value="{{ $guru->NIP }}" readonly>
                <small class="mp-hint">NIP bersifat permanen dalam sistem Scoola</small>
            </div>

            <div class="mp-field">
                <label class="mp-label">Nama Lengkap Pengajar</label>
                <input type="text" name="nama" class="mp-input" placeholder="Masukkan nama lengkap beserta gelar" required value="{{ old('nama', $guru->nama_guru) }}">
            </div>

            <div class="mp-field">
                <label class="mp-label">Bidang Mata Pelajaran</label>
                <div class="mp-checkbox-grid">
                    @foreach ($mapel as $m)
                        <label class="mp-check">
                            <input type="checkbox" name="kd_mapel[]" value="{{ $m->kd_mapel }}" @checked($guru->mapels->contains($m->kd_mapel))>
                            <span>{{ $m->nama_mapel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <section class="mp-card" style="margin-top: 28px; background: #fff4d9; border: 3px solid var(--midnight);">
                <div class="mp-label" style="margin-bottom: 12px;">Zona Sensitif</div>
                <h3 style="margin: 0 0 10px; font-family: 'Fredoka One', cursive; color: var(--midnight);">Ubah Kredensial Login</h3>
                <p style="margin: 0 0 20px; font-weight: 800; color: var(--midnight);">
                    Bagian ini sengaja dipersulit supaya email atau password tidak berubah tanpa sengaja.
                </p>

                <div class="mp-field">
                    <label class="mp-label">Email Login Guru</label>
                    <input type="email" name="email" class="mp-input" value="{{ old('email', $guru->user->email ?? '') }}" placeholder="Masukkan email login guru">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Password Baru</label>
                    <input type="password" name="password" class="mp-input" placeholder="Kosongkan jika tidak ingin mengganti password">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="mp-input" placeholder="Ulangi password baru">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Ketik Ulang NIP Guru untuk Verifikasi</label>
                    <input type="text" name="credential_confirmation" class="mp-input" value="{{ old('credential_confirmation') }}" placeholder="Harus sama persis dengan NIP guru">
                    <small class="mp-hint">NIP target: {{ $guru->NIP }}</small>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Password Admin Saat Ini</label>
                    <input type="password" name="admin_password_confirmation" class="mp-input" placeholder="Masukkan password admin untuk menyetujui perubahan ini">
                </div>

                <label class="mp-check" style="margin-top: 8px;">
                    <input type="checkbox" name="change_login_credentials" value="1" @checked(old('change_login_credentials'))>
                    <span>Saya sadar sedang mengubah email atau password login guru ini</span>
                </label>
            </section>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-check2-circle"></i> Perbarui Data Guru</button>
                <a href="{{ route('guru.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
