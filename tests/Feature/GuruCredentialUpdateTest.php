<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GuruCredentialUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_guru_email_and_password_with_extra_verification(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('admin-secret'),
        ]);
        $guruUser = User::factory()->create([
            'role' => 'guru',
            'email' => 'guru.lama@example.com',
            'password' => Hash::make('password-lama'),
        ]);
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
        $guru->mapels()->sync([$mapel->kd_mapel]);

        $response = $this->actingAs($admin)->put(route('guru.update', $guru->NIP), [
            'nama' => 'Guru Uji Baru',
            'kd_mapel' => [$mapel->kd_mapel],
            'email' => 'guru.baru@example.com',
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
            'change_login_credentials' => '1',
            'credential_confirmation' => $guru->NIP,
            'admin_password_confirmation' => 'admin-secret',
        ]);

        $response->assertRedirect(route('guru.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('guru', [
            'NIP' => $guru->NIP,
            'nama_guru' => 'Guru Uji Baru',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $guruUser->id,
            'email' => 'guru.baru@example.com',
            'name' => 'Guru Uji Baru',
        ]);
        $this->assertTrue(Hash::check('password-baru', $guruUser->fresh()->password));
    }

    public function test_admin_cannot_update_guru_credentials_without_extra_verification(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('admin-secret'),
        ]);
        $guruUser = User::factory()->create([
            'role' => 'guru',
            'email' => 'guru.lama@example.com',
            'password' => Hash::make('password-lama'),
        ]);
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
        $guru->mapels()->sync([$mapel->kd_mapel]);

        $response = $this->from(route('guru.edit', $guru->NIP))
            ->actingAs($admin)
            ->put(route('guru.update', $guru->NIP), [
                'nama' => 'Guru Uji Baru',
                'kd_mapel' => [$mapel->kd_mapel],
                'email' => 'guru.baru@example.com',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
                'change_login_credentials' => '1',
                'credential_confirmation' => 'SALAH',
                'admin_password_confirmation' => 'admin-secret',
            ]);

        $response->assertRedirect(route('guru.edit', $guru->NIP));
        $response->assertSessionHasErrors('credential_confirmation');
        $this->assertDatabaseHas('users', [
            'id' => $guruUser->id,
            'email' => 'guru.lama@example.com',
        ]);
        $this->assertTrue(Hash::check('password-lama', $guruUser->fresh()->password));
    }
}
