@extends('layouts.admin')

@section('content')

<div class="editorial-header">
    <span class="eyebrow">Manajemen Akademik</span>
    <h1 class="display-title">Pilih Jadwal Kelas</h1>
    <p class="text-body" style="color: var(--color-graphite); max-width: 600px;">
        Pilih kelas untuk melihat dan mengelola jadwal pelajaran yang tersedia.
    </p>
</div>

@php
    $tingkatan = ['X', 'XI', 'XII'];
    $jurusan = 'SIJA';
    $rombel = [1, 2];
    $totalItems = count($tingkatan) * count($rombel);
    $itemIndex = 0;
@endphp

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 32px;">
    @foreach ($tingkatan as $t)
        @foreach ($rombel as $r)
            @php 
                $kelas = "$t-$jurusan $r"; 
                $itemIndex++;
                $isFullWidth = ($totalItems % 2 !== 0 && $itemIndex === 1) || $totalItems === 1;
            @endphp
            <a href="{{ route('jadwal.kelas', $kelas) }}" 
               style="text-decoration: none; padding: 32px; border: 1px solid var(--color-hairline); color: var(--color-ink); display: block; transition: border-color 0.2s; background: #fff; border-radius: 12px;
               @if($isFullWidth) grid-column: 1 / -1; @endif">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 8px;">Kelas {{ $t }}</div>
                <div style="font-size: 20px; font-weight: 500; margin-bottom: 16px;">{{ $kelas }}</div>
                <div class="text-meta" style="color: var(--color-graphite);">LIHAT JADWAL PELAJARAN</div>
            </a>
        @endforeach
    @endforeach
</div>

@endsection
