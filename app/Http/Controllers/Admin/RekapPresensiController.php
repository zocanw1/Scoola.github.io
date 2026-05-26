<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class RekapPresensiController extends Controller
{
    private const PERIODS_PER_DAY = 12;

    public function index(Request $request)
    {
        $kelas = Kelas::query()->orderBy('nama_kelas')->get(['nama_kelas']);
        
        $selectedKelas = $request->input('kelas');
        $tanggalInput = $request->input('tanggal') ?: now()->toDateString();
        $carbonDate = Carbon::parse($tanggalInput);
        $startOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $carbonDate->copy()->startOfWeek(Carbon::MONDAY)->addDays(4); // Jumat

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $matrixData = $this->buildWeeklyMatrixData($selectedKelas, $startOfWeek, $endOfWeek, $hariList);

        return view('admin.rekap.index', compact(
            'kelas',
            'selectedKelas',
            'hariList',
            'tanggalInput',
            'startOfWeek',
            'endOfWeek'
        ) + $matrixData);
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

        $matrixData = $this->buildWeeklyMatrixData($selectedKelas, $startOfWeek, $endOfWeek, $hariList);

        $filename = "Rekap_Presensi_{$selectedKelas}_" . $startOfWeek->format('d-M-Y') . ".xls";

        return response(view('admin.rekap.export', compact(
            'selectedKelas',
            'hariList',
            'tanggalInput',
            'startOfWeek',
            'endOfWeek'
        ) + $matrixData))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
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
            $presensiRecords = Presensi::query()
                ->select(['NIS', 'kd_jp', 'status'])
                ->whereIn('kd_jp', $kdJpList)
                ->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->get();

            foreach ($presensiRecords as $record) {
                $presensiMap[$record->NIS][$record->kd_jp] = $record->status;
            }
        }

        $statusMatrix = $this->buildStatusMatrix($siswas, $slotMatrix, $hariList, $presensiMap);

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

    private function buildStatusMatrix(Collection $siswas, array $slotMatrix, array $hariList, array $presensiMap): array
    {
        $statusMatrix = [];

        foreach ($siswas as $siswa) {
            foreach ($hariList as $hari) {
                for ($jam = 1; $jam <= self::PERIODS_PER_DAY; $jam++) {
                    $kdJp = $slotMatrix[$hari][$jam]['kd_jp'] ?? null;
                    $statusMatrix[$siswa->NIS][$hari][$jam] = $kdJp ? ($presensiMap[$siswa->NIS][$kdJp] ?? null) : null;
                }
            }
        }

        return $statusMatrix;
    }
}
