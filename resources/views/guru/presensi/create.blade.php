@extends('layouts.guru')

@section('content')
<div class="container">
    <h2>Tambah Presensi</h2>

    <form action="{{ route('guru.presensi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jadwal</label>
            <select name="kd_jp" class="form-control" required>
                <option value="">-- Pilih Jadwal --</option>
                @foreach($jadwal as $j)
                    <option value="{{ $j->kd_jp }}">
                        {{ $j->kd_jp }}
                    </option>
                @endforeach
            </select>
        </div>

       <div class="mb-3">
    <label>Siswa</label>
    <select name="NIS" class="form-control" required>
        <option value="">-- Pilih Siswa --</option>
        @foreach($siswa as $s)
            <option value="{{ $s->NIS }}">
                {{ $s->nama_siswa }}
            </option>
        @endforeach
    </select>
</div>

        <div class="mb-3">
            <label>Jam Masuk</label>
            <input type="time" name="jam_masuk" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
                <option value="Alfa">Alfa</option>
            </select>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('guru.presensi.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>
@endsection
