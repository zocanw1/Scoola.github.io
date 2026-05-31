@extends($pageLayout ?? 'layouts.admin')

@section('content')

@php
    $statusColors = [
        'Hadir' => 'var(--cyber)',
        'Izin' => '#d9f1ff',
        'Sakit' => '#efe3ff',
        'Alpa' => '#ffd8d3',
        'Belum Hadir' => 'var(--gold)',
        'Ditolak' => '#ffd9b8',
    ];
@endphp

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">COUNSELOR</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-people-fill"></i> Monitoring Individual</span>
                <h1 class="mp-title">Presensi Siswa</h1>
                <p class="mp-description">Cari siswa, buka detail, lalu tentukan rentang tanggal untuk melihat rekap presensi personal.</p>
            </div>
        </section>
    </div>

    <section class="mp-card">
        <form action="{{ route($pageRoute ?? 'admin.presensi-siswa.index') }}" method="GET" class="mp-form-grid" style="align-items:end;">
            <div class="mp-field" style="margin-bottom:0;">
                <label class="mp-label">Kelas</label>
                <select name="kelas" class="mp-input">
                    <option value="">SEMUA KELAS</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->nama_kelas }}" {{ $selectedKelas === $k->nama_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mp-field" style="margin-bottom:0;">
                <label class="mp-label">Cari Siswa</label>
                <input type="text" name="q" value="{{ $search }}" class="mp-input" placeholder="Nama atau NIS">
            </div>

            <div class="mp-field" style="margin-bottom:0;">
                <label class="mp-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="mp-input">
            </div>

            <div class="mp-field" style="margin-bottom:0;">
                <label class="mp-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="mp-input">
            </div>

            <div class="mp-actions" style="border-top:0; padding-top:0; margin-top:0;">
                <button type="submit" class="mp-btn"><i class="bi bi-search"></i> Tampilkan</button>
            </div>
        </form>
    </section>

    <section class="mp-card mp-card-gold">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <div class="mp-label">Daftar Alpa Otomatis</div>
                <h2 style="margin:8px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:30px;">{{ $alpaQueue->count() }} Perlu Koreksi</h2>
                <p style="margin:10px 0 0; color:var(--midnight); font-weight:900;">Status final sesi yang sudah selesai otomatis jadi Alpa, lalu admin bisa koreksi dari sini.</p>
            </div>
            <span class="mp-badge" style="background:var(--white);">{{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}</span>
        </div>

        <div class="mp-stack-list">
            @forelse($alpaQueue as $row)
                <div class="mp-card" style="padding:16px; box-shadow:4px 4px 0 var(--midnight); background:var(--white); display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap;">
                    <div>
                        <div style="font-family:'Fredoka One', cursive; color:var(--midnight); font-size:20px;">{{ $row['nama_siswa'] }}</div>
                        <div style="color:var(--midnight); font-weight:900; margin-top:4px;">{{ $row['nis'] }} &bull; {{ $row['kelas'] }}</div>
                        <div style="color:var(--midnight); font-weight:900; margin-top:8px;">
                            {{ \Carbon\Carbon::parse($row['tanggal'])->format('d M Y') }} &bull; {{ $row['mapel'] }} &bull; {{ $row['jam'] }}
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                        <span class="mp-badge" style="background:{{ $statusColors[$row['status']] ?? 'var(--white)' }};">{{ $row['status'] }}</span>
                        <a class="mp-btn" style="text-decoration:none;" href="{{ route($pageShowRoute ?? 'admin.presensi-siswa.show', ['nis' => $row['nis'], 'q' => $search, 'kelas' => $row['kelas'], 'tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}">
                            <i class="bi bi-pencil-square"></i> Koreksi
                        </a>
                    </div>
                </div>
            @empty
                <section class="mp-empty-state" style="padding:28px 20px;">
                    <div style="font-family:'Fredoka One', cursive; font-size:22px; color:var(--midnight);">Tidak ada Alpa</div>
                    <p style="margin:10px 0 0; font-weight:800;">Tidak ada status Alpa pada rentang tanggal ini.</p>
                </section>
            @endforelse
        </div>
    </section>

    <section class="mp-card">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <div class="mp-label">Data Siswa</div>
                <h2 style="margin:8px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:30px;">{{ $siswas->count() }} Siswa</h2>
            </div>
            <span class="mp-badge" style="background:var(--white);">{{ $selectedKelas ?: 'Semua Kelas' }}</span>
        </div>

        <div class="mp-stack-list">
            @forelse($siswas as $siswa)
                <div class="mp-card" style="padding:16px; box-shadow:4px 4px 0 var(--midnight); background:var(--white); display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap;">
                    <div>
                        <div style="font-family:'Fredoka One', cursive; color:var(--midnight); font-size:20px;">{{ $siswa->nama_siswa }}</div>
                        <div style="color:var(--midnight); font-weight:900; margin-top:4px;">{{ $siswa->NIS }} &bull; {{ $siswa->kelas }}</div>
                    </div>
                    <a class="mp-btn" style="text-decoration:none;" href="{{ route($pageShowRoute ?? 'admin.presensi-siswa.show', ['nis' => $siswa->NIS, 'q' => $search, 'kelas' => $siswa->kelas, 'tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                </div>
            @empty
                <section class="mp-empty-state" style="padding:28px 20px;">
                    <div style="font-family:'Fredoka One', cursive; font-size:22px; color:var(--midnight);">Siswa tidak ditemukan</div>
                    <p style="margin:10px 0 0; font-weight:800;">Tidak ada siswa yang cocok dengan filter saat ini.</p>
                </section>
            @endforelse
        </div>
    </section>

</div>

@endsection
