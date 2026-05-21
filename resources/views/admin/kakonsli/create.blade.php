@extends('layouts.admin')

@section('content')

<div class="editorial-header">
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Konfigurasi Sistem</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Tambah Kakonsli</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Daftarkan akun Konselor/Kakonsli baru untuk mengawasi presensi dan bimbingan siswa di Scoola.
            </p>
        </div>
    </div>
</div>

@if ($errors->any())
    <div style="border: 1px solid var(--color-ink); padding: 16px; margin-bottom: 32px; background: #fff;">
        <ul style="margin: 0; padding-left: 20px; color: var(--color-ink); font-size: 13px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card" style="background: #ffffff; padding: 48px; border-radius: 16px; border: 1px solid var(--color-hairline); margin-bottom: var(--spacing-section);">
    <form method="POST" action="{{ route('admin.kakonsli.store') }}">
        @csrf

        <div style="margin-bottom: 48px;">
            <label class="text-micro-caps" style="display: block; margin-bottom: 8px; color: var(--color-stone);">Nama Lengkap Kakonsli</label>
            <input type="text" name="name" class="form-field" placeholder="Nama Lengkap" required value="{{ old('name') }}" autofocus>
        </div>

        <div style="margin-bottom: 48px;">
            <label class="text-micro-caps" style="display: block; margin-bottom: 8px; color: var(--color-stone);">Email</label>
            <input type="email" name="email" class="form-field" placeholder="kakonsli@scoola.id" required value="{{ old('email') }}">
        </div>

        <div style="margin-bottom: 48px;">
            <label class="text-micro-caps" style="display: block; margin-bottom: 8px; color: var(--color-stone);">Password Sementara</label>
            <input type="password" name="password" class="form-field" placeholder="••••••••" required>
            <small style="color: var(--color-slate); margin-top: 8px; display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Minimal 6 karakter</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Daftarkan Kakonsli</button>
            <a href="{{ route('admin.kakonsli.index') }}" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Batal</a>
        </div>
    </form>
</div>

@endsection
