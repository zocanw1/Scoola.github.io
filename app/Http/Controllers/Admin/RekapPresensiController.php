<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use Carbon\Carbon;
use App\Support\PresensiRekapBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class RekapPresensiController extends Controller
{
    private const PERIODS_PER_DAY = 12;

    public function __construct(private readonly PresensiRekapBuilder $presensiRekapBuilder)
    {
    }

    public function index(Request $request)
    {
        $access = $this->resolveRekapAccess($request);
        $kelas = $access['kelas'];
        $rekapLayout = $access['isScoped'] ? 'layouts.guru' : 'layouts.admin';
        $rekapIndexRoute = $access['isScoped'] ? 'guru.rekap.index' : 'admin.rekap.index';
        $rekapExportRoute = $access['isScoped'] ? 'guru.rekap.export' : 'admin.rekap.export';

        $mode = in_array($request->input('mode'), ['siswa', 'rentang'], true) ? $request->input('mode') : 'mingguan';
        $selectedKelas = $this->scopeSelectedKelas($request->input('kelas'), $kelas, $access['isScoped']);
        $tanggalInput = $request->input('tanggal') ?: now()->toDateString();
        $carbonDate = Carbon::parse($tanggalInput);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY)->addDays(4); // Jumat

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $matrixData = $mode === 'mingguan'
            ? $this->buildWeeklyMatrixData($selectedKelas, $startOfWeek, $endOfWeek, $hariList)
            : $this->emptyWeeklyMatrixData($hariList);

        $tanggalMulai = $request->input('tanggal_mulai') ?: now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->input('tanggal_akhir') ?: now()->toDateString();
        $selectedNamaSiswa = trim((string) $request->input('nama_siswa', ''));
        $selectedNis = $request->input('nis');
        $siswaOptions = $mode === 'siswa' ? $this->presensiRekapBuilder->getSiswaOptions($selectedKelas) : collect();
        $siswaSearchResults = $mode === 'siswa'
            ? $this->presensiRekapBuilder->filterSiswaByName($siswaOptions, $selectedNamaSiswa)
            : collect();
        $studentData = $mode === 'siswa' && $selectedNis
            ? $this->presensiRekapBuilder->buildStudentRecapData($selectedKelas, $selectedNamaSiswa, Carbon::parse($tanggalMulai), Carbon::parse($tanggalAkhir), $selectedNis)
            : $this->presensiRekapBuilder->emptyStudentRecapData();
        $rangeSummaryData = $mode === 'rentang'
            ? $this->presensiRekapBuilder->buildRangeSummaryData($selectedKelas, Carbon::parse($tanggalMulai), Carbon::parse($tanggalAkhir))
            : $this->presensiRekapBuilder->emptyRangeSummaryData();

        if ($selectedNamaSiswa === '' && $studentData['selectedSiswa']) {
            $selectedNamaSiswa = $studentData['selectedSiswa']->nama_siswa;
        }

        return view('admin.rekap.index', compact(
            'mode',
            'kelas',
            'selectedKelas',
            'hariList',
            'tanggalInput',
            'startOfWeek',
            'endOfWeek',
            'selectedNamaSiswa',
            'selectedNis',
            'siswaOptions',
            'siswaSearchResults',
            'tanggalMulai',
            'tanggalAkhir',
            'rekapLayout',
            'rekapIndexRoute',
            'rekapExportRoute'
        ) + $matrixData + $studentData + $rangeSummaryData);
    }

    public function export(Request $request)
    {
        $access = $this->resolveRekapAccess($request);
        $mode = in_array($request->input('mode'), ['siswa', 'rentang'], true) ? $request->input('mode') : 'mingguan';
        $selectedKelas = $this->scopeSelectedKelas($request->input('kelas'), $access['kelas'], $access['isScoped']);

        if ($mode === 'siswa') {
            $selectedNamaSiswa = trim((string) $request->input('nama_siswa', ''));
            $legacySelectedNis = $request->input('nis');
            $tanggalMulai = $request->input('tanggal_mulai') ?: now()->startOfMonth()->toDateString();
            $tanggalAkhir = $request->input('tanggal_akhir') ?: now()->toDateString();

            if (! $selectedKelas || ($selectedNamaSiswa === '' && ! $legacySelectedNis)) {
                return redirect()->back()->with('error', 'Silakan pilih kelas dan isi nama siswa terlebih dahulu.');
            }

            $studentData = $this->presensiRekapBuilder->buildStudentRecapData(
                $selectedKelas,
                $selectedNamaSiswa,
                Carbon::parse($tanggalMulai),
                Carbon::parse($tanggalAkhir),
                $legacySelectedNis
            );

            if (! $studentData['selectedSiswa']) {
                return redirect()->back()->with('error', 'Siswa tidak ditemukan pada kelas yang dipilih.');
            }

            $safeName = preg_replace('/[^A-Za-z0-9_-]+/', '_', $studentData['selectedSiswa']->nama_siswa);
            $filename = "Rekap_Presensi_{$safeName}_{$tanggalMulai}_{$tanggalAkhir}.xls";

            return response(view('admin.rekap.export', compact(
                'mode',
                'selectedKelas',
                'selectedNamaSiswa',
                'tanggalMulai',
                'tanggalAkhir'
            ) + $studentData))
                ->header('Content-Type', 'application/vnd-ms-excel')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        if ($mode === 'rentang') {
            $tanggalMulai = $request->input('tanggal_mulai') ?: now()->startOfMonth()->toDateString();
            $tanggalAkhir = $request->input('tanggal_akhir') ?: now()->toDateString();

            if (! $selectedKelas) {
                return redirect()->back()->with('error', 'Silakan pilih kelas terlebih dahulu.');
            }

            $rangeSummaryData = $this->presensiRekapBuilder->buildRangeSummaryData(
                $selectedKelas,
                Carbon::parse($tanggalMulai),
                Carbon::parse($tanggalAkhir)
            );

            $filename = "Rekap_Presensi_Rentang_{$selectedKelas}_{$tanggalMulai}_{$tanggalAkhir}.xls";

            return response(view('admin.rekap.export', compact(
                'mode',
                'selectedKelas',
                'tanggalMulai',
                'tanggalAkhir'
            ) + $rangeSummaryData))
                ->header('Content-Type', 'application/vnd-ms-excel')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        $tanggalInput = $request->input('tanggal') ?: now()->toDateString();
        $carbonDate = Carbon::parse($tanggalInput);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY)->addDays(4); // Jumat

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        if (!$selectedKelas) {
            return redirect()->back()->with('error', 'Silakan pilih kelas terlebih dahulu.');
        }

        $matrixData = $this->buildWeeklyMatrixData($selectedKelas, $startOfWeek, $endOfWeek, $hariList);

        $filename = "Rekap_Presensi_{$selectedKelas}_" . $startOfWeek->format('d-M-Y') . ".xls";

        return response(view('admin.rekap.export', compact(
            'mode',
            'selectedKelas',
            'hariList',
            'tanggalInput',
            'startOfWeek',
            'endOfWeek'
        ) + $matrixData))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function resolveRekapAccess(Request $request): array
    {
        $user = $request->user();
        $isScoped = $user?->role === 'guru';

        if (! $isScoped) {
            return [
                'kelas' => Kelas::query()->orderBy('nama_kelas')->get(['nama_kelas']),
                'isScoped' => false,
            ];
        }

        $guru = $user->guru;

        if (! $guru) {
            abort(403, 'Akun guru tidak terhubung dengan data pengajar.');
        }

        $kelas = Kelas::query()
            ->where('wali_kelas_nip', $guru->NIP)
            ->orderBy('nama_kelas')
            ->get(['nama_kelas']);

        if ($kelas->isEmpty()) {
            abort(403, 'Akses rekap hanya tersedia untuk wali kelas.');
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
            abort(403, 'Anda hanya dapat melihat rekap kelas yang Anda ampu.');
        }

        return $selectedKelas;
    }

    private function emptyWeeklyMatrixData(array $hariList): array
    {
        return [
            'siswas' => collect(),
            'jadwals' => collect(),
            'presensiMap' => [],
            'slotMatrix' => $this->emptySlotMatrix($hariList),
            'statusMatrix' => [],
        ];
    }

    private function buildWeeklyMatrixData(?string $selectedKelas, Carbon $startOfWeek, Carbon $endOfWeek, array $hariList): array
    {
        $siswas = collect();
        $jadwals = collect();
        $presensiMap = [];
        $slotMatrix = $this->emptySlotMatrix($hariList);
        $statusMatrix = [];

        if (! $selectedKelas) {
            return compact('siswas', 'jadwals', 'presensiMap', 'slotMatrix', 'statusMatrix');
        }

        $siswaColumns = ['NIS', 'nama_siswa'];
        if (Schema::hasColumn('siswa', 'jenis_kelamin')) {
            $siswaColumns[] = 'jenis_kelamin';
        }

        $siswas = Siswa::query()
            ->where('kelas', $selectedKelas)
            ->orderBy('nama_siswa')
            ->get($siswaColumns);

        $jadwals = JadwalPelajaran::query()
            ->with([
                'mapel:kd_mapel,nama_mapel',
                'guru:NIP,nama_guru',
            ])
            ->where('kelas', $selectedKelas)
            ->whereIn('hari', $hariList)
            ->orderByRaw("CASE hari
                WHEN 'Senin' THEN 1
                WHEN 'Selasa' THEN 2
                WHEN 'Rabu' THEN 3
                WHEN 'Kamis' THEN 4
                WHEN 'Jumat' THEN 5
                ELSE 6
            END")
            ->orderBy('jam_mulai')
            ->get(['kd_jp', 'hari', 'jam_mulai', 'jam_selesai', 'kd_mapel', 'NIP', 'kelas']);

        $slotMatrix = $this->buildSlotMatrix($jadwals, $hariList);

        $kdJpList = $jadwals->pluck('kd_jp')->filter()->values();
        if ($kdJpList->isNotEmpty()) {
            $heldSessions = SesiPresensi::query()
                ->select(['id', 'kd_jp'])
                ->where('kelas', $selectedKelas)
                ->whereIn('kd_jp', $kdJpList)
                ->whereBetween('created_at', [
                    $startOfWeek->copy()->startOfDay(),
                    $endOfWeek->copy()->endOfDay(),
                ])
                ->get();

            $heldSessionIds = $heldSessions->pluck('id')->values();
            $heldSessionMap = $heldSessions
                ->pluck('kd_jp')
                ->filter()
                ->unique()
                ->mapWithKeys(fn (string $kdJp): array => [$kdJp => true])
                ->all();

            $presensiRecords = Presensi::query()
                ->leftJoin('sesi_presensis', 'sesi_presensis.id', '=', 'presensi.sesi_id')
                ->select([
                    'presensi.NIS',
                    'presensi.status',
                    'presensi.updated_at',
                ])
                ->selectRaw('COALESCE(presensi.kd_jp, sesi_presensis.kd_jp) as effective_kd_jp')
                ->where(function ($query) use ($kdJpList, $heldSessionIds): void {
                    $query->whereIn('presensi.kd_jp', $kdJpList);

                    if ($heldSessionIds->isNotEmpty()) {
                        $query->orWhereIn('presensi.sesi_id', $heldSessionIds);
                    }
                })
                ->where(function ($query) use ($startOfWeek, $endOfWeek, $heldSessionIds): void {
                    $query->whereBetween('presensi.tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()]);

                    if ($heldSessionIds->isNotEmpty()) {
                        $query->orWhereIn('presensi.sesi_id', $heldSessionIds);
                    }
                })
                ->orderBy('presensi.updated_at')
                ->get();

            foreach ($presensiRecords as $record) {
                if (! $record->effective_kd_jp) {
                    continue;
                }

                $presensiMap[$record->NIS][$record->effective_kd_jp] = $record->status;
            }
        } else {
            $heldSessionMap = [];
        }

        $statusMatrix = $this->buildStatusMatrix($siswas, $slotMatrix, $hariList, $presensiMap, $heldSessionMap);

        return compact('siswas', 'jadwals', 'presensiMap', 'slotMatrix', 'statusMatrix');
    }

    private function emptySlotMatrix(array $hariList): array
    {
        $matrix = [];

        foreach ($hariList as $hari) {
            for ($jam = 1; $jam <= self::PERIODS_PER_DAY; $jam++) {
                $matrix[$hari][$jam] = [
                    'kd_jp' => null,
                    'mapel' => '-',
                    'guru' => '-',
                ];
            }
        }

        return $matrix;
    }

    private function buildSlotMatrix(Collection $jadwals, array $hariList): array
    {
        $slotMatrix = $this->emptySlotMatrix($hariList);

        foreach ($jadwals as $jadwal) {
            $start = max(1, (int) $jadwal->jam_mulai);
            $end = min(self::PERIODS_PER_DAY, (int) $jadwal->jam_selesai);

            for ($jam = $start; $jam <= $end; $jam++) {
                $slotMatrix[$jadwal->hari][$jam] = [
                    'kd_jp' => $jadwal->kd_jp,
                    'mapel' => $jadwal->mapel->nama_mapel ?? $jadwal->kd_jp,
                    'guru' => $jadwal->guru->nama_guru ?? '-',
                ];
            }
        }

        return $slotMatrix;
    }

    private function buildStatusMatrix(Collection $siswas, array $slotMatrix, array $hariList, array $presensiMap, array $heldSessionMap): array
    {
        $statusMatrix = [];

        foreach ($siswas as $siswa) {
            foreach ($hariList as $hari) {
                for ($jam = 1; $jam <= self::PERIODS_PER_DAY; $jam++) {
                    $kdJp = $slotMatrix[$hari][$jam]['kd_jp'] ?? null;
                    if (! $kdJp) {
                        $statusMatrix[$siswa->NIS][$hari][$jam] = null;
                        continue;
                    }

                    $statusMatrix[$siswa->NIS][$hari][$jam] = $presensiMap[$siswa->NIS][$kdJp]
                        ?? (isset($heldSessionMap[$kdJp]) ? 'Belum Hadir' : null);
                }
            }
        }

        return $statusMatrix;
    }
}
