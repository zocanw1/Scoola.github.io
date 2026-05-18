@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Struktur Institusi</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Siswa Kelas {{ $kelas }}</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Daftar seluruh siswa yang terdaftar dalam rombongan belajar kelas {{ $kelas }}.
            </p>
        </div>
    </div>

    <!-- Stats & Actions Card -->
    <div class="stats-grid">
        <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden; display: flex; align-items: center; min-height: 140px;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-people" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">TOTAL SISWA TERDAFTAR</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;">{{ $siswa->count() }} <span style="font-size: 24px; letter-spacing: 0;">Anggota</span></div>
            </div>
        </div>
        <div class="card" style="background: #ffffff; padding: 0 40px; border-radius: 16px; border: 1px solid var(--color-hairline); display: flex; align-items: center; justify-content: center;">
            <a href="{{ route('admin.kelas.index') }}" class="btn-ghost" style="text-decoration: none; height: 48px; display: flex; align-items: center; font-size: 11px;">&larr; KEMBALI KE DIREKTORI</a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card table-container-card" style="margin-bottom: var(--spacing-section);">
        <table class="data-table responsive-table" style="margin: 0; width: 100%;">
            <thead>
                <tr>
                    <th style="padding-left: 40px; width: 60px;">#</th>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th style="text-align: right; padding-right: 40px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa as $index => $s)
                <tr>
                    <td data-label="#" style="padding-left: 40px; color: var(--color-stone);">{{ $index + 1 }}</td>
                    <td data-label="NIS" style="font-family: monospace; color: var(--color-slate);">{{ $s->NIS }}</td>
                    <td data-label="Nama Lengkap" style="font-weight: 600;">{{ $s->nama_siswa }}</td>
                    <td data-label="Email" style="color: var(--color-graphite);">{{ $s->user->email ?? '-' }}</td>
                    <td data-label="Aksi" style="text-align: right; padding-right: 40px;">
                        <a href="{{ route('siswa.edit', $s->NIS) }}" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; text-decoration: none; display: inline-flex; align-items: center;">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 120px; color: var(--color-stone);">Belum ada siswa yang terdaftar di kelas {{ $kelas }}.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
