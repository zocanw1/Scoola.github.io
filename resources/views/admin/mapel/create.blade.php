@extends('layouts.admin')

@section('content')

<style>
/* ── Page Header ── */
.page-header {
    margin-bottom: 24px;
}

.breadcrumb-scoola {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--text3);
    margin-bottom: 8px;
}

.breadcrumb-scoola a { color: var(--text3); text-decoration: none; transition: color 0.2s; }
.breadcrumb-scoola a:hover { color: var(--accent); }

.ph-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px; font-weight: 800;
    color: var(--text1);
    letter-spacing: -0.02em;
}

/* ── Form Card ── */
.form-card {
    background: var(--navy2);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 32px;
    max-width: 600px;
}

.fc-title {
    font-size: 16px; font-weight: 700;
    color: var(--text1);
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 24px;
}

.form-group { margin-bottom: 20px; }

.label {
    display: block;
    font-size: 11px; font-weight: 700;
    color: var(--text2);
    text-transform: uppercase; letter-spacing: 0.05em;
    margin-bottom: 8px;
}

.input {
    width: 100%;
    background: var(--navy3);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    padding: 12px 14px;
    color: var(--text1);
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
}

.input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.15);
}

.input.error { border-color: var(--red); }

.error-msg {
    color: var(--red);
    font-size: 11.5px;
    margin-top: 5px;
    display: flex; align-items: center; gap: 5px;
}

/* ── Buttons ── */
.form-actions {
    display: flex; gap: 12px;
    margin-top: 32px;
    border-top: 1px solid var(--glass-border);
    padding-top: 24px;
}

.btn-primary-scoola {
    background: var(--accent);
    color: var(--navy);
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 14px;
    display: flex; align-items: center; gap: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary-scoola:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(88, 166, 255, 0.4); }

.btn-secondary-scoola {
    background: var(--navy3);
    color: var(--text1);
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    border: 1px solid var(--glass-border);
    transition: all 0.2s;
}

.btn-secondary-scoola:hover { background: var(--glass-hover); border-color: var(--text3); }

/* ── Animation ── */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fi { animation: fadeInUp 0.5s ease both; }

</style>

<div class="page-header fi" style="animation-delay: 0.1s;">
    <div class="breadcrumb-scoola">
        <a href="/admin/dashboard">Admin</a>
        <i class="bi bi-chevron-right"></i>
        <a href="{{ route('mapel.index') }}">Mata Pelajaran</a>
        <i class="bi bi-chevron-right"></i>
        <span>Tambah Baru</span>
    </div>
    <h2 class="ph-title">Tambah Mata Pelajaran</h2>
</div>

<div class="form-card fi" style="animation-delay: 0.2s;">
    <div class="fc-title">
        <i class="bi bi-plus-circle-dotted" style="color:var(--accent); font-size: 20px;"></i>
        Form Baru Mata Pelajaran
    </div>

    <form action="{{ route('mapel.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label class="label">Kode Mata Pelajaran</label>
            <input type="text" name="kd_mapel" class="input @error('kd_mapel') error @enderror" 
                   placeholder="e.g. MP-001" value="{{ old('kd_mapel') }}" required autofocus>
            @error('kd_mapel')
                <div class="error-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
            <small style="color: var(--text3); margin-top: 6px; display: block; font-size: 11px;">
                * Gunakan kode unik yang berbeda dengan mapel lainnya.
            </small>
        </div>

        <div class="form-group">
            <label class="label">Nama Mata Pelajaran</label>
            <input type="text" name="nama_mapel" class="input @error('nama_mapel') error @enderror" 
                   placeholder="e.g. Matematika Wajib" value="{{ old('nama_mapel') }}" required>
            @error('nama_mapel')
                <div class="error-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary-scoola">
                <i class="bi bi-check-lg"></i> Simpan Mapel
            </button>
            <a href="{{ route('mapel.index') }}" class="btn-secondary-scoola">Batal</a>
        </div>
    </form>
</div>

@endsection
