@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">CLASS MAP</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-building"></i> Struktur Institusi</span>
                <h1 class="mp-title">Data Kelas</h1>
                <p class="mp-description">
                    Daftar seluruh rombongan belajar aktif. Pilih kelas untuk melihat data siswa dan informasi spesifik lainnya.
                </p>
            </div>
        </section>
    </div>

    @php
        $countKelas = count($kelasList);
    @endphp

    <div class="mp-stats-grid">
        <section class="mp-stat-card mp-card-gold mp-hover">
            <div class="mp-stat-icon"><i class="bi bi-layers"></i></div>
            <div>
                <div class="mp-stat-label">Total Unit Kelas Tersedia</div>
                <div class="mp-stat-value">{{ $countKelas }}</div>
            </div>
        </section>
    </div>

    <section class="mp-selection-grid">
        @foreach($kelasList as $kelas)
            @php $count = $counts->get($kelas) ?? 0; @endphp
            <a href="{{ route('admin.kelas.show', $kelas) }}" class="mp-select-card mp-hover">
                <span class="mp-badge">VOL. {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="mp-select-title">Kelas {{ $kelas }}</div>
                <div class="mp-small">{{ $count }} siswa terdaftar</div>
                <div style="margin-top: 24px; font-weight: 900;">Lihat Detail <i class="bi bi-arrow-right"></i></div>
            </a>
        @endforeach
    </section>
</div>

<a href="{{ route('siswa.create') }}" class="mp-fab" title="Tambah Siswa">
    <i class="bi bi-plus-lg"></i>
</a>

@endsection
