<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Siswa::distinct()->pluck('kelas')->sort()->toArray();

        $counts = Siswa::select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->pluck('total', 'kelas');

        return view('admin.kelas.kelas-index', compact('kelasList', 'counts'));
    }

    public function show($kelas)
    {
        $siswa = Siswa::with('user')
            ->where('kelas', $kelas)
            ->orderBy('nama_siswa')
            ->get();

        return view('admin.kelas.kelas-show', compact('siswa', 'kelas'));
    }
}
