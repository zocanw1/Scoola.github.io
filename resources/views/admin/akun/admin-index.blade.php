@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">

    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Konfigurasi Sistem</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Manajemen Admin</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Kelola hak akses dan profil personil administrasi sistem Scoola.
            </p>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden;">
        <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
        <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
        <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
            <i class="bi bi-shield-lock" style="font-size: 20px;"></i>
        </div>
        <div style="padding-left: 12px;">
            <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">TOTAL ADMINISTRATOR TERDAFTAR</div>
            <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;">{{ $admins->count() }} <span style="font-size: 24px; letter-spacing: 0;">Akun</span></div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card table-container-card" style="margin-bottom: var(--spacing-section);">
        <table class="data-table responsive-table" style="margin: 0; width: 100%;">
            <thead>
                <tr>
                    <th style="padding-left: 40px; width: 80px;">#</th>
                    <th>Nama Administrator</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th style="text-align: right; padding-right: 40px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $index => $a)
                <tr>
                    <td data-label="#" style="padding-left: 40px; color: var(--color-stone);">{{ $index + 1 }}</td>
                    <td data-label="Nama" style="font-weight: 600;">{{ $a->name }}</td>
                    <td data-label="Username" style="font-family: monospace; color: var(--color-slate);">{{ explode('@', $a->email)[0] }}</td>
                    <td data-label="Email" style="color: var(--color-graphite);">{{ $a->email }}</td>
                    <td data-label="Aksi" style="text-align: right; padding-right: 40px;">
                        <a href="{{ route('admin.akun.edit', $a->id) }}" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; text-decoration: none; display: inline-flex; align-items: center;">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- FAB Action -->
<div class="fab-container">
    <a href="{{ route('admin.akun.create') }}" class="btn-fab" title="Tambah Admin">
        <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
        <span class="fab-label">Tambah Admin Baru</span>
    </a>
</div>

@endsection
