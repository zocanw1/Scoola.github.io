<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Mapel;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPresensiCorrectionTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_akhiri_kelas_marks_missing_students_as_alpa_without_overwriting_existing_statuses(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-26 09:00:00'));

        $guruUser = User::factory()->create(['role' => 'guru']);
        $jadwal = $this->createJadwalForGuru($guruUser);

        $hadirStudent = $this->createStudent('SISWA-HADIR', 'Siswa Hadir');
        $alpaStudent = $this->createStudent('SISWA-ALPA', 'Siswa Alpa');

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => $jadwal->kd_jp,
            'kode_presensi' => 'ALPA01',
            'waktu_berlaku' => now()->addHour(),
            'status' => 'aktif',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-HADIR',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => $jadwal->kd_jp,
            'jam_masuk' => now()->format('H:i:s'),
            'status' => 'Hadir',
            'NIS' => $hadirStudent->NIS,
        ]);

        $response = $this->actingAs($guruUser)->post(route('guru.presensi.akhiri-kelas', $sesi->id));

        $response->assertRedirect(route('guru.dashboard'));

        $this->assertDatabaseHas('sesi_presensis', [
            'id' => $sesi->id,
            'status' => 'selesai',
            'kode_presensi' => null,
        ]);

        $this->assertDatabaseHas('presensi', [
            'sesi_id' => $sesi->id,
            'NIS' => $hadirStudent->NIS,
            'status' => 'Hadir',
        ]);

        $this->assertDatabaseHas('presensi', [
            'sesi_id' => $sesi->id,
            'NIS' => $alpaStudent->NIS,
            'status' => 'Alpa',
            'tanggal' => now()->toDateString(),
            'kd_jp' => $jadwal->kd_jp,
        ]);
    }

    public function test_admin_can_correct_existing_presensi_status_with_required_reason_and_audit_history(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-26 13:00:00'));

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);
        $jadwal = $this->createJadwalForGuru($guruUser);
        $siswa = $this->createStudent('SISWA-KOREKSI', 'Siswa Koreksi');

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => $jadwal->kd_jp,
            'kode_presensi' => null,
            'waktu_berlaku' => now()->subHour(),
            'status' => 'selesai',
            'created_at' => now()->subHours(4),
            'updated_at' => now()->subHours(2),
        ]);

        $presensi = Presensi::create([
            'kd_presensi' => 'PRS-KOREKSI',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => $jadwal->kd_jp,
            'jam_masuk' => '07:00:00',
            'status' => 'Alpa',
            'NIS' => $siswa->NIS,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.presensi-siswa.update-status', $siswa->NIS), [
            'presensi_id' => $presensi->id,
            'sesi_id' => $sesi->id,
            'status' => 'Izin',
            'correction_reason' => 'Surat orang tua diterima admin.',
            'kelas' => $siswa->kelas,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_akhir' => now()->toDateString(),
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('presensi', [
            'id' => $presensi->id,
            'status' => 'Izin',
        ]);

        $this->assertDatabaseHas('presensi_status_histories', [
            'presensi_id' => $presensi->id,
            'sesi_id' => $sesi->id,
            'nis' => $siswa->NIS,
            'old_status' => 'Alpa',
            'new_status' => 'Izin',
            'reason' => 'Surat orang tua diterima admin.',
            'changed_by' => $admin->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
        ]);

        $detailResponse = $this->actingAs($admin)->get(route('admin.presensi-siswa.show', [
            'nis' => $siswa->NIS,
            'kelas' => $siswa->kelas,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_akhir' => now()->toDateString(),
        ]));

        $detailResponse->assertOk();
        $detailResponse->assertSee('Surat orang tua diterima admin.');
        $detailResponse->assertSee('Riwayat Koreksi');
    }

    public function test_getting_stale_status_url_for_slash_nis_redirects_to_student_detail(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $siswa = $this->createStudent('17588/122/065', 'Siswa Slash');

        $response = $this->actingAs($admin)->get('/admin/presensi-siswa/' . $siswa->NIS . '/status');

        $response->assertRedirect(route('admin.presensi-siswa.show', $siswa->NIS));
    }

    public function test_admin_can_correct_finished_session_without_existing_presensi_and_keep_original_session_date(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-28 09:30:00'));

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);
        $jadwal = $this->createJadwalForGuru($guruUser);
        $siswa = $this->createStudent('SISWA-SUSULAN', 'Siswa Susulan');

        $sessionDate = Carbon::parse('2026-05-26 07:10:00');

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => $jadwal->kd_jp,
            'kode_presensi' => null,
            'waktu_berlaku' => $sessionDate->copy()->addHours(2),
            'status' => 'selesai',
        ]);

        $sesi->forceFill([
            'created_at' => $sessionDate,
            'updated_at' => $sessionDate->copy()->addHours(3),
        ])->save();

        $response = $this->actingAs($admin)->post(route('admin.presensi-siswa.update-status', $siswa->NIS), [
            'sesi_id' => $sesi->id,
            'status' => 'Sakit',
            'correction_reason' => 'Surat dokter masuk dua hari setelahnya.',
            'kelas' => $siswa->kelas,
            'tanggal_mulai' => '2026-05-26',
            'tanggal_akhir' => '2026-05-28',
        ]);

        $response->assertRedirect();

        $presensi = Presensi::where('sesi_id', $sesi->id)->where('NIS', $siswa->NIS)->first();

        $this->assertNotNull($presensi);
        $this->assertSame('2026-05-26', $presensi->tanggal);
        $this->assertSame('Sakit', $presensi->status);

        $this->assertDatabaseHas('presensi_status_histories', [
            'presensi_id' => $presensi->id,
            'old_status' => 'Alpa',
            'new_status' => 'Sakit',
            'reason' => 'Surat dokter masuk dua hari setelahnya.',
        ]);
    }

    public function test_admin_correction_requires_reason_and_kakonsli_cannot_use_it(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-26 10:00:00'));

        $admin = User::factory()->create(['role' => 'admin']);
        $kakonsli = User::factory()->create(['role' => 'kakonsli']);
        $guruUser = User::factory()->create(['role' => 'guru']);
        $jadwal = $this->createJadwalForGuru($guruUser);
        $siswa = $this->createStudent('SISWA-VALIDASI', 'Siswa Validasi');

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => $jadwal->kd_jp,
            'kode_presensi' => null,
            'waktu_berlaku' => now()->subHour(),
            'status' => 'selesai',
        ]);

        $presensi = Presensi::create([
            'kd_presensi' => 'PRS-VALIDASI',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => $jadwal->kd_jp,
            'jam_masuk' => '07:00:00',
            'status' => 'Alpa',
            'NIS' => $siswa->NIS,
        ]);

        $invalidResponse = $this->from(route('admin.presensi-siswa.show', [
            'nis' => $siswa->NIS,
            'kelas' => $siswa->kelas,
        ]))->actingAs($admin)->post(route('admin.presensi-siswa.update-status', $siswa->NIS), [
            'presensi_id' => $presensi->id,
            'sesi_id' => $sesi->id,
            'status' => 'Izin',
            'correction_reason' => '',
            'kelas' => $siswa->kelas,
        ]);

        $invalidResponse->assertRedirect();
        $invalidResponse->assertSessionHasErrors('correction_reason');

        $this->actingAs($kakonsli)->post(route('admin.presensi-siswa.update-status', $siswa->NIS), [
            'presensi_id' => $presensi->id,
            'sesi_id' => $sesi->id,
            'status' => 'Izin',
            'correction_reason' => 'Tidak berhak.',
            'kelas' => $siswa->kelas,
        ])->assertForbidden();
    }

    public function test_admin_correction_rejects_statuses_that_are_not_persisted_corrections(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);
        $jadwal = $this->createJadwalForGuru($guruUser);
        $siswa = $this->createStudent('SISWA-STATUS-VALID', 'Siswa Status Valid');

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => $jadwal->kd_jp,
            'kode_presensi' => null,
            'waktu_berlaku' => now()->subHour(),
            'status' => 'selesai',
        ]);

        $presensi = Presensi::create([
            'kd_presensi' => 'PRS-STATUS-VALID',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => $jadwal->kd_jp,
            'jam_masuk' => '07:00:00',
            'status' => 'Alpa',
            'NIS' => $siswa->NIS,
        ]);

        foreach (['Ditolak', 'Belum Hadir'] as $status) {
            $response = $this->from(route('admin.presensi-siswa.show', [
                'nis' => $siswa->NIS,
                'kelas' => $siswa->kelas,
            ]))->actingAs($admin)->post(route('admin.presensi-siswa.update-status', $siswa->NIS), [
                'presensi_id' => $presensi->id,
                'sesi_id' => $sesi->id,
                'status' => $status,
                'correction_reason' => 'Status ini tidak boleh disimpan dari koreksi admin.',
                'kelas' => $siswa->kelas,
            ]);

            $response->assertRedirect();
            $response->assertSessionHasErrors('status');
        }
    }

    private function createJadwalForGuru(User $guruUser, string $kelas = 'XI-SIJA 1'): JadwalPelajaran
    {
        $mapel = Mapel::create([
            'kd_mapel' => 'MAPEL-' . $guruUser->id,
            'nama_mapel' => 'Mapel ' . $guruUser->id,
        ]);

        $guru = Guru::create([
            'NIP' => 'NIP-' . $guruUser->id,
            'user_id' => $guruUser->id,
            'nama_guru' => $guruUser->name,
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        return JadwalPelajaran::create([
            'kd_jp' => 'JP-' . $guruUser->id,
            'hari' => $this->currentHariIndonesia(),
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => $kelas,
        ]);
    }

    private function createStudent(string $nis, string $name, string $kelas = 'XI-SIJA 1'): Siswa
    {
        return Siswa::create([
            'NIS' => $nis,
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => $name,
            'kelas' => $kelas,
        ]);
    }

    private function currentHariIndonesia(): string
    {
        return [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ][date('l')];
    }
}
