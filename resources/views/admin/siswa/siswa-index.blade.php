@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Siswa</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Database</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Kelola informasi data diri, kelas, dan status akademik siswa secara terpusat dalam sistem Scoola.
            </p>
        </div>
    </div>


    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: var(--spacing-md);">
        <!-- Standardized Premium Design: Total Siswa -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-people" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">TOTAL DATA SISWA</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;">{{ $siswa->count() }}</div>
            </div>
        </div>

        <!-- Standardized Premium Design: Kelas Terdaftar -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-building" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">UNIT KELAS AKTIF</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;">{{ $siswa->unique('kelas')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid var(--color-hairline); display: flex; gap: 40px; align-items: flex-end;">
        <div style="flex: 1;">
            <label class="text-micro-caps" style="color: var(--color-stone); display: block; margin-bottom: 12px; font-weight: 700;">Pencarian</label>
            <input type="text" id="searchInput" class="form-field" placeholder="NIS / NAMA SISWA">
        </div>
        <div style="width: 300px;">
            <label class="text-micro-caps" style="color: var(--color-stone); display: block; margin-bottom: 12px; font-weight: 700;">Filter Kelas</label>
            <select id="kelasFilter" class="form-field" style="cursor: pointer;">
                <option value="">Semua Kelas</option>
                @foreach($siswa->unique('kelas')->pluck('kelas')->sort() as $kls)
                    <option value="{{ $kls }}">{{ $kls }}</option>
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
                    <th style="padding-left: 40px; width: 80px;">NIS</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Email</th>
                    <th>Status</th>
                    @if(auth()->user()->role === 'admin')
                    <th style="text-align: right; padding-right: 40px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody id="siswaTable">
                @forelse($siswa as $s)
                <tr class="student-row" 
                    data-name="{{ strtolower($s->nama_siswa) }}" 
                    data-nis="{{ strtolower($s->NIS) }}"
                    data-kelas="{{ $s->kelas }}">
                    <td data-label="NIS" style="padding-left: 40px; font-family: monospace; color: var(--color-stone);">{{ $s->NIS }}</td>
                    <td data-label="Nama Lengkap" style="font-weight: 600;">{{ $s->nama_siswa }}</td>
                    <td data-label="Kelas">{{ $s->kelas }}</td>
                    <td data-label="Email" style="color: var(--color-slate);">{{ $s->user->email ?? '-' }}</td>
                    <td data-label="Status">
                        <span class="badge-status bs-h">Aktif</span>
                    </td>
                    @if(auth()->user()->role === 'admin')
                    <td data-label="Aksi" style="text-align: right; padding-right: 40px;">
                        <div style="display: flex; justify-content: flex-end; gap: 16px; align-items: center;">
                            <a href="{{ route('siswa.edit', $s->NIS) }}" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; display: inline-flex; align-items: center; text-decoration: none;">Edit</a>
                            <form action="{{ route('siswa.destroy', $s->NIS) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus siswa ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="color: var(--color-slate); background: none; border: none; padding: 0; cursor: pointer; text-transform: uppercase; font-size: 10px; font-weight: 700; letter-spacing: 0.05em;">Hapus</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" style="text-align: center; padding: 120px; color: var(--color-stone);">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const kelasFilter = document.getElementById('kelasFilter');
    const rows = document.querySelectorAll('.student-row');

    function filterTable() {
        const q = searchInput.value.toLowerCase().trim();
        const k = kelasFilter.value;

        rows.forEach(row => {
            const name = row.dataset.name;
            const nis = row.dataset.nis;
            const kelas = row.dataset.kelas;

            const matchSearch = !q || name.includes(q) || nis.includes(q);
            const matchKelas = !k || kelas === k;

            if(matchSearch && matchKelas) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    kelasFilter.addEventListener('change', filterTable);
</script>

    @if(auth()->user()->role === 'admin')
    <!-- FAB Action -->
    <div class="fab-container">
        <a href="{{ route('siswa.create') }}" class="btn-fab" title="Tambah Siswa">
            <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
            <span class="fab-label">Tambah Siswa Baru</span>
        </a>
    </div>
    @endif

@endsection
