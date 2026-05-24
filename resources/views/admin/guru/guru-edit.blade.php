@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">EDIT MODE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
            <span class="mp-kicker"><i class="bi bi-person-badge"></i> Manajemen Data</span>
            <h1 class="mp-title">Edit Guru</h1>
            <p class="mp-description">
                Perbarui profil pengajar dan penugasan mata pelajaran untuk {{ $guru->nama_guru }}.
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
        <form method="POST" action="{{ route('guru.update', $guru->NIP) }}">
            @csrf
            @method('PUT')

            <div class="mp-field">
                <label class="mp-label">NIP (Nomor Induk Pegawai)</label>
                <input type="text" name="nip" class="mp-input" value="{{ $guru->NIP }}" readonly>
                <small class="mp-hint">NIP bersifat permanen dalam sistem Scoola</small>
            </div>

            <div class="mp-field">
                <label class="mp-label">Nama Lengkap Pengajar</label>
                <input type="text" name="nama" class="mp-input" placeholder="Masukkan nama lengkap beserta gelar" required value="{{ old('nama', $guru->nama_guru) }}">
            </div>

            <div class="mp-field">
                <label class="mp-label">Bidang Mata Pelajaran</label>
                <div class="mp-checkbox-grid">
                    @foreach ($mapel as $m)
                        <label class="mp-check">
                            <input type="checkbox" name="kd_mapel[]" value="{{ $m->kd_mapel }}" @checked($guru->mapels->contains($m->kd_mapel))>
                            <span>{{ $m->nama_mapel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-check2-circle"></i> Perbarui Data Guru</button>
                <a href="{{ route('guru.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
