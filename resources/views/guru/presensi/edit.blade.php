@extends('layouts.guru')

@section('content')
<div class="container">
    <h2>Edit Presensi</h2>

    <form action="{{ route('guru.presensi.update', $presensi->kd_presensi) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" 
                   value="{{ $presensi->tanggal }}" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jadwal</label>
            <select name="kd_jp" class="form-control" required>
                @foreach($jadwal as $j)
                    <option value="{{ $j->kd_jp }}"
                        {{ $presensi->kd_jp == $j->kd_jp ? 'selected' : '' }}>
                        {{ $j->kd_jp }}
                    </option>
                @endforeach
            </select>
        </div>

<div class="mb-3">
    <label>Siswa</label>
    <select name="NIS" class="form-control" required>
        @foreach($siswa as $s)
            <option value="{{ $s->NIS }}"
                {{ $presensi->NIS == $s->NIS ? 'selected' : '' }}>
                {{ $s->nama_siswa }}
            </option>
        @endforeach
    </select>
</div>

        <div class="mb-3">
            <label>Jam Masuk</label>
            <input type="time" name="jam_masuk" 
                   value="{{ $presensi->jam_masuk }}" 
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Hadir" {{ $presensi->status=='Hadir'?'selected':'' }}>Hadir</option>
                <option value="Izin" {{ $presensi->status=='Izin'?'selected':'' }}>Izin</option>
                <option value="Sakit" {{ $presensi->status=='Sakit'?'selected':'' }}>Sakit</option>
                <option value="Alfa" {{ $presensi->status=='Alfa'?'selected':'' }}>Alfa</option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('guru.presensi.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>
@endsection