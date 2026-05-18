@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Akademik</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Edit Mata Pelajaran</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Memperbarui informasi mata pelajaran akademik. Perubahan akan tercermin pada seluruh sistem penjadwalan.
            </p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card" style="background: #ffffff; padding: 48px; border-radius: 16px; border: 1px solid var(--color-hairline); margin-bottom: var(--spacing-section);">
        <form action="{{ route('mapel.update', $mapel->kd_mapel) }}" method="POST" style="max-width: 720px;">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Kode Mata Pelajaran</label>
                <input type="text" name="kd_mapel" class="form-field" style="background: var(--color-surface); cursor: not-allowed;" value="{{ $mapel->kd_mapel }}" readonly>
                <small style="color: var(--color-stone); margin-top: 12px; display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Kode Mapel tidak dapat diubah setelah didefinisikan</small>
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" class="form-field" placeholder="Masukkan nama mata pelajaran" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Perbarui Mata Pelajaran</button>
                <a href="{{ route('mapel.index') }}" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Batal</a>
            </div>
        </form>
    </div>

</div>

@endsection
