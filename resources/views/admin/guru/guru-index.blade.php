@extends('layouts.admin')

@section('content')

<style>
    /* Variabel Warna Manga-Pop */
    :root {
        --midnight: #1E1B29;
        --sakura: #FF7675;
        --cyber: #00CEC9;
        --cosmo: #6C5CE7;
        --gold: #FDCB6E;
        --mochi: #FAF9FF;
        --white: #FFFFFF;
    }

    /* Font Classes */
    .font-anime-title { font-family: 'Fredoka One', cursive; }
    .font-anime-body { font-family: 'Nunito', sans-serif; }

    /* Aturan Utama Visual: Card Manga-Pop (Neobrutalism) */
    .manga-card {
        background-color: var(--white);
        border: 4px solid var(--midnight);
        border-radius: 16px;
        box-shadow: 8px 8px 0px 0px var(--midnight);
        padding: 40px;
        position: relative;
        color: var(--midnight);
    }

    /* Varian Warna Card */
    .manga-card-cosmo { background-color: var(--cosmo); color: var(--white); }
    .manga-card-gold { background-color: var(--gold); }
    .manga-card-cyber { background-color: var(--cyber); }

    /* Efek Hover Mekanis */
    .manga-hover-effect { transition: all 0.2s ease; }
    .manga-hover-effect:hover {
        transform: translate(-2px, -2px);
        box-shadow: 10px 10px 0px 0px var(--midnight);
    }
    .manga-hover-effect:active {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0px 0px var(--midnight);
    }

    /* Input & Select Neobrutalism */
    .manga-input {
        width: 100%;
        padding: 16px;
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
        font-size: 15px;
        background-color: var(--mochi);
        border: 3px solid var(--midnight);
        border-radius: 12px;
        box-shadow: 4px 4px 0px 0px var(--midnight);
        outline: none;
        transition: all 0.2s ease;
        color: var(--midnight);
    }
    .manga-input:focus {
        background-color: var(--white);
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px 0px var(--midnight);
    }

    /* Table Styling Neobrutalism */
    .manga-table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .manga-table th {
        background-color: var(--gold);
        color: var(--midnight);
        padding: 20px;
        font-family: 'Fredoka One', cursive;
        font-size: 18px;
        border-bottom: 4px solid var(--midnight);
        border-right: 3px solid var(--midnight);
        text-align: left;
    }
    .manga-table th:last-child { border-right: none; }
    .manga-table td {
        padding: 20px;
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
        border-bottom: 3px solid var(--midnight);
        border-right: 3px solid var(--midnight);
        color: var(--midnight);
    }
    .manga-table td:last-child { border-right: none; }
    .manga-table tr:hover td { background-color: var(--mochi); }
    .manga-table tr:last-child td { border-bottom: none; }

    .mobile-field-label {
        display: none;
    }

    .guru-mapel-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .guru-action-cell {
        text-align: center;
    }

    .guru-empty-cell {
        text-align: center;
        padding: 80px;
        font-weight: 800;
        font-size: 18px;
        color: #718096;
        background: var(--mochi);
    }

    /* Button Edit (Sakura) */
    .manga-btn-edit {
        display: inline-block;
        background-color: var(--sakura);
        color: var(--white);
        font-family: 'Fredoka One', cursive;
        text-decoration: none;
        padding: 8px 24px;
        border: 3px solid var(--midnight);
        border-radius: 12px;
        box-shadow: 4px 4px 0px 0px var(--midnight);
        transition: all 0.2s ease;
        letter-spacing: 1px;
    }
    .manga-btn-edit:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px 0px var(--midnight);
    }

    /* Badges */
    .manga-badge {
        background-color: var(--cyber);
        color: var(--midnight);
        border: 2px solid var(--midnight);
        font-weight: 800;
        font-size: 11px;
        padding: 6px 12px;
        border-radius: 8px;
        box-shadow: 2px 2px 0px 0px var(--midnight);
        text-transform: uppercase;
        display: inline-block;
    }

    @media (max-width: 768px) {
        .guru-table-card {
            padding: 0 !important;
            margin-bottom: 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
        }
        .guru-table-card:hover {
            transform: none !important;
            box-shadow: none !important;
        }
        .manga-table-wrap {
            overflow: visible;
            padding-bottom: 0;
        }
        .manga-table {
            min-width: 0;
            border-collapse: collapse;
            border-spacing: 0;
            background: transparent;
        }
        .manga-table thead {
            display: none;
        }
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
            background: transparent;
        }
        .manga-table tbody tr {
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
        .manga-table tbody tr:hover {
            transform: translate(-4px, -4px);
            box-shadow: 12px 12px 0 var(--midnight);
        }
        .guru-empty-row {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .guru-empty-row:hover {
            transform: none !important;
        }
        .manga-table tr:hover td {
            background: var(--white);
        }
        .manga-table td {
            display: block;
            padding: 0;
            border-right: 0;
            border-bottom: 0;
            text-align: left;
            background: transparent;
        }
        .manga-table td::before {
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
        .guru-name-cell {
            order: 1;
            margin-bottom: 10px;
            padding: 16px 18px 14px;
            border: 3px solid var(--midnight);
            border-radius: 18px;
            background: var(--white);
            box-shadow: 4px 4px 0 var(--midnight);
        }
        .guru-name-cell .mobile-field-label {
            margin-bottom: 10px;
            opacity: 0.8;
            color: var(--cyber);
        }
        .guru-name-text {
            display: block;
            font-family: 'Fredoka One', cursive;
            font-size: 26px;
            line-height: 1.08;
            letter-spacing: -0.02em;
            color: var(--midnight);
        }
        .guru-nip-cell {
            order: 2;
            margin-bottom: 18px;
            padding-left: 2px;
        }
        .guru-id-chip {
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
        .guru-mapel-cell,
        .guru-email-cell {
            margin-bottom: 12px;
            padding: 14px 16px;
            border: 3px solid var(--midnight);
            border-radius: 18px;
            background: var(--mochi);
            box-shadow: 4px 4px 0 var(--midnight);
        }
        .guru-mapel-cell { order: 3; }
        .guru-email-cell { order: 4; }
        .guru-mapel-list {
            justify-content: flex-start;
            margin-top: 2px;
        }
        .guru-email-text {
            display: block;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
            color: #4A5568;
            word-break: break-word;
        }
        .guru-action-cell {
            order: 5;
            display: block;
            margin-top: 8px;
            padding-top: 18px;
            border-top: 3px dashed var(--midnight);
            text-align: left !important;
        }
        .guru-action-cell::before {
            display: none;
        }
        .guru-action-cell .manga-btn-edit {
            display: flex;
            justify-content: center;
            width: 100%;
            min-height: 50px;
        }
        .guru-empty-cell {
            display: block !important;
            width: 100%;
            padding: 40px 24px !important;
            text-align: center !important;
            border: 4px solid var(--midnight);
            border-radius: 24px;
            background: var(--white) !important;
            box-shadow: 6px 6px 0 var(--midnight);
        }
        .guru-empty-cell::before {
            display: none;
        }
    }
</style>

<div style="display: flex; flex-direction: column; gap: 32px; padding-bottom: 40px;" class="font-anime-body">
    
    <div class="manga-card manga-card-cosmo">
        <span style="position: absolute; top: -16px; right: 32px; background: var(--gold); color: var(--midnight); border: 3px solid var(--midnight); font-weight: 800; font-size: 12px; padding: 6px 16px; border-radius: 8px; transform: rotate(5deg); box-shadow: 3px 3px 0px var(--midnight);">
            ( ≧◡≦ ) KAWAII ✨
        </span>
        
        <span style="display: inline-block; font-weight: 800; color: var(--gold); font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 12px; background: var(--midnight); padding: 4px 12px; border-radius: 6px; box-shadow: 2px 2px 0px var(--gold);">Manajemen Sumber Daya</span>
        
        <h1 class="font-anime-title" style="font-size: 48px; margin: 0 0 16px 0; letter-spacing: 1px; text-shadow: 4px 4px 0px var(--midnight); -webkit-text-stroke: 2px var(--midnight);">DATA GURU</h1>
        
        <p style="font-weight: 700; font-size: 16px; max-width: 600px; background: var(--white); color: var(--midnight); padding: 16px; border-radius: 12px; border: 3px solid var(--midnight); box-shadow: 4px 4px 0px var(--midnight); margin: 0;">
            Kelola seluruh data guru yang terdaftar di sistem Scoola, termasuk penugasan mata pelajaran dan akun akses. 🚀
        </p>
    </div>

    <div class="fab-container">
        <a href="{{ route('guru.create') }}" class="btn-fab" title="Tambah Guru">
            <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
            <span class="fab-label">Tambah Guru Baru</span>
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 32px;">
        <div class="manga-card manga-card-gold manga-hover-effect" style="display: flex; align-items: center; gap: 24px;">
            <div style="width: 72px; height: 72px; background: var(--white); border: 4px solid var(--midnight); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 4px 4px 0px var(--midnight);">
                <i class="bi bi-person-badge" style="font-size: 32px; color: var(--midnight);"></i>
            </div>
            <div>
                <div style="font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 0.2em; background: var(--white); display: inline-block; padding: 4px 8px; border: 2px solid var(--midnight); border-radius: 6px; margin-bottom: 8px;">TOTAL DATA GURU</div>
                <div class="font-anime-title" style="font-size: 56px; color: var(--white); text-shadow: 4px 4px 0px var(--midnight); -webkit-text-stroke: 2px var(--midnight); line-height: 1;">
                    {{ $totalGuru }}
                </div>
            </div>
        </div>

        <div class="manga-card manga-card-cyber manga-hover-effect" style="display: flex; align-items: center; gap: 24px;">
            <div style="width: 72px; height: 72px; background: var(--white); border: 4px solid var(--midnight); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 4px 4px 0px var(--midnight);">
                <i class="bi bi-shield-check" style="font-size: 32px; color: var(--midnight);"></i>
            </div>
            <div>
                <div style="font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 0.2em; background: var(--white); display: inline-block; padding: 4px 8px; border: 2px solid var(--midnight); border-radius: 6px; margin-bottom: 8px;">AKSES SISTEM AKTIF</div>
                <div class="font-anime-title" style="font-size: 56px; color: var(--white); text-shadow: 4px 4px 0px var(--midnight); -webkit-text-stroke: 2px var(--midnight); line-height: 1;">
                    {{ $totalGuruAktif }}
                </div>
            </div>
        </div>
    </div>


    <form method="GET" action="{{ route('guru.index') }}" class="manga-card" style="display: flex; flex-wrap: wrap; gap: 32px; align-items: flex-end;">
        <span style="position: absolute; top: -16px; left: -16px; background: var(--sakura); color: var(--white); border: 3px solid var(--midnight); font-weight: 800; font-size: 12px; padding: 6px 12px; border-radius: 8px; transform: rotate(-5deg); box-shadow: 3px 3px 0px var(--midnight);">
            🔍 Let's Search!
        </span>

        <div style="flex: 1; min-width: 250px;">
            <label style="font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 12px; color: var(--midnight);">Pencarian Direktori</label>
            <input type="text" name="q" value="{{ request('q') }}" class="manga-input" placeholder="CARI NAMA ATAU NIP...">
        </div>
        <div style="width: 100%; max-width: 320px;">
            <label style="font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 12px; color: var(--midnight);">Spesialisasi</label>
            <select name="mapel" class="manga-input" style="cursor: pointer; appearance: none;">
                <option value="">✨ Semua Mata Pelajaran</option>
                @foreach ($allMapels as $m)
                    <option value="{{ $m->kd_mapel }}" @selected(request('mapel') === $m->kd_mapel)>{{ $m->nama_mapel }}</option>
                @endforeach
            </select>
        </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="submit" class="manga-btn-edit">Terapkan Filter</button>
            <a href="{{ route('guru.index') }}" class="manga-btn-edit" style="background: var(--white); color: var(--midnight);">Reset</a>
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
                <tbody>
                    @forelse ($guru as $g)
                    <tr>
                        <td data-label="NIP" class="guru-nip-cell" style="padding-left: 32px; font-family: monospace; font-size: 16px;"><span class="mobile-field-label">Nomor Induk</span><span class="guru-id-chip">{{ $g->NIP }}</span></td>
                        <td data-label="Nama Guru" class="guru-name-cell" style="font-weight: 800; font-size: 17px;"><span class="mobile-field-label">Profil Guru</span><span class="guru-name-text">{{ $g->nama_guru }}</span></td>
                        <td data-label="Mata Pelajaran" class="guru-mapel-cell">
                            <span class="mobile-field-label">Mata Pelajaran</span>
                            <div class="guru-mapel-list">
                                @foreach ($g->mapels as $m)
                                    <span class="manga-badge">{{ $m->nama_mapel }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td data-label="Email" class="guru-email-cell" style="color: #4A5568;"><span class="mobile-field-label">Email</span><span class="guru-email-text">{{ $g->user->email ?? '-' }}</span></td>
                        <td data-label="Aksi" class="guru-action-cell" style="padding-right: 32px;">
                            <a href="{{ route('guru.edit', $g->NIP) }}" class="manga-btn-edit">
                                Edit ✏️
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr class="guru-empty-row">
                        <td colspan="5" class="guru-empty-cell">
                            (′·_·`) Belum ada data guru ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 20px;">
            {{ $guru->links() }}
        </div>
    </div>

</div>

@endsection


