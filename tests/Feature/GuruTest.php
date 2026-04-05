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
    }

    public function test_admin_can_create_guru(): void
    {
        $admin = $this->createAdmin();
        $mapel = $this->createMapel();

        $response = $this->actingAs($admin)->post(route('guru.store'), [
            'nip'      => '198501012010011001',
            'nama'     => 'Test Guru',
            'kd_mapel' => $mapel->kd_mapel,
            'email'    => 'guru@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('guru.index'));
        $this->assertDatabaseHas('guru', ['NIP' => '198501012010011001', 'nama_guru' => 'Test Guru']);
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
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        $response->assertRedirect(route('guru.index'));
        $this->assertDatabaseHas('guru', ['NIP' => '111111111', 'nama_guru' => 'New Guru']);
    }

    public function test_admin_can_delete_guru(): void
    {
        $admin = $this->createAdmin();
        $mapel = $this->createMapel();

        $user = User::factory()->create(['role' => 'guru']);
        Guru::create([
            'NIP'       => '222222222',
            'user_id'   => $user->id,
            'nama_guru' => 'To Delete',
            'kd_mapel'  => $mapel->kd_mapel,
        ]);

        $response = $this->actingAs($admin)->delete(route('guru.destroy', '222222222'));

        $response->assertRedirect(route('guru.index'));
        $this->assertDatabaseMissing('guru', ['NIP' => '222222222']);
    }

    public function test_non_admin_cannot_access_guru(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->get(route('guru.index'));
        $response->assertStatus(403);
    }
}
