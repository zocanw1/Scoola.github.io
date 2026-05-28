<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RekapPresensiPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_rekap_index_builds_slot_and_status_matrices_for_the_selected_class(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 1']);

        $mapel = Mapel::create([
            'kd_mapel' => 'MTK',
            'nama_mapel' => 'Matematika',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011001',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Uji',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP001',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 1',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-1',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa 1',
            'kelas' => 'XI-SIJA 1',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-1',
            'sesi_id' => null,
            'tanggal' => now()->toDateString(),
            'kd_jp' => 'JP001',
            'jam_masuk' => '07:00:00',
            'status' => 'Hadir',
            'NIS' => 'SISWA-1',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 1',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertViewHas('slotMatrix', function (array $slotMatrix): bool {
            return ($slotMatrix['Senin'][1]['kd_jp'] ?? null) === 'JP001'
                && ($slotMatrix['Senin'][2]['kd_jp'] ?? null) === 'JP001';
        });
        $response->assertViewHas('statusMatrix', function (array $statusMatrix): bool {
            return ($statusMatrix['SISWA-1']['Senin'][1] ?? null) === 'Hadir'
                && ($statusMatrix['SISWA-1']['Senin'][2] ?? null) === 'Hadir';
        });
    }

    public function test_rekap_index_shows_weekly_and_student_modes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

        $response->assertOk();
        $response->assertSee('Rekap Mingguan');
        $response->assertSee('Rekap Per Siswa');
    }

    public function test_student_rekap_form_uses_student_name_input_instead_of_student_select(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA NAME']);

        Siswa::create([
            'NIS' => 'SISWA-NAME',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Nama Dicari',
            'kelas' => 'XI-SIJA NAME',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA NAME',
        ]));

        $response->assertOk();
        $response->assertSee('name="nama_siswa"', false);
        $response->assertDontSee('name="nis"', false);
        $response->assertDontSee('PILIH SISWA');
    }

    public function test_rekap_index_still_loads_when_siswa_table_has_no_jenis_kelamin_column(): void
    {
        if (Schema::hasColumn('siswa', 'jenis_kelamin')) {
            Schema::table('siswa', function ($table): void {
                $table->dropColumn('jenis_kelamin');
            });
        }

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 2']);

        $mapel = Mapel::create([
            'kd_mapel' => 'BING',
            'nama_mapel' => 'Bahasa Inggris',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011002',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Kelas 2',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP002',
            'hari' => 'Selasa',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 2',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-2',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa 2',
            'kelas' => 'XI-SIJA 2',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 2',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertSee('Siswa 2');
    }

    public function test_rekap_uses_sesi_presensi_kd_jp_when_presensi_kd_jp_is_empty(): void
    {
        Carbon::setTestNow('2026-05-25 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 3']);

        $mapel = Mapel::create([
            'kd_mapel' => 'PKN',
            'nama_mapel' => 'Pendidikan Pancasila',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011003',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Sesi',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP003',
            'hari' => 'Senin',
            'jam_mulai' => 3,
            'jam_selesai' => 4,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 3',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-3',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa 3',
            'kelas' => 'XI-SIJA 3',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 3',
            'kd_jp' => 'JP003',
            'kode_presensi' => 'SES003',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-3',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => null,
            'jam_masuk' => '08:00:00',
            'status' => 'Hadir',
            'NIS' => 'SISWA-3',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 3',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertViewHas('statusMatrix', function (array $statusMatrix): bool {
            return ($statusMatrix['SISWA-3']['Senin'][3] ?? null) === 'Hadir'
                && ($statusMatrix['SISWA-3']['Senin'][4] ?? null) === 'Hadir';
        });
    }

    public function test_rekap_marks_students_as_belum_hadir_when_a_session_was_held_without_presensi(): void
    {
        Carbon::setTestNow('2026-05-26 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 4']);

        $mapel = Mapel::create([
            'kd_mapel' => 'BIO',
            'nama_mapel' => 'Biologi',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011004',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Kosong',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP004',
            'hari' => 'Selasa',
            'jam_mulai' => 5,
            'jam_selesai' => 6,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 4',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-4',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa 4',
            'kelas' => 'XI-SIJA 4',
        ]);

        SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 4',
            'kd_jp' => 'JP004',
            'kode_presensi' => null,
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 4',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertViewHas('statusMatrix', function (array $statusMatrix): bool {
            return ($statusMatrix['SISWA-4']['Selasa'][5] ?? null) === 'Belum Hadir'
                && ($statusMatrix['SISWA-4']['Selasa'][6] ?? null) === 'Belum Hadir';
        });
    }

    public function test_student_rekap_only_builds_rows_for_selected_student(): void
    {
        Carbon::setTestNow('2026-05-25 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 5']);

        $mapel = Mapel::create([
            'kd_mapel' => 'KIM',
            'nama_mapel' => 'Kimia',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011005',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Kimia',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP005',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 5',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-5A',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Dipilih',
            'kelas' => 'XI-SIJA 5',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-5B',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Lain',
            'kelas' => 'XI-SIJA 5',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 5',
            'kd_jp' => 'JP005',
            'kode_presensi' => 'SES005',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-5A',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => null,
            'jam_masuk' => '08:05:00',
            'status' => 'Hadir',
            'NIS' => 'SISWA-5A',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-5B',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => null,
            'jam_masuk' => '08:06:00',
            'status' => 'Sakit',
            'NIS' => 'SISWA-5B',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA 5',
            'nama_siswa' => 'Siswa Dipilih',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertViewHas('studentRows', function (Collection $studentRows): bool {
            return $studentRows->count() === 1
                && $studentRows->first()['nis'] === 'SISWA-5A'
                && $studentRows->first()['status'] === 'Hadir';
        });
        $response->assertViewHas('studentTotals', function (array $studentTotals): bool {
            return $studentTotals['Hadir'] === 1
                && $studentTotals['Sakit'] === 0;
        });
    }

    public function test_student_rekap_marks_selected_student_as_belum_hadir_when_session_has_no_record(): void
    {
        Carbon::setTestNow('2026-05-27 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 6']);

        $mapel = Mapel::create([
            'kd_mapel' => 'FIS',
            'nama_mapel' => 'Fisika',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011006',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Fisika',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP006',
            'hari' => 'Rabu',
            'jam_mulai' => 3,
            'jam_selesai' => 4,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 6',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-6',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Kosong',
            'kelas' => 'XI-SIJA 6',
        ]);

        SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 6',
            'kd_jp' => 'JP006',
            'kode_presensi' => null,
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA 6',
            'nama_siswa' => 'Siswa Kosong',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertViewHas('studentRows', function (Collection $studentRows): bool {
            return $studentRows->count() === 1
                && $studentRows->first()['status'] === 'Belum Hadir'
                && $studentRows->first()['mapel'] === 'Fisika';
        });
    }

    public function test_student_rekap_export_returns_excel_for_selected_student(): void
    {
        Carbon::setTestNow('2026-05-28 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 7']);

        $mapel = Mapel::create([
            'kd_mapel' => 'IND',
            'nama_mapel' => 'Bahasa Indonesia',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011007',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Indonesia',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP007',
            'hari' => 'Kamis',
            'jam_mulai' => 5,
            'jam_selesai' => 6,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 7',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-7',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Export',
            'kelas' => 'XI-SIJA 7',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-7',
            'sesi_id' => null,
            'tanggal' => now()->toDateString(),
            'kd_jp' => 'JP007',
            'jam_masuk' => '10:00:00',
            'status' => 'Hadir',
            'NIS' => 'SISWA-7',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.export', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA 7',
            'nama_siswa' => 'Siswa Export',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd-ms-excel');
        $response->assertSee('REKAP PRESENSI PER SISWA');
        $response->assertSee('Siswa Export');
        $response->assertSee('Bahasa Indonesia');
        $response->assertSee('Hadir');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }
}
