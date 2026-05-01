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
        background: var(--accent-soft);
        color: var(--accent);
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

    /* ── FIELD HINTS ── */
    .form-hint {
        font-size: 11px;
        color: var(--text3);
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
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
    @if(request('kelas'))
        <a href="{{ route('jadwal.kelas', request('kelas')) }}">{{ request('kelas') }}</a>
        <span class="sep"><i class="bi bi-chevron-right"></i></span>
    @endif
    <span class="current">Tambah Jadwal</span>
</div>

{{-- PAGE HEADER --}}
<div class="page-header fi">
    <div class="page-header-left">
        <div class="page-title">Tambah Jadwal Baru</div>
        <div class="page-subtitle">
            <i class="bi bi-info-circle"></i>
            Atur jadwal pelajaran baru untuk kelas yang dipilih
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
            <form action="{{ route('jadwal.store') }}" method="POST" style="display: inline-block;">
                @csrf
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
        <div class="form-card-header-icon"><i class="bi bi-calendar-plus"></i></div>
        <div class="form-card-header-text">
            <h3>Detail Jadwal</h3>
            <p>Lengkapi informasi jadwal pelajaran di bawah ini</p>
        </div>
    </div>

    <div class="form-card-body">
        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf

            <div class="grid-form">
                <div class="form-group">
                    <label class="form-label"><i class="bi bi-calendar-event"></i> Hari</label>
                    <select name="hari" class="form-control" required>
                        <option value="" disabled {{ old('hari', request('hari')) ? '' : 'selected' }}>-- Pilih Hari --</option>
                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                            <option value="{{ $h }}" {{ old('hari', request('hari')) == $h ? 'selected' : '' }}>
                                {{ $h }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-building"></i> Kelas</label>
                    <select name="kelas" class="form-control" required>
                        <option value="" disabled {{ old('kelas', request('kelas')) ? '' : 'selected' }}>-- Pilih Kelas --</option>
                        @php
                            $tingkatan = ['XI'];
                            $jurusan = 'SIJA';
                            $rombel = [1, 2];
                        @endphp
                        @foreach ($tingkatan as $t)
                            @foreach ($rombel as $r)
                                @php $kls = "$t-$jurusan $r"; @endphp
                                <option value="{{ $kls }}" {{ old('kelas', request('kelas')) == $kls ? 'selected' : '' }}>
                                    {{ $kls }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-clock"></i> Jam Ke (Mulai)</label>
                    <input type="number" name="jam_mulai" class="form-control" min="1" max="12" value="{{ old('jam_mulai') }}" required placeholder="Contoh: 1">
                    <div class="form-hint"><i class="bi bi-info-circle"></i> Masukkan jam ke-1 sampai ke-12</div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-clock-history"></i> Jam Ke (Selesai)</label>
                    <input type="number" name="jam_selesai" class="form-control" min="1" max="12" value="{{ old('jam_selesai') }}" required placeholder="Contoh: 3">
                    <div class="form-hint"><i class="bi bi-info-circle"></i> Harus sama atau lebih besar dari jam mulai</div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-book"></i> Mata Pelajaran</label>
                    <select name="kd_mapel" class="form-control" required>
                        <option value="" disabled {{ old('kd_mapel') ? '' : 'selected' }}>-- Pilih Mata Pelajaran --</option>
                        @foreach ($mapel as $m)
                            <option value="{{ $m->kd_mapel }}" {{ old('kd_mapel') == $m->kd_mapel ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="bi bi-person-badge"></i> Guru Pengajar</label>
                    <select name="NIP" class="form-control" required>
                        <option value="" disabled {{ old('NIP') ? '' : 'selected' }}>-- Pilih Guru --</option>
                        @foreach ($guru as $g)
                            <option value="{{ $g->NIP }}" {{ old('NIP') == $g->NIP ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check2-circle"></i> Simpan Jadwal
                </button>
                @php
                    $cancelUrl = route('jadwal.index');
                    if (request('kelas') && request('hari')) {
                        $cancelUrl = route('jadwal.kelas', ['kelas' => request('kelas'), 'hari' => request('hari')]);
                    } elseif (request('kelas')) {
                        $cancelUrl = route('jadwal.kelas', request('kelas'));
                    }
                @endphp
                <a href="{{ $cancelUrl }}" class="btn-cancel">
                    <i class="bi bi-x-lg"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapelSelect = document.querySelector('select[name="kd_mapel"]');
    const guruSelect = document.querySelector('select[name="NIP"]');
    
    if (mapelSelect && guruSelect) {
        mapelSelect.addEventListener('change', function() {
            const kdMapel = this.value;
            if (!kdMapel) return;

            // Clear current guru options
            guruSelect.innerHTML = '<option value="" disabled selected>-- Memuat Guru... --</option>';
            guruSelect.disabled = true;

            // Fetch gurus for selected mapel
            fetch(`{{ url('admin/jadwal/get-guru-by-mapel') }}/${kdMapel}`)
                .then(response => response.json())
                .then(data => {
                    guruSelect.innerHTML = '<option value="" disabled selected>-- Pilih Guru --</option>';
                    
                    if (data.length === 0) {
                        guruSelect.innerHTML = '<option value="" disabled selected>-- Tidak ada guru untuk mapel ini --</option>';
                    } else {
                        data.forEach(guru => {
                            const option = document.createElement('option');
                            option.value = guru.NIP;
                            option.textContent = guru.nama_guru;
                            guruSelect.appendChild(option);
                        });
                    }
                    guruSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching guru:', error);
                    guruSelect.innerHTML = '<option value="" disabled selected>-- Gagal memuat data --</option>';
                });
        });
    }
});
</script>
@endpush

@endsection
