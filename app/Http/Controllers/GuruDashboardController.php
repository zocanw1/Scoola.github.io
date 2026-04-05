<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');
        $guruId = auth()->id();

        // Cari sesi presensi yang dikelola guru ini HARI INI
        $sesiHariIni = SesiPresensi::where('guru_id', $guruId)
            ->whereDate('created_at', $today)
            ->get();
        
        $kelasSesiHariIni = $sesiHariIni->pluck('kelas')->unique();
        $totalKelasDiajar = $kelasSesiHariIni->count();

        // Hitung total siswa yang seharusnya absen di kelas-kelas tsb
        $siswaHarusAbsen = Siswa::whereIn('kelas', $kelasSesiHariIni)->count();

        // Absensi HARI INI khusus untuk sesi guru ini
        $sesiIds = $sesiHariIni->pluck('id');
        $hadirHariIni = Presensi::whereIn('sesi_id', $sesiIds)->where('status', 'Hadir')->count();
        $izinSakitHariIni = Presensi::whereIn('sesi_id', $sesiIds)->whereIn('status', ['Izin', 'Sakit'])->count();
        
        $alpaHariIni = max(0, $siswaHarusAbsen - ($hadirHariIni + $izinSakitHariIni));

        $persentaseHadir = $siswaHarusAbsen > 0 ? round(($hadirHariIni / $siswaHarusAbsen) * 100, 1) : 0;

        // Absensi Masuk Terbaru (khusus sesi guru ini)
        $absensiTerbaru = Presensi::with(['siswa', 'sesi.guru'])
            ->whereIn('sesi_id', $sesiIds)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Kehadiran Per Kelas (untuk hari ini, khusus sesi guru ini)
        $kelasBreakdown = [];
        foreach ($sesiHariIni as $sesi) {
            $ts = Siswa::where('kelas', $sesi->kelas)->count();
            $hd = Presensi::where('sesi_id', $sesi->id)->where('status', 'Hadir')->count();
            
            $pct = $ts > 0 ? round(($hd / $ts) * 100) : 0;
            
            // Hindari duplikasi kalau buka sesi 2x di kelas yang sama
            if (!isset($kelasBreakdown[$sesi->kelas])) {
                $kelasBreakdown[$sesi->kelas] = (object)[
                    'nama' => $sesi->kelas,
                    'persentase' => $pct
                ];
            }
        }
        $kelasBreakdown = array_values($kelasBreakdown);

        // Tren 7 hari terakhir (khusus guru ini)
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dStr = $date->format('Y-m-d');
            
            $sesiDay = SesiPresensi::where('guru_id', $guruId)->whereDate('created_at', $dStr)->get();
            $sesiDayIds = $sesiDay->pluck('id');
            $klsDay = $sesiDay->pluck('kelas')->unique();
            $tSiswaDay = Siswa::whereIn('kelas', $klsDay)->count();
            
            $hDay = Presensi::whereIn('sesi_id', $sesiDayIds)->where('status', 'Hadir')->count();
            $izDay = Presensi::whereIn('sesi_id', $sesiDayIds)->whereIn('status', ['Izin', 'Sakit'])->count();
            
            $alpDay = max(0, $tSiswaDay - ($hDay + $izDay));

            $totalActivity = $hDay + $izDay + $alpDay;
            $pctH = $totalActivity > 0 ? ($hDay / $totalActivity) * 100 : 0;
            $pctI = $totalActivity > 0 ? ($izDay / $totalActivity) * 100 : 0;
            $pctA = $totalActivity > 0 ? ($alpDay / $totalActivity) * 100 : 0;

            $hariIndo = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

            $trendData[] = (object)[
                'hari' => $date->isToday() ? 'Today' : $hariIndo[$date->dayOfWeek],
                'pct_hadir' => $pctH,
                'pct_izin' => $pctI,
                'pct_alpa' => $pctA,
                'is_empty' => $totalActivity == 0
            ];
        }

        return view('guru.dashboard', compact(
            'totalKelasDiajar', 'siswaHarusAbsen',
            'hadirHariIni', 'izinSakitHariIni', 'alpaHariIni', 'persentaseHadir',
            'absensiTerbaru', 'kelasBreakdown', 'trendData'
        ));
    }
}
