<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi</title>
</head>
<body>
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
</body>
</html>
