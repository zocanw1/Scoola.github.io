@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">COUNSELOR</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-shield-plus"></i> Konfigurasi Sistem</span>
                <h1 class="mp-title">Tambah Kakonsli</h1>
                <p class="mp-description">
                    Daftarkan akun Konselor/Kakonsli baru untuk mengawasi presensi dan bimbingan siswa di Scoola.
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
        <form method="POST" action="{{ route('admin.kakonsli.store') }}">
            @csrf

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">Nama Lengkap Kakonsli</label>
                    <input type="text" name="name" class="mp-input" placeholder="Nama Lengkap" required value="{{ old('name') }}" autofocus>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Email</label>
                    <input type="email" name="email" class="mp-input" placeholder="kakonsli@scoola.id" required value="{{ old('email') }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Password Sementara</label>
                    <input type="password" name="password" class="mp-input" placeholder="Minimal 6 karakter" required>
                    <small class="mp-hint">Minimal 6 karakter</small>
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-shield-check"></i> Daftarkan Kakonsli</button>
                <a href="{{ route('admin.kakonsli.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
