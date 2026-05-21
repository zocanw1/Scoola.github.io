<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\Presensi;
use Carbon\Carbon;

class RekapPresensiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        
        $selectedKelas = $request->input('kelas');
        $tanggalInput = $request->input('tanggal') ?: now()->toDateString();
        $carbonDate = Carbon::parse($tanggalInput);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY)->addDays(4); // Jumat

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $siswas = [];
        $jadwals = [];
        $presensiMap = [];

        if ($selectedKelas) {
            $siswas = Siswa::where('kelas', $selectedKelas)->orderBy('nama_siswa')->get();
            
            // Ambil jadwal pada hari Senin sampai Jumat
            $jadwals = JadwalPelajaran::with(['mapel', 'guru'])
                ->where('kelas', $selectedKelas)
                ->whereIn('hari', $hariList)
                ->get();
                
            $kdJpList = $jadwals->pluck('kd_jp')->toArray();
            
            $presensiRecords = Presensi::whereIn('kd_jp', $kdJpList)
                ->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->where('status', 'Hadir')
                ->get();
                
            foreach ($presensiRecords as $p) {
                $presensiMap[$p->NIS][$p->kd_jp] = $p->status;
            }
        }

        return view('admin.rekap.index', compact('kelas', 'selectedKelas', 'hariList', 'siswas', 'jadwals', 'presensiMap', 'tanggalInput', 'startOfWeek', 'endOfWeek'));
    }

    public function export(Request $request)
    {
        $selectedKelas = $request->input('kelas');
        $tanggalInput = $request->input('tanggal') ?: now()->toDateString();
        $carbonDate = Carbon::parse($tanggalInput);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY)->addDays(4); // Jumat

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        if (!$selectedKelas) {
            return redirect()->back()->with('error', 'Silakan pilih kelas terlebih dahulu.');
        }

        $siswas = Siswa::where('kelas', $selectedKelas)->orderBy('nama_siswa')->get();
        $jadwals = JadwalPelajaran::with(['mapel', 'guru'])
            ->where('kelas', $selectedKelas)
            ->whereIn('hari', $hariList)
            ->get();
            
        $kdJpList = $jadwals->pluck('kd_jp')->toArray();
        $presensiRecords = Presensi::whereIn('kd_jp', $kdJpList)
            ->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->where('status', 'Hadir')
            ->get();
            
        $presensiMap = [];
        foreach ($presensiRecords as $p) {
            $presensiMap[$p->NIS][$p->kd_jp] = $p->status;
        }

        $filename = "Rekap_Presensi_{$selectedKelas}_" . $startOfWeek->format('d-M-Y') . ".xls";

        return response(view('admin.rekap.export', compact('selectedKelas', 'hariList', 'siswas', 'jadwals', 'presensiMap', 'tanggalInput', 'startOfWeek', 'endOfWeek')))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
