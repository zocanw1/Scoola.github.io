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
            'hari' => 'Senin',
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
}
