<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Support\PresensiRekapBuilder;
use App\Support\PresensiStatusManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PresensiSiswaController extends Controller
{
    public function __construct(
        private readonly PresensiRekapBuilder $presensiRekapBuilder,
        private readonly PresensiStatusManager $presensiStatusManager
    )
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

        if ($selectedNis && ($request->boolean('detail') || $request->filled('tanggal_mulai') || $request->filled('tanggal_akhir'))) {
            return $this->show($request, $selectedNis);
        }

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

        $alpaQueue = $this->presensiRekapBuilder->buildCorrectionQueue(
            $selectedKelas,
            Carbon::parse($tanggalMulai),
            Carbon::parse($tanggalAkhir),
            $search
        );

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
            'alpaQueue' => $alpaQueue,
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
            'statusOptions' => PresensiStatusManager::ALLOWED_STATUSES,
        ]);
    }

    public function redirectStatusUrl(Request $request, string $nis)
    {
        return redirect()->route($request->user()?->role === 'guru' ? 'guru.presensi-siswa.show' : 'admin.presensi-siswa.show', [
            'nis' => $nis,
            'kelas' => $request->input('kelas'),
            'q' => $request->input('q'),
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
        ]);
    }

    public function updateStatus(Request $request, string $nis)
    {
        abort_unless($request->user()?->role === 'admin', 403, 'Akses ditolak');

        $validated = $request->validate([
            'presensi_id' => 'nullable|string|required_without:sesi_id',
            'sesi_id' => 'nullable|integer|exists:sesi_presensis,id|required_without:presensi_id',
            'status' => 'required|in:' . implode(',', PresensiStatusManager::ALLOWED_STATUSES),
            'correction_reason' => 'required|string|max:1000',
            'kelas' => 'nullable|string',
            'q' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
        ]);

        $this->presensiStatusManager->correctStatus(
            $nis,
            $validated['presensi_id'] ?? null,
            $validated['sesi_id'] ?? null,
            $validated['status'],
            $validated['correction_reason'],
            $request->user()
        );

        return redirect()->route('admin.presensi-siswa.show', [
            'nis' => $nis,
            'kelas' => $validated['kelas'] ?? null,
            'q' => $validated['q'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_akhir' => $validated['tanggal_akhir'] ?? null,
        ])->with('success', 'Status presensi berhasil diperbarui.');
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
