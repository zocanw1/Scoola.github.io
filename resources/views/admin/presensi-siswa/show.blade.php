@extends($pageLayout ?? 'layouts.admin')

@section('content')

@php
    $statusColors = [
        'Hadir' => 'var(--cyber)',
        'Izin' => '#d9f1ff',
        'Sakit' => '#efe3ff',
        'Alpa' => '#ffd8d3',
        'Belum Hadir' => 'var(--gold)',
        'Ditolak' => '#ffd9b8',
    ];
    $canCorrect = auth()->user()?->role === 'admin';
    $formatDateLabel = static function ($value): string {
        if ($value === null || $value === '') {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($value)->format('d M Y');
        } catch (\Throwable) {
            return (string) $value;
        }
    };
    $formatDateTimeLabel = static function ($value): string {
        if ($value === null || $value === '') {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($value)->format('d M Y H:i');
        } catch (\Throwable) {
            return (string) $value;
        }
    };
@endphp

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">COUNSELOR</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-person-vcard-fill"></i> Detail Kehadiran</span>
                <h1 class="mp-title">{{ $selectedSiswaDetail->nama_siswa }}</h1>
                <p class="mp-description">Halaman detail siswa untuk melihat ringkasan presensi personal dan riwayat kehadiran pada rentang tanggal yang dipilih.</p>
            </div>
        </section>
    </div>

    <section class="mp-card">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
            <div>
                <div class="mp-label">Identitas Siswa</div>
                <h2 style="margin:8px 0 6px; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:30px;">{{ $selectedSiswaDetail->nama_siswa }}</h2>
                <p style="margin:0; color:var(--midnight); font-weight:900;">{{ $selectedSiswaDetail->NIS }} &bull; {{ $selectedSiswaDetail->kelas }}</p>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <span class="mp-badge" style="background:var(--white);">{{ $formatDateLabel($tanggalMulai) }} - {{ $formatDateLabel($tanggalAkhir) }}</span>
                <a href="{{ route($pageRoute ?? 'admin.presensi-siswa.index', ['kelas' => $selectedKelas, 'q' => $search]) }}" class="mp-btn mp-btn-secondary" style="text-decoration:none;">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <form action="{{ route($pageShowRoute ?? 'admin.presensi-siswa.show', ['nis' => $selectedSiswaDetail->NIS]) }}" method="GET" class="mp-form-grid" style="align-items:end; margin-top:20px;">
            <input type="hidden" name="kelas" value="{{ $selectedSiswaDetail->kelas }}">
            <input type="hidden" name="q" value="{{ $search }}">

            <div class="mp-field" style="margin-bottom:0;">
                <label class="mp-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="mp-input">
            </div>

            <div class="mp-field" style="margin-bottom:0;">
                <label class="mp-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="mp-input">
            </div>

            <div class="mp-actions" style="border-top:0; padding-top:0; margin-top:0;">
                <button type="submit" class="mp-btn"><i class="bi bi-funnel"></i> Terapkan</button>
            </div>
        </form>
    </section>

    <section class="mp-card mp-card-gold">
        <div class="mp-touch-grid" style="grid-template-columns:repeat(auto-fit, minmax(130px, 1fr));">
            @foreach($detailTotals as $label => $count)
                <div class="mp-card" style="padding:16px; box-shadow:4px 4px 0 var(--midnight);">
                    <div class="mp-label">{{ $label }}</div>
                    <div style="font-family:'Fredoka One', cursive; font-size:26px; color:var(--midnight);">{{ $count }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mp-table-card mp-desktop-only">
        <div style="padding:24px 30px; background:var(--gold); border-bottom:4px solid var(--midnight);">
            <div class="mp-label" style="margin-bottom:6px;">Riwayat Presensi</div>
            <div style="font-family:'Fredoka One', cursive; font-size:24px; color:var(--midnight);">{{ $selectedSiswaDetail->nama_siswa }}</div>
        </div>

        <div class="mp-table-wrap" style="padding:24px;">
            <table style="width:100%; min-width:980px; border-collapse:collapse; font-size:13px; font-family:'Nunito', sans-serif; color:var(--midnight);">
                <thead>
                    <tr>
                        <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">NO</th>
                        <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">TANGGAL</th>
                        <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">HARI</th>
                        <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">JAM</th>
                        <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber); text-align:left;">MAPEL</th>
                        <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber); text-align:left;">GURU</th>
                        <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">JAM MASUK</th>
                        <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber);">STATUS</th>
                        <th style="border:2px solid var(--midnight); padding:10px; background:var(--cyber); text-align:left;">KOREKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailRows as $index => $row)
                        <tr>
                            <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ $index + 1 }}</td>
                            <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ $row['tanggal_label'] ?? $formatDateLabel($row['tanggal'] ?? null) }}</td>
                            <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ $row['hari'] }}</td>
                            <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ $row['jam'] }}</td>
                            <td style="border:2px solid var(--midnight); padding:10px;">{{ $row['mapel'] }}</td>
                            <td style="border:2px solid var(--midnight); padding:10px;">{{ $row['guru'] }}</td>
                            <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">{{ $row['jam_masuk'] }}</td>
                            <td style="border:2px solid var(--midnight); padding:10px; text-align:center;">
                                <span class="mp-badge" style="background:{{ $statusColors[$row['status']] ?? 'var(--white)' }};">{{ $row['status'] }}</span>
                            </td>
                            <td style="border:2px solid var(--midnight); padding:10px; min-width:280px;">
                                @if($canCorrect)
                                    <form action="{{ route('admin.presensi-siswa.update-status', ['nis' => $row['nis']]) }}" method="POST" style="display:grid; gap:8px;">
                                        @csrf
                                        <input type="hidden" name="presensi_id" value="{{ $row['presensi_id'] }}">
                                        <input type="hidden" name="sesi_id" value="{{ $row['sesi_id'] }}">
                                        <input type="hidden" name="kelas" value="{{ $selectedSiswaDetail->kelas }}">
                                        <input type="hidden" name="q" value="{{ $search }}">
                                        <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                                        <input type="hidden" name="tanggal_akhir" value="{{ $tanggalAkhir }}">

                                        <select name="status" class="mp-input" style="min-height:48px;">
                                            @foreach($statusOptions as $statusOption)
                                                <option value="{{ $statusOption }}" {{ $row['status'] === $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                                            @endforeach
                                        </select>
                                        <textarea name="correction_reason" class="mp-input" rows="3" placeholder="Alasan wajib, misalnya surat orang tua atau surat dokter." required>{{ old('correction_reason') }}</textarea>
                                        <button type="submit" class="mp-btn"><i class="bi bi-save"></i> Simpan Koreksi</button>
                                    </form>
                                @endif

                                @if($row['latest_correction_reason'])
                                    <div style="margin-top:{{ $canCorrect ? '12px' : '0' }}; padding:10px 12px; border:2px solid var(--midnight); background:#fff7d1;">
                                        <div class="mp-label">Riwayat Koreksi</div>
                                        <div style="margin-top:6px; font-weight:900; color:var(--midnight);">{{ $row['latest_correction_reason'] }}</div>
                                        <div style="margin-top:6px; color:var(--midnight); font-weight:800;">
                                            {{ $row['latest_correction_by'] }} &bull; {{ $formatDateTimeLabel($row['latest_correction_at']) }}
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="border:2px solid var(--midnight); padding:24px; text-align:center; font-weight:900;">Belum ada data presensi pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mp-mobile-only mp-stack-list">
        @forelse($detailRows as $row)
            <section class="mp-card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                    <div>
                        <span class="mp-label">{{ $row['hari'] }}, {{ $row['tanggal_label'] ?? $formatDateLabel($row['tanggal'] ?? null) }}</span>
                        <h3 style="margin:6px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:24px;">{{ $row['mapel'] }}</h3>
                    </div>
                    <span class="mp-badge" style="background:{{ $statusColors[$row['status']] ?? 'var(--white)' }};">{{ $row['status'] }}</span>
                </div>
                <div style="display:grid; gap:8px; margin-top:14px; color:var(--midnight); font-weight:900;">
                    <div>{{ $row['jam'] }} &bull; Masuk: {{ $row['jam_masuk'] }}</div>
                    <div>{{ $row['guru'] }}</div>
                </div>
                @if($canCorrect)
                    <form action="{{ route('admin.presensi-siswa.update-status', ['nis' => $row['nis']]) }}" method="POST" style="display:grid; gap:10px; margin-top:14px;">
                        @csrf
                        <input type="hidden" name="presensi_id" value="{{ $row['presensi_id'] }}">
                        <input type="hidden" name="sesi_id" value="{{ $row['sesi_id'] }}">
                        <input type="hidden" name="kelas" value="{{ $selectedSiswaDetail->kelas }}">
                        <input type="hidden" name="q" value="{{ $search }}">
                        <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                        <input type="hidden" name="tanggal_akhir" value="{{ $tanggalAkhir }}">

                        <select name="status" class="mp-input" style="min-height:48px;">
                            @foreach($statusOptions as $statusOption)
                                <option value="{{ $statusOption }}" {{ $row['status'] === $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                            @endforeach
                        </select>
                        <textarea name="correction_reason" class="mp-input" rows="3" placeholder="Alasan wajib." required>{{ old('correction_reason') }}</textarea>
                        <button type="submit" class="mp-btn"><i class="bi bi-save"></i> Simpan Koreksi</button>
                    </form>
                @endif
                @if($row['latest_correction_reason'])
                    <div style="margin-top:14px; padding:12px; border:2px solid var(--midnight); background:#fff7d1;">
                        <div class="mp-label">Riwayat Koreksi</div>
                        <div style="margin-top:6px; font-weight:900; color:var(--midnight);">{{ $row['latest_correction_reason'] }}</div>
                        <div style="margin-top:6px; color:var(--midnight); font-weight:800;">
                            {{ $row['latest_correction_by'] }} &bull; {{ $formatDateTimeLabel($row['latest_correction_at']) }}
                        </div>
                    </div>
                @endif
            </section>
        @empty
            <section class="mp-empty-state">
                <div style="font-family:'Fredoka One', cursive; font-size:22px; color:var(--midnight);">Belum ada data</div>
                <p style="margin:10px 0 0; font-weight:800;">Tidak ada presensi pada rentang tanggal ini.</p>
            </section>
        @endforelse
    </div>
</div>

@endsection
