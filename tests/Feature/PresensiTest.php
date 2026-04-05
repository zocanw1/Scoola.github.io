<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\SesiPresensi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class PresensiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_can_open_session(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        // Create a student so class exists
        $siswaUser = User::factory()->create(['role' => 'siswa']);
        Siswa::create([
            'NIS'        => '11111111',
            'user_id'    => $siswaUser->id,
            'nama_siswa' => 'Test Student',
            'kelas'      => 'X-SIJA 1',
        ]);

        $response = $this->actingAs($guru)->post(route('guru.presensi.buka'), [
            'kelas' => 'X-SIJA 1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sesi_presensis', [
            'guru_id' => $guru->id,
            'kelas'   => 'X-SIJA 1',
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
            'kelas'      => 'X-SIJA 1',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id'       => $guru->id,
            'kelas'         => 'X-SIJA 1',
            'kode_presensi' => 'ABC123',
            'waktu_berlaku' => Carbon::now()->addHours(2),
            'status'        => 'aktif',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('siswa.presensi.store'), [
            'kode_presensi' => 'ABC123',
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
            'kelas'         => 'X-SIJA 1',
            'kode_presensi' => 'DEF456',
            'waktu_berlaku' => Carbon::now()->addHours(2),
            'status'        => 'aktif',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('siswa.presensi.store'), [
            'kode_presensi' => 'DEF456',
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
            'kelas'      => 'X-SIJA 1',
        ]);

        $response = $this->actingAs($siswaUser)->post(route('siswa.presensi.store'), [
            'kode_presensi' => 'XXXXXX',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
