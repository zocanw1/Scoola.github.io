@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">CLASS GUIDE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-mortarboard"></i> Manajemen Institusi</span>
                <h1 class="mp-title">Penunjukan Wali Kelas</h1>
                <p class="mp-description">
                    Tugaskan tenaga pendidik sebagai wali kelas pada rombongan belajar yang tersedia.
                </p>
            </div>
        </section>
    </div>

    @if(session('error'))
        <div class="mp-alert danger">{{ session('error') }}</div>
    @endif

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
        <form action="{{ route('admin.walikelas.store') }}" method="POST">
            @csrf

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">Pilih Kelas</label>
                    <select name="kelas_id" class="mp-input" required>
                        <option value="">PILIH KELAS</option>
                        @foreach($kelasAvailable as $kls)
                            <option value="{{ $kls->id }}" @selected(old('kelas_id', request('kelas_id')) == $kls->id)>
                                {{ $kls->nama_kelas }} ({{ $kls->siswa->count() }} siswa)
                            </option>
                        @endforeach
                    </select>
                    <small class="mp-hint">Hanya kelas yang belum memiliki wali kelas</small>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Pilih Guru</label>
                    <select name="guru_nip" class="mp-input" required>
                        <option value="">PILIH GURU</option>
                        @foreach($guruList as $guru)
                            <option value="{{ $guru->NIP }}" @selected(old('guru_nip') == $guru->NIP)>
                                {{ $guru->nama_guru }} - {{ $guru->NIP }}
                                @if($guru->mapel) ({{ $guru->mapel->nama_mapel }}) @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="mp-hint">Hanya guru yang belum menjadi wali kelas</small>
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-check2-circle"></i> Simpan Penunjukan</button>
                <a href="{{ route('admin.walikelas.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
