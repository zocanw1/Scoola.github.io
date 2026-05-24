@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">EDIT SCHEDULE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-calendar-check"></i> Manajemen Akademik</span>
                <h1 class="mp-title">Edit Jadwal</h1>
                <p class="mp-description">
                    Perbarui slot waktu atau pengajar untuk mata pelajaran yang telah dijadwalkan.
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

    @if (session('confirm_replace'))
        <div class="mp-alert">
            <div class="mp-label" style="margin-bottom: 10px;">Peringatan Jadwal Bentrok</div>
            <p style="margin: 0 0 20px; color: var(--midnight); font-weight: 800;">{{ session('confirm_replace') }}</p>
            <form action="{{ route('jadwal.update', $jadwal->kd_jp) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="hari" value="{{ old('hari') }}">
                <input type="hidden" name="kelas" value="{{ old('kelas') }}">
                <input type="hidden" name="jam_mulai" value="{{ old('jam_mulai') }}">
                <input type="hidden" name="jam_selesai" value="{{ old('jam_selesai') }}">
                <input type="hidden" name="kd_mapel" value="{{ old('kd_mapel') }}">
                <input type="hidden" name="NIP" value="{{ old('NIP') }}">
                <input type="hidden" name="force" value="1">
                <button type="submit" class="mp-btn"><i class="bi bi-exclamation-triangle"></i> Ya, Tetap Simpan & Ganti</button>
            </form>
        </div>
    @endif

    <section class="mp-form-card">
        <form action="{{ route('jadwal.update', $jadwal->kd_jp) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">Hari</label>
                    <select name="hari" class="mp-input" required>
                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                            <option value="{{ $h }}" {{ old('hari', $jadwal->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Kelas</label>
                    <select name="kelas" class="mp-input" required>
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
                </div>

                <div class="mp-field">
                    <label class="mp-label">Jam Ke (Mulai)</label>
                    <input type="number" name="jam_mulai" class="mp-input" min="1" max="12" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Jam Ke (Selesai)</label>
                    <input type="number" name="jam_selesai" class="mp-input" min="1" max="12" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Mata Pelajaran</label>
                    <select name="kd_mapel" class="mp-input" required>
                        @foreach ($mapel as $m)
                            <option value="{{ $m->kd_mapel }}" {{ old('kd_mapel', $jadwal->kd_mapel) == $m->kd_mapel ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Guru Pengajar</label>
                    <select name="NIP" class="mp-input" required>
                        @foreach ($guru as $g)
                            <option value="{{ $g->NIP }}" {{ old('NIP', $jadwal->NIP) == $g->NIP ? 'selected' : '' }}>{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-check2-circle"></i> Simpan Perubahan</button>
                <a href="{{ route('jadwal.index') }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
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
