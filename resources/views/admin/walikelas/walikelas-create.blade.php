@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Data</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Penunjukan Wali Kelas</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Tugaskan tenaga pendidik untuk menjadi wali kelas pada rombongan belajar yang tersedia.
            </p>
        </div>
    </div>

    @if(session('error'))
        <div class="card" style="background: #ffffff; padding: 24px; border: 1px solid var(--color-ink); border-radius: 12px; color: var(--color-ink); font-size: 13px;">
            {{ session('error') }}
        </div>
    @endif

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
        <form action="{{ route('admin.walikelas.store') }}" method="POST" style="max-width: 720px;">
            @csrf

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Pilih Kelas</label>
                <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                    <select name="kelas_id" style="width: 100%; border: none; padding: 16px 0; font-family: var(--font-family-base); font-size: 18px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink); font-weight: 500; text-transform: uppercase;" required>
                        <option value="">PILIH KELAS</option>
                        @foreach($kelasAvailable as $kls)
                            <option value="{{ $kls->id }}" @selected(old('kelas_id', request('kelas_id')) == $kls->id)>
                                {{ $kls->nama_kelas }} ({{ $kls->siswa->count() }} siswa)
                            </option>
                        @endforeach
                    </select>
                    <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                </div>
                <small style="color: var(--color-stone); margin-top: 12px; display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Hanya menampilkan kelas yang belum memiliki wali kelas</small>
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Pilih Guru</label>
                <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                    <select name="guru_nip" style="width: 100%; border: none; padding: 16px 0; font-family: var(--font-family-base); font-size: 18px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink); font-weight: 500; text-transform: uppercase;" required>
                        <option value="">PILIH GURU</option>
                        @foreach($guruList as $guru)
                            <option value="{{ $guru->NIP }}" @selected(old('guru_nip') == $guru->NIP)>
                                {{ $guru->nama_guru }} — {{ $guru->NIP }}
                                @if($guru->mapel) ({{ $guru->mapel->nama_mapel }}) @endif
                            </option>
                        @endforeach
                    </select>
                    <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                </div>
                <small style="color: var(--color-stone); margin-top: 12px; display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Hanya menampilkan guru yang belum menjadi wali kelas</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Simpan Penunjukan</button>
                <a href="{{ route('admin.walikelas.index') }}" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Batal</a>
            </div>
        </form>
    </div>

</div>

@endsection
