@extends('layouts.admin')

@section('content')

<style>
    :root {
        --sakura: #FF7675;
        --cyber: #00CEC9;
        --cosmo: #6C5CE7;
        --gold: #FDCB6E;
        --midnight: #1E1B29;
        --mochi: #FAF9FF;
        --white: #FFFFFF;
    }

    body {
        background: var(--mochi);
    }

    .anime-dashboard {
        padding: 24px;
        background-image:
            radial-gradient(var(--cosmo) 1px, transparent 0);
        background-size: 24px 24px;
        min-height: 100vh;
    }

    .neo-card {
        background: var(--white);
        border: 4px solid var(--midnight);
        border-radius: 22px;
        box-shadow: 8px 8px 0px 0px var(--midnight);
        transition: all .2s ease;
        position: relative;
        overflow: hidden;
    }

    .neo-card:hover {
        transform: translate(-2px,-2px);
        box-shadow: 10px 10px 0px 0px var(--midnight);
    }

    .hero-section {
        background: var(--cosmo);
        padding: 52px;
        margin-bottom: 28px;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: var(--gold);
        border: 4px solid var(--midnight);
        box-shadow: 6px 6px 0px var(--midnight);
    }

    .hero-section::after {
        content: '★';
        position: absolute;
        bottom: 10px;
        left: 24px;
        font-size: 72px;
        color: var(--cyber);
        text-shadow: 4px 4px 0px var(--midnight);
        -webkit-text-stroke: 2px var(--midnight);
        transform: rotate(-15deg);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--cyber);
        color: var(--midnight);
        border: 3px solid var(--midnight);
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 4px 4px 0px var(--midnight);
        transform: rotate(-2deg);
        margin-bottom: 24px;
    }

    .hero-title {
        font-family: 'Fredoka One', cursive;
        font-size: clamp(42px, 5vw, 68px);
        line-height: 1;
        color: var(--white);
        text-shadow: 5px 5px 0px var(--midnight);
        -webkit-text-stroke: 2px var(--midnight);
        margin-bottom: 22px;
    }

    .hero-description {
        max-width: 720px;
        background: rgba(30,27,41,.9);
        color: var(--white);
        padding: 22px;
        border-radius: 14px;
        border: 3px solid var(--midnight);
        box-shadow: 5px 5px 0px var(--midnight);
        font-size: 16px;
        font-weight: 700;
        line-height: 1.7;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(320px,1fr));
        gap: 24px;
        margin-bottom: 28px;
    }

.stats-card {
    padding: 34px;
    padding-top: 90px;
}
.sticker {
    position: absolute;
    top: 16px;
    right: 20px;

    padding: 10px 18px;

    border-radius: 12px;
    border: 3px solid var(--midnight);

    box-shadow: 4px 4px 0px var(--midnight);

    font-size: 13px;
    font-weight: 900;

    letter-spacing: 1px;
    text-transform: uppercase;

    z-index: 20;
}

.sticker-sakura {
    background: var(--sakura);
    color: var(--white);
}

.sticker-gold {
    background: var(--gold);
    color: var(--midnight);
}

    .stats-icon {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        border: 4px solid var(--midnight);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        box-shadow: 5px 5px 0px var(--midnight);
        margin-bottom: 22px;
    }

    .stats-icon.sakura {
        background: var(--sakura);
        color: var(--white);
    }

    .stats-icon.cyber {
        background: var(--cyber);
        color: var(--midnight);
    }

    .stats-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 14px;
        color: var(--midnight);
    }

    .stats-number {
        font-family: 'Fredoka One', cursive;
        font-size: 64px;
        line-height: 1;
        color: var(--midnight);
    }

    .filter-card {
        padding: 30px;
        margin-bottom: 28px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 24px;
    }

    .input-label {
        display: block;
        margin-bottom: 10px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--midnight);
    }

    .anime-input {
        width: 100%;
        padding: 16px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--mochi);
        color: var(--midnight);
        font-size: 15px;
        font-weight: 700;
        outline: none;
        transition: all .2s ease;
        box-shadow: 4px 4px 0px var(--midnight);
    }

    .anime-input:focus {
        background: var(--white);
        transform: translate(-2px,-2px);
        box-shadow: 6px 6px 0px var(--midnight);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .anime-table {
        width: 100%;
        border-collapse: collapse;
    }

    .anime-table thead {
        background: var(--midnight);
        color: var(--white);
    }

    .anime-table th {
        padding: 22px;
        text-align: left;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .anime-table td {
        padding: 24px 22px;
        font-weight: 700;
        color: var(--midnight);
        border-bottom: 3px solid var(--midnight);
        background: var(--white);
    }

    .anime-table tbody tr:hover td {
        background: var(--mochi);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--cyber);
        color: var(--midnight);
        border: 3px solid var(--midnight);
        border-radius: 999px;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 800;
        box-shadow: 3px 3px 0px var(--midnight);
    }

    .edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 18px;
        background: var(--gold);
        color: var(--midnight);
        border: 3px solid var(--midnight);
        border-radius: 12px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        box-shadow: 4px 4px 0px var(--midnight);
        transition: all .2s ease;
    }

    .edit-btn:hover {
        transform: translate(-2px,-2px);
        box-shadow: 6px 6px 0px var(--midnight);
    }

    .fab-button {
        position: fixed;
        right: 30px;
        bottom: 30px;
        width: 78px;
        height: 78px;
        border-radius: 50%;
        background: var(--sakura);
        color: var(--white);
        border: 4px solid var(--midnight);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 30px;
        box-shadow: 6px 6px 0px var(--midnight);
        transition: all .2s ease;
        z-index: 999;
    }

    .fab-button:hover {
        transform: translate(-2px,-2px);
        box-shadow: 8px 8px 0px var(--midnight);
    }

    @media (max-width: 768px) {

        .hero-section {
            padding: 34px 24px;
        }

        .hero-title {
            font-size: 42px;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .anime-table {
            min-width: 720px;
        }
    }
</style>

<div class="anime-dashboard">

    <!-- HERO -->
    <div class="neo-card hero-section">

        <div class="hero-content">

            <div class="hero-badge">
                ✨ Anime Professional Database
            </div>

            <h1 class="hero-title">
                Database Siswa
            </h1>

            <div class="hero-description">
                Kelola seluruh informasi siswa secara modern dengan visual dashboard
                Manga-Pop khas Scoola. ( ≧◡≦ )
            </div>

        </div>

    </div>

    <!-- STATS -->
    <div class="stats-grid">

        <div class="neo-card stats-card">

            <div class="sticker sticker-sakura">
                TOTAL ✨
            </div>

            <div class="stats-icon sakura">
                <i class="bi bi-people-fill"></i>
            </div>

            <div class="stats-label">
                Total Siswa
            </div>

            <div class="stats-number">
                {{ $siswa->count() }}
            </div>

        </div>

        <div class="neo-card stats-card">

            <div class="sticker sticker-gold">
                CLASS ⚡
            </div>

            <div class="stats-icon cyber">
                <i class="bi bi-building"></i>
            </div>

            <div class="stats-label">
                Kelas Aktif
            </div>

            <div class="stats-number">
                {{ $siswa->unique('kelas')->count() }}
            </div>

        </div>

    </div>

    <!-- FILTER -->
    <div class="neo-card filter-card">

        <div class="filter-grid">

            <div>
                <label class="input-label">
                    Pencarian
                </label>

                <input
                    type="text"
                    id="searchInput"
                    class="anime-input"
                    placeholder="Cari nama siswa / NIS..."
                >
            </div>

            <div>
                <label class="input-label">
                    Filter Kelas
                </label>

                <select id="kelasFilter" class="anime-input">

                    <option value="">
                        Semua Kelas
                    </option>

                    @foreach($siswa->unique('kelas')->pluck('kelas')->sort() as $kls)
                        <option value="{{ $kls }}">
                            {{ $kls }}
                        </option>
                    @endforeach

                </select>
            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="neo-card table-wrapper">

        <table class="anime-table">

            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Email</th>
                    <th>Status</th>

                    @if(auth()->user()->role === 'admin')
                    <th style="text-align:right;">
                        Aksi
                    </th>
                    @endif
                </tr>
            </thead>

            <tbody id="siswaTable">

                @forelse($siswa as $s)

                <tr
                    class="student-row"
                    data-name="{{ strtolower($s->nama_siswa) }}"
                    data-nis="{{ strtolower($s->NIS) }}"
                    data-kelas="{{ $s->kelas }}"
                >

                    <td>{{ $s->NIS }}</td>

                    <td>{{ $s->nama_siswa }}</td>

                    <td>{{ $s->kelas }}</td>

                    <td>{{ $s->user->email ?? '-' }}</td>

                    <td>
                        <span class="status-badge">
                            ⚡ Aktif
                        </span>
                    </td>

                    @if(auth()->user()->role === 'admin')
                    <td style="text-align:right;">

                        <a
                            href="{{ route('siswa.edit', $s->NIS) }}"
                            class="edit-btn"
                        >
                            Edit ✏️
                        </a>

                    </td>
                    @endif

                </tr>

                @empty

                <tr>
                    <td colspan="6" style="text-align:center;padding:70px;">
                        Data siswa belum tersedia (╥﹏╥)
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@if(auth()->user()->role === 'admin')

<a
    href="{{ route('siswa.create') }}"
    class="fab-button"
>
    <i class="bi bi-plus-lg"></i>
</a>

@endif

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

            const matchSearch =
                !q ||
                name.includes(q) ||
                nis.includes(q);

            const matchKelas =
                !k ||
                kelas === k;

            row.style.display =
                (matchSearch && matchKelas)
                ? ''
                : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    kelasFilter.addEventListener('change', filterTable);
</script>

@endsection