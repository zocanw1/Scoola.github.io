@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">ATTENDANCE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-file-earmark-spreadsheet"></i> Manajemen Presensi</span>
                <h1 class="mp-title">Rekap Mingguan</h1>
                <p class="mp-description">
                    Pilih kelas dan tanggal untuk melihat matriks kehadiran Senin sampai Jumat.
                    @if(isset($startOfWeek) && isset($endOfWeek))
                        Minggu: {{ $startOfWeek->format('d M Y') }} - {{ $endOfWeek->format('d M Y') }}.
                    @endif
                </p>
            </div>
        </section>
    </div>

    <section class="mp-card">
        <form action="{{ route('admin.rekap.index') }}" method="GET" class="mp-form-grid" style="align-items: end;">
            <div class="mp-field" style="margin-bottom: 0;">
                <label class="mp-label">Kelas</label>
                <select name="kelas" class="mp-input">
                    <option value="">PILIH KELAS</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->nama_kelas }}" {{ $selectedKelas == $k->nama_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mp-field" style="margin-bottom: 0;">
                <label class="mp-label">Pilih Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggalInput ?? now()->toDateString() }}" class="mp-input">
            </div>

            <div class="mp-actions" style="border-top: 0; padding-top: 0; margin-top: 0;">
                <button type="submit" class="mp-btn"><i class="bi bi-search"></i> Tampilkan</button>
                @if($selectedKelas)
                    <button type="submit" formaction="{{ route('admin.rekap.export') }}" formmethod="GET" class="mp-btn mp-btn-green">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </button>
                @endif
            </div>
        </form>
    </section>

    @if(session('error'))
        <div class="mp-alert danger">{{ session('error') }}</div>
    @endif

    @if($selectedKelas)
        <section class="mp-table-card">
            <div style="padding: 24px 30px; background: var(--gold); border-bottom: 4px solid var(--midnight);">
                <div class="mp-label" style="margin-bottom: 6px;">Matriks Presensi</div>
                <div style="font-family: 'Fredoka One', cursive; font-size: 24px; color: var(--midnight);">{{ $selectedKelas }}</div>
            </div>

            <div class="mp-table-wrap" style="padding: 24px;">
                <table style="width: 100%; min-width: 1200px; border-collapse: collapse; font-size: 12px; font-family: 'Nunito', sans-serif; text-align: center; color: var(--midnight);">
                    <thead>
                        <tr>
                            <th rowspan="3" style="border: 2px solid var(--midnight); padding: 8px; background: var(--cyber);">NO</th>
                            <th rowspan="3" style="border: 2px solid var(--midnight); padding: 8px; background: var(--cyber);">NIS</th>
                            <th rowspan="3" style="border: 2px solid var(--midnight); padding: 8px; background: var(--cyber); min-width: 200px; text-align: left;">NAMA SISWA</th>
                            <th rowspan="3" style="border: 2px solid var(--midnight); padding: 8px; background: var(--cyber);">L/P</th>
                            @foreach($hariList as $hari)
                                <th colspan="12" style="border: 2px solid var(--midnight); padding: 8px; background: var(--gold); font-weight: 900; text-transform: uppercase;">
                                    {{ $hari }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($hariList as $hari)
                                @for($i = 1; $i <= 12; $i++)
                                    <th style="border: 2px solid var(--midnight); padding: 4px; background: var(--mochi);">{{ $i }}</th>
                                @endfor
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($hariList as $hari)
                                @for($i = 1; $i <= 12; $i++)
                                    @php
                                        $slot = $slotMatrix[$hari][$i] ?? ['mapel' => '-', 'guru' => '-'];
                                    @endphp
                                    <th style="border: 2px solid var(--midnight); padding: 4px; font-size: 10px; background: var(--white); font-weight: 800; vertical-align: top; width: 45px;">
                                        <div style="writing-mode: vertical-rl; text-orientation: mixed; height: 120px; margin: 0 auto; line-height: 1.2;">
                                            <strong>{{ $slot['mapel'] }}</strong><br>
                                            <span style="font-size: 9px;">{{ $slot['guru'] }}</span>
                                        </div>
                                    </th>
                                @endfor
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $index => $siswa)
                            <tr>
                                <td style="border: 2px solid var(--midnight); padding: 6px;">{{ $index + 1 }}</td>
                                <td style="border: 2px solid var(--midnight); padding: 6px;">{{ $siswa->NIS }}</td>
                                <td style="border: 2px solid var(--midnight); padding: 6px; text-align: left;">{{ $siswa->nama_siswa }}</td>
                                <td style="border: 2px solid var(--midnight); padding: 6px;">{{ $siswa->jenis_kelamin ?? 'L' }}</td>

                                @foreach($hariList as $hari)
                                    @for($i = 1; $i <= 12; $i++)
                                        @php
                                            $statusBadge = match($statusMatrix[$siswa->NIS][$hari][$i] ?? null) {
                                                'Hadir' => ['H', '#107c41'],
                                                'Izin' => ['I', '#0f6cbd'],
                                                'Sakit' => ['S', '#8e44ad'],
                                                'Alpa' => ['A', '#c0392b'],
                                                'Ditolak' => ['D', '#d35400'],
                                                'Belum Hadir' => ['B', '#7f8c8d'],
                                                default => [null, null],
                                            };
                                        @endphp
                                        <td style="border: 2px solid var(--midnight); padding: 6px; text-align: center; vertical-align: middle;">
                                            @if($statusBadge[0])
                                                <span style="color: {{ $statusBadge[1] }}; font-weight: 900; font-size: 14px;">{{ $statusBadge[0] }}</span>
                                            @endif
                                        </td>
                                    @endfor
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + (count($hariList) * 12) }}" style="border: 2px solid var(--midnight); padding: 24px; text-align: center;">Belum ada data siswa di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @else
        <section class="mp-empty-state">
            <div class="mp-stat-icon" style="margin: 0 auto 20px;"><i class="bi bi-table"></i></div>
            <h3 style="font-family: 'Fredoka One', cursive; color: var(--midnight); margin: 0 0 10px;">Pilih Kelas</h3>
            <p style="margin: 0; color: var(--midnight); font-weight: 800;">Silakan pilih kelas dari panel filter untuk menampilkan matriks rekap mingguan.</p>
        </section>
    @endif
</div>

@endsection
