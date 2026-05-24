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

        $sesiHariIni = SesiPresensi::where('guru_id', $guruId)
            ->whereDate('created_at', $todayString)
            ->get(['id', 'kelas']);

        $kelasSesiHariIni = $sesiHariIni->pluck('kelas')->unique()->values();
        $totalKelasDiajar = $kelasSesiHariIni->count();
        $siswaHarusAbsen = Siswa::whereIn('kelas', $kelasSesiHariIni)->count();

        $sesiIds = $sesiHariIni->pluck('id');
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

        $siswaByKelas = Siswa::query()
            ->select('kelas', DB::raw('count(*) as total'))
            ->whereIn('kelas', $kelasSesiHariIni)
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        $hadirByKelas = Presensi::query()
            ->join('sesi_presensis', 'sesi_presensis.id', '=', 'presensi.sesi_id')
            ->select('sesi_presensis.kelas', DB::raw('count(*) as total'))
            ->where('sesi_presensis.guru_id', $guruId)
            ->whereDate('sesi_presensis.created_at', $todayString)
            ->where('presensi.status', 'Hadir')
            ->groupBy('sesi_presensis.kelas')
            ->pluck('total', 'kelas');

        $kelasBreakdown = $kelasSesiHariIni->map(function ($kelas) use ($siswaByKelas, $hadirByKelas) {
            $ts = (int) ($siswaByKelas[$kelas] ?? 0);
            $hd = (int) ($hadirByKelas[$kelas] ?? 0);

            return (object) [
                'nama' => $kelas,
                'persentase' => $ts > 0 ? round(($hd / $ts) * 100) : 0,
            ];
        })->all();

        $dateKeys = collect(range(6, 0))->map(fn ($i) => $today->copy()->subDays($i)->toDateString());
        $sesiPerHari = SesiPresensi::query()
            ->selectRaw('date(created_at) as tanggal, kelas')
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
            $kelasHariIni = collect($sesiPerHari->get($dateKey, []))->pluck('kelas')->unique()->values();
            $tSiswaDay = Siswa::whereIn('kelas', $kelasHariIni)->count();
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
