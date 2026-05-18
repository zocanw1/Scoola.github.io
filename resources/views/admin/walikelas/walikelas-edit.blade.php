@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Institusi</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Edit Wali Kelas — {{ $kelas->nama_kelas }}</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Memperbarui penugasan wali kelas untuk rombongan belajar {{ $walikelas->kelas }}.
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
        <form action="{{ route('admin.walikelas.update', $walikelas->id) }}" method="POST" style="max-width: 720px;">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Unit Kelas</label>
                <input type="text" class="form-field" style="background: var(--color-surface); cursor: not-allowed;" value="{{ $walikelas->kelas }}" readonly>
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Guru Wali Kelas</label>
                <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                    <select name="NIP" class="form-field" required>
                        @foreach ($guru as $g)
                            <option value="{{ $g->NIP }}" {{ old('NIP', $walikelas->NIP) == $g->NIP ? 'selected' : '' }}>{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                    <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Simpan Perubahan</button>
                <a href="{{ route('admin.walikelas.index') }}" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Batal</a>
            </div>
        </form>
    </div>

</div>

@endsection
