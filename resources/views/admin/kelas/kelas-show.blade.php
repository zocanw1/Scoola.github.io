@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">CLASS DETAIL</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
            <span class="mp-kicker"><i class="bi bi-people"></i> Struktur Institusi</span>
            <h1 class="mp-title">Siswa Kelas {{ $kelas }}</h1>
            <p class="mp-description">
                Daftar seluruh siswa yang terdaftar dalam rombongan belajar kelas {{ $kelas }}.
            </p>
            </div>
        </section>
    </div>

    <div class="mp-stats-grid">
        <section class="mp-stat-card mp-card-cyber mp-hover">
            <div class="mp-stat-icon"><i class="bi bi-people"></i></div>
            <div>
                <div class="mp-stat-label">Total Siswa Terdaftar</div>
                <div class="mp-stat-value">{{ $siswa->count() }}</div>
            </div>
        </section>

        <section class="mp-stat-card">
            <a href="{{ route('admin.kelas.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Direktori</a>
        </section>
    </div>

    <section class="mp-table-card">
        <div class="mp-table-wrap">
            <table class="mp-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th class="mp-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $s)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="mp-mono">{{ $s->NIS }}</td>
                        <td>{{ $s->nama_siswa }}</td>
                        <td>{{ $s->user->email ?? '-' }}</td>
                        <td class="mp-right">
                            <a href="{{ route('siswa.edit', $s->NIS) }}" class="mp-btn-secondary" style="min-height: 36px; padding: 0 16px; font-size: 12px;">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="mp-center" style="padding: 72px;">Belum ada siswa yang terdaftar di kelas {{ $kelas }}.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

@endsection
