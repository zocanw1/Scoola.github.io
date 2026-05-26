<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_import_guru_rows_from_parsed_excel_payload(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('guru.import'), [
            'rows' => json_encode([
                ['nama' => 'AHMAD ZAKY ABRORIYANSYAH, S.Pd.I.', 'nip' => '603 202207 1 19921015'],
                ['nama' => 'ANDRI HIDAYATI, S.Pd.', 'nip' => '19771009 202221 2 008'],
            ], JSON_THROW_ON_ERROR),
        ]);

        $response->assertRedirect(route('guru.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('guru', [
            'NIP' => '603202207119921015',
            'nama_guru' => 'AHMAD ZAKY ABRORIYANSYAH, S.Pd.I.',
            'kd_mapel' => null,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'guru-603202207119921015@import.scoola.local',
            'role' => 'guru',
        ]);

        $this->assertDatabaseHas('guru', [
            'NIP' => '197710092022212008',
            'nama_guru' => 'ANDRI HIDAYATI, S.Pd.',
        ]);
    }

    public function test_import_skips_existing_nip_without_breaking_other_rows(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $existingUser = User::factory()->create([
            'role' => 'guru',
            'email' => 'guru-197710092022212008@import.scoola.local',
        ]);

        Guru::create([
            'NIP' => '197710092022212008',
            'user_id' => $existingUser->id,
            'nama_guru' => 'Guru Lama',
            'kd_mapel' => null,
        ]);

        $response = $this->actingAs($admin)->post(route('guru.import'), [
            'rows' => json_encode([
                ['nama' => 'ANDRI HIDAYATI, S.Pd.', 'nip' => '19771009 202221 2 008'],
                ['nama' => 'Dra. LILIK WAHYUNINGSIH', 'nip' => '19670923 202221 2 002'],
            ], JSON_THROW_ON_ERROR),
        ]);

        $response->assertRedirect(route('guru.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('guru', 2);
        $this->assertDatabaseHas('guru', [
            'NIP' => '196709232022212002',
            'nama_guru' => 'Dra. LILIK WAHYUNINGSIH',
        ]);
    }
}
