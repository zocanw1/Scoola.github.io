<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BreadcrumbNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_edit_guru_page_renders_clickable_breadcrumbs(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $mapel = Mapel::create([
            'kd_mapel' => 'MAPEL-BREADCRUMB',
            'nama_mapel' => 'Matematika Breadcrumb',
        ]);

        $guruUser = User::factory()->create(['role' => 'guru', 'name' => 'Pak Breadcrumb']);
        $guru = Guru::create([
            'NIP' => 'NIP-BREADCRUMB',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Pak Breadcrumb',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        $guru->mapels()->sync([$mapel->kd_mapel]);

        $response = $this->actingAs($admin)->get(route('guru.edit', $guru->NIP));

        $response->assertOk();
        $response->assertSee('scoola-breadcrumbs', false);
        $response->assertSee(route('admin.dashboard', [], false), false);
        $response->assertSee(route('guru.index', [], false), false);
        $response->assertSee('Edit Guru', false);
        $response->assertSee('Pak Breadcrumb', false);
    }

    public function test_guru_presensi_page_renders_breadcrumbs_in_guru_layout(): void
    {
        $guruUser = User::factory()->create(['role' => 'guru']);
        $this->createJadwalForGuru($guruUser, 'JP-BREADCRUMB', 'XI-SIJA 1');

        $response = $this->actingAs($guruUser)->get(route('guru.presensi.index'));

        $response->assertOk();
        $response->assertSee('scoola-breadcrumbs', false);
        $response->assertSee(route('guru.dashboard', [], false), false);
        $response->assertSee('Presensi', false);
    }

    public function test_siswa_dashboard_renders_breadcrumbs_in_siswa_layout(): void
    {
        $siswaUser = User::factory()->create(['role' => 'siswa']);

        Siswa::create([
            'NIS' => 'SISWA-BREADCRUMB',
            'user_id' => $siswaUser->id,
            'nama_siswa' => 'Siswa Breadcrumb',
            'kelas' => 'XI-SIJA 2',
        ]);

        $response = $this->actingAs($siswaUser)->get(route('siswa.dashboard'));

        $response->assertOk();
        $response->assertSee('scoola-breadcrumbs', false);
        $response->assertSee('Dashboard', false);
        $response->assertSee(route('siswa.dashboard', [], false), false);
    }

    private function createJadwalForGuru(User $guruUser, string $kodeJp, string $kelas): JadwalPelajaran
    {
        $mapel = Mapel::create([
            'kd_mapel' => 'MAPEL-' . $kodeJp,
            'nama_mapel' => 'Mapel ' . $kodeJp,
        ]);

        $guru = Guru::create([
            'NIP' => 'NIP-' . $kodeJp,
            'user_id' => $guruUser->id,
            'nama_guru' => $guruUser->name,
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        Kelas::firstOrCreate(['nama_kelas' => $kelas]);

        return JadwalPelajaran::create([
            'kd_jp' => $kodeJp,
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => $kelas,
        ]);
    }
}
