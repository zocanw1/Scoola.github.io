@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Akademik</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Mata Pelajaran</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Kelola kurikulum dan daftar mata pelajaran yang diajarkan di lingkungan sekolah Scoola.
            </p>
        </div>
    </div>


    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: var(--spacing-md);">
        <!-- Standardized Premium Design: Total Mapel -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-book" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">TOTAL KURIKULUM</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;">{{ $mapel->count() }}</div>
            </div>
        </div>

        <!-- Standardized Premium Design: Pengajar Aktif -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-person-workspace" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">PENGAJAR TERSEDIA</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;">{{ App\Models\Guru::count() }}</div>
            </div>
        </div>
    </div>


    <!-- Search Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <label class="text-micro-caps" style="color: var(--color-stone); display: block; margin-bottom: 12px; font-weight: 700;">Pencarian Direktori</label>
        <input type="text" id="mapelSearch" class="form-field" placeholder="CARI MATA PELAJARAN...">
    </div>

    <!-- Table Card -->
    <div class="card table-container-card" style="margin-bottom: var(--spacing-section);">
        <table class="data-table responsive-table" style="margin: 0; width: 100%;">
            <thead>
                <tr>
                    <th style="padding-left: 40px; width: 80px;">No</th>
                    <th style="width: 200px;">Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th style="text-align: right; padding-right: 40px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="mapelTable">
                @forelse($mapel as $key => $m)
                <tr class="mapel-row">
                    <td data-label="No" style="padding-left: 40px; color: var(--color-stone);">{{ $key + 1 }}</td>
                    <td data-label="Kode" style="color: var(--color-ink); font-weight: 600; font-family: monospace;">{{ $m->kd_mapel }}</td>
                    <td data-label="Mata Pelajaran" style="font-weight: 600;">{{ $m->nama_mapel }}</td>
                    <td data-label="Aksi" style="text-align: right; padding-right: 40px;">
                        <a href="{{ route('mapel.edit', $m->kd_mapel) }}" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; text-decoration: none; display: inline-flex; align-items: center;">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 120px; color: var(--color-stone);">Belum ada data mata pelajaran ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

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

    <!-- FAB Action -->
    <div class="fab-container">
        <a href="{{ route('mapel.create') }}" class="btn-fab" title="Tambah Mapel">
            <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
            <span class="fab-label">Tambah Mapel Baru</span>
        </a>
    </div>

@endsection

