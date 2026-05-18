@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Data</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Edit Guru</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Memperbarui profil pengajar dan koordinasi mata pelajaran untuk {{ $guru->nama_guru }}.
            </p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card" style="background: #ffffff; padding: 48px; border-radius: 16px; border: 1px solid var(--color-hairline); margin-bottom: var(--spacing-section);">
        <form method="POST" action="{{ route('guru.update', $guru->NIP) }}" style="max-width: 720px;">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">NIP (Nomor Induk Pegawai)</label>
                <input type="text" name="nip" class="form-field" style="background: var(--color-surface); cursor: not-allowed;" value="{{ $guru->NIP }}" readonly>
                <small style="color: var(--color-stone); margin-top: 12px; display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">NIP bersifat permanen dalam sistem Scoola</small>
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Nama Lengkap Pengajar</label>
                <input type="text" name="nama" class="form-field" placeholder="Masukkan nama lengkap beserta gelar" required value="{{ old('nama', $guru->nama_guru) }}">
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Bidang Mata Pelajaran</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; padding: 24px; background: var(--color-surface); border-radius: 12px;">
                    @foreach ($mapels as $m)
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 14px;">
                            <input type="checkbox" name="mapels[]" value="{{ $m->kd_mapel }}" 
                                @if($guru->mapels->contains($m->kd_mapel)) checked @endif
                                style="width: 18px; height: 18px; accent-color: var(--color-ink);">
                            {{ $m->nama_mapel }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Perbarui Data Guru</button>
                <a href="{{ route('guru.index') }}" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Batal</a>
            </div>
        </form>
    </div>

</div>

@endsection
