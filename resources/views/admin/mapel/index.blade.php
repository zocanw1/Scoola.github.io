@extends('layouts.admin')

@section('content')

<style>
/* ── Page Header ── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap;
}

.ph-left h2 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px; font-weight: 800;
    color: var(--text1);
    letter-spacing: -0.02em;
    margin-bottom: 4px;
}

.ph-left p { font-size: 13px; color: var(--text2); }

/* ── Stats Row ── */
.mapel-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--navy2);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s;
}

.stat-card:hover { border-color: rgba(88,166,255,0.3); transform: translateY(-2px); }

.sc-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: grid; place-items: center;
    font-size: 20px;
}

.sc-blue { background: rgba(88,166,255,0.1); color: var(--accent); }
.sc-purple { background: rgba(188,140,255,0.1); color: var(--purple); }

.sc-val {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px; font-weight: 800;
    color: var(--text1);
    line-height: 1;
}

.sc-lbl { font-size: 12px; color: var(--text2); margin-top: 4px; }

/* ── Table Card ── */
.table-card {
    background: var(--navy2);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    overflow: hidden;
}

.tc-header {
    padding: 20px;
    border-bottom: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.tc-title {
    font-size: 14px; font-weight: 700;
    color: var(--text1);
    display: flex; align-items: center; gap: 10px;
}

.tc-search {
    position: relative; width: 240px;
}

.tc-search input {
    width: 100%;
    background: var(--navy3);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    padding: 8px 12px 8px 36px;
    color: var(--text1);
    font-size: 13px;
    outline: none;
}

.tc-search i {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%);
    color: var(--text3); font-size: 14px;
}

/* ── Custom Table ── */
.custom-table { width: 100%; border-collapse: collapse; }
.custom-table th {
    background: var(--glass);
    padding: 12px 20px;
    text-align: left;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.05em;
    color: var(--text3);
    border-bottom: 1px solid var(--glass-border);
}

.custom-table td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--text2);
    border-bottom: 1px solid var(--glass-border);
}

.custom-table tr:hover td { background: var(--glass-hover); }

.td-code { font-family: 'Mono', monospace; font-weight: 600; color: var(--accent); }
.td-name { font-weight: 600; color: var(--text1); }

/* ── Actions ── */
.action-btns { display: flex; gap: 8px; }

.btn-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    display: grid; place-items: center;
    font-size: 14px;
    transition: all 0.2s;
    border: 1px solid var(--glass-border);
    cursor: pointer;
    background: var(--navy3);
    color: var(--text2);
    text-decoration: none;
}

.btn-edit:hover { background: rgba(88,166,255,0.1); color: var(--accent); border-color: var(--accent); }

/* ── Primary Button ── */
.btn-primary-scoola {
    background: var(--accent);
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 13.5px;
    display: flex; align-items: center; gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
}

.btn-primary-scoola:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(88,166,255,0.4); color: #fff; }

/* ── Animations ── */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fi { animation: fadeInUp 0.5s ease both; }
.d1 { animation-delay: 0.1s; }
.d2 { animation-delay: 0.2s; }
.d3 { animation-delay: 0.3s; }

</style>

<div class="page-header fi d1">
    <div class="ph-left">
        <h2>Mata Pelajaran</h2>
        <p>Kelola data mata pelajaran yang diajarkan di sekolah</p>
    </div>
    <a href="{{ route('mapel.create') }}" class="btn-primary-scoola">
        <i class="bi bi-plus-lg"></i> Tambah Mapel
    </a>
</div>

<div class="mapel-stats fi d2">
    <div class="stat-card">
        <div class="sc-icon sc-blue"><i class="bi bi-book-half"></i></div>
        <div>
            <div class="sc-val">{{ $mapel->count() }}</div>
            <div class="sc-lbl">Total Mapel</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="sc-icon sc-purple"><i class="bi bi-person-badge"></i></div>
        <div>
            <div class="sc-val">{{ App\Models\Guru::count() }}</div>
            <div class="sc-lbl">Total Pengajar</div>
        </div>
    </div>
</div>

<div class="table-card fi d3">
    <div class="tc-header">
        <div class="tc-title">
            <i class="bi bi-list-stars" style="color:var(--accent); font-size: 18px;"></i>
            Daftar Mata Pelajaran
        </div>
        <div class="tc-search">
            <i class="bi bi-search"></i>
            <input type="text" id="mapelSearch" placeholder="Cari Mata Pelajaran...">
        </div>
    </div>
    <div style="overflow-x: auto;">
        <table class="custom-table" id="mapelTable">
            <thead>
                <tr>
                    <th style="width: 80px;">No</th>
                    <th style="width: 150px;">Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th style="width: 120px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mapel as $key => $m)
                <tr class="mapel-row">
                    <td>{{ $key + 1 }}</td>
                    <td class="td-code">{{ $m->kd_mapel }}</td>
                    <td class="td-name">{{ $m->nama_mapel }}</td>
                    <td>
                        <div class="action-btns" style="justify-content: center;">
                            <a href="{{ route('mapel.edit', $m->kd_mapel) }}" class="btn-icon btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px; color: var(--text3);">
                        <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                        Belum ada data mata pelajaran.
                    </td>
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

@endsection
