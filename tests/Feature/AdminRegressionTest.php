<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_kelas_index_uses_master_kelas_data_even_without_students(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Kelas::create([
            'nama_kelas' => 'XI-SIJA 9',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.kelas.index'));

        $response->assertOk();
        $response->assertSee('XI-SIJA 9');
    }

    public function test_jadwal_store_rejects_guru_mapel_pair_that_is_not_registered(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        $mapelA = Mapel::create([
            'kd_mapel' => 'MTK',
            'nama_mapel' => 'Matematika',
        ]);

        $mapelB = Mapel::create([
            'kd_mapel' => 'BIO',
            'nama_mapel' => 'Biologi',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011001',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Uji',
            'kd_mapel' => $mapelA->kd_mapel,
        ]);
        $guru->mapels()->sync([$mapelA->kd_mapel]);

        $response = $this->from(route('jadwal.create'))
            ->actingAs($admin)
            ->post(route('jadwal.store'), [
                'hari' => 'Senin',
                'kelas' => 'XI-SIJA 1',
                'kd_mapel' => $mapelB->kd_mapel,
                'NIP' => $guru->NIP,
                'jam_mulai' => 1,
                'jam_selesai' => 2,
            ]);

        $response->assertRedirect(route('jadwal.create'));
        $response->assertSessionHasErrors('NIP');
        $this->assertDatabaseCount('jadwal_pelajaran', 0);
    }

    public function test_mapel_create_page_shows_next_generated_kd_mapel_code(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Mapel::create([
            'kd_mapel' => 'KD_1',
            'nama_mapel' => 'Bahasa Indonesia',
        ]);

        Mapel::create([
            'kd_mapel' => 'KD_2',
            'nama_mapel' => 'Bahasa Inggris',
        ]);

        $response = $this->actingAs($admin)->get(route('mapel.create'));

        $response->assertOk();
        $response->assertSee('value="KD_3"', false);
        $response->assertSee('Kode dibuat otomatis saat mapel disimpan');
    }

    public function test_mapel_store_generates_sequential_kd_mapel_code(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Mapel::create([
            'kd_mapel' => 'KD_1',
            'nama_mapel' => 'Bahasa Indonesia',
        ]);

        Mapel::create([
            'kd_mapel' => 'KD_2',
            'nama_mapel' => 'Bahasa Inggris',
        ]);

        $response = $this->actingAs($admin)->post(route('mapel.store'), [
            'kd_mapel' => 'MANUAL_OVERRIDE',
            'nama_mapel' => 'Matematika',
        ]);

        $response->assertRedirect(route('mapel.index'));

        $this->assertDatabaseHas('mapel', [
            'kd_mapel' => 'KD_3',
            'nama_mapel' => 'Matematika',
        ]);
    }
}
