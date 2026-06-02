<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\SesiPresensi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class PresensiTest extends TestCase
{
    use RefreshDatabase;

    private const SCHOOL_LAT = -7.974867815619122;
    private const SCHOOL_LNG = 112.67166658058967;
    private const METERS_PER_LAT_DEGREE = 111_320;

    private function createJadwalForGuru(User $guruUser, string $kelas = 'XI-SIJA 1'): JadwalPelajaran
    {
        $mapel = Mapel::create([
            'kd_mapel' => 'MTK',
            'nama_mapel' => 'Matematika',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011001',
            'user_id' => $guruUser->id,
            'nama_guru' => $guruUser->name,
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        return JadwalPelajaran::create([
            'kd_jp' => 'JP001',
            'hari' => $this->currentHariIndonesia(),
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => $kelas,
        ]);
    }

    public function test_guru_can_open_session(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        // Create a student so class exists
        $siswaUser = User::factory()->create(['role' => 'siswa']);
        Siswa::create([
            'NIS'        => '11111111',
            'user_id'    => $siswaUser->id,
            'nama_siswa' => 'Test Student',
            'kelas'      => 'XI-SIJA 1',
        ]);

        $jadwal = $this->createJadwalForGuru($guru);

        $response = $this->actingAs($guru)->post(route('guru.presensi.buka'), [
            'kd_jp' => $jadwal->kd_jp,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sesi_presensis', [
            'guru_id' => $guru->id,
            'kelas'   => 'XI-SIJA 1',
            'status'  => 'aktif',
        ]);
    }

    public function test_guru_cannot_open_session_for_another_guru_schedule(): void
    {
        $guruA = User::factory()->create(['role' => 'guru']);
        $guruB = User::factory()->create(['role' => 'guru']);

        $jadwalGuruB = $this->createJadwalForGuru($guruB);

        $response = $this->actingAs($guruA)->post(route('guru.presensi.buka'), [
            'kd_jp' => $jadwalGuruB->kd_jp,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('sesi_presensis', [
            'guru_id' => $guruA->id,
            'kd_jp' => $jadwalGuruB->kd_jp,
        ]);
    }

    public function test_guru_with_multiple_schedules_can_choose_which_class_to_open(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-25 08:00:00'));

        $guru = User::factory()->create(['role' => 'guru']);

        $siswaUserA = User::factory()->create(['role' => 'siswa']);
        Siswa::create([
            'NIS' => '77777771',
            'user_id' => $siswaUserA->id,
            'nama_siswa' => 'Siswa A',
            'kelas' => 'XI-SIJA 1',
        ]);

        $siswaUserB = User::factory()->create(['role' => 'siswa']);
        Siswa::create([
            'NIS' => '77777772',
            'user_id' => $siswaUserB->id,
            'nama_siswa' => 'Siswa B',
            'kelas' => 'XI-SIJA 2',
        ]);

        $jadwalPertama = $this->createJadwalForGuru($guru, 'XI-SIJA 1');

        $mapelDua = Mapel::create([
            'kd_mapel' => 'BIG2',
            'nama_mapel' => 'Bahasa Inggris 2',
        ]);

        $guruModel = Guru::where('user_id', $guru->id)->firstOrFail();
        $jadwalKedua = JadwalPelajaran::create([
            'kd_jp' => 'JP002',
            'hari' => $this->currentHariIndonesia(),
            'jam_mulai' => 3,
            'jam_selesai' => 4,
            'kd_mapel' => $mapelDua->kd_mapel,
            'NIP' => $guruModel->NIP,
            'kelas' => 'XI-SIJA 2',
        ]);

        $response = $this->actingAs($guru)->get(route('guru.presensi.index'));

        $response->assertOk();
        $response->assertSee('multipleScheduleHint', false);
        $response->assertSee($jadwalPertama->kd_jp);
        $response->assertSee($jadwalKedua->kd_jp);
        $response->assertSee('XI-SIJA 1');
        $response->assertSee('XI-SIJA 2');

        $openResponse = $this->actingAs($guru)->post(route('guru.presensi.buka'), [
            'kd_jp' => $jadwalKedua->kd_jp,
        ]);

        $openResponse->assertRedirect();
        $this->assertDatabaseHas('sesi_presensis', [
            'guru_id' => $guru->id,
            'kelas' => 'XI-SIJA 2',
            'kd_jp' => $jadwalKedua->kd_jp,
            'status' => 'aktif',
        ]);
        $this->assertDatabaseMissing('sesi_presensis', [
            'guru_id' => $guru->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => $jadwalPertama->kd_jp,
            'status' => 'aktif',
        ]);
    }

    public function test_siswa_can_absen_with_valid_code(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswaUser = User::factory()->create(['role' => 'siswa']);

        Siswa::create([
            'NIS'        => '22222222',
            'user_id'    => $siswaUser->id,
            'nama_siswa' => 'Test Student',
            'kelas'      => 'XI-SIJA 1',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id'       => $guru->id,
            'kelas'         => 'XI-SIJA 1',
            'kode_presensi' => 'ABC123',
            'waktu_berlaku' => Carbon::now()->addHours(2),
            'status'        => 'aktif',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('siswa.presensi.store'), [
            'kode_presensi' => 'ABC123',
            'latitude' => self::SCHOOL_LAT,
            'longitude' => self::SCHOOL_LNG,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('presensi', [
            'NIS'     => '22222222',
            'sesi_id' => $sesi->id,
            'status'  => 'Hadir',
        ]);
    }

    public function test_siswa_can_absen_when_device_location_is_near_and_accuracy_explains_offset(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswaUser = User::factory()->create(['role' => 'siswa']);

        Siswa::create([
            'NIS' => '22223333',
            'user_id' => $siswaUser->id,
            'nama_siswa' => 'Offset Student',
            'kelas' => 'XI-SIJA 1',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guru->id,
            'kelas' => 'XI-SIJA 1',
            'kode_presensi' => 'AKURAT',
            'waktu_berlaku' => Carbon::now()->addHours(2),
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('siswa.presensi.store'), [
            'kode_presensi' => 'AKURAT',
            'latitude' => self::SCHOOL_LAT + (240 / self::METERS_PER_LAT_DEGREE),
            'longitude' => self::SCHOOL_LNG,
            'accuracy' => 80,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('presensi', [
            'NIS' => '22223333',
            'sesi_id' => $sesi->id,
            'status' => 'Hadir',
            'is_dalam_radius' => true,
        ]);
    }

    public function test_siswa_still_cannot_absen_when_offset_exceeds_accuracy_tolerance(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswaUser = User::factory()->create(['role' => 'siswa']);

        Siswa::create([
            'NIS' => '22224444',
            'user_id' => $siswaUser->id,
            'nama_siswa' => 'Far Offset Student',
            'kelas' => 'XI-SIJA 1',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guru->id,
            'kelas' => 'XI-SIJA 1',
            'kode_presensi' => 'JAUH01',
            'waktu_berlaku' => Carbon::now()->addHours(2),
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('siswa.presensi.store'), [
            'kode_presensi' => 'JAUH01',
            'latitude' => self::SCHOOL_LAT + (620 / self::METERS_PER_LAT_DEGREE),
            'longitude' => self::SCHOOL_LNG,
            'accuracy' => 80,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('presensi', [
            'NIS' => '22224444',
            'sesi_id' => $sesi->id,
            'status' => 'Ditolak',
            'is_dalam_radius' => false,
        ]);
    }

    public function test_siswa_cannot_absen_with_wrong_class(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswaUser = User::factory()->create(['role' => 'siswa']);

        Siswa::create([
            'NIS'        => '33333333',
            'user_id'    => $siswaUser->id,
            'nama_siswa' => 'Wrong Class Student',
            'kelas'      => 'XI-SIJA 2',
        ]);

        SesiPresensi::create([
            'guru_id'       => $guru->id,
            'kelas'         => 'XI-SIJA 1',
            'kode_presensi' => 'DEF456',
            'waktu_berlaku' => Carbon::now()->addHours(2),
            'status'        => 'aktif',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('siswa.presensi.store'), [
            'kode_presensi' => 'DEF456',
            'latitude' => self::SCHOOL_LAT,
            'longitude' => self::SCHOOL_LNG,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_siswa_cannot_absen_with_invalid_code(): void
    {
        $siswaUser = User::factory()->create(['role' => 'siswa']);

        Siswa::create([
            'NIS'        => '44444444',
            'user_id'    => $siswaUser->id,
            'nama_siswa' => 'Invalid Code Student',
            'kelas'      => 'XI-SIJA 1',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('siswa.presensi.store'), [
            'kode_presensi' => 'XXXXXX',
            'latitude' => self::SCHOOL_LAT,
            'longitude' => self::SCHOOL_LNG,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_expired_code_does_not_end_session(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $siswaUser = User::factory()->create(['role' => 'siswa']);

        Siswa::create([
            'NIS'        => '55555555',
            'user_id'    => $siswaUser->id,
            'nama_siswa' => 'Expired Session Student',
            'kelas'      => 'XI-SIJA 1',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id'       => $guru->id,
            'kelas'         => 'XI-SIJA 1',
            'kode_presensi' => 'EXP123',
            'waktu_berlaku' => Carbon::now()->subMinute(),
            'status'        => 'aktif',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('siswa.presensi.store'), [
            'kode_presensi' => 'EXP123',
            'latitude' => self::SCHOOL_LAT,
            'longitude' => self::SCHOOL_LNG,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('sesi_presensis', [
            'id' => $sesi->id,
            'status' => 'aktif',
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
