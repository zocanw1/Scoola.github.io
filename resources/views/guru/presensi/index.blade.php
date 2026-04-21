@extends('layouts.guru')

@section('content')
<div class="container">
    <h2>Data Presensi</h2>

    <a href="{{ route('guru.presensi.create') }}" class="btn btn-primary mb-3">
        + Tambah Presensi
    </a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Jadwal</th>
                <th>NIS</th> <!-- Kolom baru -->
                <th>Siswa</th>
                <th>Jam Masuk</th>
                <th>Status</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensi as $p)
                <tr>
                    <td>{{ $p->kd_presensi }}</td>
                    <td>{{ $p->tanggal }}</td>
                    <td>{{ $p->jadwal->kd_jp ?? '-' }}</td>

                    <!-- Kolom NIS -->
                    <td>{{ $p->siswa->NIS ?? '-' }}</td>

                    <!-- Kolom Nama Siswa -->
                    <td>{{ $p->siswa->nama_siswa ?? '-' }}</td>

                    <td>{{ $p->jam_masuk }}</td>

                    <td>
                        <span class="badge bg-{{ 
                            $p->status == 'Hadir' ? 'success' : 
                            ($p->status == 'Izin' ? 'warning' : 
                            ($p->status == 'Sakit' ? 'info' : 'danger')) 
                        }}">
                            {{ $p->status }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('guru.presensi.edit', $p->kd_presensi) }}" 
                           class="btn btn-sm btn-warning">
                           Edit
                        </a>

                        <form action="{{ route('guru.presensi.destroy', $p->kd_presensi) }}" 
                              method="POST" 
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin hapus?')" 
                                    class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">
                        Belum ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection