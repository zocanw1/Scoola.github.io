<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Support\PresensiRekapBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresensiSiswaController extends Controller
{
    public function __construct(private readonly PresensiRekapBuilder $presensiRekapBuilder)
    {
    }

    public function index(Request $request)
    {
        $selectedKelas = $request->input('kelas') ?: null;
        $search = trim((string) $request->input('q', ''));
        $selectedNis = $request->input('nis');
        $tanggalMulai = $request->input('tanggal_mulai') ?: now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->input('tanggal_akhir') ?: now()->toDateString();

        $kelas = Kelas::query()->orderBy('nama_kelas')->get(['nama_kelas']);

        $siswas = Siswa::query()
            ->when($selectedKelas, function ($query, string $selectedKelas): void {
                $query->where('kelas', $selectedKelas);
            })
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($scope) use ($search): void {
                    $scope->where('nama_siswa', 'like', '%' . $search . '%')
                        ->orWhere('NIS', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('nama_siswa')
            ->get(['NIS', 'nama_siswa', 'kelas']);

        $detailKelas = $selectedKelas;
        if ($selectedNis && ! $detailKelas) {
            $detailKelas = Siswa::query()
                ->where('NIS', $selectedNis)
                ->value('kelas');
        }

        $detailData = $selectedNis
            ? $this->presensiRekapBuilder->buildStudentRecapData(
                $detailKelas,
                $search,
                Carbon::parse($tanggalMulai),
                Carbon::parse($tanggalAkhir),
                $selectedNis
            )
            : $this->presensiRekapBuilder->emptyStudentRecapData();

        return view('admin.presensi-siswa.index', [
            'kelas' => $kelas,
            'siswas' => $siswas,
            'selectedKelas' => $selectedKelas,
            'search' => $search,
            'selectedNis' => $selectedNis,
            'tanggalMulai' => $tanggalMulai,
            'tanggalAkhir' => $tanggalAkhir,
            'selectedSiswaDetail' => $detailData['selectedSiswa'],
            'detailRows' => $detailData['studentRows'],
            'detailTotals' => $detailData['studentTotals'],
        ]);
    }
}
