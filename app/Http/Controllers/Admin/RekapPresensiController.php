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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class RekapPresensiController extends Controller
{
    private const PERIODS_PER_DAY = 12;
    private const STATUS_LABELS = ['Hadir', 'Izin', 'Sakit', 'Alpa', 'Belum Hadir', 'Ditolak'];

    public function index(Request $request)
    {
        $kelas = Kelas::query()->orderBy('nama_kelas')->get(['nama_kelas']);

        $mode = $request->input('mode') === 'siswa' ? 'siswa' : 'mingguan';
        $selectedKelas = $request->input('kelas');
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
        $selectedNis = $request->input('nis');
        $siswaOptions = $this->getSiswaOptions($selectedKelas);
        $studentData = $mode === 'siswa'
            ? $this->buildStudentRecapData($selectedKelas, $selectedNis, Carbon::parse($tanggalMulai), Carbon::parse($tanggalAkhir))
            : $this->emptyStudentRecapData();

        return view('admin.rekap.index', compact(
            'mode',
            'kelas',
            'selectedKelas',
            'hariList',
            'tanggalInput',
            'startOfWeek',
            'endOfWeek',
            'siswaOptions',
            'selectedNis',
            'tanggalMulai',
            'tanggalAkhir'
        ) + $matrixData + $studentData);
    }

    public function export(Request $request)
    {
        $mode = $request->input('mode') === 'siswa' ? 'siswa' : 'mingguan';
        $selectedKelas = $request->input('kelas');

        if ($mode === 'siswa') {
            $selectedNis = $request->input('nis');
            $tanggalMulai = $request->input('tanggal_mulai') ?: now()->startOfMonth()->toDateString();
            $tanggalAkhir = $request->input('tanggal_akhir') ?: now()->toDateString();

            if (! $selectedKelas || ! $selectedNis) {
                return redirect()->back()->with('error', 'Silakan pilih kelas dan siswa terlebih dahulu.');
            }

            $studentData = $this->buildStudentRecapData(
                $selectedKelas,
                $selectedNis,
                Carbon::parse($tanggalMulai),
                Carbon::parse($tanggalAkhir)
            );

            if (! $studentData['selectedSiswa']) {
                return redirect()->back()->with('error', 'Siswa tidak ditemukan pada kelas yang dipilih.');
            }

            $safeName = preg_replace('/[^A-Za-z0-9_-]+/', '_', $studentData['selectedSiswa']->nama_siswa);
            $filename = "Rekap_Presensi_{$safeName}_{$tanggalMulai}_{$tanggalAkhir}.xls";

            return response(view('admin.rekap.export', compact(
                'mode',
                'selectedKelas',
                'selectedNis',
                'tanggalMulai',
                'tanggalAkhir'
            ) + $studentData))
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

    private function getSiswaOptions(?string $selectedKelas): Collection
    {
        if (! $selectedKelas) {
            return collect();
        }

        return Siswa::query()
            ->where('kelas', $selectedKelas)
            ->orderBy('nama_siswa')
            ->get(['NIS', 'nama_siswa', 'kelas']);
    }

    private function emptyStudentRecapData(): array
    {
        return [
            'selectedSiswa' => null,
            'studentRows' => collect(),
            'studentTotals' => array_fill_keys(self::STATUS_LABELS, 0),
        ];
    }

    private function buildStudentRecapData(?string $selectedKelas, ?string $selectedNis, Carbon $tanggalMulai, Carbon $tanggalAkhir): array
    {
        $data = $this->emptyStudentRecapData();

        if (! $selectedKelas || ! $selectedNis) {
            return $data;
        }

        $start = $tanggalMulai->copy()->startOfDay();
        $end = $tanggalAkhir->copy()->endOfDay();

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        $selectedSiswa = Siswa::query()
            ->where('kelas', $selectedKelas)
            ->where('NIS', $selectedNis)
            ->first(['NIS', 'nama_siswa', 'kelas']);

        if (! $selectedSiswa) {
            return $data;
        }

        $jadwals = JadwalPelajaran::query()
            ->with([
                'mapel:kd_mapel,nama_mapel',
                'guru:NIP,nama_guru',
            ])
            ->where('kelas', $selectedKelas)
            ->get(['kd_jp', 'hari', 'jam_mulai', 'jam_selesai', 'kd_mapel', 'NIP', 'kelas'])
            ->keyBy('kd_jp');

        $heldSessions = SesiPresensi::query()
            ->with([
                'jadwal.mapel:kd_mapel,nama_mapel',
                'jadwal.guru:NIP,nama_guru',
            ])
            ->where('kelas', $selectedKelas)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get(['id', 'guru_id', 'kelas', 'kd_jp', 'kode_presensi', 'waktu_berlaku', 'status', 'created_at']);

        $heldSessionIds = $heldSessions->pluck('id')->values();
        $presensiBySession = $heldSessionIds->isEmpty()
            ? collect()
            : Presensi::query()
                ->where('NIS', $selectedNis)
                ->whereIn('sesi_id', $heldSessionIds)
                ->orderBy('updated_at')
                ->get(['id', 'kd_presensi', 'sesi_id', 'tanggal', 'kd_jp', 'jam_masuk', 'status', 'NIS', 'updated_at'])
                ->keyBy('sesi_id');

        $rows = collect();

        foreach ($heldSessions as $session) {
            $record = $presensiBySession->get($session->id);
            $jadwal = $session->jadwal ?: ($session->kd_jp ? $jadwals->get($session->kd_jp) : null);
            $tanggal = $record && $record->tanggal
                ? Carbon::parse($record->tanggal)
                : $session->created_at->copy();

            $rows->push($this->makeStudentRow(
                $selectedSiswa,
                $record,
                $session,
                $jadwal,
                $tanggal,
                $record->status ?? 'Belum Hadir'
            ));
        }

        $legacyRecords = Presensi::query()
            ->with([
                'jadwal.mapel:kd_mapel,nama_mapel',
                'jadwal.guru:NIP,nama_guru',
                'sesi.jadwal.mapel:kd_mapel,nama_mapel',
                'sesi.jadwal.guru:NIP,nama_guru',
            ])
            ->where('NIS', $selectedNis)
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->when($heldSessionIds->isNotEmpty(), function ($query) use ($heldSessionIds): void {
                $query->where(function ($scope) use ($heldSessionIds): void {
                    $scope->whereNull('sesi_id')
                        ->orWhereNotIn('sesi_id', $heldSessionIds);
                });
            })
            ->where(function ($query) use ($jadwals, $selectedKelas): void {
                $query->whereIn('kd_jp', $jadwals->keys())
                    ->orWhereHas('sesi', function ($sessionQuery) use ($selectedKelas): void {
                        $sessionQuery->where('kelas', $selectedKelas);
                    });
            })
            ->orderBy('tanggal')
            ->orderBy('jam_masuk')
            ->get(['id', 'kd_presensi', 'sesi_id', 'tanggal', 'kd_jp', 'jam_masuk', 'status', 'NIS', 'updated_at']);

        foreach ($legacyRecords as $record) {
            $session = $record->sesi;
            $jadwal = $record->jadwal ?: ($session?->jadwal ?: ($record->kd_jp ? $jadwals->get($record->kd_jp) : null));
            $rows->push($this->makeStudentRow(
                $selectedSiswa,
                $record,
                $session,
                $jadwal,
                Carbon::parse($record->tanggal),
                $record->status
            ));
        }

        $rows = $rows
            ->sortBy([
                ['sort_date', 'asc'],
                ['sort_jam', 'asc'],
                ['mapel', 'asc'],
            ])
            ->values();

        $totals = array_fill_keys(self::STATUS_LABELS, 0);
        foreach ($rows as $row) {
            if (array_key_exists($row['status'], $totals)) {
                $totals[$row['status']]++;
            }
        }

        return [
            'selectedSiswa' => $selectedSiswa,
            'studentRows' => $rows,
            'studentTotals' => $totals,
        ];
    }

    private function makeStudentRow(Siswa $siswa, ?Presensi $record, ?SesiPresensi $session, ?JadwalPelajaran $jadwal, Carbon $tanggal, string $status): array
    {
        return [
            'nis' => $siswa->NIS,
            'nama_siswa' => $siswa->nama_siswa,
            'tanggal' => $tanggal->toDateString(),
            'hari' => $jadwal->hari ?? $this->hariIndonesia($tanggal),
            'jam' => $jadwal ? "Jam {$jadwal->jam_mulai} - {$jadwal->jam_selesai}" : '-',
            'jam_masuk' => $record?->jam_masuk ?: '-',
            'mapel' => $jadwal?->mapel?->nama_mapel ?? $jadwal?->kd_jp ?? $session?->kd_jp ?? '-',
            'guru' => $jadwal?->guru?->nama_guru ?? '-',
            'status' => $status,
            'kd_jp' => $record?->kd_jp ?? $session?->kd_jp,
            'kode_presensi' => $session?->kode_presensi ?? '-',
            'sort_date' => $tanggal->toDateString(),
            'sort_jam' => $jadwal?->jam_mulai ?? 99,
        ];
    }

    private function hariIndonesia(Carbon $date): string
    {
        return [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ][$date->format('l')];
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
