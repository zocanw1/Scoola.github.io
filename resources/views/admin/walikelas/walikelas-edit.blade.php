@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">EDIT GUIDE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-mortarboard-fill"></i> Manajemen Institusi</span>
                <h1 class="mp-title">Edit Wali Kelas</h1>
                <p class="mp-description">
                    Perbarui penugasan wali kelas untuk rombongan belajar {{ $kelas->nama_kelas }}.
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
        <form action="{{ route('admin.walikelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">Unit Kelas</label>
                    <input type="text" class="mp-input" value="{{ $kelas->nama_kelas }}" readonly>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Guru Wali Kelas</label>
                    <select name="guru_nip" class="mp-input" required>
                        @foreach ($guruList as $g)
                            <option value="{{ $g->NIP }}" {{ old('guru_nip', $kelas->wali_kelas_nip) == $g->NIP ? 'selected' : '' }}>
                                {{ $g->nama_guru }} - {{ $g->NIP }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-check2-circle"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.walikelas.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@endsection
