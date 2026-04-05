@extends('layouts.admin')

@section('content')

<style>
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: 12px;
        color: var(--text2);
        margin-top: 4px;
    }

    .kelas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .kelas-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 24px;
        text-align: center;
        text-decoration: none;
        transition: all .2s;
        display: block;
    }

    .kelas-card:hover {
        background: var(--glass-hover);
        border-color: rgba(88,166,255,.4);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .kelas-icon {
        width: 48px;
        height: 48px;
        background: rgba(88,166,255,.12);
        color: var(--accent);
        border-radius: 12px;
        display: inline-grid;
        place-items: center;
        font-size: 20px;
        margin-bottom: 12px;
    }

    .kelas-name {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--text1);
    }
</style>

<div class="page-header">
    <div class="page-title">Pilih Jadwal Kelas</div>
    <div class="page-subtitle">Pilih kelas untuk melihat jadwal pelajaran yang tersedia</div>
</div>

<div class="kelas-grid">
    @php
        $tingkatan = ['X', 'XI', 'XII'];
        $jurusan = 'SIJA';
        $rombel = [1, 2];
    @endphp

    @foreach ($tingkatan as $t)
        @foreach ($rombel as $r)
            @php $kelas = "$t-$jurusan $r"; @endphp
            <a href="{{ route('jadwal.kelas', $kelas) }}" class="kelas-card">
                <div class="kelas-icon"><i class="bi bi-building"></i></div>
                <div class="kelas-name">{{ $kelas }}</div>
            </a>
        @endforeach
    @endforeach
</div>

@endsection
