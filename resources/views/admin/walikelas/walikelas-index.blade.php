@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">CLASS GUIDE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-mortarboard-fill"></i> Manajemen Institusi</span>
                <h1 class="mp-title">Wali Kelas</h1>
                <p class="mp-description">
                    Kelola penugasan guru sebagai wali kelas untuk setiap rombongan belajar aktif di Scoola.
                </p>
            </div>
        </section>
    </div>

    @php
        $totalKelas = $kelasList->count();
        $terisi = $kelasList->whereNotNull('wali_kelas_nip')->count();
    @endphp

    <div class="mp-stats-grid">
        <section class="mp-stat-card mp-card-gold mp-hover">
            <div class="mp-stat-icon"><i class="bi bi-door-open"></i></div>
            <div>
                <div class="mp-stat-label">Total Unit Kelas</div>
                <div class="mp-stat-value">{{ $totalKelas }}</div>
            </div>
        </section>

        <section class="mp-stat-card mp-card-cyber mp-hover">
            <div class="mp-stat-icon"><i class="bi bi-person-check"></i></div>
            <div>
                <div class="mp-stat-label">Wali Kelas Aktif</div>
                <div class="mp-stat-value">{{ $terisi }}</div>
            </div>
        </section>
    </div>

    <section class="mp-card">
        <label class="mp-label">Pencarian Direktori</label>
        <input type="text" id="searchInput" class="mp-input" placeholder="CARI KELAS ATAU GURU...">
    </section>

    <section class="mp-table-card">
        <div class="mp-table-wrap">
            <table class="mp-table" id="walikelasTable">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Wali Kelas</th>
                        <th>NIP</th>
                        <th class="mp-center">Siswa</th>
                        <th class="mp-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelasList as $kls)
                    <tr class="walikelas-row">
                        <td>{{ $kls->nama_kelas }}</td>
                        <td>
                            @if($kls->waliKelas)
                                {{ $kls->waliKelas->nama_guru }}
                            @else
                                <span class="mp-badge" style="background: var(--sakura);">Belum Ada</span>
                            @endif
                        </td>
                        <td class="mp-mono">{{ $kls->wali_kelas_nip ?? '-' }}</td>
                        <td class="mp-center">{{ $kls->siswa->count() }}</td>
                        <td class="mp-right">
                            @if($kls->waliKelas)
                                <a href="{{ route('admin.walikelas.edit', $kls->id) }}" class="mp-btn-secondary" style="min-height: 36px; padding: 0 16px; font-size: 12px;">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            @else
                                <a href="{{ route('admin.walikelas.create') }}?kelas_id={{ $kls->id }}" class="mp-btn" style="min-height: 36px; padding: 0 16px; font-size: 12px;">
                                    <i class="bi bi-plus-circle"></i> Assign
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="mp-center" style="padding: 72px;">Belum ada data wali kelas ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<a href="{{ route('admin.walikelas.create') }}" class="mp-fab" title="Tambah Wali Kelas">
    <i class="bi bi-plus-lg"></i>
</a>

<script>
const normalizeLiveSearch = (value) => String(value || '').toLocaleLowerCase('id-ID').trim();

document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = normalizeLiveSearch(this.value);
    const rows = document.querySelectorAll('.walikelas-row');

    rows.forEach(row => {
        const text = normalizeLiveSearch(row.innerText);
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>

@endsection
