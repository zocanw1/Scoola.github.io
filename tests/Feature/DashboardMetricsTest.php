<?php

namespace Tests\Feature;

use App\Models\JadwalPelajaran;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardMetricsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-05-25 08:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_admin_dashboard_counts_multiple_sessions_in_same_class_correctly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guru = User::factory()->create(['role' => 'guru']);

        $this->createStudentsForClass('XI-SIJA 1', 2);
        $this->createJadwal('JP001', 'XI-SIJA 1');
        $this->createJadwal('JP002', 'XI-SIJA 1');

        $sesi1 = SesiPresensi::create([
            'guru_id' => $guru->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => 'JP001',
            'kode_presensi' => 'AAA111',
            'waktu_berlaku' => now()->addHour(),
            'status' => 'aktif',
        ]);

        $sesi2 = SesiPresensi::create([
            'guru_id' => $guru->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => 'JP002',
            'kode_presensi' => 'BBB222',
            'waktu_berlaku' => now()->addHour(),
            'status' => 'aktif',
        ]);

        $this->createPresensi('P001', $sesi1->id, 'JP001', 'SISWA-1', 'Hadir');
        $this->createPresensi('P002', $sesi1->id, 'JP001', 'SISWA-2', 'Sakit');
        $this->createPresensi('P003', $sesi2->id, 'JP002', 'SISWA-1', 'Hadir');
        $this->createPresensi('P004', $sesi2->id, 'JP002', 'SISWA-2', 'Hadir');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('siswaHarusAbsen', 4);
        $response->assertViewHas('hadirHariIni', 3);
        $response->assertViewHas('izinSakitHariIni', 1);
        $response->assertViewHas('alpaHariIni', 0);
        $response->assertViewHas('persentaseHadir', 75.0);
        $response->assertViewHas('kelasBreakdown', function ($kelasBreakdown) {
            return collect($kelasBreakdown)->contains(function ($kelas) {
                return $kelas->nama === 'XI-SIJA 1'
                    && (float) $kelas->persentase === 75.0;
            });
        });
    }

    public function test_guru_dashboard_counts_multiple_sessions_in_same_class_correctly(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $this->createStudentsForClass('XI-SIJA 1', 2);
        $this->createJadwal('JP101', 'XI-SIJA 1');
        $this->createJadwal('JP102', 'XI-SIJA 1');

        $sesi1 = SesiPresensi::create([
            'guru_id' => $guru->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => 'JP101',
            'kode_presensi' => 'CCC333',
            'waktu_berlaku' => now()->addHour(),
            'status' => 'aktif',
        ]);

        $sesi2 = SesiPresensi::create([
            'guru_id' => $guru->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => 'JP102',
            'kode_presensi' => 'DDD444',
            'waktu_berlaku' => now()->addHour(),
            'status' => 'aktif',
        ]);

        $this->createPresensi('P101', $sesi1->id, 'JP101', 'SISWA-1', 'Hadir');
        $this->createPresensi('P102', $sesi1->id, 'JP101', 'SISWA-2', 'Sakit');
        $this->createPresensi('P103', $sesi2->id, 'JP102', 'SISWA-1', 'Hadir');
        $this->createPresensi('P104', $sesi2->id, 'JP102', 'SISWA-2', 'Hadir');

        $response = $this->actingAs($guru)->get(route('guru.dashboard'));

        $response->assertOk();
        $response->assertViewHas('siswaHarusAbsen', 4);
        $response->assertViewHas('hadirHariIni', 3);
        $response->assertViewHas('izinSakitHariIni', 1);
        $response->assertViewHas('alpaHariIni', 0);
        $response->assertViewHas('persentaseHadir', 75.0);
        $response->assertViewHas('kelasBreakdown', function ($kelasBreakdown) {
            return collect($kelasBreakdown)->contains(function ($kelas) {
                return $kelas->nama === 'XI-SIJA 1'
                    && (float) $kelas->persentase === 75.0;
            });
        });
    }

    private function createStudentsForClass(string $kelas, int $count): void
    {
        foreach (range(1, $count) as $index) {
            $user = User::factory()->create([
                'role' => 'siswa',
                'email' => "siswa{$index}@test.com",
            ]);

            Siswa::create([
                'NIS' => 'SISWA-' . $index,
                'user_id' => $user->id,
                'nama_siswa' => 'Siswa ' . $index,
                'kelas' => $kelas,
            ]);
        }
    }

    private function createPresensi(string $kode, int $sesiId, string $kdJp, string $nis, string $status): void
    {
        Presensi::create([
            'kd_presensi' => $kode,
            'sesi_id' => $sesiId,
            'tanggal' => now()->toDateString(),
            'kd_jp' => $kdJp,
            'jam_masuk' => now()->format('H:i:s'),
            'status' => $status,
            'NIS' => $nis,
        ]);
    }

    private function createJadwal(string $kdJp, string $kelas): void
    {
        JadwalPelajaran::create([
            'kd_jp' => $kdJp,
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kelas' => $kelas,
        ]);
    }
}
