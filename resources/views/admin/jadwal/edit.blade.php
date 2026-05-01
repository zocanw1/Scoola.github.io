@extends('layouts.admin')

@section('content')

<style>
    /* ── PAGE HEADER ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header-left .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .page-header-left .page-subtitle {
        font-size: 13px;
        color: var(--text2);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── BREADCRUMB ── */
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 12.5px;
    }

    .breadcrumb-nav a {
        color: var(--text3);
        text-decoration: none;
        font-weight: 500;
        transition: color .2s;
    }

    .breadcrumb-nav a:hover { color: var(--accent); }

    .breadcrumb-nav .sep {
        color: var(--text3);
        opacity: 0.4;
        font-size: 10px;
    }

    .breadcrumb-nav .current {
        color: var(--text1);
        font-weight: 600;
    }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        max-width: 820px;
        overflow: hidden;
    }

    .form-card-header {
        padding: 20px 28px;
        border-bottom: 1px solid var(--glass-border);
        background: var(--glass);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(227,179,65,0.12);
        color: var(--amber);
        display: grid;
        place-items: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .form-card-header-text h3 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--text1);
        margin: 0;
    }

    .form-card-header-text p {
        font-size: 12px;
        color: var(--text2);
        margin: 2px 0 0;
    }

    .form-card-body {
        padding: 28px;
    }

    /* Redundant styles removed to use global shared-components.blade.php */

    /* ── ACTION BUTTONS ── */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid var(--glass-border);
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 24px;
        background: var(--gradient-accent, linear-gradient(135deg, #60a5fa, #818cf8));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .25s;
        box-shadow: 0 2px 10px rgba(96,165,250,0.25);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(96,165,250,0.4);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 11px 24px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        transition: all .25s;
    }

    .btn-cancel:hover {
        background: var(--glass-hover);
        color: var(--text1);
        border-color: var(--text3);
    }

    /* ── GRID ── */
    .grid-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 24px;
    }

    /* ── ALERTS ── */
    .alert-danger {
        background: var(--red-soft, rgba(248,81,73,0.08));
        border: 1px solid rgba(248,113,113,0.2);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        max-width: 820px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .alert-danger-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(248,113,113,0.15);
        color: var(--red);
        display: grid;
        place-items: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 16px;
        color: var(--red);
        font-size: 13px;
        line-height: 1.6;
    }

    .alert-warning-card {
        background: rgba(227,179,65,0.06);
        border: 1px solid rgba(227,179,65,0.2);
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        max-width: 820px;
    }

    .alert-warning-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .alert-warning-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(227,179,65,0.15);
        color: var(--amber);
        display: grid;
        place-items: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .alert-warning-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--amber);
    }

    .alert-warning-body {
        font-size: 13px;
        color: var(--text2);
        margin-bottom: 16px;
        padding-left: 42px;
        line-height: 1.5;
    }

    .alert-warning-actions {
        padding-left: 42px;
    }

    .btn-warning {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--amber);
        color: #1a1a2e;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all .2s;
        font-family: 'Inter', sans-serif;
    }

    .btn-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(227,179,65,0.3);
    }

    /* ── CURRENT VALUE BADGE ── */
    .current-value {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 10.5px;
        font-weight: 600;
        color: var(--accent);
        background: var(--accent-soft, rgba(96,165,250,0.08));
        padding: 3px 10px;
        border-radius: 6px;
        margin-left: auto;
        text-transform: none;
        letter-spacing: 0;
    }

    /* ── MOBILE ── */
    @media (max-width: 768px) {
        .grid-form {
            grid-template-columns: 1fr;
        }
        .form-card-body {
            padding: 20px 18px;
        }
        .form-actions {
            flex-direction: column;
        }
        .btn-submit, .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }

    /* ── ANIMATION ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fi { animation: fadeInUp .35s ease both; }
    .fi.d1 { animation-delay: .05s; }
    .fi.d2 { animation-delay: .1s; }
</style>

{{-- BREADCRUMB --}}
<div class="breadcrumb-nav fi">
    <a href="{{ route('jadwal.index') }}"><i class="bi bi-calendar3"></i> Jadwal</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <a href="{{ route('jadwal.kelas', $jadwal->kelas) }}">{{ $jadwal->kelas }}</a>
    <span class="sep"><i class="bi bi-chevron-right"></i></span>
    <span class="current">Edit Jadwal</span>
</div>

{{-- PAGE HEADER --}}
<div class="page-header fi">
    <div class="page-header-left">
        <div class="page-title">Edit Jadwal Pelajaran</div>
        <div class="page-subtitle">
            <i class="bi bi-pencil-square"></i>
            Perbarui jadwal untuk kelas {{ $jadwal->kelas }}
        </div>
    </div>
</div>

{{-- VALIDATION ERRORS --}}
@if ($errors->any())
    <div class="alert-danger fi d1">
        <div class="alert-danger-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- COLLISION WARNING --}}
@if (session('confirm_replace'))
    <div class="alert-warning-card fi d1">
        <div class="alert-warning-header">
            <div class="alert-warning-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="alert-warning-title">Peringatan Jadwal Bentrok</div>
        </div>
        <div class="alert-warning-body">
            {{ session('confirm_replace') }}
        </div>
        <div class="alert-warning-actions">
            <form action="{{ route('jadwal.update', $jadwal->kd_jp) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('PUT')
                <input type="hidden" name="hari" value="{{ old('hari') }}">
                <input type="hidden" name="kelas" value="{{ old('kelas') }}">
                <input type="hidden" name="jam_mulai" value="{{ old('jam_mulai') }}">
                <input type="hidden" name="jam_selesai" value="{{ old('jam_selesai') }}">
                <input type="hidden" name="kd_mapel" value="{{ old('kd_mapel') }}">
                <input type="hidden" name="NIP" value="{{ old('NIP') }}">
                <input type="hidden" name="force" value="1">
                <button type="submit" class="btn-warning">
                    <i class="bi bi-check-circle-fill"></i> Ya, Tetap Simpan & Ganti
                </button>
            </form>
        </div>
    </div>
@endif

{{-- FORM CARD --}}
<div class="form-card fi d2">
    <div class="form-card-header">
        <div class="form-card-header-icon"><i class="bi bi-pencil-square"></i></div>
        <div class="form-card-header-text">
            <h3>Edit Detail Jadwal</h3>
            <p>Kode Jadwal: <strong>{{ $jadwal->kd_jp }}</strong></p>
        </div>
    </div>

    <div class="form-card-body">
        <form action="{{ route('jadwal.update', $jadwal->kd_jp) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid-form">
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-calendar-event"></i> Hari
                        <span class="current-value">{{ $jadwal->hari }}</span>
                    </label>
                    <select name="hari" class="form-control" required>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                            <option value="{{ $h }}" @selected(old('hari', $jadwal->hari) == $h)>
                                {{ $h }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-building"></i> Kelas
                        <span class="current-value">{{ $jadwal->kelas }}</span>
                    </label>
                    <select name="kelas" class="form-control" required>
                        @php
                            $tingkatan = ['XI'];
                            $jurusan = 'SIJA';
                            $rombel = [1, 2];
                        @endphp
                        @foreach ($tingkatan as $t)
                            @foreach ($rombel as $r)
                                @php $kls = "$t-$jurusan $r"; @endphp
                                <option value="{{ $kls }}" @selected(old('kelas', $jadwal->kelas) == $kls)>
                                    {{ $kls }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-clock"></i> Jam Ke (Mulai)</label>
                    <input type="number"
                           name="jam_mulai"
                           class="form-control"
                           min="1"
                           max="12"
                           value="{{ old('jam_mulai', $jadwal->jam_mulai) }}"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-clock-history"></i> Jam Ke (Selesai)</label>
                    <input type="number"
                           name="jam_selesai"
                           class="form-control"
                           min="1"
                           max="12"
                           value="{{ old('jam_selesai', $jadwal->jam_selesai) }}"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-book"></i> Mata Pelajaran</label>
                    <select name="kd_mapel" class="form-control" required>
                        @foreach($mapel as $m)
                            <option value="{{ $m->kd_mapel }}" @selected(old('kd_mapel', $jadwal->kd_mapel) == $m->kd_mapel)>
                                {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-person-badge"></i> Guru Pengajar</label>
                    <select name="NIP" class="form-control" required>
                        @foreach($guru as $g)
                            <option value="{{ $g->NIP }}" @selected(old('NIP', $jadwal->NIP) == $g->NIP)>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check2-circle"></i> Simpan Perubahan
                </button>
                <a href="{{ route('jadwal.index') }}" class="btn-cancel">
                    <i class="bi bi-x-lg"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
