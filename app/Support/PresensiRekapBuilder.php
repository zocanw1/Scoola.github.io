<?php

namespace App\Support;

use App\Models\JadwalPelajaran;
use App\Models\Presensi;
use App\Models\PresensiStatusHistory;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PresensiRekapBuilder
{
    public const STATUS_LABELS = ['Hadir', 'Izin', 'Sakit', 'Alpa', 'Belum Hadir', 'Ditolak'];

    public function emptyStudentRecapData(): array
    {
        return [
            'selectedSiswa' => null,
            'studentRows' => collect(),
            'studentTotals' => array_fill_keys(self::STATUS_LABELS, 0),
        ];
    }

    public function emptyRangeSummaryData(): array
    {
        return [
            'rangeSummaryRows' => collect(),
            'rangeSummaryTotals' => array_fill_keys(self::STATUS_LABELS, 0),
        ];
    }

    public function getSiswaOptions(?string $selectedKelas): Collection
    {
        if (! $selectedKelas) {
            return collect();
        }

        return Siswa::query()
            ->where('kelas', $selectedKelas)
            ->orderBy('nama_siswa')
            ->get(['NIS', 'nama_siswa', 'kelas']);
    }

    public function filterSiswaByName(Collection $siswaOptions, string $keyword): Collection
    {
        $needle = strtolower(trim($keyword));

        if ($needle === '') {
            return collect();
        }

        return $siswaOptions
            ->filter(function (Siswa $siswa) use ($needle): bool {
                return str_contains(strtolower($siswa->nama_siswa), $needle)
                    || str_contains(strtolower($siswa->NIS), $needle);
            })
            ->values();
    }

    public function buildStudentRecapData(?string $selectedKelas, ?string $selectedNamaSiswa, Carbon $tanggalMulai, Carbon $tanggalAkhir, ?string $selectedNis = null): array
    {
        $data = $this->emptyStudentRecapData();
        $selectedNamaSiswa = trim((string) $selectedNamaSiswa);

        if (! $selectedKelas || ($selectedNamaSiswa === '' && ! $selectedNis)) {
            return $data;
        }

        [$start, $end] = $this->normalizeDateRange($tanggalMulai, $tanggalAkhir);

        $siswaQuery = Siswa::query()->where('kelas', $selectedKelas);
        if ($selectedNis) {
            $selectedSiswa = (clone $siswaQuery)
                ->where('NIS', $selectedNis)
                ->first(['NIS', 'nama_siswa', 'kelas']);
        } else {
            $normalizedName = strtolower($selectedNamaSiswa);
            $selectedSiswa = (clone $siswaQuery)
                ->whereRaw('LOWER(nama_siswa) = ?', [$normalizedName])
                ->first(['NIS', 'nama_siswa', 'kelas']);

            if (! $selectedSiswa) {
                $selectedSiswa = (clone $siswaQuery)
                    ->where('nama_siswa', 'like', '%' . $selectedNamaSiswa . '%')
                    ->orderBy('nama_siswa')
                    ->first(['NIS', 'nama_siswa', 'kelas']);
            }
        }

        if (! $selectedSiswa) {
            return $data;
        }

        $selectedNis = $selectedSiswa->NIS;

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
                $record->status ?? $this->resolveImplicitStatusForSession($session)
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

        $rows = $this->attachCorrectionMetadata($rows);

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

    public function buildRangeSummaryData(?string $selectedKelas, Carbon $tanggalMulai, Carbon $tanggalAkhir): array
    {
        if (! $selectedKelas) {
            return $this->emptyRangeSummaryData();
        }

        $siswas = $this->getSiswaOptions($selectedKelas);
        [$start, $end] = $this->normalizeDateRange($tanggalMulai, $tanggalAkhir);

        $rowsByNis = $siswas->mapWithKeys(function (Siswa $siswa): array {
            return [$siswa->NIS => [
                'nis' => $siswa->NIS,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas' => $siswa->kelas,
                'totals' => array_fill_keys(self::STATUS_LABELS, 0),
                'total_records' => 0,
            ]];
        });

        if ($rowsByNis->isEmpty()) {
            return $this->emptyRangeSummaryData();
        }

        $nisList = $rowsByNis->keys()->values();
        $jadwalKeys = JadwalPelajaran::query()
            ->where('kelas', $selectedKelas)
            ->pluck('kd_jp')
            ->filter()
            ->values();

        $heldSessions = SesiPresensi::query()
            ->where('kelas', $selectedKelas)
            ->whereBetween('created_at', [$start, $end])
            ->get(['id', 'status']);

        $implicitSessionTotals = array_fill_keys(self::STATUS_LABELS, 0);
        $implicitSessionStatuses = $heldSessions
            ->mapWithKeys(function (SesiPresensi $session) use (&$implicitSessionTotals): array {
                $status = $this->resolveImplicitStatusForSession($session);
                $implicitSessionTotals[$status]++;

                return [$session->id => $status];
            })
            ->all();

        $baseRecordCount = count($implicitSessionStatuses);

        $rowsByNis = $rowsByNis->map(function (array $row) use ($implicitSessionTotals, $baseRecordCount): array {
            $row['totals'] = $implicitSessionTotals;
            $row['total_records'] = $baseRecordCount;

            return $row;
        });

        $heldSessionIds = array_keys($implicitSessionStatuses);

        if ($heldSessionIds !== []) {
            $sessionRecords = Presensi::query()
                ->whereIn('NIS', $nisList)
                ->whereIn('sesi_id', $heldSessionIds)
                ->orderBy('updated_at')
                ->get(['sesi_id', 'status', 'NIS']);

            $latestSessionRecords = $sessionRecords
                ->groupBy(fn (Presensi $record): string => $record->NIS . '|' . $record->sesi_id)
                ->map(fn (Collection $records): ?Presensi => $records->last())
                ->filter();

            foreach ($latestSessionRecords as $record) {
                $implicitStatus = $implicitSessionStatuses[$record->sesi_id] ?? null;

                if (! $rowsByNis->has($record->NIS)) {
                    continue;
                }

                $row = $rowsByNis->get($record->NIS);

                if ($implicitStatus && array_key_exists($implicitStatus, $row['totals'])) {
                    $row['totals'][$implicitStatus] = max(0, $row['totals'][$implicitStatus] - 1);
                }

                if (array_key_exists($record->status, $row['totals'])) {
                    $row['totals'][$record->status]++;
                }

                $rowsByNis->put($record->NIS, $row);
            }
        }

        $legacyRecords = Presensi::query()
            ->leftJoin('sesi_presensis', 'sesi_presensis.id', '=', 'presensi.sesi_id')
            ->select(['presensi.NIS', 'presensi.status'])
            ->whereIn('presensi.NIS', $nisList)
            ->whereBetween('presensi.tanggal', [$start->toDateString(), $end->toDateString()])
            ->when($heldSessionIds !== [], function ($query) use ($heldSessionIds): void {
                $query->where(function ($scope) use ($heldSessionIds): void {
                    $scope->whereNull('presensi.sesi_id')
                        ->orWhereNotIn('presensi.sesi_id', $heldSessionIds);
                });
            })
            ->where(function ($query) use ($jadwalKeys, $selectedKelas): void {
                if ($jadwalKeys->isNotEmpty()) {
                    $query->whereIn('presensi.kd_jp', $jadwalKeys)
                        ->orWhere('sesi_presensis.kelas', $selectedKelas);

                    return;
                }

                $query->where('sesi_presensis.kelas', $selectedKelas);
            })
            ->orderBy('presensi.tanggal')
            ->orderBy('presensi.jam_masuk')
            ->get();

        foreach ($legacyRecords as $record) {
            if (! $rowsByNis->has($record->NIS)) {
                continue;
            }

            $row = $rowsByNis->get($record->NIS);

            if (array_key_exists($record->status, $row['totals'])) {
                $row['totals'][$record->status]++;
            }

            $row['total_records']++;
            $rowsByNis->put($record->NIS, $row);
        }

        $rows = $rowsByNis->values();

        $grandTotals = array_fill_keys(self::STATUS_LABELS, 0);
        foreach ($rows as $row) {
            foreach ($row['totals'] as $status => $count) {
                $grandTotals[$status] += $count;
            }
        }

        return [
            'rangeSummaryRows' => $rows,
            'rangeSummaryTotals' => $grandTotals,
        ];
    }

    public function buildCorrectionQueue(?string $selectedKelas, Carbon $tanggalMulai, Carbon $tanggalAkhir, string $search = ''): Collection
    {
        [$start, $end] = $this->normalizeDateRange($tanggalMulai, $tanggalAkhir);
        $needle = trim(strtolower($search));

        return Presensi::query()
            ->with([
                'siswa:NIS,nama_siswa,kelas',
                'jadwal:kd_jp,kd_mapel,NIP,jam_mulai,jam_selesai',
                'jadwal.mapel:kd_mapel,nama_mapel',
            ])
            ->where('status', 'Alpa')
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->when($selectedKelas, function ($query, string $kelas): void {
                $query->whereHas('siswa', function ($studentQuery) use ($kelas): void {
                    $studentQuery->where('kelas', $kelas);
                });
            })
            ->when($needle !== '', function ($query) use ($needle): void {
                $query->where(function ($scope) use ($needle): void {
                    $scope->whereHas('siswa', function ($studentQuery) use ($needle): void {
                        $studentQuery->whereRaw('LOWER(nama_siswa) like ?', ['%' . $needle . '%'])
                            ->orWhereRaw('LOWER(NIS) like ?', ['%' . $needle . '%']);
                    });
                });
            })
            ->orderByDesc('tanggal')
            ->orderBy('jam_masuk')
            ->get(['id', 'sesi_id', 'tanggal', 'kd_jp', 'jam_masuk', 'status', 'NIS'])
            ->map(function (Presensi $presensi): array {
                return [
                    'presensi_id' => $presensi->id,
                    'sesi_id' => $presensi->sesi_id,
                    'nis' => $presensi->NIS,
                    'nama_siswa' => $presensi->siswa?->nama_siswa ?? $presensi->NIS,
                    'kelas' => $presensi->siswa?->kelas ?? '-',
                    'tanggal' => $presensi->tanggal,
                    'jam' => $presensi->jadwal ? "Jam {$presensi->jadwal->jam_mulai} - {$presensi->jadwal->jam_selesai}" : '-',
                    'mapel' => $presensi->jadwal?->mapel?->nama_mapel ?? $presensi->kd_jp ?? '-',
                    'status' => $presensi->status,
                ];
            })
            ->values();
    }

    private function normalizeDateRange(Carbon $tanggalMulai, Carbon $tanggalAkhir): array
    {
        $start = $tanggalMulai->copy()->startOfDay();
        $end = $tanggalAkhir->copy()->endOfDay();

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        return [$start, $end];
    }

    private function makeStudentRow(Siswa $siswa, ?Presensi $record, ?SesiPresensi $session, ?JadwalPelajaran $jadwal, Carbon $tanggal, string $status): array
    {
        return [
            'presensi_id' => $record?->id,
            'sesi_id' => $record?->sesi_id ?? $session?->id,
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

    private function attachCorrectionMetadata(Collection $rows): Collection
    {
        $presensiIds = $rows->pluck('presensi_id')->filter()->values();

        if ($presensiIds->isEmpty()) {
            return $rows->map(fn (array $row): array => $row + [
                'latest_correction_reason' => null,
                'latest_correction_at' => null,
                'latest_correction_by' => null,
                'correction_history' => collect(),
            ]);
        }

        $historyMap = PresensiStatusHistory::query()
            ->with('changedBy:id,name')
            ->whereIn('presensi_id', $presensiIds)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('presensi_id');

        return $rows->map(function (array $row) use ($historyMap): array {
            $history = collect($historyMap->get($row['presensi_id'], collect()))
                ->map(fn (PresensiStatusHistory $entry): array => [
                    'old_status' => $entry->old_status,
                    'new_status' => $entry->new_status,
                    'reason' => $entry->reason,
                    'changed_at' => optional($entry->created_at)->toDateTimeString(),
                    'changed_by' => $entry->changedBy?->name ?? '-',
                ])
                ->values();

            $latest = $history->first();

            return $row + [
                'latest_correction_reason' => $latest['reason'] ?? null,
                'latest_correction_at' => $latest['changed_at'] ?? null,
                'latest_correction_by' => $latest['changed_by'] ?? null,
                'correction_history' => $history,
            ];
        });
    }

    private function resolveImplicitStatusForSession(?SesiPresensi $session): string
    {
        if (! $session) {
            return 'Belum Hadir';
        }

        return $session->status === 'selesai' ? 'Alpa' : 'Belum Hadir';
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
}
