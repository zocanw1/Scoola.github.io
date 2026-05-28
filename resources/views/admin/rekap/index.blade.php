@extends('layouts.admin')

@section('content')

@php
    $activeMode = $mode ?? 'mingguan';
    $statusColors = [
        'Hadir' => 'var(--cyber)',
        'Izin' => '#d9f1ff',
        'Sakit' => '#efe3ff',
        'Alpa' => '#ffd8d3',
        'Belum Hadir' => 'var(--gold)',
        'Ditolak' => '#ffd9b8',
    ];
@endphp

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">ATTENDANCE</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-file-earmark-spreadsheet"></i> Manajemen Presensi</span>
                <h1 class="mp-title">{{ $activeMode === 'siswa' ? 'Rekap Per Siswa' : 'Rekap Mingguan' }}</h1>
                <p class="mp-description">
                    @if($activeMode === 'siswa')
                        Pilih kelas, siswa, dan rentang tanggal untuk melihat riwayat presensi personal.
                    @else
                        Pilih kelas dan tanggal untuk melihat matriks kehadiran Senin sampai Jumat.
                        @if(isset($startOfWeek) && isset($endOfWeek))
                            Minggu: {{ $startOfWeek->format('d M Y') }} - {{ $endOfWeek->format('d M Y') }}.
                        @endif
                    @endif
                </p>
            </div>
        </section>
    </div>

    <section class="mp-card">
        <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:22px;">
            <a href="{{ route('admin.rekap.index', ['mode' => 'mingguan', 'kelas' => $selectedKelas, 'tanggal' => $tanggalInput ?? now()->toDateString()]) }}"
               class="mp-btn {{ $activeMode === 'mingguan' ? '' : 'mp-btn-secondary' }}"
               style="text-decoration:none;">
                <i class="bi bi-calendar-week"></i> Rekap Mingguan
            </a>
            <a href="{{ route('admin.rekap.index', ['mode' => 'siswa', 'kelas' => $selectedKelas, 'nis' => $selectedNis, 'tanggal_mulai' => $tanggalMulai ?? now()->startOfMonth()->toDateString(), 'tanggal_akhir' => $tanggalAkhir ?? now()->toDateString()]) }}"
               class="mp-btn {{ $activeMode === 'siswa' ? '' : 'mp-btn-secondary' }}"
               style="text-decoration:none;">
                <i class="bi bi-person-vcard"></i> Rekap Per Siswa
            </a>
        </div>

        <form action="{{ route('admin.rekap.index') }}" method="GET" class="mp-form-grid" style="align-items: end;">
            <input type="hidden" name="mode" value="{{ $activeMode }}">

            <div class="mp-field" style="margin-bottom: 0;">
                <label class="mp-label">Kelas</label>
                <select name="kelas" class="mp-input">
                    <option value="">PILIH KELAS</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->nama_kelas }}" {{ $selectedKelas == $k->nama_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            @if($activeMode === 'siswa')
                <div class="mp-field" style="margin-bottom: 0;">
                    <label class="mp-label">Siswa</label>
                    <select name="nis" class="mp-input">
                        <option value="">PILIH SISWA</option>
                        @foreach($siswaOptions as $siswaOption)
                            <option value="{{ $siswaOption->NIS }}" {{ $selectedNis == $siswaOption->NIS ? 'selected' : '' }}>
                                {{ $siswaOption->nama_siswa }} ({{ $siswaOption->NIS }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mp-field" style="margin-bottom: 0;">
                    <label class="mp-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai ?? now()->startOfMonth()->toDateString() }}" class="mp-input">
                </div>

                <div class="mp-field" style="margin-bottom: 0;">
                    <label class="mp-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir ?? now()->toDateString() }}" class="mp-input">
                </div>
            @else
                <div class="mp-field" style="margin-bottom: 0;">
                    <label class="mp-label">Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggalInput ?? now()->toDateString() }}" class="mp-input">
                </div>
            @endif

            <div class="mp-actions" style="border-top: 0; padding-top: 0; margin-top: 0;">
                <button type="submit" class="mp-btn"><i class="bi bi-search"></i> Tampilkan</button>
                @if(($activeMode === 'mingguan' && $selectedKelas) || ($activeMode === 'siswa' && $selectedKelas && $selectedNis))
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

    @if($activeMode === 'siswa')
        @if(! $selectedKelas)
            <section class="mp-empty-state">
                <div class="mp-stat-icon" style="margin: 0 auto 20px;"><i class="bi bi-people"></i></div>
                <h3 style="font-family: 'Fredoka One', cursive; color: var(--midnight); margin: 0 0 10px;">Pilih Kelas</h3>
                <p style="margin: 0; color: var(--midnight); font-weight: 800;">Pilih kelas terlebih dahulu untuk membuka daftar siswa.</p>
            </section>
        @elseif(! $selectedNis)
            <section class="mp-empty-state">
                <div class="mp-stat-icon" style="margin: 0 auto 20px;"><i class="bi bi-person-check"></i></div>
                <h3 style="font-family: 'Fredoka One', cursive; color: var(--midnight); margin: 0 0 10px;">Pilih Siswa</h3>
                <p style="margin: 0; color: var(--midnight); font-weight: 800;">Pilih siswa untuk melihat rekap personal dalam rentang tanggal.</p>
            </section>
        @elseif(! $selectedSiswa)
            <section class="mp-empty-state">
                <div class="mp-stat-icon" style="margin: 0 auto 20px;"><i class="bi bi-exclamation-triangle"></i></div>
                <h3 style="font-family: 'Fredoka One', cursive; color: var(--midnight); margin: 0 0 10px;">Siswa Tidak Ditemukan</h3>
                <p style="margin: 0; color: var(--midnight); font-weight: 800;">Siswa yang dipilih tidak ada pada kelas ini.</p>
            </section>
        @else
            <div class="rekap-student-board">
                <section class="mp-card mp-card-gold">
                    <span class="mp-label">Rekap Per Siswa</span>
                    <h2 style="margin:8px 0 6px; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:30px;">{{ $selectedSiswa->nama_siswa }}</h2>
                    <p style="margin:0; color:var(--midnight); font-weight:900;">{{ $selectedSiswa->NIS }} &bull; {{ $selectedSiswa->kelas }} &bull; {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}</p>

                    <div class="mp-touch-grid" style="grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); margin-top:18px;">
                        @foreach($studentTotals as $label => $count)
                            <div class="mp-card" style="padding:16px; box-shadow:4px 4px 0 var(--midnight);">
                                <div class="mp-label">{{ $label }}</div>
                                <div style="font-family:'Fredoka One', cursive; font-size:26px; color:var(--midnight);">{{ $count }}</div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="mp-table-card mp-desktop-only">
                    <div style="padding: 24px 30px; background: var(--gold); border-bottom: 4px solid var(--midnight);">
                        <div class="mp-label" style="margin-bottom: 6px;">Riwayat Presensi</div>
                        <div style="font-family: 'Fredoka One', cursive; font-size: 24px; color: var(--midnight);">{{ $selectedSiswa->nama_siswa }}</div>
                    </div>

                    <div class="mp-table-wrap" style="padding: 24px;">
                        <table style="width: 100%; min-width: 980px; border-collapse: collapse; font-size: 13px; font-family: 'Nunito', sans-serif; color: var(--midnight);">
                            <thead>
                                <tr>
                                    <th style="border: 2px solid var(--midnight); padding: 10px; background: var(--cyber);">NO</th>
                                    <th style="border: 2px solid var(--midnight); padding: 10px; background: var(--cyber);">TANGGAL</th>
                                    <th style="border: 2px solid var(--midnight); padding: 10px; background: var(--cyber);">HARI</th>
                                    <th style="border: 2px solid var(--midnight); padding: 10px; background: var(--cyber);">JAM</th>
                                    <th style="border: 2px solid var(--midnight); padding: 10px; background: var(--cyber); text-align:left;">MAPEL</th>
                                    <th style="border: 2px solid var(--midnight); padding: 10px; background: var(--cyber); text-align:left;">GURU</th>
                                    <th style="border: 2px solid var(--midnight); padding: 10px; background: var(--cyber);">JAM MASUK</th>
                                    <th style="border: 2px solid var(--midnight); padding: 10px; background: var(--cyber);">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentRows as $index => $row)
                                    <tr>
                                        <td style="border: 2px solid var(--midnight); padding: 10px; text-align:center;">{{ $index + 1 }}</td>
                                        <td style="border: 2px solid var(--midnight); padding: 10px; text-align:center;">{{ \Carbon\Carbon::parse($row['tanggal'])->format('d M Y') }}</td>
                                        <td style="border: 2px solid var(--midnight); padding: 10px; text-align:center;">{{ $row['hari'] }}</td>
                                        <td style="border: 2px solid var(--midnight); padding: 10px; text-align:center;">{{ $row['jam'] }}</td>
                                        <td style="border: 2px solid var(--midnight); padding: 10px;">{{ $row['mapel'] }}</td>
                                        <td style="border: 2px solid var(--midnight); padding: 10px;">{{ $row['guru'] }}</td>
                                        <td style="border: 2px solid var(--midnight); padding: 10px; text-align:center;">{{ $row['jam_masuk'] }}</td>
                                        <td style="border: 2px solid var(--midnight); padding: 10px; text-align:center;">
                                            <span class="mp-badge" style="background:{{ $statusColors[$row['status']] ?? 'var(--white)' }};">{{ $row['status'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="border: 2px solid var(--midnight); padding: 24px; text-align:center; font-weight:900;">Belum ada data presensi pada rentang tanggal ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <div class="mp-mobile-only mp-stack-list">
                    @forelse($studentRows as $row)
                        <section class="mp-card rekap-student-row">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                                <div>
                                    <span class="mp-label">{{ $row['hari'] }}, {{ \Carbon\Carbon::parse($row['tanggal'])->format('d M Y') }}</span>
                                    <h3 style="margin:6px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:24px;">{{ $row['mapel'] }}</h3>
                                </div>
                                <span class="mp-badge" style="background:{{ $statusColors[$row['status']] ?? 'var(--white)' }};">{{ $row['status'] }}</span>
                            </div>
                            <div style="display:grid; gap:8px; margin-top:14px; color:var(--midnight); font-weight:900;">
                                <div>{{ $row['jam'] }} &bull; Masuk: {{ $row['jam_masuk'] }}</div>
                                <div>{{ $row['guru'] }}</div>
                            </div>
                        </section>
                    @empty
                        <section class="mp-empty-state">
                            <div style="font-family:'Fredoka One', cursive; font-size:22px; color:var(--midnight);">Belum ada data</div>
                            <p style="margin:10px 0 0; font-weight:800;">Tidak ada presensi pada rentang tanggal ini.</p>
                        </section>
                    @endforelse
                </div>
            </div>
        @endif
    @elseif($selectedKelas)
        @php
            $weeklyTotals = [
                'Hadir' => 0,
                'Izin' => 0,
                'Sakit' => 0,
                'Alpa' => 0,
                'Belum Hadir' => 0,
                'Ditolak' => 0,
            ];
            $daySummaries = [];
            $totalSessionsInWeek = 0;

            foreach ($hariList as $hari) {
                $sessions = [];

                for ($jam = 1; $jam <= 12; $jam++) {
                    $slot = $slotMatrix[$hari][$jam] ?? null;
                    $kdJp = $slot['kd_jp'] ?? null;

                    if (! $kdJp) {
                        continue;
                    }

                    if (! isset($sessions[$kdJp])) {
                        $sessions[$kdJp] = [
                            'kd_jp' => $kdJp,
                            'mapel' => $slot['mapel'] ?? '-',
                            'guru' => $slot['guru'] ?? '-',
                            'start' => $jam,
                            'end' => $jam,
                            'students' => [],
                            'counts' => [
                                'Hadir' => 0,
                                'Izin' => 0,
                                'Sakit' => 0,
                                'Alpa' => 0,
                                'Belum Hadir' => 0,
                                'Ditolak' => 0,
                            ],
                        ];
                    } else {
                        $sessions[$kdJp]['end'] = $jam;
                    }
                }

                foreach ($siswas as $siswa) {
                    foreach ($sessions as $kdJp => $session) {
                        $chosenStatus = null;

                        for ($jam = $session['start']; $jam <= $session['end']; $jam++) {
                            $status = $statusMatrix[$siswa->NIS][$hari][$jam] ?? null;
                            if ($status) {
                                $chosenStatus = $status;
                                break;
                            }
                        }

                        if (! $chosenStatus) {
                            continue;
                        }

                        $sessions[$kdJp]['students'][$siswa->NIS] = [
                            'nama' => $siswa->nama_siswa,
                            'status' => $chosenStatus,
                        ];
                    }
                }

                foreach ($sessions as $kdJp => $session) {
                    foreach ($session['students'] as $student) {
                        if (isset($sessions[$kdJp]['counts'][$student['status']])) {
                            $sessions[$kdJp]['counts'][$student['status']]++;
                            $weeklyTotals[$student['status']]++;
                        }
                    }
                }

                $totalSessionsInWeek += count($sessions);
                $daySummaries[$hari] = $sessions;
            }
        @endphp

        <div class="rekap-mobile-board mp-mobile-only">
            <section class="mp-card mp-card-gold">
                <span class="mp-label">Ringkasan Minggu Ini</span>
                <h2 style="margin:8px 0 16px; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:28px;">{{ $selectedKelas }}</h2>
                <div class="mp-touch-grid" style="grid-template-columns:repeat(2, minmax(0, 1fr));">
                    <div class="mp-card" style="padding:18px; box-shadow:4px 4px 0 var(--midnight);">
                        <div class="mp-label">Sesi</div>
                        <div style="font-family:'Fredoka One', cursive; font-size:28px; color:var(--midnight);">{{ $totalSessionsInWeek }}</div>
                    </div>
                    <div class="mp-card" style="padding:18px; box-shadow:4px 4px 0 var(--midnight);">
                        <div class="mp-label">Siswa</div>
                        <div style="font-family:'Fredoka One', cursive; font-size:28px; color:var(--midnight);">{{ $siswas->count() }}</div>
                    </div>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:16px;">
                    @foreach($weeklyTotals as $label => $count)
                        <span class="mp-badge" style="background:{{ $statusColors[$label] ?? 'var(--white)' }};">{{ $label }} {{ $count }}</span>
                    @endforeach
                </div>
            </section>

            @foreach($hariList as $hari)
                <section class="rekap-mobile-day-card mp-card">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap; margin-bottom:16px;">
                        <div>
                            <span class="mp-label">{{ $hari }}</span>
                            <h3 style="margin:6px 0 0; color:var(--midnight); font-family:'Fredoka One', cursive; font-size:24px;">{{ count($daySummaries[$hari] ?? []) }} sesi</h3>
                        </div>
                        <span class="mp-badge" style="background:var(--white);">{{ count($daySummaries[$hari] ?? []) > 0 ? 'Siap dipindai' : 'Kosong' }}</span>
                    </div>

                    @if(!empty($daySummaries[$hari]))
                        <div class="mp-stack-list">
                            @foreach($daySummaries[$hari] as $session)
                                <div style="padding:16px; border:3px solid var(--midnight); border-radius:16px; background:var(--mochi); box-shadow:4px 4px 0 var(--midnight);">
                                    <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap;">
                                        <div>
                                            <div style="font-family:'Fredoka One', cursive; color:var(--midnight); font-size:22px;">{{ $session['mapel'] }}</div>
                                            <div style="margin-top:6px; font-size:12px; font-weight:900; color:var(--cosmo);">{{ $session['guru'] }}</div>
                                        </div>
                                        <span class="mp-badge" style="background:var(--gold);">Jam {{ $session['start'] }} - {{ $session['end'] }}</span>
                                    </div>

                                    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:12px;">
                                        @foreach($session['counts'] as $label => $count)
                                            @if($count > 0)
                                                <span class="mp-badge" style="background:{{ $statusColors[$label] ?? 'var(--white)' }};">{{ $label }} {{ $count }}</span>
                                            @endif
                                        @endforeach
                                    </div>

                                    <div class="mp-stack-list" style="margin-top:14px;">
                                        @forelse($session['students'] as $student)
                                            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; padding:12px 0; border-top:2px dashed rgba(30,27,41,.16);">
                                                <div style="font-weight:900; color:var(--midnight);">{{ $student['nama'] }}</div>
                                                <span class="mp-badge" style="background:{{ $statusColors[$student['status']] ?? 'var(--white)' }};">{{ $student['status'] }}</span>
                                            </div>
                                        @empty
                                            <div style="font-weight:800; color:var(--midnight);">Belum ada status siswa untuk sesi ini.</div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mp-empty-state" style="padding:28px 20px;">
                            <div style="font-family:'Fredoka One', cursive; font-size:22px; color:var(--midnight);">Tidak ada sesi</div>
                            <p style="margin:10px 0 0; font-weight:800;">Belum ada jadwal aktif yang terpetakan pada hari ini.</p>
                        </div>
                    @endif
                </section>
            @endforeach
        </div>

        <section class="mp-table-card mp-desktop-only">
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
