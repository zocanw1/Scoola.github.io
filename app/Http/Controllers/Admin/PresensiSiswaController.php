<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Support\PresensiRekapBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PresensiSiswaController extends Controller
{
    public function __construct(private readonly PresensiRekapBuilder $presensiRekapBuilder)
    {
    }

    public function index(Request $request)
    {
        $pageContext = $this->resolvePageContext($request);
        $selectedKelas = $this->scopeSelectedKelas($request->input('kelas'), $pageContext['kelas'], $pageContext['isScoped']);
        $search = trim((string) $request->input('q', ''));
        $selectedNis = $request->input('nis');
        $tanggalMulai = $request->input('tanggal_mulai') ?: now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->input('tanggal_akhir') ?: now()->toDateString();

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

        if ($request->boolean('detail') && $selectedNis) {
            return redirect()->route($pageContext['showRoute'], [
                'nis' => $selectedNis,
                'kelas' => $selectedKelas,
                'q' => $search,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_akhir' => $tanggalAkhir,
            ]);
        }

        return view('admin.presensi-siswa.index', [
            'pageLayout' => $pageContext['layout'],
            'pageRoute' => $pageContext['indexRoute'],
            'pageShowRoute' => $pageContext['showRoute'],
            'kelas' => $pageContext['kelas'],
            'siswas' => $siswas,
            'selectedKelas' => $selectedKelas,
            'search' => $search,
            'selectedNis' => $selectedNis,
            'tanggalMulai' => $tanggalMulai,
            'tanggalAkhir' => $tanggalAkhir,
        ]);
    }

    public function show(Request $request, string $nis)
    {
        $pageContext = $this->resolvePageContext($request);
        $tanggalMulai = $request->input('tanggal_mulai') ?: now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->input('tanggal_akhir') ?: now()->toDateString();
        $search = trim((string) $request->input('q', ''));

        $student = Siswa::query()
            ->where('NIS', $nis)
            ->firstOrFail(['NIS', 'nama_siswa', 'kelas']);

        $selectedKelas = $this->scopeSelectedKelas($request->input('kelas') ?: $student->kelas, $pageContext['kelas'], $pageContext['isScoped']);

        if ($selectedKelas !== $student->kelas) {
            abort(404);
        }

        $detailData = $this->presensiRekapBuilder->buildStudentRecapData(
            $selectedKelas,
            $student->nama_siswa,
            Carbon::parse($tanggalMulai),
            Carbon::parse($tanggalAkhir),
            $student->NIS
        );

        abort_if(! $detailData['selectedSiswa'], 404);

        return view('admin.presensi-siswa.show', [
            'pageLayout' => $pageContext['layout'],
            'pageRoute' => $pageContext['indexRoute'],
            'pageShowRoute' => $pageContext['showRoute'],
            'kelas' => $pageContext['kelas'],
            'selectedKelas' => $selectedKelas,
            'search' => $search,
            'selectedNis' => $student->NIS,
            'tanggalMulai' => $tanggalMulai,
            'tanggalAkhir' => $tanggalAkhir,
            'selectedSiswaDetail' => $detailData['selectedSiswa'],
            'breadcrumbSubject' => $detailData['selectedSiswa'],
            'detailRows' => $detailData['studentRows'],
            'detailTotals' => $detailData['studentTotals'],
        ]);
    }

    private function resolveAccess(Request $request): array
    {
        $user = $request->user();

        if ($user?->role !== 'guru') {
            return [
                'kelas' => Kelas::query()->orderBy('nama_kelas')->get(['nama_kelas']),
                'isScoped' => false,
            ];
        }

        $guru = Guru::query()->where('user_id', $user->id)->first();

        if (! $guru) {
            abort(403, 'Akun guru tidak terhubung dengan data pengajar.');
        }

        $kelas = Kelas::query()
            ->where('wali_kelas_nip', $guru->NIP)
            ->orderBy('nama_kelas')
            ->get(['nama_kelas']);

        if ($kelas->isEmpty()) {
            abort(403, 'Akses presensi siswa hanya tersedia untuk wali kelas.');
        }

        return [
            'kelas' => $kelas,
            'isScoped' => true,
        ];
    }

    private function scopeSelectedKelas(?string $selectedKelas, Collection $kelas, bool $isScoped): ?string
    {
        if (! $isScoped) {
            return $selectedKelas;
        }

        if (! $selectedKelas) {
            return $kelas->first()->nama_kelas ?? null;
        }

        if (! $kelas->pluck('nama_kelas')->contains($selectedKelas)) {
            abort(403, 'Anda hanya dapat melihat data presensi siswa di kelas yang Anda ampu.');
        }

        return $selectedKelas;
    }

    private function resolvePageContext(Request $request): array
    {
        $access = $this->resolveAccess($request);

        return [
            'kelas' => $access['kelas'],
            'isScoped' => $access['isScoped'],
            'layout' => $access['isScoped'] ? 'layouts.guru' : 'layouts.admin',
            'indexRoute' => $access['isScoped'] ? 'guru.presensi-siswa.index' : 'admin.presensi-siswa.index',
            'showRoute' => $access['isScoped'] ? 'guru.presensi-siswa.show' : 'admin.presensi-siswa.show',
        ];
    }
}
