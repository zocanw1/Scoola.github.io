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

    .anime-dashboard {
        width: 100%;
        min-height: 100vh;
        padding: 32px;
        background: transparent !important;
    }

    .neo-card {
        position: relative;
        overflow: hidden;
        background: var(--white);
        border: 4px solid var(--midnight);
        border-radius: 20px;
        box-shadow: 8px 8px 0 var(--midnight);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .neo-card:hover {
        transform: translate(-2px, -2px);
        box-shadow: 10px 10px 0 var(--midnight);
    }

    .hero-section {
        margin-bottom: 32px;
        padding: 48px;
        background: var(--cosmo);
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        padding: 8px 16px;
        border: 3px solid var(--midnight);
        border-radius: 10px;
        background: var(--cyber);
        color: var(--midnight);
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        transform: rotate(-1.5deg);
    }

    .hero-title {
        margin: 0 0 18px;
        color: var(--white);
        font-family: 'Fredoka One', cursive;
        font-size: clamp(36px, 5vw, 56px);
        line-height: 1.1;
        text-shadow: 5px 5px 0 var(--midnight);
        -webkit-text-stroke: 1.5px var(--midnight);
    }

    .hero-description {
        max-width: 680px;
        margin: 0;
        padding: 20px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: rgba(30, 27, 41, 0.95);
        color: var(--white);
        box-shadow: 5px 5px 0 var(--midnight);
        font-size: 15px;
        font-weight: 700;
        line-height: 1.65;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 32px;
        margin-bottom: 32px;
    }

    .stats-card {
        padding: 28px;
        padding-top: 78px;
    }

    .sticker {
        position: absolute;
        top: 16px;
        right: 16px;
        padding: 6px 14px;
        border: 3px solid var(--midnight);
        border-radius: 10px;
        box-shadow: 3px 3px 0 var(--midnight);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .sticker-sakura { background: var(--sakura); color: var(--white); transform: rotate(3deg); }
    .sticker-gold { background: var(--gold); color: var(--midnight); transform: rotate(-3deg); }

    .stats-icon {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        border: 3px solid var(--midnight);
        border-radius: 16px;
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 26px;
    }

    .stats-icon.sakura { background: var(--sakura); color: var(--white); }
    .stats-icon.cyber { background: var(--cyber); color: var(--midnight); }

    .stats-label {
        margin-bottom: 8px;
        color: var(--midnight);
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    .stats-number {
        color: var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: 54px;
        line-height: 1;
    }

    .anime-input {
        width: 100%;
        padding: 16px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--mochi);
        color: var(--midnight);
        font-size: 14px;
        font-weight: 800;
        box-shadow: 4px 4px 0 var(--midnight);
        outline: none;
        transition: all 0.18s ease;
    }

    .anime-input:focus {
        background: var(--white);
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0 var(--midnight);
    }

    .edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 50px;
        padding: 0 18px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--gold);
        color: var(--midnight);
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .edit-btn:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0 var(--midnight);
    }

    .toolbar-card {
        display: flex;
        flex-direction: column;
        gap: 24px;
        margin-bottom: 24px;
        padding: 24px;
    }

    .toolbar-head {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
    }

    .toolbar-title {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        font-family: 'Fredoka One', cursive;
        font-size: 20px;
        color: var(--midnight);
    }

    .toolbar-note {
        max-width: 760px;
        color: #4A5568;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.65;
    }

    .live-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 38px;
        padding: 0 14px;
        border: 3px solid var(--midnight);
        border-radius: 999px;
        background: var(--cyber);
        color: var(--midnight);
        box-shadow: 3px 3px 0 var(--midnight);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .search-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(220px, 0.8fr) auto;
        gap: 18px;
        align-items: end;
    }

    .search-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .search-input-wrap {
        position: relative;
    }

    .search-input-wrap .anime-input {
        padding-right: 58px;
    }

    .search-indicator {
        position: absolute;
        top: 50%;
        right: 16px;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--midnight);
        border-radius: 12px;
        background: var(--gold);
        box-shadow: 3px 3px 0 var(--midnight);
        pointer-events: none;
    }

    .import-card {
        margin-bottom: 32px;
        padding: 24px;
        background:
            radial-gradient(circle at top right, rgba(108, 92, 231, 0.14), transparent 32%),
            linear-gradient(135deg, rgba(0, 206, 201, 0.12), rgba(253, 203, 110, 0.18)),
            var(--white);
    }

    .import-shell {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .import-intro {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .import-icon {
        width: 66px;
        height: 66px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 4px solid var(--midnight);
        border-radius: 18px;
        background: var(--gold);
        box-shadow: 5px 5px 0 var(--midnight);
        font-size: 28px;
        color: var(--midnight);
    }

    .import-dropzone {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        gap: 14px;
        align-items: center;
        padding: 16px;
        border: 4px dashed var(--midnight);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.86);
        box-shadow: 6px 6px 0 var(--midnight);
    }

    .import-picker {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 52px;
        padding: 0 18px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--midnight);
        color: var(--white);
        font-family: 'Fredoka One', cursive;
        font-size: 14px;
        box-shadow: 4px 4px 0 var(--midnight);
        cursor: pointer;
        transition: all 0.18s ease;
    }

    .import-picker:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0 var(--midnight);
    }

    .import-file-name {
        min-height: 52px;
        display: flex;
        align-items: center;
        padding: 0 16px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--white);
        font-size: 14px;
        font-weight: 900;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .import-submit-btn {
        min-width: 172px;
        background: var(--cyber);
    }

    .visually-hidden-file {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .table-wrapper {
        margin-bottom: 0;
        overflow-x: auto;
    }

    .anime-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Nunito', sans-serif;
        min-width: 920px;
    }

    .anime-table thead {
        background: var(--midnight);
        color: var(--white);
    }

    .anime-table th {
        padding: 18px 20px;
        border-bottom: 4px solid var(--midnight);
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 0.14em;
        text-align: left;
        text-transform: uppercase;
    }

    .anime-table td {
        padding: 20px;
        border-bottom: 3px solid var(--midnight);
        background: var(--white);
        color: var(--midnight);
        font-size: 14px;
        font-weight: 800;
    }

    .anime-table tbody tr:last-child td { border-bottom: 0; }
    .anime-table tbody tr:hover td { background: #F1EFFF; }
    .anime-table td:last-child { text-align: right; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border: 2.5px solid var(--midnight);
        border-radius: 30px;
        background: var(--cyber);
        color: var(--midnight);
        box-shadow: 3px 3px 0 var(--midnight);
        font-size: 11px;
        font-weight: 900;
    }

    .mobile-field-label { display: none; }
    .student-action-cell { text-align: right; }
    .student-id-chip { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; }

    .table-empty-cell {
        padding: 72px;
        text-align: center;
        color: #64748B;
        font-size: 18px;
        font-weight: 900;
    }

    .manga-pagination-wrap {
        padding: 22px 26px 26px;
        border-top: 4px solid var(--midnight);
        background: linear-gradient(135deg, rgba(253, 203, 110, 0.3), rgba(250, 249, 255, 0.92));
    }

    .manga-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .manga-pagination__meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .manga-pagination__sticker {
        display: inline-flex;
        align-items: center;
        min-height: 34px;
        padding: 0 12px;
        border: 3px solid var(--midnight);
        border-radius: 10px;
        background: var(--sakura);
        color: var(--white);
        box-shadow: 3px 3px 0 var(--midnight);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        transform: rotate(-4deg);
    }

    .manga-pagination__summary {
        font-size: 13px;
        font-weight: 900;
        color: var(--midnight);
    }

    .manga-pagination__controls {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .manga-page-link,
    .manga-page-gap {
        min-width: 42px;
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--midnight);
        border-radius: 12px;
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 13px;
        font-weight: 900;
    }

    .manga-page-link {
        background: var(--white);
        color: var(--midnight);
        text-decoration: none;
        transition: all 0.16s ease;
    }

    .manga-page-link:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0 var(--midnight);
    }

    .manga-page-link.is-active {
        background: var(--midnight);
        color: var(--gold);
    }

    .manga-page-link.is-disabled {
        background: rgba(255, 255, 255, 0.7);
        color: #94A3B8;
        box-shadow: none;
        opacity: 0.75;
    }

    .manga-page-gap {
        min-width: auto;
        padding: 0 12px;
        border-style: dashed;
        background: transparent;
        color: #64748B;
        box-shadow: none;
    }

    .fab-button {
        position: fixed;
        right: 32px;
        bottom: 32px;
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid var(--midnight);
        border-radius: 50%;
        background: var(--sakura);
        color: var(--white);
        text-decoration: none;
        font-size: 28px;
        box-shadow: 6px 6px 0 var(--midnight);
        transition: all 0.18s ease;
        z-index: 999;
    }

    .fab-button:hover {
        transform: translate(-2px, -2px);
        box-shadow: 8px 8px 0 var(--midnight);
    }

    @media (max-width: 768px) {
        .anime-dashboard { padding: 16px; }
        .hero-section { padding: 32px 20px; margin-bottom: 24px; }
        .hero-title { font-size: 32px; }
        .stats-grid,
        .search-grid,
        .import-dropzone {
            grid-template-columns: 1fr;
        }
        .search-actions,
        .manga-pagination {
            justify-content: stretch;
        }
        .search-actions .edit-btn,
        .import-picker,
        .import-submit-btn {
            width: 100%;
        }
        .import-intro {
            align-items: flex-start;
        }
        .import-file-name {
            min-height: 68px;
            white-space: normal;
        }
        .neo-card.table-shell {
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        .table-wrapper {
            overflow: visible;
        }
        .anime-table {
            min-width: 0;
            background: transparent;
        }
        .anime-table thead { display: none; }
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
            padding: 20px;
            border: 4px solid var(--midnight);
            border-radius: 24px;
            background: var(--white);
            box-shadow: 8px 8px 0 var(--midnight);
        }
        .anime-table tbody tr:hover td { background: transparent; }
        .anime-table td {
            padding: 0;
            border: 0;
            background: transparent;
            text-align: left;
        }
        .mobile-field-label {
            display: block;
            margin-bottom: 8px;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            opacity: 0.55;
        }
        .student-name-cell,
        .student-detail-cell,
        .student-status-cell {
            margin-bottom: 12px;
            padding: 14px 16px;
            border: 3px solid var(--midnight);
            border-radius: 18px;
            box-shadow: 4px 4px 0 var(--midnight);
        }
        .student-name-cell { background: var(--white); }
        .student-detail-cell,
        .student-status-cell { background: var(--mochi); }
        .student-name-text {
            display: block;
            font-family: 'Fredoka One', cursive;
            font-size: 26px;
            line-height: 1.08;
        }
        .student-action-cell {
            margin-top: 8px;
            padding-top: 18px;
            border-top: 3px dashed var(--midnight);
            text-align: left;
        }
        .student-action-cell .edit-btn {
            width: 100%;
        }
        .table-empty-cell {
            display: block !important;
            width: 100%;
            padding: 40px 24px !important;
            border: 4px solid var(--midnight);
            border-radius: 24px;
            background: var(--white) !important;
            box-shadow: 6px 6px 0 var(--midnight);
        }
    }
</style>

<div class="anime-dashboard">
    @if(session('success'))
        <div class="neo-card" style="padding: 20px 24px; margin-bottom: 24px; background: var(--cyber); color: var(--midnight); font-weight: 900;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="neo-card" style="padding: 20px 24px; margin-bottom: 24px; background: #ffe0df; color: var(--midnight); font-weight: 900;">
            {{ session('error') }}
        </div>
    @endif

    <div class="neo-card hero-section">
        <div class="hero-badge">Professional Database</div>
        <h1 class="hero-title">Database Siswa</h1>
        <p class="hero-description">
            Kelola seluruh informasi siswa secara modern dengan visual dashboard khas Scoola.
        </p>
    </div>

    <div class="stats-grid">
        <div class="neo-card stats-card">
            <div class="sticker sticker-sakura">Total</div>
            <div class="stats-icon sakura"><i class="bi bi-people-fill"></i></div>
            <div class="stats-label">Total Siswa</div>
            <div class="stats-number">{{ $totalSiswa }}</div>
        </div>

        <div class="neo-card stats-card">
            <div class="sticker sticker-gold">Class</div>
            <div class="stats-icon cyber"><i class="bi bi-building"></i></div>
            <div class="stats-label">Kelas Aktif</div>
            <div class="stats-number">{{ $totalKelasAktif }}</div>
        </div>
    </div>

    <form method="GET" action="{{ route('siswa.index') }}" id="siswaFilterForm" class="neo-card toolbar-card" data-live-filter="siswa">
        <div class="toolbar-head">
            <div>
                <div class="toolbar-title">
                    <i class="bi bi-stars"></i>
                    Direktori Siswa
                </div>
                <div class="toolbar-note">Live search di bawah hanya memfilter data yang sedang tampil di halaman ini, tanpa reload dan tanpa request pencarian tambahan.</div>
            </div>
            <div class="live-chip">
                <i class="bi bi-lightning-charge-fill"></i>
                Live Search Aktif
            </div>
        </div>

        <div class="search-grid">
            <div>
                <label class="input-label" style="margin-bottom: 12px;">Pencarian Direktori</label>
                <div class="search-input-wrap">
                    <input type="text" id="liveSiswaSearch" name="q" class="anime-input" value="{{ request('q') }}" placeholder="CARI NAMA, NIS, EMAIL, ATAU KELAS...">
                    <span class="search-indicator"><i class="bi bi-search"></i></span>
                </div>
            </div>

            <div>
                <label class="input-label" style="margin-bottom: 12px;">Filter Kelas</label>
                <select name="kelas" class="anime-input">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasOptions as $kls)
                        <option value="{{ $kls }}" @selected(request('kelas') === $kls)>{{ $kls }}</option>
                    @endforeach
                </select>
            </div>

            <div class="search-actions">
                <button type="submit" class="edit-btn">Terapkan Filter</button>
                <a href="{{ route('siswa.index') }}" data-search-reset="siswa" class="edit-btn" style="background: var(--white);">Reset</a>
            </div>
        </div>
    </form>

    @if(auth()->user()->role === 'admin')
        <form method="POST" action="{{ route('siswa.import') }}" id="siswaImportForm" class="neo-card import-card">
            @csrf
            <input type="hidden" name="rows" id="siswaImportRows">
            <input type="file" id="siswaImportFile" class="visually-hidden-file" accept=".csv,.xlsx,.xls">

            <div class="import-shell">
                <div class="import-intro">
                    <div class="import-icon"><i class="bi bi-file-earmark-arrow-up-fill"></i></div>
                    <div>
                        <div class="toolbar-title" style="margin-bottom: 8px;">Import Data Siswa</div>
                        <div class="toolbar-note">
                            Format kolom: <strong>nis</strong>, <strong>nama_siswa</strong>, <strong>kelas</strong>. Email default dibuat <strong>siswa-NIS@gmail.com</strong> dan password default memakai NIS tanpa simbol.
                        </div>
                    </div>
                </div>

                <div class="import-dropzone">
                    <label for="siswaImportFile" class="import-picker">
                        <i class="bi bi-folder2-open-fill"></i>
                        Pilih File
                    </label>
                    <div class="import-file-name" id="siswaImportFileName">Belum ada file dipilih</div>
                    <button type="submit" class="edit-btn import-submit-btn">Import Siswa</button>
                </div>
            </div>
        </form>
    @endif

    <div id="siswaDirectoryResults">
    <div class="neo-card table-shell" style="padding: 0; overflow: hidden;">
        <div class="table-wrapper">
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
                <tbody id="siswaTableBody">
                    @forelse($siswa as $s)
                        <tr class="siswa-row" data-search-text="{{ $s->NIS }} {{ $s->nama_siswa }} {{ $s->kelas }} {{ $s->user->email ?? '' }}" data-kelas="{{ $s->kelas }}">
                            <td data-label="NIS" class="student-nis-cell">
                                <span class="mobile-field-label">Nomor Induk</span>
                                <span class="student-id-chip">{{ $s->NIS }}</span>
                            </td>
                            <td data-label="Nama Lengkap" class="student-name-cell">
                                <span class="mobile-field-label">Profil Siswa</span>
                                <span class="student-name-text">{{ $s->nama_siswa }}</span>
                            </td>
                            <td data-label="Kelas" class="student-detail-cell student-kelas-cell">
                                <span class="mobile-field-label">Kelas</span>
                                <span class="mobile-field-value">{{ $s->kelas }}</span>
                            </td>
                            <td data-label="Email" class="student-detail-cell student-email-cell">
                                <span class="mobile-field-label">Email</span>
                                <span class="mobile-field-value">{{ $s->user->email ?? '-' }}</span>
                            </td>
                            <td data-label="Status" class="student-status-cell">
                                <span class="mobile-field-label">Status</span>
                                <span class="status-badge">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                    Aktif
                                </span>
                            </td>
                            @if(auth()->user()->role === 'admin')
                                <td data-label="Aksi" class="student-action-cell">
                                    <a href="{{ route('siswa.edit', $s->NIS) }}" class="edit-btn">Edit</a>
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
                </tbody>
            </table>
        </div>

        <div class="manga-pagination-wrap">
            {{ $siswa->onEachSide(1)->links('vendor.pagination.manga-pop') }}
        </div>
    </div>
    </div>
</div>

@if(auth()->user()->role === 'admin')
    <a href="{{ route('siswa.create') }}" class="fab-button">
        <i class="bi bi-plus-lg"></i>
    </a>
@endif

@if(auth()->user()->role === 'admin')
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    const debounce = (fn, delay) => {
        let timeoutId = null;

        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = window.setTimeout(() => fn(...args), delay);
        };
    };

    const filterForm = document.getElementById('siswaFilterForm');
    const liveSearchInput = document.getElementById('liveSiswaSearch');
    const kelasFilter = filterForm?.querySelector('select[name="kelas"]');
    const resetButton = filterForm?.querySelector('[data-search-reset="siswa"]');
    const tableBody = document.getElementById('siswaTableBody');
    const paginationWrap = document.querySelector('#siswaDirectoryResults .manga-pagination-wrap');

    if (filterForm && liveSearchInput && tableBody) {
        const rows = Array.from(tableBody.querySelectorAll('.siswa-row'));
        const serverEmptyRow = tableBody.querySelector('.empty-placeholder-base');
        let clientEmptyRow = tableBody.querySelector('[data-client-empty="siswa"]');

        if (!clientEmptyRow && rows.length > 0) {
            clientEmptyRow = document.createElement('tr');
            clientEmptyRow.setAttribute('data-client-empty', 'siswa');
            clientEmptyRow.hidden = true;
            clientEmptyRow.innerHTML = `
                <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="table-empty-cell">
                    Tidak ada siswa yang cocok di halaman ini.
                </td>
            `;
            tableBody.appendChild(clientEmptyRow);
        }

        const normalize = (value) => String(value || '').toLocaleLowerCase('id-ID').trim();

        const applyLiveFilter = () => {
            const query = normalize(liveSearchInput.value);
            const selectedKelas = normalize(kelasFilter?.value || '');
            let visibleRows = 0;

            rows.forEach((row) => {
                const rowSearchText = normalize(row.dataset.searchText);
                const rowKelas = normalize(row.dataset.kelas);
                const matchQuery = query === '' || rowSearchText.includes(query);
                const matchKelas = selectedKelas === '' || rowKelas === selectedKelas;
                const isVisible = matchQuery && matchKelas;

                row.hidden = !isVisible;

                if (isVisible) {
                    visibleRows += 1;
                }
            });

            if (clientEmptyRow) {
                clientEmptyRow.hidden = visibleRows > 0 || rows.length === 0;
            }

            if (serverEmptyRow && rows.length > 0) {
                serverEmptyRow.hidden = true;
            }

            if (paginationWrap) {
                paginationWrap.style.display = query !== '' || selectedKelas !== '' ? 'none' : '';
            }
        };

        const runLiveSearch = debounce(applyLiveFilter, 120);

        liveSearchInput.addEventListener('input', runLiveSearch);
        filterForm.addEventListener('submit', function (event) {
            event.preventDefault();
            applyLiveFilter();
        });

        if (kelasFilter) {
            kelasFilter.addEventListener('change', applyLiveFilter);
        }

        if (resetButton) {
            resetButton.addEventListener('click', function (event) {
                event.preventDefault();
                liveSearchInput.value = '';

                if (kelasFilter) {
                    kelasFilter.value = '';
                }

                applyLiveFilter();
                liveSearchInput.focus({ preventScroll: true });
            });
        }

        applyLiveFilter();
    }

    const form = document.getElementById('siswaImportForm');
    const fileInput = document.getElementById('siswaImportFile');
    const fileName = document.getElementById('siswaImportFileName');
    const rowsInput = document.getElementById('siswaImportRows');

    if (!form || !fileInput || !rowsInput) {
        return;
    }

    const normalizeHeader = (value) => String(value || '')
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '');

    fileInput.addEventListener('change', function () {
        fileName.textContent = fileInput.files[0]?.name || 'Belum ada file dipilih';
    });

    const mapRows = (rows) => {
        if (!rows.length) {
            return [];
        }

        const headerRow = rows[0].map(normalizeHeader);
        const nisIndex = headerRow.findIndex((header) => header === 'nis');
        const namaIndex = headerRow.findIndex((header) => header.includes('nama'));
        const kelasIndex = headerRow.findIndex((header) => header.includes('kelas'));

        if (nisIndex === -1 || namaIndex === -1 || kelasIndex === -1) {
            return null;
        }

        return rows.slice(1)
            .map((row) => ({
                nis: String(row[nisIndex] || '').trim(),
                nama: String(row[namaIndex] || '').trim(),
                kelas: String(row[kelasIndex] || '').trim(),
            }))
            .filter((row) => row.nis !== '' && row.nama !== '' && row.kelas !== '');
    };

    const parseCsv = (text) => {
        return mapRows(
            String(text)
                .split(/\r?\n/)
                .map((line) => line.trim())
                .filter((line) => line !== '')
                .map((line) => line.split(';').map((cell) => cell.trim()))
        );
    };

    const parseWorkbook = (buffer) => {
        const workbook = XLSX.read(buffer, { type: 'array' });
        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
        const rows = XLSX.utils.sheet_to_json(firstSheet, { header: 1, defval: '' });
        return mapRows(rows);
    };

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const file = fileInput.files[0];
        if (!file) {
            alert('Pilih file siswa dulu.');
            return;
        }

        const extension = (file.name.split('.').pop() || '').toLowerCase();
        const reader = new FileReader();

        reader.onload = function (loadEvent) {
            let rows = null;

            if (extension === 'csv') {
                rows = parseCsv(loadEvent.target.result);
            } else {
                if (typeof XLSX === 'undefined') {
                    alert('Library pembaca spreadsheet gagal dimuat.');
                    return;
                }
                rows = parseWorkbook(loadEvent.target.result);
            }

            if (rows === null) {
                alert('Kolom file harus berisi NIS, nama_siswa, dan kelas.');
                return;
            }

            if (!rows.length) {
                alert('Tidak ada data siswa yang bisa diimport.');
                return;
            }

            rowsInput.value = JSON.stringify(rows);
            form.submit();
        };

        if (extension === 'csv') {
            reader.readAsText(file);
            return;
        }

        reader.readAsArrayBuffer(file);
    });
});
</script>

@endsection
