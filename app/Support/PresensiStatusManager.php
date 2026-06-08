<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\Presensi;
use App\Models\PresensiStatusHistory;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PresensiStatusManager
{
    public const ALLOWED_STATUSES = ['Hadir', 'Izin', 'Sakit', 'Alpa', 'Belum Hadir', 'Ditolak'];
    public const CORRECTION_STATUSES = ['Hadir', 'Izin', 'Sakit', 'Alpa'];

    public function finalizeMissingStudentsAsAlpa(SesiPresensi $sesi): void
    {
        $sessionDate = $this->resolveSessionDate($sesi);

        $existingPresensi = Presensi::query()
            ->where('sesi_id', $sesi->id)
            ->get(['NIS', 'status'])
            ->keyBy('NIS');

        Siswa::query()
            ->where('kelas', $sesi->kelas)
            ->orderBy('nama_siswa')
            ->get(['NIS'])
            ->each(function (Siswa $siswa) use ($existingPresensi, $sesi, $sessionDate): void {
                if ($existingPresensi->has($siswa->NIS)) {
                    return;
                }

                Presensi::create([
                    'kd_presensi' => $this->generatePresensiCode($sessionDate),
                    'sesi_id' => $sesi->id,
                    'tanggal' => $sessionDate->toDateString(),
                    'kd_jp' => $sesi->kd_jp,
                    'jam_masuk' => null,
                    'status' => 'Alpa',
                    'NIS' => $siswa->NIS,
                ]);
            });
    }

    public function correctStatus(string $nis, ?string $presensiId, ?int $sesiId, string $newStatus, string $reason, User $actor): Presensi
    {
        return DB::transaction(function () use ($nis, $presensiId, $sesiId, $newStatus, $reason, $actor): Presensi {
            $presensi = $this->resolvePresensiForCorrection($nis, $presensiId, $sesiId);
            $oldStatus = $presensi->status;

            $presensi->update([
                'status' => $newStatus,
            ]);

            PresensiStatusHistory::create([
                'presensi_id' => $presensi->id,
                'sesi_id' => $presensi->sesi_id,
                'nis' => $nis,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $reason,
                'changed_by' => $actor->id,
            ]);

            ActivityLog::log("Koreksi presensi {$nis}: {$oldStatus} -> {$newStatus}. Alasan: {$reason}");

            return $presensi->fresh();
        });
    }

    public function resolveImplicitStatusForSession(?SesiPresensi $sesi): string
    {
        if (! $sesi) {
            return 'Belum Hadir';
        }

        return $sesi->status === 'selesai' ? 'Alpa' : 'Belum Hadir';
    }

    private function resolvePresensiForCorrection(string $nis, ?string $presensiId, ?int $sesiId): Presensi
    {
        if ($presensiId) {
            $presensi = Presensi::query()->where('id', $presensiId)->where('NIS', $nis)->first();

            if ($presensi) {
                return $presensi;
            }
        }

        if (! $sesiId) {
            throw new ModelNotFoundException('Data presensi tidak ditemukan.');
        }

        $sesi = SesiPresensi::query()->findOrFail($sesiId);
        $sessionDate = $this->resolveSessionDate($sesi);

        return Presensi::firstOrCreate(
            [
                'sesi_id' => $sesi->id,
                'NIS' => $nis,
            ],
            [
                'kd_presensi' => $this->generatePresensiCode($sessionDate),
                'tanggal' => $sessionDate->toDateString(),
                'kd_jp' => $sesi->kd_jp,
                'jam_masuk' => null,
                'status' => $this->resolveImplicitStatusForSession($sesi),
            ]
        );
    }

    private function generatePresensiCode(Carbon $date): string
    {
        return 'PRS-' . $date->format('Ymd') . '-' . strtoupper(Str::random(6));
    }

    private function resolveSessionDate(SesiPresensi $sesi): Carbon
    {
        return ($sesi->created_at ?: now())->copy();
    }
}
