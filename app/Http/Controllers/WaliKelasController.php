<?php

namespace App\Http\Controllers;

use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WaliKelasController extends Controller
{
    /**
     * Ambil kelas yang di-wali oleh guru yang sedang login
     */
    private function getKelasWali()
    {
        $user = auth()->user();
        $guru = $user->guru;

        if (!$guru) {
            abort(403, 'Anda bukan guru.');
        }

        $kelas = Kelas::where('wali_kelas_nip', $guru->NIP)->first();

        if (!$kelas) {
            abort(403, 'Anda bukan wali kelas.');
        }

        return $kelas;
    }

    /**
     * Rekap per sesi — hanya untuk kelas yang di-wali
     */
    public function index(Request $request)
    {
        $kelas = $this->getKelasWali();
        $namaKelas = $kelas->nama_kelas;

        $query = SesiPresensi::with('guru')
            ->where('status', 'selesai')
            ->where('kelas', $namaKelas)
            ->orderBy('updated_at', 'desc');

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $sesiSelesai = $query->get();

        // Stats
        $totalSesi = $sesiSelesai->count();
        $sesiIds = $sesiSelesai->pluck('id');

        $totalHadir = Presensi::whereIn('sesi_id', $sesiIds)->where('status', 'Hadir')->count();
        $totalAlpa = 0;
        foreach ($sesiSelesai as $s) {
            $siswaKelas = Siswa::where('kelas', $s->kelas)->count();
            $hadirSesi = Presensi::where('sesi_id', $s->id)->where('status', 'Hadir')->count();
            $totalAlpa += ($siswaKelas - $hadirSesi);
        }

        $persentaseHadir = ($totalHadir + $totalAlpa) > 0
            ? round(($totalHadir / ($totalHadir + $totalAlpa)) * 100, 1)
            : 0;

        $totalSiswa = Siswa::where('kelas', $namaKelas)->count();

        return view('guru.walikelas.rekap-index', compact(
            'sesiSelesai', 'namaKelas', 'totalSesi',
            'totalHadir', 'totalAlpa', 'persentaseHadir', 'totalSiswa'
        ));
    }

    /**
     * Detail rekap per sesi
     */
    public function show($id)
    {
        $kelas = $this->getKelasWali();
        $sesi = SesiPresensi::with('guru')->findOrFail($id);

        // Pastikan sesi ini untuk kelas yang di-wali
        if ($sesi->kelas !== $kelas->nama_kelas) {
            abort(403, 'Sesi ini bukan untuk kelas Anda.');
        }

        $namaKelas = $kelas->nama_kelas;
        $tanggal = $sesi->created_at->format('Y-m-d');

        $siswa = Siswa::where('kelas', $namaKelas)->orderBy('nama_siswa')->get();

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

        return view('guru.walikelas.rekap-show', compact(
            'rekapData', 'sesi', 'namaKelas', 'tanggal',
            'totalSiswa', 'totalHadir', 'totalAlpa', 'persentase'
        ));
    }

    /**
     * Rekap harian
     */
    public function harian(Request $request)
    {
        $kelas = $this->getKelasWali();
        $namaKelas = $kelas->nama_kelas;
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));

        $sesiHari = SesiPresensi::with('guru')
            ->where('status', 'selesai')
            ->where('kelas', $namaKelas)
            ->whereDate('created_at', $tanggal)
            ->orderBy('created_at')
            ->get();

        $totalSiswa = Siswa::where('kelas', $namaKelas)->count();
        $hadirCount = 0;

        foreach ($sesiHari as $sesi) {
            $hadirCount += Presensi::where('sesi_id', $sesi->id)
                ->where('status', 'Hadir')->count();
        }

        $alpaCount = max(0, $totalSiswa - $hadirCount);
        $persentase = $totalSiswa > 0 ? round(($hadirCount / $totalSiswa) * 100, 1) : 0;

        $ringkasan = (object) [
            'total_siswa' => $totalSiswa,
            'hadir'       => $hadirCount,
            'alpa'        => $alpaCount,
            'persentase'  => $persentase,
            'sesi_count'  => $sesiHari->count(),
        ];

        return view('guru.walikelas.rekap-harian', compact(
            'namaKelas', 'tanggal', 'sesiHari', 'ringkasan'
        ));
    }

    /**
     * Rekap bulanan
     */
    public function bulanan(Request $request)
    {
        $kelas = $this->getKelasWali();
        $namaKelas = $kelas->nama_kelas;

        $bulanStr = $request->get('bulan', Carbon::today()->format('Y-m'));
        $bulan = Carbon::createFromFormat('Y-m', $bulanStr)->startOfMonth();
        $bulanAkhir = $bulan->copy()->endOfMonth();

        // Semua sesi selesai di bulan+kelas ini
        $sesiList = SesiPresensi::where('status', 'selesai')
            ->where('kelas', $namaKelas)
            ->whereBetween('created_at', [$bulan, $bulanAkhir])
            ->orderBy('created_at')
            ->get();

        $totalSesi = $sesiList->count();
        $sesiIds = $sesiList->pluck('id');

        // Siswa kelas
        $siswaKelas = Siswa::where('kelas', $namaKelas)->orderBy('nama_siswa')->get();

        // Rekap per siswa
        $rekapSiswa = $siswaKelas->map(function ($siswa) use ($sesiIds, $totalSesi) {
            $totalHadir = Presensi::where('NIS', $siswa->NIS)
                ->whereIn('sesi_id', $sesiIds)
                ->where('status', 'Hadir')
                ->count();

            $totalAlpa = $totalSesi - $totalHadir;
            $persentase = $totalSesi > 0 ? round(($totalHadir / $totalSesi) * 100, 1) : 0;

            return (object) [
                'NIS'         => $siswa->NIS,
                'nama_siswa'  => $siswa->nama_siswa,
                'total_hadir' => $totalHadir,
                'total_alpa'  => $totalAlpa,
                'persentase'  => $persentase,
            ];
        });

        // Ringkasan
        $rataRata = $rekapSiswa->count() > 0 ? round($rekapSiswa->avg('persentase'), 1) : 0;
        $siswaTerbaik = $rekapSiswa->sortByDesc('persentase')->first();
        $siswaTerendah = $rekapSiswa->sortBy('persentase')->first();

        $ringkasan = (object) [
            'rata_rata'      => $rataRata,
            'siswa_terbaik'  => $siswaTerbaik,
            'siswa_terendah' => $siswaTerendah,
        ];

        return view('guru.walikelas.rekap-bulanan', compact(
            'namaKelas', 'bulan', 'rekapSiswa', 'totalSesi', 'ringkasan'
        ));
    }
}
