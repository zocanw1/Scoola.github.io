@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">PICK CLASS</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-calendar3"></i> Manajemen Akademik</span>
                <h1 class="mp-title">Pilih Jadwal Kelas</h1>
                <p class="mp-description">
                    Pilih kelas untuk melihat dan mengelola jadwal pelajaran yang tersedia.
                </p>
            </div>
        </section>
    </div>

    @php
        $tingkatan = ['XI'];
        $jurusan = 'SIJA';
        $rombel = [1, 2];
    @endphp

    <section class="mp-selection-grid">
        @foreach ($tingkatan as $t)
            @foreach ($rombel as $r)
                @php $kelas = "$t-$jurusan $r"; @endphp
                <a href="{{ route('jadwal.kelas', $kelas) }}" class="mp-select-card mp-hover">
                    <span class="mp-badge">Kelas {{ $t }}</span>
                    <div class="mp-select-title">{{ $kelas }}</div>
                    <div class="mp-small">Lihat jadwal pelajaran</div>
                    <div style="margin-top: 24px; font-weight: 900;">Buka <i class="bi bi-arrow-right"></i></div>
                </a>
            @endforeach
        @endforeach
    </section>
</div>

@endsection
