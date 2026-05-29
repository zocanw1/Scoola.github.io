<?php

namespace App\Http\Controllers;

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
        $guruId = auth()->id();
        $studentCountsByClass = Siswa::query()
            ->select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        $sesiHariIni = SesiPresensi::where('guru_id', $guruId)
            ->whereDate('created_at', $todayString)
            ->get(['id', 'kelas']);

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

        return view('guru.dashboard', compact(
            'totalKelasDiajar', 'siswaHarusAbsen',
            'hadirHariIni', 'izinSakitHariIni', 'alpaHariIni', 'persentaseHadir',
            'absensiTerbaru', 'kelasBreakdown', 'trendData'
        ));
    }
}
