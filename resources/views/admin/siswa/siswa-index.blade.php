@extends('layouts.admin')

@section('content')

<style>
    /* 🎨 PALET WARNA UTAMA (THE MANGA PALETTE) */
    :root {
        --sakura: #FF7675;
        --cyber: #00CEC9;
        --cosmo: #6C5CE7;
        --gold: #FDCB6E;
        --midnight: #1E1B29;
        --mochi: #FAF9FF;
        --white: #FFFFFF;
    }

    /* 🚀 CONTAINER DASHBOARD */
    .anime-dashboard {
        padding: 32px;
        min-height: 100vh;
        position: relative;
        width: 100%;
        background: transparent !important; /* Dibuat transparan agar polanya menembus dari body/wrapper */
    }

    /* 📦 NEOBRUTALISM CARD BASE */
    .neo-card {
        background: var(--white);
        border: 4px solid var(--midnight);
        border-radius: 20px;
        box-shadow: 8px 8px 0px 0px var(--midnight);
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .neo-card:hover {
        transform: translate(2px, 2px);
        box-shadow: 6px 6px 0px 0px var(--midnight);
    }

    /* 🌟 HERO SECTION STYLE */
    .hero-section {
        background: var(--cosmo);
        padding: 48px;
        margin-bottom: 32px;
        position: relative;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: var(--gold);
        border: 4px solid var(--midnight);
        box-shadow: 6px 6px 0px var(--midnight);
        z-index: 1;
    }

    .hero-section::after {
        content: '★';
        position: absolute;
        bottom: 12px;
        left: 24px;
        font-size: 64px;
        color: var(--cyber);
        text-shadow: 4px 4px 0px var(--midnight);
        -webkit-text-stroke: 2px var(--midnight);
        transform: rotate(-15deg);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--cyber);
        color: var(--midnight);
        border: 3px solid var(--midnight);
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 4px 4px 0px var(--midnight);
        transform: rotate(-1.5deg);
        margin-bottom: 20px;
    }

    .hero-title {
        font-family: 'Fredoka One', cursive;
        font-size: clamp(36px, 5vw, 56px);
        line-height: 1.1;
        color: var(--white);
        text-shadow: 5px 5px 0px var(--midnight);
        -webkit-text-stroke: 1.5px var(--midnight);
        margin-bottom: 18px;
    }

    .hero-description {
        max-width: 680px;
        background: rgba(30, 27, 41, 0.95);
        color: var(--white);
        padding: 20px;
        border-radius: 14px;
        border: 3px solid var(--midnight);
        box-shadow: 5px 5px 0px var(--midnight);
        font-size: 15px;
        font-weight: 600;
        line-height: 1.6;
    }

    /* 📊 STATS GRID */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 32px;
        margin-bottom: 32px;
    }

    .stats-card {
        padding: 28px;
        padding-top: 80px;
    }

    .sticker {
        position: absolute;
        top: 16px;
        right: 16px;
        padding: 6px 14px;
        border-radius: 10px;
        border: 3px solid var(--midnight);
        box-shadow: 3px 3px 0px var(--midnight);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 1px;
        text-transform: uppercase;
        z-index: 5;
    }

    .sticker-sakura { background: var(--sakura); color: var(--white); transform: rotate(3deg); }
    .sticker-gold { background: var(--gold); color: var(--midnight); transform: rotate(-3deg); }

    .stats-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        border: 3px solid var(--midnight);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        box-shadow: 4px 4px 0px var(--midnight);
        margin-bottom: 16px;
    }

    .stats-icon.sakura { background: var(--sakura); color: var(--white); }
    .stats-icon.cyber { background: var(--cyber); color: var(--midnight); }

    .stats-label {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        color: var(--midnight);
    }

    .stats-number {
        font-family: 'Fredoka One', cursive;
        font-size: 54px;
        line-height: 1;
        color: var(--midnight);
    }

    /* 🔍 FILTER COMPONENT */
    .filter-card {
        padding: 24px;
        margin-bottom: 32px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 260px;
        gap: 24px;
    }

    .input-label {
        display: block;
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--midnight);
    }

    .anime-input {
        width: 100%;
        padding: 14px 16px;
        border: 3px solid var(--midnight);
        border-radius: 12px;
        background: var(--mochi);
        color: var(--midnight);
        font-family: 'Nunito', sans-serif;
        font-size: 14px;
        font-weight: 700;
        outline: none;
        transition: all 0.2s ease;
        box-shadow: 4px 4px 0px var(--midnight);
    }

    .anime-input:focus {
        background: var(--white);
        transform: translate(1px, 1px);
        box-shadow: 3px 3px 0px var(--midnight);
    }

    /* 📅 MANGA COMIC TABLE WRAPPER */
    .table-wrapper {
        overflow-x: auto;
        margin-bottom: 40px;
    }

    .anime-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Nunito', sans-serif;
    }

    .anime-table thead {
        background: var(--midnight);
        color: var(--white);
    }

    .anime-table th {
        padding: 18px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 4px solid var(--midnight);
    }

    .anime-table td {
        padding: 20px;
        font-weight: 700;
        color: var(--midnight);
        border-bottom: 3px solid var(--midnight);
        background: var(--white);
    }

    .anime-table td:last-child {
        text-align: right;
    }

    .mobile-field-label {
        display: none;
    }

    .student-name-text,
    .student-id-chip,
    .mobile-field-value {
        display: inline;
    }

    .student-action-cell {
        text-align: right;
    }

    .table-empty-cell {
        text-align: center;
        padding: 72px;
    }

    .anime-table tbody tr:last-child td {
        border-bottom: none;
    }

    .anime-table tbody tr:hover td {
        background: #F1EFFF;
    }

    /* 🏷️ BADGES & BUTTONS */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--cyber);
        color: var(--midnight);
        border: 2.5px solid var(--midnight);
        border-radius: 30px;
        padding: 6px 14px;
        font-size: 11px;
        font-weight: 800;
        box-shadow: 3px 3px 0px var(--midnight);
    }

    .edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        background: var(--gold);
        color: var(--midnight);
        border: 3px solid var(--midnight);
        border-radius: 12px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        box-shadow: 4px 4px 0px var(--midnight);
        transition: all 0.15s ease;
    }

    .edit-btn:hover {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0px var(--midnight);
    }

    /* ➕ FLOATING ACTION BUTTON */
    .fab-button {
        position: fixed;
        right: 32px;
        bottom: 32px;
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--sakura);
        color: var(--white);
        border: 4px solid var(--midnight);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 28px;
        box-shadow: 6px 6px 0px var(--midnight);
        transition: all 0.2s ease;
        z-index: 999;
    }

    .fab-button:hover {
        transform: translate(2px, 2px);
        box-shadow: 4px 4px 0px var(--midnight);
    }

    /* 📱 RESPONSIVE MEDIA BREAKPOINTS */
    @media (max-width: 768px) {
        .anime-dashboard { padding: 16px; }
        .hero-section { padding: 32px 20px; margin-bottom: 24px; }
        .hero-title { font-size: 32px; }
        .filter-grid { grid-template-columns: 1fr; gap: 16px; }
        .stats-grid { gap: 20px; margin-bottom: 24px; }
        .neo-card.table-wrapper {
            padding: 0 !important;
            margin-bottom: 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
        }
        .neo-card.table-wrapper:hover {
            transform: none !important;
            box-shadow: none !important;
        }
        .table-wrapper {
            overflow: visible;
            padding-bottom: 0;
            margin-bottom: 0;
            background: transparent;
            border: none;
            box-shadow: none;
        }
        .anime-table {
            min-width: 0;
            border-collapse: collapse;
            border-spacing: 0;
            background: transparent;
        }
        .anime-table thead {
            display: none;
        }
        .anime-table tbody,
        .anime-table tr,
        .anime-table td {
            display: block;
            width: 100%;
        }
        .anime-table tbody {
            display: flex;
            flex-direction: column;
            gap: 24px;
            background: transparent;
        }
        .anime-table tbody tr {
            display: flex;
            flex-direction: column;
            margin: 0;
            border: 4px solid var(--midnight);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 8px 8px 0 var(--midnight);
            background: var(--white);
            padding: 20px;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .anime-table tbody tr:hover {
            transform: translate(-4px, -4px);
            box-shadow: 12px 12px 0 var(--midnight);
        }
        .empty-placeholder-base,
        #jsEmptyMessage {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .empty-placeholder-base:hover,
        #jsEmptyMessage:hover {
            transform: none !important;
        }
        .anime-table tbody tr:hover td {
            background: var(--white);
        }
        .anime-table td {
            display: block;
            padding: 0;
            border-bottom: 0;
            text-align: left;
            background: transparent;
        }
        .anime-table td::before {
            display: none;
        }
        .mobile-field-label {
            display: block;
            margin-bottom: 8px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: var(--midnight);
            opacity: 0.55;
        }
        .mobile-field-value {
            display: block;
            color: var(--midnight);
            font-size: 14px;
            font-weight: 800;
            line-height: 1.5;
            word-break: break-word;
        }
        .student-name-cell {
            order: 1;
            margin-bottom: 10px;
            padding: 16px 18px 14px;
            border: 3px solid var(--midnight);
            border-radius: 18px;
            background: var(--white);
            box-shadow: 4px 4px 0 var(--midnight);
        }
        .student-name-cell .mobile-field-label {
            margin-bottom: 10px;
            opacity: 0.8;
            color: var(--cosmo);
        }
        .student-name-text {
            display: block;
            font-family: 'Fredoka One', sans-serif;
            font-size: 26px;
            line-height: 1.08;
            letter-spacing: -0.02em;
            color: var(--midnight);
        }
        .student-nis-cell {
            order: 2;
            margin-bottom: 18px;
            padding-left: 2px;
        }
        .student-id-chip {
            display: inline-flex;
            align-items: center;
            min-height: 38px;
            padding: 0 14px;
            border: 3px solid var(--midnight);
            border-radius: 999px;
            background: var(--gold);
            color: var(--midnight);
            box-shadow: 4px 4px 0 var(--midnight);
            font-family: ui-monospace, SFMono-Regular, Consolas, monospace;
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.08em;
        }
        .student-detail-cell,
        .student-status-cell {
            margin-bottom: 12px;
            padding: 14px 16px;
            border: 3px solid var(--midnight);
            border-radius: 18px;
            background: var(--mochi);
            box-shadow: 4px 4px 0 var(--midnight);
        }
        .student-kelas-cell { order: 3; }
        .student-email-cell { order: 4; }
        .student-status-cell { order: 5; }
        .student-email-cell .mobile-field-value {
            font-size: 13px;
            font-weight: 700;
            color: #4A5568;
        }
        .student-action-cell {
            order: 6;
            display: block;
            margin-top: 8px;
            padding-top: 18px;
            border-top: 3px dashed var(--midnight);
            text-align: left;
        }
        .student-action-cell::before {
            display: none;
        }
        .student-action-cell .edit-btn {
            display: flex;
            width: 100%;
            min-height: 50px;
        }
        .table-empty-cell {
            display: block !important;
            width: 100%;
            padding: 40px 24px !important;
            text-align: center !important;
            border: 4px solid var(--midnight);
            border-radius: 24px;
            background: var(--white) !important;
            box-shadow: 6px 6px 0 var(--midnight);
        }
        .table-empty-cell::before {
            display: none;
        }
    }
</style>

<div class="anime-dashboard">

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
                Manga-Pop khas Scoola! Yuk intip datanya di bawah. ( ≧◡≦ ) ✨
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="neo-card stats-card">
            <div class="sticker sticker-sakura">
                TOTAL ✨
            </div>
            <div class="stats-icon sakura">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stats-label">Total Siswa</div>
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
            <div class="stats-label">Kelas Aktif</div>
            <div class="stats-number">
                {{ $siswa->unique('kelas')->count() }}
            </div>
        </div>
    </div>

    <div class="neo-card filter-card">
        <div class="filter-grid">
            <div>
                <label class="input-label">Pencarian</label>
                <input
                    type="text"
                    id="searchInput"
                    class="anime-input"
                    placeholder="Cari nama siswa / NIS kamu di sini... 🔎"
                >
            </div>

            <div>
                <label class="input-label">Filter Kelas</label>
                <select id="kelasFilter" class="anime-input">
                    <option value="">Semua Kelas</option>
                    @foreach($siswa->unique('kelas')->pluck('kelas')->sort() as $kls)
                        <option value="{{ $kls }}">{{ $kls }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

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
                        <th style="text-align:right;">Aksi</th>
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
                    <td data-label="NIS" class="student-nis-cell"><span class="mobile-field-label">Nomor Induk</span><span class="student-id-chip">{{ $s->NIS }}</span></td>
                    <td data-label="Nama Lengkap" class="student-name-cell"><span class="mobile-field-label">Profil Siswa</span><span class="student-name-text">{{ $s->nama_siswa }}</span></td>
                    <td data-label="Kelas" class="student-detail-cell student-kelas-cell"><span class="mobile-field-label">Kelas</span><span class="mobile-field-value">{{ $s->kelas }}</span></td>
                    <td data-label="Email" class="student-detail-cell student-email-cell"><span class="mobile-field-label">Email</span><span class="mobile-field-value">{{ $s->user->email ?? '-' }}</span></td>
                    <td data-label="Status" class="student-status-cell">
                        <span class="mobile-field-label">Status</span>
                        <span class="status-badge">
                            ⚡ Aktif
                        </span>
                    </td>
                    @if(auth()->user()->role === 'admin')
                    <td data-label="Aksi" class="student-action-cell">
                        <a href="{{ route('siswa.edit', $s->NIS) }}" class="edit-btn">
                            Edit ✏️
                        </a>
                    </td>
                    @endif
                </tr>
                @empty
                <tr class="empty-placeholder-base">
                    <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="table-empty-cell">
                        Data siswa belum tersedia nih... (╥﹏╥)
                    </td>
                </tr>
                @endforelse

                <tr id="jsEmptyMessage" style="display: none;">
                    <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="table-empty-cell">
                        Duh, siswa yang kamu cari nggak ketemu... (╥﹏╥)
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@if(auth()->user()->role === 'admin')
<a href="{{ route('siswa.create') }}" class="fab-button">
    <i class="bi bi-plus-lg"></i>
</a>
@endif

<script>
    const searchInput = document.getElementById('searchInput');
    const kelasFilter = document.getElementById('kelasFilter');
    const rows = document.querySelectorAll('.student-row');
    const jsEmptyMessage = document.getElementById('jsEmptyMessage');

    function filterTable() {
        const q = searchInput.value.toLowerCase().trim();
        const k = kelasFilter.value;
        let visibleRowsCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name;
            const nis = row.dataset.nis;
            const kelas = row.dataset.kelas;

            const matchSearch = !q || name.includes(q) || nis.includes(q);
            const matchKelas = !k || kelas === k;

            if (matchSearch && matchKelas) {
                row.style.display = '';
                visibleRowsCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Tampilkan pesan "nggak ketemu" jika pencarian menghasilkan 0 baris data
        if (rows.length > 0) {
            if (visibleRowsCount === 0) {
                jsEmptyMessage.style.display = '';
            } else {
                jsEmptyMessage.style.display = 'none';
            }
        }
    }

    searchInput.addEventListener('input', filterTable);
    kelasFilter.addEventListener('change', filterTable);
</script>

@endsection
