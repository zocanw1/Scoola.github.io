@extends('layouts.admin')

@section('content')

<style>
    /* ── PAGE HEADER ─────────────────────────── */
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

    /* ── KELAS GRID ─────────────────────────── */
    .kelas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 16px;
    }

    .kelas-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 24px;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        gap: 12px;
        position: relative;
        overflow: hidden;
    }

    .kelas-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 100px; height: 100px;
        background: var(--accent);
        opacity: 0.03;
        border-radius: 0 0 0 100%;
        transition: opacity 0.2s;
    }

    .kelas-card:hover {
        border-color: rgba(88, 166, 255, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .kelas-card:hover::before {
        opacity: 0.08;
    }

    .kelas-icon {
        width: 42px; height: 42px;
        background: rgba(88, 166, 255, 0.1);
        color: var(--accent);
        border-radius: 10px;
        display: grid; place-items: center;
        font-size: 20px;
    }

    .kelas-name {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px;
        font-weight: 800;
        color: var(--text1);
    }

    .kelas-stats {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--text2);
    }

    .btn-view {
        margin-top: 8px;
        font-size: 11px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-view i {
        font-size: 12px;
        transition: transform 0.2s;
    }

    .kelas-card:hover .btn-view i {
        transform: translateX(4px);
    }
</style>

<div class="page-header">
    <div class="page-title">Data Kelas</div>
    <div class="page-subtitle">Pilih kelas untuk melihat daftar siswa yang terdaftar</div>
</div>

<div class="kelas-grid">
    @foreach($kelasList as $kelas)
        @php
            $count = $counts->get($kelas) ?? 0;
        @endphp
        <a href="{{ route('admin.kelas.show', $kelas) }}" class="kelas-card">
            <div class="kelas-icon">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="kelas-name">{{ $kelas }}</div>
                <div class="kelas-stats">
                    <i class="bi bi-people-fill"></i>
                    {{ $count }} Siswa terdaftar
                </div>
            </div>
            <div class="btn-view">
                Lihat Detail Siswa <i class="bi bi-arrow-right-short"></i>
            </div>
        </a>
    @endforeach
</div>

@endsection
