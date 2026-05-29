<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi</title>
</head>
<body>
    @if(($mode ?? 'mingguan') === 'siswa')
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="8" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #f3f3f3;">
                        REKAP PRESENSI PER SISWA<br>
                        {{ $selectedSiswa->nama_siswa }} ({{ $selectedSiswa->NIS }}) - KELAS {{ $selectedKelas }}<br>
                        PERIODE {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
                    </th>
                </tr>
                <tr>
                    <th style="background-color: #e2efda; font-weight: bold;">NO</th>
                    <th style="background-color: #e2efda; font-weight: bold;">TANGGAL</th>
                    <th style="background-color: #e2efda; font-weight: bold;">HARI</th>
                    <th style="background-color: #e2efda; font-weight: bold;">JAM</th>
                    <th style="background-color: #e2efda; font-weight: bold;">MAPEL</th>
                    <th style="background-color: #e2efda; font-weight: bold;">GURU</th>
                    <th style="background-color: #e2efda; font-weight: bold;">JAM MASUK</th>
                    <th style="background-color: #e2efda; font-weight: bold;">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($studentRows as $index => $row)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">{{ \Carbon\Carbon::parse($row['tanggal'])->format('d M Y') }}</td>
                        <td style="text-align: center;">{{ $row['hari'] }}</td>
                        <td style="text-align: center;">{{ $row['jam'] }}</td>
                        <td>{{ $row['mapel'] }}</td>
                        <td>{{ $row['guru'] }}</td>
                        <td style="text-align: center;">{{ $row['jam_masuk'] }}</td>
                        <td style="text-align: center;">{{ $row['status'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Belum ada data presensi pada rentang tanggal ini.</td>
                    </tr>
                @endforelse

                <tr>
                    <td colspan="8" style="font-weight: bold; background-color: #f3f3f3;">RINGKASAN STATUS</td>
                </tr>
                @foreach($studentTotals as $label => $count)
                    <tr>
                        <td colspan="7">{{ $label }}</td>
                        <td style="text-align: center;">{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(($mode ?? 'mingguan') === 'rentang')
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="{{ 4 + count($rangeSummaryTotals ?? []) }}" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #f3f3f3;">
                        REKAP PRESENSI RENTANG TANGGAL<br>
                        KELAS {{ $selectedKelas }}<br>
                        PERIODE {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
                    </th>
                </tr>
                <tr>
                    <th style="background-color: #e2efda; font-weight: bold;">NO</th>
                    <th style="background-color: #e2efda; font-weight: bold;">NIS</th>
                    <th style="background-color: #e2efda; font-weight: bold;">NAMA SISWA</th>
                    @foreach(($rangeSummaryTotals ?? []) as $label => $count)
                        <th style="background-color: #e2efda; font-weight: bold;">{{ strtoupper($label) }}</th>
                    @endforeach
                    <th style="background-color: #e2efda; font-weight: bold;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($rangeSummaryRows ?? collect()) as $index => $row)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center; mso-number-format:'\@';">{{ $row['nis'] }}</td>
                        <td>{{ $row['nama_siswa'] }}</td>
                        @foreach($row['totals'] as $count)
                            <td style="text-align: center;">{{ $count }}</td>
                        @endforeach
                        <td style="text-align: center;">{{ $row['total_records'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 4 + count($rangeSummaryTotals ?? []) }}" style="text-align: center;">Belum ada data siswa pada kelas ini.</td>
                    </tr>
                @endforelse

                <tr>
                    <td colspan="3" style="font-weight: bold; background-color: #f3f3f3;">TOTAL SELURUH SISWA</td>
                    @foreach(($rangeSummaryTotals ?? []) as $count)
                        <td style="text-align: center; font-weight: bold;">{{ $count }}</td>
                    @endforeach
                    <td style="text-align: center; font-weight: bold;">{{ collect($rangeSummaryRows ?? [])->sum('total_records') }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="{{ 4 + (count($hariList) * 12) }}" style="text-align: center; font-size: 16px; font-weight: bold; background-color: #f3f3f3;">
                        DAFTAR HADIR SISWA<br>
                        KELAS {{ $selectedKelas }} - MINGGU: {{ $startOfWeek->format('d M Y') }} - {{ $endOfWeek->format('d M Y') }}
                    </th>
                </tr>
                <tr>
                    <th rowspan="3" style="background-color: #e2efda; font-weight: bold;">NO</th>
                    <th rowspan="3" style="background-color: #e2efda; font-weight: bold;">NIS</th>
                    <th rowspan="3" style="background-color: #e2efda; font-weight: bold; width: 300px;">NAMA SISWA</th>
                    <th rowspan="3" style="background-color: #e2efda; font-weight: bold;">L/P</th>
                    @foreach($hariList as $hari)
                        <th colspan="12" style="background-color: #fff2cc; font-weight: bold; text-align: center;">{{ strtoupper($hari) }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($hariList as $hari)
                        @for($i = 1; $i <= 12; $i++)
                            <th style="background-color: #fff2cc; text-align: center;">{{ $i }}</th>
                        @endfor
                    @endforeach
                </tr>
                <tr>
                    @foreach($hariList as $hari)
                        @for($i = 1; $i <= 12; $i++)
                            <th style="background-color: #fce4d6; font-size: 10px; text-align: center;">{{ $slotMatrix[$hari][$i]['mapel'] ?? '' }}</th>
                        @endfor
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($siswas as $index => $siswa)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center; mso-number-format:'\@';">{{ $siswa->NIS }}</td>
                        <td>{{ $siswa->nama_siswa }}</td>
                        <td style="text-align: center;">{{ $siswa->jenis_kelamin ?? 'L' }}</td>

                        @foreach($hariList as $hari)
                            @for($i = 1; $i <= 12; $i++)
                                @php
                                    $statusShort = match($statusMatrix[$siswa->NIS][$hari][$i] ?? null) {
                                        'Hadir' => 'H',
                                        'Izin' => 'I',
                                        'Sakit' => 'S',
                                        'Alpa' => 'A',
                                        'Ditolak' => 'D',
                                        'Belum Hadir' => 'B',
                                        default => '',
                                    };
                                @endphp
                                <td style="text-align: center;">{{ $statusShort }}</td>
                            @endfor
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
