@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">ADMIN ACCESS</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
            <span class="mp-kicker"><i class="bi bi-shield-plus"></i> Konfigurasi Sistem</span>
            <h1 class="mp-title">Tambah Administrator</h1>
            <p class="mp-description">
                Daftarkan personil baru dengan hak akses penuh ke sistem Scoola.
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
        <form method="POST" action="{{ route('admin.akun.store') }}">
            @csrf

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">Nama Lengkap</label>
                    <input type="text" name="name" class="mp-input" placeholder="System Administrator" required value="{{ old('name') }}" autofocus>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Email</label>
                    <input type="email" name="email" class="mp-input" placeholder="admin@scoola.id" required value="{{ old('email') }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Password Sementara</label>
                    <input type="password" name="password" class="mp-input" placeholder="Minimal 6 karakter" required>
                    <small class="mp-hint">Minimal 6 karakter</small>
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-shield-check"></i> Daftarkan Admin</button>
                <a href="{{ route('admin.akun.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
