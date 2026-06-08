<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SiswaTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_siswa_index(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('siswa.index'));
        $response->assertStatus(200);
        $response->assertSee('liveSiswaSearch', false);
    }

    public function test_siswa_search_filters_results_using_query_parameter(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create(['role' => 'siswa', 'name' => 'Susi Andini']);
        $otherUser = User::factory()->create(['role' => 'siswa', 'name' => 'Budi Prasetyo']);

        Siswa::create([
            'NIS' => '12345678',
            'user_id' => $user->id,
            'nama_siswa' => 'Susi Andini',
            'kelas' => 'XI-SIJA 1',
        ]);

        Siswa::create([
            'NIS' => '87654321',
            'user_id' => $otherUser->id,
            'nama_siswa' => 'Budi Prasetyo',
            'kelas' => 'XI-SIJA 2',
        ]);

        $response = $this->actingAs($admin)->get(route('siswa.index', [
            'q' => 'Susi',
        ]));

        $response->assertOk();
        $response->assertSee('Susi Andini');
        $response->assertDontSee('Budi Prasetyo');
    }

    public function test_admin_can_view_siswa_create_form(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('siswa.create'));
        $response->assertStatus(200);
        $response->assertSee('name="jenis_kelamin"', false);
    }

    public function test_admin_can_create_siswa(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('siswa.store'), [
            'nis'      => '12345678',
            'nama'     => 'Test Siswa',
            'kelas'    => 'XI-SIJA 1',
            'jenis_kelamin' => 'P',
            'email'    => 'siswa@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('siswa.index'));
        $this->assertDatabaseHas('siswa', ['NIS' => '12345678', 'nama_siswa' => 'Test Siswa', 'jenis_kelamin' => 'P']);
        $this->assertDatabaseHas('users', ['email' => 'siswa@test.com', 'role' => 'siswa']);
    }

    public function test_admin_can_create_siswa_when_gender_column_has_not_reached_production_yet(): void
    {
        $admin = $this->createAdmin();

        Schema::table('siswa', function ($table) {
            $table->dropColumn('jenis_kelamin');
        });

        $response = $this->actingAs($admin)->post(route('siswa.store'), [
            'nis' => '22334455',
            'nama' => 'Siswa Schema Lama',
            'kelas' => 'XI-SIJA 1',
            'jenis_kelamin' => 'L',
            'email' => 'schema-lama@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('siswa.index'));
        $this->assertDatabaseHas('siswa', [
            'NIS' => '22334455',
            'nama_siswa' => 'Siswa Schema Lama',
            'kelas' => 'XI-SIJA 1',
        ]);
        $this->assertDatabaseHas('users', ['email' => 'schema-lama@test.com', 'role' => 'siswa']);
    }

    public function test_admin_can_update_siswa(): void
    {
        $admin = $this->createAdmin();

        $user = User::factory()->create(['role' => 'siswa', 'name' => 'Old Name']);
        Siswa::create([
            'NIS'        => '99999999',
            'user_id'    => $user->id,
            'nama_siswa' => 'Old Name',
            'kelas'      => 'XI-SIJA 1',
        ]);

        $response = $this->actingAs($admin)->put(route('siswa.update', '99999999'), [
            'nama'  => 'New Name',
            'kelas' => 'XI-SIJA 1',
            'jenis_kelamin' => 'L',
            'email' => $user->email,
        ]);

        $response->assertRedirect(route('siswa.index'));
        $this->assertDatabaseHas('siswa', ['NIS' => '99999999', 'nama_siswa' => 'New Name', 'kelas' => 'XI-SIJA 1', 'jenis_kelamin' => 'L']);
    }

    public function test_admin_can_open_edit_form_for_siswa_with_slashes_in_nis(): void
    {
        $admin = $this->createAdmin();

        $user = User::factory()->create(['role' => 'siswa', 'name' => 'Slash Student']);
        Siswa::create([
            'NIS' => '18401/076/065',
            'user_id' => $user->id,
            'nama_siswa' => 'Slash Student',
            'kelas' => 'XI-SIJA 1',
        ]);

        $response = $this->actingAs($admin)->get(route('siswa.edit', ['nis' => '18401/076/065']));

        $response->assertOk();
        $response->assertSee('Slash Student');
    }

    public function test_admin_can_update_siswa_with_slashes_in_nis(): void
    {
        $admin = $this->createAdmin();

        $user = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Slash Student',
            'email' => 'slash@student.test',
        ]);

        Siswa::create([
            'NIS' => '18401/076/065',
            'user_id' => $user->id,
            'nama_siswa' => 'Slash Student',
            'kelas' => 'XI-SIJA 1',
        ]);

        $response = $this->actingAs($admin)->put(route('siswa.update', ['nis' => '18401/076/065']), [
            'nama' => 'Slash Student Updated',
            'kelas' => 'XI-SIJA 2',
            'jenis_kelamin' => 'P',
            'email' => 'slash@student.test',
        ]);

        $response->assertRedirect(route('siswa.index'));
        $this->assertDatabaseHas('siswa', [
            'NIS' => '18401/076/065',
            'nama_siswa' => 'Slash Student Updated',
            'kelas' => 'XI-SIJA 2',
            'jenis_kelamin' => 'P',
        ]);
    }

    public function test_non_admin_cannot_access_siswa(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('siswa.index'));
        $response->assertStatus(403);
    }

    public function test_create_siswa_requires_valid_data(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('siswa.store'), [
            'nis'  => '',
            'nama' => '',
        ]);

        $response->assertSessionHasErrors(['nis', 'nama', 'kelas', 'jenis_kelamin', 'email', 'password']);
    }
}
