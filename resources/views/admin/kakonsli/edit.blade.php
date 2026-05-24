@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">COUNSELOR</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-shield-lock"></i> Konfigurasi Sistem</span>
                <h1 class="mp-title">Edit Kakonsli</h1>
                <p class="mp-description">
                    Perbarui profil hak akses untuk Konselor/Kakonsli {{ $kakonsli->name }}.
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
        <form method="POST" action="{{ route('admin.kakonsli.update', $kakonsli->id) }}">
            @csrf
            @method('PUT')

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">Nama Lengkap Kakonsli</label>
                    <input type="text" name="name" class="mp-input" placeholder="Nama Lengkap" required value="{{ old('name', $kakonsli->name) }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Alamat Email</label>
                    <input type="email" name="email" class="mp-input" placeholder="Email" required value="{{ old('email', $kakonsli->email) }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Ganti Password (Opsional)</label>
                    <input type="password" name="password" class="mp-input" placeholder="Biarkan kosong jika tidak diubah">
                    <small class="mp-hint">Kosongkan jika password tetap sama</small>
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-check2-circle"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.kakonsli.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
