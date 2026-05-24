@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">SCHEDULE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-calendar-plus"></i> Manajemen Akademik</span>
                <h1 class="mp-title">Tambah Jadwal Baru</h1>
                <p class="mp-description">
                    Atur slot jadwal pelajaran baru untuk kelas yang dipilih. Sistem akan mendeteksi konflik secara otomatis.
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
            <form action="{{ route('jadwal.store') }}" method="POST">
                @csrf
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
        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf

            <div class="mp-form-grid">
                <div class="mp-field">
                    <label class="mp-label">Hari</label>
                    <select name="hari" class="mp-input" required>
                        <option value="" disabled {{ old('hari', request('hari')) ? '' : 'selected' }}>PILIH HARI</option>
                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                            <option value="{{ $h }}" {{ old('hari', request('hari')) == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Kelas</label>
                    <select name="kelas" class="mp-input" required>
                        <option value="" disabled {{ old('kelas', request('kelas')) ? '' : 'selected' }}>PILIH KELAS</option>
                        @php
                            $tingkatan = ['XI'];
                            $jurusan = 'SIJA';
                            $rombel = [1, 2];
                        @endphp
                        @foreach ($tingkatan as $t)
                            @foreach ($rombel as $r)
                                @php $kls = "$t-$jurusan $r"; @endphp
                                <option value="{{ $kls }}" {{ old('kelas', request('kelas')) == $kls ? 'selected' : '' }}>{{ $kls }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Jam Ke (Mulai)</label>
                    <input type="number" name="jam_mulai" class="mp-input" min="1" max="12" value="{{ old('jam_mulai') }}" required placeholder="1">
                    <small class="mp-hint">Masukkan jam ke-1 sampai ke-12</small>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Jam Ke (Selesai)</label>
                    <input type="number" name="jam_selesai" class="mp-input" min="1" max="12" value="{{ old('jam_selesai') }}" required placeholder="3">
                    <small class="mp-hint">Harus sama atau lebih besar dari jam mulai</small>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Mata Pelajaran</label>
                    <select name="kd_mapel" class="mp-input" required>
                        <option value="" disabled {{ old('kd_mapel') ? '' : 'selected' }}>PILIH MATA PELAJARAN</option>
                        @foreach ($mapel as $m)
                            <option value="{{ $m->kd_mapel }}" {{ old('kd_mapel') == $m->kd_mapel ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mp-field">
                    <label class="mp-label">Guru Pengajar</label>
                    <select name="NIP" class="mp-input" required>
                        <option value="" disabled {{ old('NIP') ? '' : 'selected' }}>PILIH GURU</option>
                        @foreach ($guru as $g)
                            <option value="{{ $g->NIP }}" {{ old('NIP') == $g->NIP ? 'selected' : '' }}>{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @php
                $cancelUrl = route('jadwal.index');
                if (request('kelas') && request('hari')) {
                    $cancelUrl = route('jadwal.kelas', ['kelas' => request('kelas'), 'hari' => request('hari')]);
                } elseif (request('kelas')) {
                    $cancelUrl = route('jadwal.kelas', request('kelas'));
                }
            @endphp

            <div class="mp-actions">
                <button type="submit" class="mp-btn"><i class="bi bi-save2"></i> Simpan Jadwal</button>
                <a href="{{ $cancelUrl }}" class="mp-btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
            </div>
        </form>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapelSelect = document.querySelector('select[name="kd_mapel"]');
    const guruSelect = document.querySelector('select[name="NIP"]');

    if (mapelSelect && guruSelect) {
        mapelSelect.addEventListener('change', function() {
            const kdMapel = this.value;
            if (!kdMapel) return;

            guruSelect.innerHTML = '<option value="" disabled selected>MEMUAT GURU...</option>';
            guruSelect.disabled = true;

            fetch(`{{ url('admin/jadwal/get-guru-by-mapel') }}/${kdMapel}`)
                .then(response => response.json())
                .then(data => {
                    guruSelect.innerHTML = '<option value="" disabled selected>PILIH GURU</option>';

                    if (data.length === 0) {
                        guruSelect.innerHTML = '<option value="" disabled selected>TIDAK ADA GURU UNTUK MAPEL INI</option>';
                    } else {
                        data.forEach(guru => {
                            const option = document.createElement('option');
                            option.value = guru.NIP;
                            option.textContent = guru.nama_guru;
                            guruSelect.appendChild(option);
                        });
                    }
                    guruSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching guru:', error);
                    guruSelect.innerHTML = '<option value="" disabled selected>GAGAL MEMUAT DATA</option>';
                });
        });
    }
});
</script>
@endpush

@endsection
