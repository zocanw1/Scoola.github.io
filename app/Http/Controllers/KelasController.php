<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->pluck('nama_kelas')->toArray();

        $counts = Siswa::select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        return view('admin.kelas.kelas-index', compact('kelasList', 'counts'));
    }

    public function show($kelas)
    {
        $kelasRecord = Kelas::where('nama_kelas', $kelas)->firstOrFail();
        $siswa = Siswa::with('user')
            ->where('kelas', $kelasRecord->nama_kelas)
            ->orderBy('nama_siswa')
            ->get();

        $kelas = $kelasRecord->nama_kelas;

        return view('admin.kelas.kelas-show', compact('siswa', 'kelas'));
    }
}
