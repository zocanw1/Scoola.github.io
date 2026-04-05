<?php

namespace App\Http\Controllers;

use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapController extends Controller
{
    /**
     * Halaman utama rekap — ringkasan + daftar sesi + filter
     */
    public function index(Request $request)
    {
        $query = SesiPresensi::with('guru')
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc');

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Filter guru
        if ($request->filled('guru')) {
            $query->where('guru_id', $request->guru);
        }

        $sesiSelesai = $query->get();

        // Data untuk filter dropdowns
        $kelasList = SesiPresensi::where('status', 'selesai')
            ->distinct()->pluck('kelas')->sort()->values();
        $guruList = \App\Models\User::whereIn('id',
            SesiPresensi::where('status', 'selesai')->distinct()->pluck('guru_id')
        )->get();

        // Stats ringkasan
        $allSesi = SesiPresensi::where('status', 'selesai')->get();
        $totalSesi = $allSesi->count();
        $totalKelasAktif = $allSesi->unique('kelas')->count();

        // Hitung total hadir & alpa dari semua sesi selesai
        $sesiIds = $allSesi->pluck('id');
        $totalHadir = Presensi::whereIn('sesi_id', $sesiIds)->where('status', 'Hadir')->count();
        $totalAlpa = 0;
        foreach ($allSesi as $s) {
            $siswaKelas = Siswa::where('kelas', $s->kelas)->count();
            $hadirSesi = Presensi::where('sesi_id', $s->id)->where('status', 'Hadir')->count();
            $totalAlpa += ($siswaKelas - $hadirSesi);
        }

        $persentaseHadir = ($totalHadir + $totalAlpa) > 0
            ? round(($totalHadir / ($totalHadir + $totalAlpa)) * 100, 1)
            : 0;

        return view('admin.rekap.rekap-index', compact(
            'sesiSelesai', 'kelasList', 'guruList',
            'totalSesi', 'totalKelasAktif', 'totalHadir', 'totalAlpa', 'persentaseHadir'
        ));
    }

    /**
     * Detail rekap per sesi
     */
    public function show($id)
    {
        $sesi = SesiPresensi::with('guru')->findOrFail($id);

        $kelas = $sesi->kelas;
        $tanggal = $sesi->created_at->format('Y-m-d');

        $siswa = Siswa::where('kelas', $kelas)->orderBy('nama_siswa')->get();

        $presensi = Presensi::where('sesi_id', $sesi->id)
            ->get()
            ->keyBy('NIS');

        $rekapData = $siswa->map(function($s) use ($presensi) {
            $record = $presensi->get($s->NIS);

            return (object) [
                'NIS'        => $s->NIS,
                'nama_siswa' => $s->nama_siswa,
                'status'     => $record ? $record->status : 'Alpa',
                'jam_masuk'  => $record ? $record->jam_masuk : '-'
            ];
        });

        $totalSiswa = $rekapData->count();
        $totalHadir = $rekapData->where('status', 'Hadir')->count();
        $totalAlpa  = $totalSiswa - $totalHadir;
        $persentase = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;

        return view('admin.rekap.rekap-show', compact(
            'rekapData', 'sesi', 'kelas', 'tanggal',
            'totalSiswa', 'totalHadir', 'totalAlpa', 'persentase'
        ));
    }

    /**
     * Rekap harian — lihat semua kelas di tanggal tertentu
     */
    public function harian(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));

        $sesiHari = SesiPresensi::with('guru')
            ->where('status', 'selesai')
            ->whereDate('created_at', $tanggal)
            ->orderBy('kelas')
            ->get();

        // Per kelas breakdown
        $kelasBreakdown = $sesiHari->groupBy('kelas')->map(function($sessions, $kelas) {
            $totalSiswa = Siswa::where('kelas', $kelas)->count();
            $hadirCount = 0;

            foreach ($sessions as $sesi) {
                $hadirCount += Presensi::where('sesi_id', $sesi->id)
                    ->where('status', 'Hadir')->count();
            }

            return (object) [
                'kelas'       => $kelas,
                'total_siswa' => $totalSiswa,
                'hadir'       => $hadirCount,
                'alpa'        => $totalSiswa - $hadirCount,
                'persentase'  => $totalSiswa > 0 ? round(($hadirCount / $totalSiswa) * 100, 1) : 0,
                'sesi_count'  => $sessions->count(),
                'guru_list'   => $sessions->pluck('guru.name')->unique()->implode(', '),
            ];
        })->values();

        return view('admin.rekap.rekap-harian', compact('tanggal', 'sesiHari', 'kelasBreakdown'));
    }
}
