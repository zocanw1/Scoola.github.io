@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">EDIT MODE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-person-lines-fill"></i> Manajemen Data</span>
                <h1 class="mp-title">Edit Siswa</h1>
                <p class="mp-description">
                    Perbarui profil dan penempatan kelas untuk {{ $siswa->nama_siswa }} tanpa mengubah identitas NIS utama.
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
        <form method="POST" action="{{ route('siswa.update', $siswa->NIS) }}">
            @csrf
            @method('PUT')

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">NIS (Nomor Induk Siswa)</label>
                    <input type="text" name="nis" class="mp-input" value="{{ $siswa->NIS }}" readonly>
                    <small class="mp-hint">NIS tidak dapat diubah untuk menjaga integritas data</small>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Nama Lengkap Siswa</label>
                    <input type="text" name="nama" class="mp-input" placeholder="Masukkan nama lengkap siswa" required value="{{ old('nama', $siswa->nama_siswa) }}">
                </div>

                <div class="mp-field">
                    <label class="mp-label">Penempatan Kelas</label>
                    <select name="kelas" class="mp-input" required>
                        <option value="XI-SIJA 1" {{ old('kelas', $siswa->kelas) == 'XI-SIJA 1' ? 'selected' : '' }}>XI-SIJA 1</option>
                        <option value="XI-SIJA 2" {{ old('kelas', $siswa->kelas) == 'XI-SIJA 2' ? 'selected' : '' }}>XI-SIJA 2</option>
                    </select>
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-check2-circle"></i> Perbarui Data Siswa</button>
                <a href="{{ route('siswa.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
