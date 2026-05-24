@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">ACCESS HQ</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
            <span class="mp-kicker"><i class="bi bi-shield-lock"></i> Konfigurasi Sistem</span>
            <h1 class="mp-title">Manajemen Admin</h1>
            <p class="mp-description">
                Kelola hak akses dan profil personil administrasi sistem Scoola.
            </p>
            </div>
        </section>
    </div>

    <div class="mp-stats-grid">
        <section class="mp-stat-card mp-card-gold mp-hover">
            <div class="mp-stat-icon"><i class="bi bi-shield-lock"></i></div>
            <div>
                <div class="mp-stat-label">Total Administrator Terdaftar</div>
                <div class="mp-stat-value">{{ $admins->count() }}</div>
            </div>
        </section>
    </div>

    <section class="mp-table-card">
        <div class="mp-table-wrap">
            <table class="mp-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Nama Administrator</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th class="mp-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $index => $a)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $a->name }}</td>
                        <td class="mp-mono">{{ explode('@', $a->email)[0] }}</td>
                        <td>{{ $a->email }}</td>
                        <td class="mp-right">
                            <a href="{{ route('admin.akun.edit', $a->id) }}" class="mp-btn-secondary" style="min-height: 36px; padding: 0 16px; font-size: 12px;">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

<a href="{{ route('admin.akun.create') }}" class="mp-fab" title="Tambah Admin">
    <i class="bi bi-plus-lg"></i>
</a>

@endsection
