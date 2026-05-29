<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Mapel;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresensiStatusRefreshTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_can_update_student_status_when_nis_contains_slashes(): void
    {
        [$guru, $sesi, $siswa] = $this->createActiveSessionWithStudent('18434/109/065');

        $response = $this->actingAs($guru)->post(
            "/guru/presensi/ruang/{$sesi->id}/update-status/{$siswa->NIS}",
            ['status' => 'Hadir']
        );

        $response->assertRedirect(route('guru.presensi.ruang', $sesi->id));
        $this->assertDatabaseHas('presensi', [
            'sesi_id' => $sesi->id,
            'NIS' => '18434/109/065',
            'status' => 'Hadir',
        ]);
    }

    public function test_attendance_snapshot_changes_after_status_update(): void
    {
        [$guru, $sesi, $siswa] = $this->createActiveSessionWithStudent('24680/135/790');

        $initial = $this->actingAs($guru)
            ->getJson(route('guru.presensi.snapshot', $sesi->id))
            ->assertOk()
            ->json('version');

        Presensi::create([
            'kd_presensi' => 'PRS-20260529-ABC123',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => $sesi->kd_jp,
            'jam_masuk' => now()->format('H:i:s'),
            'status' => 'Izin',
            'NIS' => $siswa->NIS,
        ]);

        $updated = $this->actingAs($guru)
            ->getJson(route('guru.presensi.snapshot', $sesi->id))
            ->assertOk()
            ->json('version');

        $this->assertNotSame($initial, $updated);
    }

    public function test_attendance_snapshot_changes_when_existing_status_changes(): void
    {
        [$guru, $sesi, $siswa] = $this->createActiveSessionWithStudent('11223/445/667');

        $presensi = Presensi::create([
            'kd_presensi' => 'PRS-20260529-DEF456',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => $sesi->kd_jp,
            'jam_masuk' => now()->format('H:i:s'),
            'status' => 'Izin',
            'NIS' => $siswa->NIS,
        ]);

        $initial = $this->actingAs($guru)
            ->getJson(route('guru.presensi.snapshot', $sesi->id))
            ->assertOk()
            ->json('version');

        $presensi->update(['status' => 'Hadir']);

        $updated = $this->actingAs($guru)
            ->getJson(route('guru.presensi.snapshot', $sesi->id))
            ->assertOk()
            ->json('version');

        $this->assertNotSame($initial, $updated);
    }

    public function test_session_room_contains_auto_refresh_snapshot_hook(): void
    {
        [$guru, $sesi] = $this->createActiveSessionWithStudent('13579/246/800');

        $response = $this->actingAs($guru)->get(route('guru.presensi.ruang', $sesi->id));

        $response->assertOk();
        $response->assertSee(route('guru.presensi.snapshot', $sesi->id), false);
        $response->assertSee('data-attendance-state-version', false);
    }

    private function createActiveSessionWithStudent(string $nis): array
    {
        $guruUser = User::factory()->create(['role' => 'guru']);

        Mapel::create([
            'kd_mapel' => 'MTK',
            'nama_mapel' => 'Matematika',
        ]);

        Guru::create([
            'NIP' => '198501012010011001',
            'user_id' => $guruUser->id,
            'nama_guru' => $guruUser->name,
            'kd_mapel' => 'MTK',
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-SLASH',
            'hari' => 'Jumat',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => 'MTK',
            'NIP' => '198501012010011001',
            'kelas' => 'XI-SIJA 1',
        ]);

        $siswaUser = User::factory()->create(['role' => 'siswa']);
        $siswa = Siswa::create([
            'NIS' => $nis,
            'user_id' => $siswaUser->id,
            'nama_siswa' => 'Siswa Slash',
            'kelas' => 'XI-SIJA 1',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => 'JP-SLASH',
            'kode_presensi' => 'SLH123',
            'waktu_berlaku' => now()->addHour(),
            'status' => 'aktif',
        ]);

        return [$guruUser, $sesi, $siswa];
    }
}
