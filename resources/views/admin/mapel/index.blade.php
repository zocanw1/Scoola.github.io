@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">CURRICULUM</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-book"></i> Akademik</span>
                <h1 class="mp-title">Mata Pelajaran</h1>
                <p class="mp-description">
                    Kelola kurikulum dan daftar mata pelajaran yang diajarkan di lingkungan sekolah Scoola.
                </p>
            </div>
        </section>
    </div>

    <div class="mp-stats-grid">
        <section class="mp-stat-card mp-card-gold mp-hover">
            <div class="mp-stat-icon"><i class="bi bi-book"></i></div>
            <div>
                <div class="mp-stat-label">Total Kurikulum</div>
                <div class="mp-stat-value">{{ $mapel->count() }}</div>
            </div>
        </section>

        <section class="mp-stat-card mp-card-cyber mp-hover">
            <div class="mp-stat-icon"><i class="bi bi-person-workspace"></i></div>
            <div>
                <div class="mp-stat-label">Pengajar Tersedia</div>
                <div class="mp-stat-value">{{ $totalGuru }}</div>
            </div>
        </section>
    </div>

    <section class="mp-card">
        <label class="mp-label">Pencarian Direktori</label>
        <input type="text" id="mapelSearch" class="mp-input" placeholder="CARI MATA PELAJARAN...">
    </section>

    <section class="mp-table-card">
        <div class="mp-table-wrap">
            <table class="mp-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">No</th>
                        <th style="width: 200px;">Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th class="mp-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="mapelTable">
                    @forelse($mapel as $key => $m)
                    <tr class="mapel-row">
                        <td>{{ $key + 1 }}</td>
                        <td class="mp-mono">{{ $m->kd_mapel }}</td>
                        <td>{{ $m->nama_mapel }}</td>
                        <td class="mp-right">
                            <a href="{{ route('mapel.edit', $m->kd_mapel) }}" class="mp-btn-secondary" style="min-height: 36px; padding: 0 16px; font-size: 12px;">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="mp-center" style="padding: 72px;">Belum ada data mata pelajaran ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<a href="{{ route('mapel.create') }}" class="mp-fab" title="Tambah Mapel">
    <i class="bi bi-plus-lg"></i>
</a>

<script>
document.getElementById('mapelSearch').addEventListener('keyup', function() {
    let filter = this.value.toUpperCase();
    let rows = document.querySelectorAll('.mapel-row');

    rows.forEach(row => {
        let text = row.innerText.toUpperCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

@endsection
