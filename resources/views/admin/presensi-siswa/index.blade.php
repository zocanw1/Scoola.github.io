@extends('layouts.admin')

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
        <form action="{{ route('admin.presensi-siswa.index') }}" method="GET" class="mp-form-grid" style="align-items:end;">
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

            <div class="mp-actions" style="border-top:0; padding-top:0; margin-top:0;">
                <button type="submit" class="mp-btn"><i class="bi bi-search"></i> Tampilkan</button>
            </div>
        </form>
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
                <div class="mp-card" style="padding:16px; box-shadow:4px 4px 0 var(--midnight); display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap;">
                    <div>
                        <div style="font-family:'Fredoka One', cursive; color:var(--midnight); font-size:20px;">{{ $siswa->nama_siswa }}</div>
                        <div style="color:var(--midnight); font-weight:900; margin-top:4px;">{{ $siswa->NIS }} &bull; {{ $siswa->kelas }}</div>
                    </div>
                    <a class="mp-btn" style="text-decoration:none;" href="{{ route('admin.presensi-siswa.index', ['q' => $search, 'kelas' => $siswa->kelas, 'nis' => $siswa->NIS, 'tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir]) }}">
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

    @if($selectedSiswaDetail)
        <section class="mp-card mp-card-gold">
            <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
                <div>
                    <span class="mp-label">Detail Kehadiran</span>
                    <h2 style="margin:8px 0 6px; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:30px;">{{ $selectedSiswaDetail->nama_siswa }}</h2>
                    <p style="margin:0; color:var(--midnight); font-weight:900;">{{ $selectedSiswaDetail->NIS }} &bull; {{ $selectedSiswaDetail->kelas }}</p>
                </div>
                <span class="mp-badge" style="background:var(--white);">{{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}</span>
            </div>

            <form action="{{ route('admin.presensi-siswa.index') }}" method="GET" class="mp-form-grid" style="align-items:end; margin-top:20px;">
                <input type="hidden" name="kelas" value="{{ $selectedSiswaDetail->kelas }}">
                <input type="hidden" name="q" value="{{ $search }}">
                <input type="hidden" name="nis" value="{{ $selectedSiswaDetail->NIS }}">

                <div class="mp-field" style="margin-bottom:0;">
                    <label class="mp-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="mp-input">
                </div>

                <div class="mp-field" style="margin-bottom:0;">
                    <label class="mp-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="mp-input">
                </div>

                <div class="mp-actions" style="border-top:0; padding-top:0; margin-top:0;">
                    <button type="submit" class="mp-btn"><i class="bi bi-funnel"></i> Terapkan</button>
                </div>
            </form>

            <div class="mp-touch-grid" style="grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); margin-top:18px;">
                @foreach($detailTotals as $label => $count)
                    <div class="mp-card" style="padding:16px; box-shadow:4px 4px 0 var(--midnight);">
                        <div class="mp-label">{{ $label }}</div>
                        <div style="font-family:'Fredoka One', cursive; font-size:26px; color:var(--midnight);">{{ $count }}</div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mp-table-card mp-desktop-only">
            <div style="padding:24px 30px; background:var(--gold); border-bottom:4px solid var(--midnight);">
                <div class="mp-label" style="margin-bottom:6px;">Riwayat Presensi</div>
                <div style="font-family:'Fredoka One', cursive; font-size:24px; color:var(--midnight);">{{ $selectedSiswaDetail->nama_siswa }}</div>
            </div>

            <div class="mp-table-wrap" style="padding:24px;">
                <table style="width:100%; min-width:980px; border-collapse:collapse; font-size:13px; font-family:'Nunito', sans-serif; color:var(--midnight);">
                    <thead>
                        <tr>
                            <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">NO</th>
                            <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">TANGGAL</th>
                            <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">HARI</th>
                            <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">JAM</th>
                            <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber); text-align:left;">MAPEL</th>
                            <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber); text-align:left;">GURU</th>
                            <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">JAM MASUK</th>
                            <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailRows as $index => $row)
                            <tr>
                                <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ $index + 1 }}</td>
                                <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ \Carbon\Carbon::parse($row['tanggal'])->format('d M Y') }}</td>
                                <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ $row['hari'] }}</td>
                                <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ $row['jam'] }}</td>
                                <td style="border:2px solid var(--midnight); padding:10px;">{{ $row['mapel'] }}</td>
                                <td style="border:2px solid var(--midnight); padding:10px;">{{ $row['guru'] }}</td>
                                <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ $row['jam_masuk'] }}</td>
                                <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">
                                    <span class="mp-badge" style="background:{{ $statusColors[$row['status']] ?? 'var(--white)' }};">{{ $row['status'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="border:2px solid var(--midnight); padding:24px; text-align:center; font-weight:900;">Belum ada data presensi pada rentang tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="mp-mobile-only mp-stack-list">
            @forelse($detailRows as $row)
                <section class="mp-card">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                        <div>
                            <span class="mp-label">{{ $row['hari'] }}, {{ \Carbon\Carbon::parse($row['tanggal'])->format('d M Y') }}</span>
                            <h3 style="margin:6px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:24px;">{{ $row['mapel'] }}</h3>
                        </div>
                        <span class="mp-badge" style="background:{{ $statusColors[$row['status']] ?? 'var(--white)' }};">{{ $row['status'] }}</span>
                    </div>
                    <div style="display:grid; gap:8px; margin-top:14px; color:var(--midnight); font-weight:900;">
                        <div>{{ $row['jam'] }} &bull; Masuk: {{ $row['jam_masuk'] }}</div>
                        <div>{{ $row['guru'] }}</div>
                    </div>
                </section>
            @empty
                <section class="mp-empty-state">
                    <div style="font-family:'Fredoka One', cursive; font-size:22px; color:var(--midnight);">Belum ada data</div>
                    <p style="margin:10px 0 0; font-weight:800;">Tidak ada presensi pada rentang tanggal ini.</p>
                </section>
            @endforelse
        </div>
    @endif
</div>

@endsection
