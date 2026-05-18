@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Sumber Daya</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Data Guru</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Kelola seluruh data guru yang terdaftar di sistem Scoola, termasuk penugasan mata pelajaran dan akun akses.
            </p>
        </div>
    </div>

    <!-- FAB Action -->
    <div class="fab-container">
        <a href="{{ route('guru.create') }}" class="btn-fab" title="Tambah Guru">
            <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
            <span class="fab-label">Tambah Guru Baru</span>
        </a>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: var(--spacing-md);">
        <!-- Standardized Premium Design: Total Guru -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-person-badge" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">TOTAL DATA GURU</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;">{{ $guru->count() }}</div>
            </div>
        </div>

        <!-- Standardized Premium Design: Akun Aktif -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-shield-check" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">AKSES SISTEM AKTIF</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;">{{ $guru->whereNotNull('user_id')->count() }}</div>
            </div>
        </div>
    </div>


    <!-- Search & Filter Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid var(--color-hairline); display: flex; gap: 40px; align-items: flex-end;">
        <div style="flex: 1;">
            <label class="text-micro-caps" style="color: var(--color-stone); display: block; margin-bottom: 12px; font-weight: 700;">Pencarian Direktori</label>
            <input type="text" id="searchInput" class="form-field" placeholder="CARI NAMA ATAU NIP...">
        </div>
        <div style="width: 300px;">
            <label class="text-micro-caps" style="color: var(--color-stone); display: block; margin-bottom: 12px; font-weight: 700;">Spesialisasi</label>
            <select id="mapelFilter" class="form-field" style="cursor: pointer;">
                <option value="">Semua Mata Pelajaran</option>
                @php
                    $allMapels = $guru->flatMap(fn($g) => $g->mapels)->unique('kd_mapel');
                @endphp
                @foreach ($allMapels->sortBy('nama_mapel') as $m)
                    <option value="{{ $m->kd_mapel }}">{{ $m->nama_mapel }}</option>
                @endforeach
            </select>
            <div style="height: 1px; background: var(--color-hairline); margin-top: 12px;"></div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card table-container-card" style="margin-bottom: var(--spacing-section);">
        <table class="data-table responsive-table" style="margin: 0; width: 100%;">
            <thead>
                <tr>
                    <th style="padding-left: 40px;">NIP</th>
                    <th>Nama Guru</th>
                    <th>Mata Pelajaran</th>
                    <th>Email</th>
                    <th style="text-align: right; padding-right: 40px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="guruBody">
                @forelse ($guru as $g)
                <tr class="guru-row" 
                    data-name="{{ strtolower($g->nama_guru) }}" 
                    data-nip="{{ strtolower($g->NIP) }}"
                    data-mapels="{{ json_encode($g->mapels->pluck('kd_mapel')) }}">
                    <td data-label="NIP" style="padding-left: 40px; font-family: monospace; color: var(--color-stone);">{{ $g->NIP }}</td>
                    <td data-label="Nama Lengkap" style="font-weight: 600;">{{ $g->nama_guru }}</td>
                    <td data-label="Mata Pelajaran">
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end;">
                            @foreach ($g->mapels as $m)
                                <span class="badge-status" style="background: transparent; border: 1px solid var(--color-hairline); color: var(--color-stone); font-size: 10px;">{{ $m->nama_mapel }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td data-label="Email" style="color: var(--color-slate);">{{ $g->user->email ?? '-' }}</td>
                    <td data-label="Aksi" style="text-align: right; padding-right: 40px;">
                        <a href="{{ route('guru.edit', $g->NIP) }}" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; display: inline-flex; align-items: center; text-decoration: none;">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 120px; color: var(--color-stone);">Belum ada data guru ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const mapelFilter = document.getElementById('mapelFilter');
    const rows = document.querySelectorAll('.guru-row');

    function filterTable() {
        const q = searchInput.value.toLowerCase().trim();
        const mapel = mapelFilter.value;

        rows.forEach(row => {
            const name = row.dataset.name;
            const nip = row.dataset.nip;
            const mapels = JSON.parse(row.dataset.mapels);

            const matchSearch = !q || name.includes(q) || nip.includes(q);
            const matchMapel = !mapel || mapels.includes(mapel);

            if(matchSearch && matchMapel) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    mapelFilter.addEventListener('change', filterTable);
</script>

@endsection

