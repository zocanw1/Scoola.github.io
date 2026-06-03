<?php

namespace Tests\Feature;

use App\Models\JadwalPelajaran;
use App\Models\Guru;
use App\Models\Mapel;
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
        $response->assertViewHas('studentComposition', function ($studentComposition) {
            return collect($studentComposition)->contains(function ($item) {
                return $item->label === 'XI-SIJA 1'
                    && $item->total === 2;
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

    public function test_admin_dashboard_renders_real_schedule_and_critical_data_instead_of_dummy_blocks(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);
        $guru = $this->createGuruProfile($guruUser, 'GURU-ADMIN-DASH', 'Guru Admin Dash', 'MAPEL-ADMIN-DASH', 'Sejarah');

        $this->createStudentsForClass('XI-SIJA 9', 2);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-ADMIN-DASH',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => 'MAPEL-ADMIN-DASH',
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 9',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 9',
            'kd_jp' => 'JP-ADMIN-DASH',
            'kode_presensi' => 'ADMIN9',
            'waktu_berlaku' => now()->subMinutes(15),
            'status' => 'aktif',
        ]);

        $this->createPresensi('P-ADMIN-1', $sesi->id, 'JP-ADMIN-DASH', 'SISWA-1', 'Alpa');
        $this->createPresensi('P-ADMIN-2', $sesi->id, 'JP-ADMIN-DASH', 'SISWA-2', 'Alpa');

        Presensi::create([
            'kd_presensi' => 'P-ADMIN-3',
            'sesi_id' => null,
            'tanggal' => now()->subDay()->toDateString(),
            'kd_jp' => 'JP-ADMIN-DASH',
            'jam_masuk' => null,
            'status' => 'Alpa',
            'NIS' => 'SISWA-1',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Sejarah');
        $response->assertSee('Guru Admin Dash');
        $response->assertSee('XI-SIJA 9');
        $response->assertSee('Anomali Kehadiran');
        $response->assertSee('Sesi Aktif Melewati Batas Waktu');
        $response->assertDontSee('Terdapat 5 siswa dengan status Alpha berulang minggu ini.');
        $response->assertDontSee('Kelas XII-A belum mengunggah rekap presensi jam ke-4.');
        $response->assertDontSee('Pak Hendra');
        $response->assertDontSee('Bu Dewi');
        $response->assertDontSee('Pak Rizal');
    }

    public function test_guru_dashboard_renders_logged_in_teacher_schedule_instead_of_dummy_agenda(): void
    {
        $guruUser = User::factory()->create(['role' => 'guru']);
        $guru = $this->createGuruProfile($guruUser, 'GURU-GURU-DASH', 'Guru Jadwal', 'MAPEL-GURU-DASH', 'Biologi');

        $this->createStudentsForClass('XI-SIJA 8', 2);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-GURU-DASH',
            'hari' => 'Senin',
            'jam_mulai' => 3,
            'jam_selesai' => 4,
            'kd_mapel' => 'MAPEL-GURU-DASH',
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 8',
        ]);

        $response = $this->actingAs($guruUser)->get(route('guru.dashboard'));

        $response->assertOk();
        $response->assertSee('Biologi');
        $response->assertSee('XI-SIJA 8');
        $response->assertDontSee('Kelas X-A');
        $response->assertDontSee('Kelas XI-B');
        $response->assertDontSee('Pastikan data presensi jam pertama diunggah sebelum pukul 09:00.');
        $response->assertDontSee('Pertemuan bulanan guru akan diadakan besok di Aula Utama.');
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

    private function createGuruProfile(User $user, string $nip, string $namaGuru, string $kdMapel, string $namaMapel): Guru
    {
        Mapel::create([
            'kd_mapel' => $kdMapel,
            'nama_mapel' => $namaMapel,
        ]);

        return Guru::create([
            'NIP' => $nip,
            'user_id' => $user->id,
            'nama_guru' => $namaGuru,
            'kd_mapel' => $kdMapel,
        ]);
    }
}
