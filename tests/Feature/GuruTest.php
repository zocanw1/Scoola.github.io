<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function createMapel(): Mapel
    {
        return Mapel::create([
            'kd_mapel'   => 'MTK',
            'nama_mapel' => 'Matematika',
        ]);
    }

    public function test_admin_can_view_guru_index(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('guru.index'));
        $response->assertStatus(200);
        $response->assertSee('liveGuruSearch', false);
        $response->assertSee('data-live-filter="guru"', false);
    }

    public function test_guru_search_filters_results_using_query_parameter_case_insensitively(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['role' => 'guru', 'name' => 'Susi Nur Jayanti, S.Pd.']);
        $otherUser = User::factory()->create(['role' => 'guru', 'name' => 'Budi Santoso, S.Pd.']);

        Guru::create([
            'NIP' => '199311292025212115',
            'user_id' => $user->id,
            'nama_guru' => 'Susi Nur Jayanti, S.Pd.',
            'kd_mapel' => null,
        ]);

        Guru::create([
            'NIP' => '198410192022212030',
            'user_id' => $otherUser->id,
            'nama_guru' => 'Budi Santoso, S.Pd.',
            'kd_mapel' => null,
        ]);

        $response = $this->actingAs($admin)->get(route('guru.index', [
            'q' => 'sUsI',
        ]));

        $response->assertOk();
        $response->assertSee('Susi Nur Jayanti, S.Pd.');
        $response->assertDontSee('Budi Santoso, S.Pd.');
    }

    public function test_admin_can_create_guru(): void
    {
        $admin = $this->createAdmin();
        $mapel = $this->createMapel();

        $response = $this->actingAs($admin)->post(route('guru.store'), [
            'nip'      => '198501012010011001',
            'nama'     => 'Test Guru',
            'jenis_kelamin' => 'L',
            'kd_mapel' => [$mapel->kd_mapel],
            'email'    => 'guru@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('guru.index'));
        $this->assertDatabaseHas('guru', ['NIP' => '198501012010011001', 'nama_guru' => 'Test Guru', 'jenis_kelamin' => 'L']);
        $this->assertDatabaseHas('users', ['email' => 'guru@test.com', 'role' => 'guru']);
    }

    public function test_admin_can_update_guru(): void
    {
        $admin = $this->createAdmin();
        $mapel = $this->createMapel();

        $user = User::factory()->create(['role' => 'guru', 'name' => 'Old Guru']);
        Guru::create([
            'NIP'       => '111111111',
            'user_id'   => $user->id,
            'nama_guru' => 'Old Guru',
            'kd_mapel'  => $mapel->kd_mapel,
        ]);

        $response = $this->actingAs($admin)->put(route('guru.update', '111111111'), [
            'nama'     => 'New Guru',
            'jenis_kelamin' => 'P',
            'kd_mapel' => [$mapel->kd_mapel],
        ]);

        $response->assertRedirect(route('guru.index'));
        $this->assertDatabaseHas('guru', ['NIP' => '111111111', 'nama_guru' => 'New Guru', 'jenis_kelamin' => 'P']);
    }

    public function test_non_admin_cannot_access_guru(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->get(route('guru.index'));
        $response->assertStatus(403);
    }
}
