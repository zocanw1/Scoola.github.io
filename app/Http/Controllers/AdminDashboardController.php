<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use App\Models\Kelas; // if exists, or distinct kelas from Siswa
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');

        $totalSiswa = Siswa::count();
        $totalKelasAktif = Siswa::distinct('kelas')->count('kelas');

        // Sesi hari ini
        $sesiHariIni = SesiPresensi::whereDate('created_at', $today)->get();
        $kelasSesiHariIni = $sesiHariIni->pluck('kelas')->unique();
        
        $siswaHarusAbsen = Siswa::whereIn('kelas', $kelasSesiHariIni)->count();

        // Absensi hari ini (dari semua tipe status yang mungkin)
        $hadirHariIni = Presensi::where('tanggal', $today)->where('status', 'Hadir')->count();
        $izinSakitHariIni = Presensi::where('tanggal', $today)->whereIn('status', ['Izin', 'Sakit'])->count();
        
        // Alpa dihitung dari siswa yang kelasnya buka sesi hari ini tapi belum ada rekor presensi Hadir/Izin/Sakit
        // (Atau gunakan logika sederhana alpaSistem)
        $alpaHariIni = max(0, $siswaHarusAbsen - ($hadirHariIni + $izinSakitHariIni));

        // Total persentase (dari siswaHarusAbsen)
        $persentaseHadir = $siswaHarusAbsen > 0 ? round(($hadirHariIni / $siswaHarusAbsen) * 100, 1) : 0;

        // Absensi Masuk Terbaru
        $absensiTerbaru = Presensi::with('siswa')
            ->where('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Kehadiran Per Kelas (untuk hari ini)
        $kelasBreakdown = [];
        foreach ($kelasSesiHariIni as $kelas) {
            $ts = Siswa::where('kelas', $kelas)->count();
            $hd = Presensi::whereHas('sesi', function($q) use ($kelas, $today) {
                $q->where('kelas', $kelas)->whereDate('created_at', $today);
            })->where('status', 'Hadir')->count();
            
            $pct = $ts > 0 ? round(($hd / $ts) * 100) : 0;
            $kelasBreakdown[] = (object)[
                'nama' => $kelas,
                'persentase' => $pct
            ];
        }

        // Tren 7 hari terakhir
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dStr = $date->format('Y-m-d');
            
            $sesiDay = SesiPresensi::whereDate('created_at', $dStr)->get();
            $klsDay = $sesiDay->pluck('kelas')->unique();
            $tSiswaDay = Siswa::whereIn('kelas', $klsDay)->count();
            
            $hDay = Presensi::where('tanggal', $dStr)->where('status', 'Hadir')->count();
            $izDay = Presensi::where('tanggal', $dStr)->whereIn('status', ['Izin', 'Sakit'])->count();
            
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

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalKelasAktif',
            'hadirHariIni', 'izinSakitHariIni', 'alpaHariIni', 'persentaseHadir',
            'siswaHarusAbsen', 'absensiTerbaru', 'kelasBreakdown', 'trendData'
        ));
    }
}
