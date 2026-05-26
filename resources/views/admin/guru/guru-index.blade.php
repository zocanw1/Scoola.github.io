@extends('layouts.admin')

@section('content')

<style>
    :root {
        --midnight: #1E1B29;
        --sakura: #FF7675;
        --cyber: #00CEC9;
        --cosmo: #6C5CE7;
        --gold: #FDCB6E;
        --mochi: #FAF9FF;
        --white: #FFFFFF;
    }

    .font-anime-title { font-family: 'Fredoka One', cursive; }
    .font-anime-body { font-family: 'Nunito', sans-serif; }

    .manga-stack {
        display: flex;
        flex-direction: column;
        gap: 32px;
        padding-bottom: 40px;
    }

    .manga-card {
        position: relative;
        padding: 40px;
        border: 4px solid var(--midnight);
        border-radius: 18px;
        background: var(--white);
        color: var(--midnight);
        box-shadow: 8px 8px 0 var(--midnight);
    }

    .manga-card-cosmo { background: var(--cosmo); color: var(--white); }
    .manga-card-gold { background: var(--gold); }
    .manga-card-cyber { background: var(--cyber); }

    .manga-hover-effect { transition: transform 0.18s ease, box-shadow 0.18s ease; }
    .manga-hover-effect:hover {
        transform: translate(-2px, -2px);
        box-shadow: 10px 10px 0 var(--midnight);
    }

    .manga-input {
        width: 100%;
        padding: 16px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--mochi);
        color: var(--midnight);
        font-size: 15px;
        font-weight: 800;
        box-shadow: 4px 4px 0 var(--midnight);
        outline: none;
        transition: all 0.18s ease;
    }

    .manga-input:focus {
        background: var(--white);
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0 var(--midnight);
    }

    .manga-btn-edit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 50px;
        padding: 0 22px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--sakura);
        color: var(--white);
        font-family: 'Fredoka One', cursive;
        font-size: 14px;
        text-decoration: none;
        letter-spacing: 0.04em;
        box-shadow: 4px 4px 0 var(--midnight);
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .manga-btn-edit:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0 var(--midnight);
    }

    .manga-badge {
        display: inline-flex;
        align-items: center;
        min-height: 32px;
        padding: 0 12px;
        border: 2px solid var(--midnight);
        border-radius: 10px;
        background: var(--cyber);
        color: var(--midnight);
        box-shadow: 2px 2px 0 var(--midnight);
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .hero-note {
        max-width: 620px;
        margin: 0;
        padding: 16px;
        border: 3px solid var(--midnight);
        border-radius: 14px;
        background: var(--white);
        color: var(--midnight);
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 16px;
        font-weight: 700;
        line-height: 1.65;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 32px;
    }

    .stats-card {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .stats-icon {
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 4px solid var(--midnight);
        border-radius: 50%;
        background: var(--white);
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 32px;
    }

    .stats-label {
        display: inline-block;
        margin-bottom: 8px;
        padding: 4px 8px;
        border: 2px solid var(--midnight);
        border-radius: 8px;
        background: var(--white);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.18em;
        text-transform: uppercase;
    }

    .stats-number {
        font-family: 'Fredoka One', cursive;
        font-size: 56px;
        line-height: 1;
        color: var(--white);
        text-shadow: 4px 4px 0 var(--midnight);
        -webkit-text-stroke: 2px var(--midnight);
    }

    .toolbar-card {
        display: flex;
        flex-direction: column;
        gap: 24px;
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
    }

    .toolbar-note {
        max-width: 760px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.65;
        color: #4A5568;
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
        grid-template-columns: minmax(0, 1.4fr) minmax(240px, 0.8fr) auto;
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

    .search-input-wrap .manga-input {
        padding-right: 60px;
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
        color: var(--midnight);
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

    .manga-table-wrap { overflow-x: auto; }
    .manga-table { width: 100%; border-collapse: collapse; min-width: 840px; }

    .manga-table th {
        padding: 20px;
        background: var(--gold);
        color: var(--midnight);
        border-bottom: 4px solid var(--midnight);
        border-right: 3px solid var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: 18px;
        text-align: left;
    }

    .manga-table td {
        padding: 20px;
        border-bottom: 3px solid var(--midnight);
        border-right: 3px solid var(--midnight);
        color: var(--midnight);
        font-size: 15px;
        font-weight: 800;
        background: var(--white);
    }

    .manga-table th:last-child,
    .manga-table td:last-child { border-right: 0; }
    .manga-table tbody tr:last-child td { border-bottom: 0; }
    .manga-table tbody tr:hover td { background: var(--mochi); }

    .mobile-field-label { display: none; }
    .guru-mapel-list { display: flex; flex-wrap: wrap; gap: 8px; }
    .guru-id-chip { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; }
    .guru-email-text { color: #4A5568; }

    .guru-empty-cell {
        padding: 72px;
        text-align: center;
        background: var(--mochi) !important;
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

    .fab-container {
        position: fixed !important;
        right: 40px !important;
        bottom: 40px !important;
        z-index: 99999 !important;
    }

    .btn-fab {
        width: 60px !important;
        height: 60px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 4px solid var(--midnight) !important;
        border-radius: 50% !important;
        background: var(--gold) !important;
        color: var(--midnight) !important;
        text-decoration: none !important;
        box-shadow: 5px 5px 0 var(--midnight) !important;
        transition: all 0.18s ease !important;
    }

    .btn-fab:hover {
        transform: translate(-2px, -2px) !important;
        box-shadow: 8px 8px 0 var(--midnight) !important;
    }

    .fab-label {
        position: absolute !important;
        right: 80px !important;
        background: var(--white) !important;
        color: var(--midnight) !important;
        border: 3px solid var(--midnight) !important;
        border-radius: 12px !important;
        padding: 8px 16px !important;
        box-shadow: 4px 4px 0 var(--midnight) !important;
        font-family: 'Fredoka One', cursive !important;
        font-size: 12px !important;
        white-space: nowrap !important;
        opacity: 0 !important;
        transform: translateX(20px) !important;
        transition: all 0.2s ease !important;
    }

    .btn-fab:hover .fab-label {
        opacity: 1 !important;
        transform: translateX(0) !important;
    }

    @media (max-width: 768px) {
        .manga-card { padding: 24px; }
        .stats-grid,
        .search-grid,
        .import-dropzone {
            grid-template-columns: 1fr;
        }
        .search-actions,
        .manga-pagination {
            justify-content: stretch;
        }
        .search-actions .manga-btn-edit,
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
        .guru-table-card {
            padding: 0 !important;
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        .manga-table-wrap { overflow: visible; }
        .manga-table { min-width: 0; }
        .manga-table thead { display: none; }
        .manga-table tbody,
        .manga-table tr,
        .manga-table td {
            display: block;
            width: 100%;
        }
        .manga-table tbody {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        .manga-table tbody tr {
            padding: 20px;
            border: 4px solid var(--midnight);
            border-radius: 24px;
            background: var(--white);
            box-shadow: 8px 8px 0 var(--midnight);
        }
        .manga-table td {
            padding: 0;
            border: 0;
            background: transparent;
        }
        .manga-table tr:hover td { background: transparent; }
        .mobile-field-label {
            display: block;
            margin-bottom: 8px;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            opacity: 0.6;
        }
        .guru-name-cell,
        .guru-mapel-cell,
        .guru-email-cell {
            margin-bottom: 12px;
            padding: 14px 16px;
            border: 3px solid var(--midnight);
            border-radius: 18px;
            box-shadow: 4px 4px 0 var(--midnight);
        }
        .guru-name-cell { background: var(--white); }
        .guru-mapel-cell,
        .guru-email-cell { background: var(--mochi); }
        .guru-name-text {
            display: block;
            font-family: 'Fredoka One', cursive;
            font-size: 26px;
            line-height: 1.08;
        }
        .guru-action-cell {
            margin-top: 8px;
            padding-top: 18px;
            border-top: 3px dashed var(--midnight);
            text-align: left !important;
        }
        .guru-action-cell .manga-btn-edit {
            width: 100%;
        }
        .guru-empty-cell {
            display: block !important;
            width: 100%;
            padding: 40px 24px !important;
            border: 4px solid var(--midnight);
            border-radius: 24px;
            box-shadow: 6px 6px 0 var(--midnight);
        }
    }
</style>

<div class="manga-stack font-anime-body">
    <div class="manga-card manga-card-cosmo">
        <span style="position: absolute; top: -16px; right: 32px; background: var(--gold); color: var(--midnight); border: 3px solid var(--midnight); font-weight: 900; font-size: 12px; padding: 6px 16px; border-radius: 8px; transform: rotate(5deg); box-shadow: 3px 3px 0 var(--midnight);">
            ( ≧◡≦ ) KAWAII
        </span>

        <span style="display: inline-block; margin-bottom: 12px; padding: 4px 12px; border-radius: 6px; background: var(--midnight); color: var(--gold); box-shadow: 2px 2px 0 var(--gold); font-size: 12px; font-weight: 900; letter-spacing: 0.15em; text-transform: uppercase;">Manajemen Sumber Daya</span>
        <h1 class="font-anime-title" style="margin: 0 0 16px; font-size: 48px; letter-spacing: 1px; text-shadow: 4px 4px 0 var(--midnight); -webkit-text-stroke: 2px var(--midnight);">DATA GURU</h1>
        <p class="hero-note">
            Kelola seluruh data guru yang terdaftar di sistem Scoola, termasuk penugasan mata pelajaran dan akun akses.
        </p>
    </div>

    @if(session('success'))
        <div class="manga-card manga-card-cyber" style="padding: 24px 28px;">
            <div style="font-weight: 900; font-size: 16px;">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="manga-card" style="padding: 24px 28px; background: #ffe0df;">
            <div style="font-weight: 900; font-size: 16px;">{{ session('error') }}</div>
        </div>
    @endif

    <div class="fab-container">
        <a href="{{ route('guru.create') }}" class="btn-fab" title="Tambah Guru">
            <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
            <span class="fab-label">Tambah Guru Baru</span>
        </a>
    </div>

    <div class="stats-grid">
        <div class="manga-card manga-card-gold manga-hover-effect stats-card">
            <div class="stats-icon"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="stats-label">Total Data Guru</div>
                <div class="stats-number">{{ $totalGuru }}</div>
            </div>
        </div>

        <div class="manga-card manga-card-cyber manga-hover-effect stats-card">
            <div class="stats-icon"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="stats-label">Akses Sistem Aktif</div>
                <div class="stats-number">{{ $totalGuruAktif }}</div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('guru.index') }}" class="manga-card toolbar-card">
        <span style="position: absolute; top: -16px; left: -16px; background: var(--sakura); color: var(--white); border: 3px solid var(--midnight); font-weight: 900; font-size: 12px; padding: 6px 12px; border-radius: 8px; transform: rotate(-5deg); box-shadow: 3px 3px 0 var(--midnight);">
            LET'S SEARCH
        </span>

        <div class="toolbar-head">
            <div>
                <div class="toolbar-title">
                    <i class="bi bi-stars"></i>
                    Direktori Guru
                </div>
                <div class="toolbar-note">
                    Live search di bawah hanya memfilter baris yang sedang tampil di browser, jadi tetap ringan dan tidak menambah latency. Untuk pencarian lintas halaman, tetap gunakan tombol filter.
                </div>
            </div>
            <div class="live-chip">
                <i class="bi bi-lightning-charge-fill"></i>
                Live Search Aktif
            </div>
        </div>

        <div class="search-grid">
            <div>
                <label style="font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 12px;">Pencarian Direktori</label>
                <div class="search-input-wrap">
                    <input type="text" id="liveGuruSearch" name="q" value="{{ request('q') }}" class="manga-input" placeholder="CARI NAMA, NIP, EMAIL, ATAU MAPEL...">
                    <span class="search-indicator"><i class="bi bi-search"></i></span>
                </div>
            </div>

            <div>
                <label style="font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 12px;">Spesialisasi</label>
                <select name="mapel" class="manga-input" style="cursor: pointer; appearance: none;">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach ($allMapels as $m)
                        <option value="{{ $m->kd_mapel }}" @selected(request('mapel') === $m->kd_mapel)>{{ $m->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>

            <div class="search-actions">
                <button type="submit" class="manga-btn-edit">Terapkan Filter</button>
                <a href="{{ route('guru.index') }}" class="manga-btn-edit" style="background: var(--white); color: var(--midnight);">Reset</a>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ route('guru.import') }}" id="guruImportForm" class="manga-card manga-hover-effect import-card">
        @csrf
        <input type="hidden" name="rows" id="guruImportRows">
        <input type="file" id="guruImportFile" accept=".xlsx,.xls,.csv" class="visually-hidden-file">

        <div class="import-shell">
            <div class="import-intro">
                <div class="import-icon"><i class="bi bi-file-earmark-arrow-up-fill"></i></div>
                <div>
                    <div class="toolbar-title" style="margin-bottom: 8px;">Import Excel Guru</div>
                    <div class="toolbar-note">
                        Format kolom: <strong>nama</strong> lalu <strong>nip</strong>. Akun login otomatis dibuat dengan password default NIP tanpa spasi.
                    </div>
                </div>
            </div>

            <div class="import-dropzone">
                <label for="guruImportFile" class="import-picker">
                    <i class="bi bi-folder2-open-fill"></i>
                    Pilih File
                </label>
                <div class="import-file-name" id="guruImportFileName">Belum ada file dipilih</div>
                <button type="submit" class="manga-btn-edit import-submit-btn">Import Guru</button>
            </div>
        </div>
    </form>

    <div class="manga-card guru-table-card" style="padding: 0; overflow: hidden;">
        <div class="manga-table-wrap">
            <table class="manga-table">
                <thead>
                    <tr>
                        <th style="padding-left: 32px;">NIP</th>
                        <th>Nama Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Email</th>
                        <th style="text-align: center; padding-right: 32px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="guruTableBody">
                    @forelse ($guru as $g)
                        <tr class="guru-row">
                            <td data-label="NIP" class="guru-nip-cell" style="padding-left: 32px; font-size: 16px;">
                                <span class="mobile-field-label">Nomor Induk</span>
                                <span class="guru-id-chip">{{ $g->NIP }}</span>
                            </td>
                            <td data-label="Nama Guru" class="guru-name-cell" style="font-size: 17px;">
                                <span class="mobile-field-label">Profil Guru</span>
                                <span class="guru-name-text">{{ $g->nama_guru }}</span>
                            </td>
                            <td data-label="Mata Pelajaran" class="guru-mapel-cell">
                                <span class="mobile-field-label">Mata Pelajaran</span>
                                <div class="guru-mapel-list">
                                    @foreach ($g->mapels as $m)
                                        <span class="manga-badge">{{ $m->nama_mapel }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td data-label="Email" class="guru-email-cell">
                                <span class="mobile-field-label">Email</span>
                                <span class="guru-email-text">{{ $g->user->email ?? '-' }}</span>
                            </td>
                            <td data-label="Aksi" class="guru-action-cell" style="padding-right: 32px; text-align: center;">
                                <a href="{{ route('guru.edit', $g->NIP) }}" class="manga-btn-edit">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="guru-empty-row">
                            <td colspan="5" class="guru-empty-cell">(′·_·`) Belum ada data guru ditemukan.</td>
                        </tr>
                    @endforelse
                    <tr id="guruLiveEmptyRow" class="guru-empty-row" style="display: none;">
                        <td colspan="5" class="guru-empty-cell">Tidak ada guru yang cocok di halaman ini.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="manga-pagination-wrap">
            {{ $guru->onEachSide(1)->links('vendor.pagination.manga-pop') }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const importForm = document.getElementById('guruImportForm');
    const fileInput = document.getElementById('guruImportFile');
    const fileName = document.getElementById('guruImportFileName');
    const rowsInput = document.getElementById('guruImportRows');
    const liveSearchInput = document.getElementById('liveGuruSearch');
    const tableRows = Array.from(document.querySelectorAll('#guruTableBody .guru-row'));
    const liveEmptyRow = document.getElementById('guruLiveEmptyRow');

    const debounce = (fn, delay) => {
        let timeoutId = null;

        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = window.setTimeout(() => fn(...args), delay);
        };
    };

    const normalizeHeader = (value) => String(value || '')
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '');

    if (fileInput && fileName) {
        fileInput.addEventListener('change', function () {
            fileName.textContent = fileInput.files[0]?.name || 'Belum ada file dipilih';
        });
    }

    if (liveSearchInput && tableRows.length) {
        const runLiveFilter = debounce(() => {
            const keyword = liveSearchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            tableRows.forEach((row) => {
                const matches = keyword === '' || row.textContent.toLowerCase().includes(keyword);
                row.style.display = matches ? '' : 'none';
                if (matches) {
                    visibleCount++;
                }
            });

            if (liveEmptyRow) {
                liveEmptyRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        }, 120);

        liveSearchInput.addEventListener('input', runLiveFilter);
    }

    if (!importForm || !fileInput || !rowsInput) {
        return;
    }

    importForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const file = fileInput.files[0];
        if (!file) {
            alert('Pilih file Excel dulu.');
            return;
        }

        const extension = (file.name.split('.').pop() || '').toLowerCase();
        const reader = new FileReader();

        reader.onload = function (loadEvent) {
            let rawRows = [];

            if (extension === 'csv') {
                rawRows = String(loadEvent.target.result)
                    .split(/\r?\n/)
                    .map((line) => line.trim())
                    .filter((line) => line !== '')
                    .map((line) => line.split(';').map((cell) => cell.trim()));
            } else {
                if (typeof XLSX === 'undefined') {
                    alert('Library pembaca Excel gagal dimuat.');
                    return;
                }

                const workbook = XLSX.read(loadEvent.target.result, { type: 'array' });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                rawRows = XLSX.utils.sheet_to_json(firstSheet, { header: 1, defval: '' });
            }

            if (!rawRows.length) {
                alert('File Excel kosong.');
                return;
            }

            const headerRow = rawRows[0].map(normalizeHeader);
            const namaIndex = headerRow.findIndex((header) => header.includes('nama'));
            const nipIndex = headerRow.findIndex((header) => header.includes('nip') || header.includes('nig'));

            if (namaIndex === -1 || nipIndex === -1) {
                alert('Kolom Excel harus berisi header nama dan nip.');
                return;
            }

            const rows = rawRows.slice(1)
                .map((row) => ({
                    nama: String(row[namaIndex] || '').trim(),
                    nip: String(row[nipIndex] || '').trim(),
                }))
                .filter((row) => row.nama !== '' && row.nip !== '');

            if (!rows.length) {
                alert('Tidak ada data guru yang bisa diimport.');
                return;
            }

            rowsInput.value = JSON.stringify(rows);
            importForm.submit();
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
