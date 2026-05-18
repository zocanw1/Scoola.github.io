@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Data</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Edit Siswa</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Memperbarui informasi profil dan penempatan kelas untuk siswa {{ $siswa->nama_siswa }}.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="card" style="background: #ffffff; padding: 24px; border: 1px solid var(--color-ink); border-radius: 12px;">
            <ul style="margin: 0; padding-left: 20px; color: var(--color-ink); font-size: 13px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card" style="background: #ffffff; padding: 48px; border-radius: 16px; border: 1px solid var(--color-hairline); margin-bottom: var(--spacing-section);">
        <form method="POST" action="{{ route('siswa.update', $siswa->NIS) }}" style="max-width: 720px;">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">NIS (Nomor Induk Siswa)</label>
                <input type="text" name="nis" class="form-field" style="background: var(--color-surface); cursor: not-allowed;" value="{{ $siswa->NIS }}" readonly>
                <small style="color: var(--color-stone); margin-top: 12px; display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">NIS tidak dapat diubah untuk menjaga integritas data</small>
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Nama Lengkap Siswa</label>
                <input type="text" name="nama" class="form-field" placeholder="Masukkan nama lengkap siswa" required value="{{ old('nama', $siswa->nama_siswa) }}">
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Penempatan Kelas</label>
                <div style="position: relative;">
                    <select name="kelas" class="form-field" style="cursor: pointer; appearance: none; text-transform: uppercase;" required>
                        <option value="XI-SIJA 1" {{ old('kelas', $siswa->kelas) == 'XI-SIJA 1' ? 'selected' : '' }}>XI-SIJA 1</option>
                        <option value="XI-SIJA 2" {{ old('kelas', $siswa->kelas) == 'XI-SIJA 2' ? 'selected' : '' }}>XI-SIJA 2</option>
                    </select>
                    <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                </div>
            </div>

            <div class="form-actions" style="display: flex; gap: 32px; align-items: center; margin-top: 64px; padding-top: 48px; border-top: 1px solid var(--color-hairline);">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Perbarui Data Siswa</button>
                <a href="{{ route('siswa.index') }}" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Batal</a>
            </div>
        </form>
    </div>

</div>

@endsection
