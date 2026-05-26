<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SiswaImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_import_siswa_rows_from_parsed_sheet_payload(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('siswa.import'), [
            'rows' => json_encode([
                ['nis' => '18537/127/009', 'nama' => 'ACH IWAN DARMANSYAH', 'kelas' => 'XI SIJA 1'],
                ['nis' => '17588/122/065', 'nama' => 'MAULANA HARI YAHYA', 'kelas' => 'XI SIJA 2'],
            ], JSON_THROW_ON_ERROR),
        ]);

        $response->assertRedirect(route('siswa.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'NIS' => '18537/127/009',
            'nama_siswa' => 'ACH IWAN DARMANSYAH',
            'kelas' => 'XI-SIJA 1',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'siswa-18537127009@gmail.com',
            'role' => 'siswa',
        ]);

        $user = User::where('email', 'siswa-18537127009@gmail.com')->firstOrFail();
        $this->assertTrue(Hash::check('18537127009', $user->password));
    }

    public function test_import_skips_existing_nis_and_invalid_rows(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $existingUser = User::factory()->create([
            'role' => 'siswa',
            'email' => 'siswa-18537127009@gmail.com',
        ]);

        Siswa::create([
            'NIS' => '18537/127/009',
            'user_id' => $existingUser->id,
            'nama_siswa' => 'Siswa Lama',
            'kelas' => 'XI-SIJA 1',
        ]);

        $response = $this->actingAs($admin)->post(route('siswa.import'), [
            'rows' => json_encode([
                ['nis' => '18537/127/009', 'nama' => 'ACH IWAN DARMANSYAH', 'kelas' => 'XI SIJA 1'],
                ['nis' => '18430/105/065', 'nama' => 'MUHAMMAD AQIL NAUFAL', 'kelas' => 'XI SIJA 2'],
                ['nis' => '', 'nama' => 'BARIS SALAH', 'kelas' => 'XI SIJA 2'],
            ], JSON_THROW_ON_ERROR),
        ]);

        $response->assertRedirect(route('siswa.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('siswa', 2);
        $this->assertDatabaseHas('siswa', [
            'NIS' => '18430/105/065',
            'nama_siswa' => 'MUHAMMAD AQIL NAUFAL',
            'kelas' => 'XI-SIJA 2',
        ]);
    }
}
