@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">EDIT SUBJECT</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-book-half"></i> Manajemen Akademik</span>
                <h1 class="mp-title">Edit Mata Pelajaran</h1>
                <p class="mp-description">
                    Perbarui informasi mata pelajaran. Perubahan akan tercermin pada seluruh sistem penjadwalan.
                </p>
            </div>
        </section>
    </div>

    @if ($errors->any())
        <div class="mp-alert danger">
            <div class="mp-label" style="margin-bottom: 10px;">Ditemukan Kesalahan Validasi</div>
            <ul style="margin: 0; padding-left: 20px; color: var(--midnight); font-weight: 800;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="mp-form-card">
        <form action="{{ route('mapel.update', $mapel->kd_mapel) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">Kode Mata Pelajaran</label>
                    <input type="text" name="kd_mapel" class="mp-input" value="{{ $mapel->kd_mapel }}" readonly>
                    <small class="mp-hint">Kode mapel tidak dapat diubah</small>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Nama Mata Pelajaran</label>
                    <input type="text" name="nama_mapel" class="mp-input" placeholder="Masukkan nama mata pelajaran" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-check2-circle"></i> Perbarui Mata Pelajaran</button>
                <a href="{{ route('mapel.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
