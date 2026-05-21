@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Akademik</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Edit Jadwal</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Memperbarui slot waktu atau pengajar untuk mata pelajaran yang telah dijadwalkan.
            </p>
        </div>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="card" style="background: #ffffff; padding: 24px; border: 2px solid var(--color-ink); border-radius: 16px;">
            <ul style="margin: 0; padding-left: 20px; color: var(--color-ink); font-size: 13px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- COLLISION WARNING --}}
    @if (session('confirm_replace'))
        <div class="card" style="background: #ffffff; padding: 32px; border: 2px solid var(--color-ink); border-radius: 16px; box-shadow: 0 12px 32px rgba(0,0,0,0.08);">
            <div class="text-body-strong" style="margin-bottom: 8px; font-size: 18px; font-weight: 700;">Peringatan Jadwal Bentrok</div>
            <p class="text-body" style="color: var(--color-graphite); margin-bottom: 24px;">{{ session('confirm_replace') }}</p>
            <form action="{{ route('jadwal.update', $jadwal->id_jadwal) }}" method="POST" style="display: inline-block; width: 100%;">
                @csrf
                @method('PUT')
                <input type="hidden" name="hari" value="{{ old('hari') }}">
                <input type="hidden" name="kelas" value="{{ old('kelas') }}">
                <input type="hidden" name="jam_mulai" value="{{ old('jam_mulai') }}">
                <input type="hidden" name="jam_selesai" value="{{ old('jam_selesai') }}">
                <input type="hidden" name="kd_mapel" value="{{ old('kd_mapel') }}">
                <input type="hidden" name="NIP" value="{{ old('NIP') }}">
                <input type="hidden" name="force" value="1">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 32px; width: 100%;">Ya, Tetap Simpan & Ganti</button>
            </form>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card" style="background: #ffffff; padding: 48px; border-radius: 16px; border: 1px solid var(--color-hairline); margin-bottom: var(--spacing-section);">
        <form action="{{ route('jadwal.update', $jadwal->id_jadwal) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 64px; max-width: 900px;">
                <div style="margin-bottom: 48px;">
                    <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Hari</label>
                    <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                        <select name="hari" class="form-field" required style="width: 100%; border: none; padding: 16px 0; font-family: var(--font-family-base); font-size: 18px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink); font-weight: 500; text-transform: uppercase;">
                            @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                                <option value="{{ $h }}" {{ old('hari', $jadwal->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                        <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                    </div>
                </div>

                <div style="margin-bottom: 48px;">
                    <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Kelas</label>
                    <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                        <select name="kelas" class="form-field" required style="width: 100%; border: none; padding: 16px 0; font-family: var(--font-family-base); font-size: 18px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink); font-weight: 500; text-transform: uppercase;">
                            @php
                                $tingkatan = ['XI'];
                                $jurusan = 'SIJA';
                                $rombel = [1, 2];
                            @endphp
                            @foreach ($tingkatan as $t)
                                @foreach ($rombel as $r)
                                    @php $kls = "$t-$jurusan $r"; @endphp
                                    <option value="{{ $kls }}" {{ old('kelas', $jadwal->kelas) == $kls ? 'selected' : '' }}>{{ $kls }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                    </div>
                </div>

                <div style="margin-bottom: 48px;">
                    <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Jam Ke (Mulai)</label>
                    <input type="number" name="jam_mulai" class="form-field" min="1" max="12" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required style="width: 100%; border: none; border-bottom: 1px solid var(--color-hairline); padding: 16px 0; font-family: var(--font-family-base); font-size: 18px; outline: none; background: transparent; color: var(--color-ink); font-weight: 500;">
                </div>

                <div style="margin-bottom: 48px;">
                    <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Jam Ke (Selesai)</label>
                    <input type="number" name="jam_selesai" class="form-field" min="1" max="12" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required style="width: 100%; border: none; border-bottom: 1px solid var(--color-hairline); padding: 16px 0; font-family: var(--font-family-base); font-size: 18px; outline: none; background: transparent; color: var(--color-ink); font-weight: 500;">
                </div>

                <div style="margin-bottom: 48px;">
                    <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Mata Pelajaran</label>
                    <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                        <select name="kd_mapel" class="form-field" required style="width: 100%; border: none; padding: 16px 0; font-family: var(--font-family-base); font-size: 18px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink); font-weight: 500; text-transform: uppercase;">
                            @foreach ($mapel as $m)
                                <option value="{{ $m->kd_mapel }}" {{ old('kd_mapel', $jadwal->kd_mapel) == $m->kd_mapel ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                        <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                    </div>
                </div>

                <div style="margin-bottom: 48px;">
                    <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Guru Pengajar</label>
                    <div style="position: relative; border-bottom: 1px solid var(--color-hairline);">
                        <select name="NIP" class="form-field" required style="width: 100%; border: none; padding: 16px 0; font-family: var(--font-family-base); font-size: 18px; outline: none; background: transparent; cursor: pointer; appearance: none; color: var(--color-ink); font-weight: 500; text-transform: uppercase;">
                            @foreach ($guru as $g)
                                <option value="{{ $g->NIP }}" {{ old('NIP', $jadwal->NIP) == $g->NIP ? 'selected' : '' }}>{{ $g->nama_guru }}</option>
                            @endforeach
                        </select>
                        <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Simpan Perubahan</button>
                <a href="{{ route('jadwal.index') }}" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Batal</a>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapelSelect = document.querySelector('select[name="kd_mapel"]');
    const guruSelect = document.querySelector('select[name="NIP"]');
    const currentNip = "{{ $jadwal->NIP }}";
    
    function updateGuruList(kdMapel, selectedNip = null) {
        if (!kdMapel) return;

        guruSelect.innerHTML = '<option value="" disabled>MEMUAT GURU...</option>';
        guruSelect.disabled = true;

        fetch(`{{ url('admin/jadwal/get-guru-by-mapel') }}/${kdMapel}`)
            .then(response => response.json())
            .then(data => {
                guruSelect.innerHTML = '<option value="" disabled>PILIH GURU</option>';
                
                if (data.length === 0) {
                    guruSelect.innerHTML = '<option value="" disabled selected>TIDAK ADA GURU UNTUK MAPEL INI</option>';
                } else {
                    data.forEach(guru => {
                        const option = document.createElement('option');
                        option.value = guru.NIP;
                        option.textContent = guru.nama_guru;
                        if (selectedNip && guru.NIP === selectedNip) {
                            option.selected = true;
                        }
                        guruSelect.appendChild(option);
                    });
                }
                guruSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching guru:', error);
                guruSelect.innerHTML = '<option value="" disabled>GAGAL MEMUAT DATA</option>';
            });
    }

    if (mapelSelect && guruSelect) {
        if (mapelSelect.value) {
            updateGuruList(mapelSelect.value, currentNip);
        }

        mapelSelect.addEventListener('change', function() {
            updateGuruList(this.value);
        });
    }
});
</script>
@endpush

@endsection
