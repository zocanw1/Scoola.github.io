@extends('layouts.guru')

@section('content')

<style>
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: 12px;
        color: var(--text2);
        margin-top: 4px;
    }

    .form-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 24px;
        max-width: 500px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--text2);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .form-control {
        width: 100%;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 8px;
        padding: 10px 14px;
        color: var(--text1);
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        transition: all .2s;
        outline: none;
    }

    .form-control:focus {
        border-color: rgba(88,166,255,.4);
        box-shadow: 0 0 0 3px rgba(88,166,255,.1);
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%238b949e' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 12px 12px;
        padding-right: 40px;
        cursor: pointer;
    }
    
    [data-theme="light"] select.form-control {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2357606a' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--glass-border);
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        background: var(--accent);
        color: var(--navy);
        border: none;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all .2s;
        width: 100%;
        justify-content: center;
    }

    .btn-submit:hover {
        background: #79baff;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(88,166,255,0.3);
    }
</style>

<div class="page-header">
    <div class="page-title">Mulai Mengajar</div>
    <div class="page-subtitle">Pilih kelas yang akan diajar untuk meng-generate kode presensi</div>
</div>

<div class="form-card fi">
    <form method="POST" action="{{ route('guru.presensi.buka') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Kelas Yang Diajar</label>
            <select name="kelas" class="form-control" required>
                <option value="" disabled selected>-- Pilih Kelas --</option>
                @foreach($kelasList as $k)
                    @php
                        $activeInThisClass = $allActiveSessions->get($k);
                    @endphp
                    <option value="{{ $k }}" style="{{ $activeInThisClass ? 'color: var(--accent);' : '' }}">
                        {{ $k }} 
                        @if($activeInThisClass)
                            (Aktif: {{ $activeInThisClass->map(fn($s) => $s->guru->name)->join(', ') }})
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="bi bi-door-open-fill"></i> Buka Ruang Kelas
            </button>
        </div>
    </form>
</div>

@endsection
