<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $todayString = $today->toDateString();
        $hariIni = $this->hariIndonesia($today);
        $guruId = auth()->id();
        $guru = Guru::query()->where('user_id', $guruId)->first();
        $studentCountsByClass = Siswa::query()
            ->select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        $sesiHariIni = SesiPresensi::where('guru_id', $guruId)
            ->whereDate('created_at', $todayString)
            ->get(['id', 'kelas', 'kd_jp', 'status']);

        $sesiIds = $sesiHariIni->pluck('id');
        $sesiCountByKelas = $sesiHariIni->countBy('kelas');
        $kelasSesiHariIni = $sesiCountByKelas->keys()->values();
        $totalKelasDiajar = $sesiCountByKelas->count();
        $siswaHarusAbsen = $sesiCountByKelas
            ->map(fn (int $sessionCount, string $kelas): int => ((int) ($studentCountsByClass[$kelas] ?? 0)) * $sessionCount)
            ->sum();

        $statusCounts = Presensi::query()
            ->select('status', DB::raw('count(*) as total'))
            ->whereIn('sesi_id', $sesiIds)
            ->whereIn('status', ['Hadir', 'Izin', 'Sakit'])
            ->groupBy('status')
            ->pluck('total', 'status');

        $hadirHariIni = (int) ($statusCounts['Hadir'] ?? 0);
        $izinSakitHariIni = (int) (($statusCounts['Izin'] ?? 0) + ($statusCounts['Sakit'] ?? 0));
        $alpaHariIni = max(0, $siswaHarusAbsen - ($hadirHariIni + $izinSakitHariIni));
        $persentaseHadir = $siswaHarusAbsen > 0 ? round(($hadirHariIni / $siswaHarusAbsen) * 100, 1) : 0;

        $absensiTerbaru = Presensi::with(['siswa', 'sesi.guru'])
            ->whereIn('sesi_id', $sesiIds)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $hadirByKelas = Presensi::query()
            ->join('sesi_presensis', 'sesi_presensis.id', '=', 'presensi.sesi_id')
            ->select('sesi_presensis.kelas', DB::raw('count(*) as total'))
            ->where('sesi_presensis.guru_id', $guruId)
            ->whereDate('sesi_presensis.created_at', $todayString)
            ->where('presensi.status', 'Hadir')
            ->groupBy('sesi_presensis.kelas')
            ->pluck('total', 'kelas');

        $kelasBreakdown = $sesiCountByKelas->map(function (int $sessionCount, string $kelas) use ($studentCountsByClass, $hadirByKelas) {
            $ts = ((int) ($studentCountsByClass[$kelas] ?? 0)) * $sessionCount;
            $hd = (int) ($hadirByKelas[$kelas] ?? 0);

            return (object) [
                'nama' => $kelas,
                'persentase' => $ts > 0 ? round(($hd / $ts) * 100) : 0,
            ];
        })->values()->all();

        $dateKeys = collect(range(6, 0))->map(fn ($i) => $today->copy()->subDays($i)->toDateString());
        $sesiPerHari = SesiPresensi::query()
            ->selectRaw('date(created_at) as tanggal, kelas, count(*) as total_sesi')
            ->where('guru_id', $guruId)
            ->whereBetween('created_at', [$today->copy()->subDays(6)->startOfDay(), $today->copy()->endOfDay()])
            ->groupByRaw('date(created_at), kelas')
            ->get()
            ->groupBy('tanggal');

        $presensiPerHari = Presensi::query()
            ->join('sesi_presensis', 'sesi_presensis.id', '=', 'presensi.sesi_id')
            ->selectRaw("date(sesi_presensis.created_at) as tanggal, sum(case when presensi.status = 'Hadir' then 1 else 0 end) as hadir")
            ->selectRaw("sum(case when presensi.status in ('Izin', 'Sakit') then 1 else 0 end) as izin_sakit")
            ->where('sesi_presensis.guru_id', $guruId)
            ->whereBetween('sesi_presensis.created_at', [$today->copy()->subDays(6)->startOfDay(), $today->copy()->endOfDay()])
            ->groupByRaw('date(sesi_presensis.created_at)')
            ->get()
            ->keyBy('tanggal');

        $trendData = [];
        foreach ($dateKeys as $dateKey) {
            $date = Carbon::parse($dateKey);
            $kelasHariIni = collect($sesiPerHari->get($dateKey, []));
            $tSiswaDay = $kelasHariIni->sum(
                fn ($row): int => ((int) ($studentCountsByClass[$row->kelas] ?? 0)) * (int) $row->total_sesi
            );
            $dailyCounts = $presensiPerHari->get($dateKey);
            $hDay = (int) ($dailyCounts->hadir ?? 0);
            $izDay = (int) ($dailyCounts->izin_sakit ?? 0);
            $alpDay = max(0, $tSiswaDay - ($hDay + $izDay));
            $totalActivity = $hDay + $izDay + $alpDay;
            $pctH = $totalActivity > 0 ? ($hDay / $totalActivity) * 100 : 0;
            $pctI = $totalActivity > 0 ? ($izDay / $totalActivity) * 100 : 0;
            $pctA = $totalActivity > 0 ? ($alpDay / $totalActivity) * 100 : 0;
            $hariIndo = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

            $trendData[] = (object) [
                'hari' => $date->isToday() ? 'Today' : $hariIndo[$date->dayOfWeek],
                'pct_hadir' => $pctH,
                'pct_izin' => $pctI,
                'pct_alpa' => $pctA,
                'is_empty' => $totalActivity == 0,
            ];
        }

        $todaySessionsBySchedule = $sesiHariIni->keyBy(fn (SesiPresensi $session): string => $session->kd_jp . '|' . $session->kelas);
        $agendaMengajar = $guru
            ? JadwalPelajaran::query()
                ->with('mapel:kd_mapel,nama_mapel')
                ->where('NIP', $guru->NIP)
                ->where('hari', $hariIni)
                ->orderBy('jam_mulai')
                ->get(['kd_jp', 'hari', 'jam_mulai', 'jam_selesai', 'kd_mapel', 'NIP', 'kelas'])
                ->map(function (JadwalPelajaran $jadwal) use ($todaySessionsBySchedule) {
                    $session = $todaySessionsBySchedule->get($jadwal->kd_jp . '|' . $jadwal->kelas);

                    return (object) [
                        'jam_label' => $this->formatJamPelajaran($jadwal->jam_mulai, $jadwal->jam_selesai),
                        'mapel' => $jadwal->mapel?->nama_mapel ?? $jadwal->kd_jp,
                        'kelas' => $jadwal->kelas,
                        'status_label' => match ($session?->status) {
                            'aktif' => 'Aktif',
                            'selesai' => 'Selesai',
                            default => 'Belum Dimulai',
                        },
                        'status_color' => match ($session?->status) {
                            'aktif' => 'var(--gold)',
                            'selesai' => 'var(--cyber)',
                            default => 'var(--white)',
                        },
                    ];
                })
            : collect();

        $statusSesiHariIni = collect([
            (object) [
                'title' => 'Jadwal Hari Ini',
                'value' => $agendaMengajar->count(),
                'description' => 'slot mengajar terjadwal',
            ],
            (object) [
                'title' => 'Sesi Aktif',
                'value' => $sesiHariIni->where('status', 'aktif')->count(),
                'description' => 'kelas sedang dibuka',
            ],
            (object) [
                'title' => 'Sesi Selesai',
                'value' => $sesiHariIni->where('status', 'selesai')->count(),
                'description' => 'kelas sudah ditutup',
            ],
            (object) [
                'title' => 'Belum Dimulai',
                'value' => max(0, $agendaMengajar->count() - $sesiHariIni->pluck('kd_jp')->unique()->count()),
                'description' => 'jadwal belum dibuka',
            ],
        ]);

        return view('guru.dashboard', compact(
            'totalKelasDiajar', 'siswaHarusAbsen',
            'hadirHariIni', 'izinSakitHariIni', 'alpaHariIni', 'persentaseHadir',
            'absensiTerbaru', 'kelasBreakdown', 'trendData', 'agendaMengajar', 'statusSesiHariIni'
        ));
    }

    private function hariIndonesia(Carbon $date): string
    {
        return [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ][$date->format('l')];
    }

    private function formatJamPelajaran(mixed $jamMulai, mixed $jamSelesai): string
    {
        $mulai = trim((string) $jamMulai);
        $selesai = trim((string) $jamSelesai);

        if (is_numeric($mulai) && is_numeric($selesai)) {
            return "Jam {$mulai}-{$selesai}";
        }

        if ($selesai === '' || $selesai === $mulai) {
            return $mulai !== '' ? $mulai : '-';
        }

        return trim($mulai . ' - ' . $selesai);
    }
}
