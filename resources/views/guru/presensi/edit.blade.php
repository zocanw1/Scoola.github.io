@extends('layouts.guru')

@section('content')

<div class="editorial-header">
    <span class="eyebrow">Log Akademik</span>
    <h1 class="display-title">Edit Presensi</h1>
    <p class="text-body" style="color: var(--color-graphite); max-width: 600px;">
        Perbarui data kehadiran siswa untuk sesi mata pelajaran tertentu.
    </p>
</div>

@if ($errors->any())
    <div style="border: 1px solid var(--color-ink); padding: 16px; margin-bottom: 32px; background: #fff;">
        <ul style="margin: 0; padding-left: 20px; color: var(--color-ink); font-size: 13px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="max-width: 720px; margin-bottom: 64px;">
    <form action="{{ route('guru.presensi.update-status', [$presensi->sesi_id, $presensi->NIS]) }}" method="POST">
        @csrf

        <div style="margin-bottom: 48px;">
            <label class="text-micro-caps" style="display: block; margin-bottom: 8px; color: var(--color-stone);">Tanggal</label>
            <input type="date" name="tanggal" style="width: 100%; border: none; border-bottom: 1px solid var(--color-hairline); padding: 12px 0; font-family: var(--font-family-base); font-size: 15px; outline: none; background: transparent; color: var(--color-ink);" required value="{{ $presensi->tanggal }}">
        </div>

        <div style="margin-bottom: 48px;">
            <label class="text-micro-caps" style="display: block; margin-bottom: 8px; color: var(--color-stone);">Pilih Jadwal</label>
            <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                <select name="kd_jp" style="width: 100%; border: none; padding: 12px 0; font-family: var(--font-family-base); font-size: 15px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink);" required>
                    @foreach($jadwal as $j)
                        <option value="{{ $j->kd_jp }}" {{ $presensi->kd_jp == $j->kd_jp ? 'selected' : '' }}>{{ $j->kd_jp }}</option>
                    @endforeach
                </select>
                <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
            </div>
        </div>

        <div style="margin-bottom: 48px;">
            <label class="text-micro-caps" style="display: block; margin-bottom: 8px; color: var(--color-stone);">Pilih Siswa</label>
            <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                <select name="NIS" style="width: 100%; border: none; padding: 12px 0; font-family: var(--font-family-base); font-size: 15px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink);" required>
                    @foreach($siswa as $s)
                        <option value="{{ $s->NIS }}" {{ $presensi->NIS == $s->NIS ? 'selected' : '' }}>{{ $s->nama_siswa }} ({{ $s->NIS }})</option>
                    @endforeach
                </select>
                <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
            </div>
        </div>

        <div style="margin-bottom: 48px;">
            <label class="text-micro-caps" style="display: block; margin-bottom: 8px; color: var(--color-stone);">Jam Masuk</label>
            <input type="time" name="jam_masuk" style="width: 100%; border: none; border-bottom: 1px solid var(--color-hairline); padding: 12px 0; font-family: var(--font-family-base); font-size: 15px; outline: none; background: transparent; color: var(--color-ink);" required value="{{ $presensi->jam_masuk }}">
        </div>

        <div style="margin-bottom: 48px;">
            <label class="text-micro-caps" style="display: block; margin-bottom: 8px; color: var(--color-stone);">Status Kehadiran</label>
            <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                <select name="status" style="width: 100%; border: none; padding: 12px 0; font-family: var(--font-family-base); font-size: 15px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink);" required>
                    <option value="Hadir" {{ $presensi->status == 'Hadir' ? 'selected' : '' }}>HADIR</option>
                    <option value="Izin" {{ $presensi->status == 'Izin' ? 'selected' : '' }}>IZIN</option>
                    <option value="Sakit" {{ $presensi->status == 'Sakit' ? 'selected' : '' }}>SAKIT</option>
                    <option value="Alfa" {{ $presensi->status == 'Alfa' ? 'selected' : '' }}>ALFA</option>
                </select>
                <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
            </div>
        </div>

        <div style="display: flex; gap: 32px; align-items: center; margin-top: 64px; padding-top: 32px; border-top: 1px solid var(--color-hairline);">
            <button type="submit" class="btn-primary">Perbarui Presensi</button>
            <a href="{{ route('guru.presensi.index') }}" class="text-link-sm" style="color: var(--color-stone); text-decoration: none;">Batal</a>
        </div>
    </form>
</div>

@endsection
