<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Presensi;
use App\Models\SesiPresensi;
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

    public function test_kakonsli_uses_role_specific_root_breadcrumb_on_shared_admin_pages(): void
    {
        $kakonsli = User::factory()->create([
            'role' => 'kakonsli',
            'name' => 'Tes Kakonsli',
        ]);

        $response = $this->actingAs($kakonsli)->get(route('admin.rekap.index'));

        $response->assertOk();
        $response->assertSee('scoola-breadcrumbs', false);
        $response->assertSee('class="scoola-breadcrumb-link">Kakonsli</a>', false);
        $response->assertDontSee('class="scoola-breadcrumb-link">Admin</a>', false);
        $response->assertSee(route('admin.presensi-siswa.index', [], false), false);
    }

    public function test_presensi_siswa_index_keeps_listing_mode_even_if_nis_is_present(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Kelas::create([
            'nama_kelas' => 'XI-SIJA DETAIL',
            'wali_kelas_nip' => null,
        ]);

        $siswa = Siswa::create([
            'NIS' => 'DETAIL-001',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Muhammad Akhadi Al Machzumi',
            'kelas' => 'XI-SIJA DETAIL',
        ]);

        $listingResponse = $this->actingAs($admin)->get(route('admin.presensi-siswa.index', [
            'kelas' => 'XI-SIJA DETAIL',
            'nis' => $siswa->NIS,
        ]));

        $listingResponse->assertOk();
        $listingResponse->assertDontSee('Detail Kehadiran');
        $listingResponse->assertDontSee('scoola-breadcrumb-current">Muhammad Akhadi Al Machzumi', false);
        $listingResponse->assertSee('/admin/presensi-siswa/DETAIL-001', false);
        $listingResponse->assertSee('kelas=XI-SIJA%20DETAIL', false);
    }

    public function test_presensi_siswa_detail_uses_dedicated_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Kelas::create([
            'nama_kelas' => 'XI-SIJA ANCHOR',
            'wali_kelas_nip' => null,
        ]);

        $siswa = Siswa::create([
            'NIS' => 'ANCHOR-001',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Anchor',
            'kelas' => 'XI-SIJA ANCHOR',
        ]);

        $detailResponse = $this->actingAs($admin)->get(route('admin.presensi-siswa.show', [
            'nis' => $siswa->NIS,
            'kelas' => 'XI-SIJA ANCHOR',
        ]));

        $detailResponse->assertOk();
        $detailResponse->assertSee('Detail Kehadiran');
        $detailResponse->assertSee('scoola-breadcrumb-current">Siswa Anchor', false);
        $detailResponse->assertSee(route('admin.presensi-siswa.index', ['kelas' => 'XI-SIJA ANCHOR'], false), false);
    }

    public function test_presensi_siswa_detail_page_accepts_literal_slash_nis_urls(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Kelas::firstOrCreate([
            'nama_kelas' => 'XI-SIJA 2',
        ], [
            'wali_kelas_nip' => null,
        ]);

        $siswa = Siswa::create([
            'NIS' => '17588/122/065',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Literal Slash',
            'kelas' => 'XI-SIJA 2',
        ]);

        $detailResponse = $this->actingAs($admin)->get(
            '/admin/presensi-siswa/17588/122/065?q=&kelas=XI-SIJA%202&tanggal_mulai=2026-05-01&tanggal_akhir=2026-06-01'
        );

        $detailResponse->assertOk();
        $detailResponse->assertSee($siswa->nama_siswa);
        $detailResponse->assertSee('Detail Kehadiran');
    }

    public function test_presensi_siswa_detail_page_tolerates_malformed_legacy_attendance_dates(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 2'], ['wali_kelas_nip' => null]);

        $mapel = Mapel::create([
            'kd_mapel' => 'MAPEL-MALFORMED',
            'nama_mapel' => 'Basis Data',
        ]);

        Guru::create([
            'NIP' => 'NIP-MALFORMED',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Malformed',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-MALFORMED',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => 'NIP-MALFORMED',
            'kelas' => 'XI-SIJA 2',
        ]);

        $siswa = Siswa::create([
            'NIS' => '17588/122/065',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Tanggal Rusak',
            'kelas' => 'XI-SIJA 2',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 2',
            'kd_jp' => 'JP-MALFORMED',
            'kode_presensi' => 'BAD123',
            'waktu_berlaku' => now()->addHour(),
            'status' => 'selesai',
        ]);

        Presensi::create([
            'kd_presensi' => 'BAD-DATE-001',
            'sesi_id' => $sesi->id,
            'tanggal' => '2026-05-xx',
            'kd_jp' => 'JP-MALFORMED',
            'jam_masuk' => null,
            'status' => 'Hadir',
            'NIS' => $siswa->NIS,
        ]);

        $detailResponse = $this->actingAs($admin)->get(
            '/admin/presensi-siswa/17588/122/065?q=&kelas=XI-SIJA%202&tanggal_mulai=2026-05-01&tanggal_akhir=2026-06-01'
        );

        $detailResponse->assertOk();
        $detailResponse->assertSee($siswa->nama_siswa);
        $detailResponse->assertSee('2026-05-xx');
    }

    public function test_presensi_siswa_index_tolerates_malformed_queue_dates(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 2'], ['wali_kelas_nip' => null]);

        $mapel = Mapel::create([
            'kd_mapel' => 'MAPEL-QUEUE-BAD',
            'nama_mapel' => 'Mapel Queue Bad',
        ]);

        Guru::create([
            'NIP' => 'NIP-QUEUE-BAD',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Queue Bad',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-QUEUE-BAD',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => 'NIP-QUEUE-BAD',
            'kelas' => 'XI-SIJA 2',
        ]);

        $siswa = Siswa::create([
            'NIS' => 'MUH-QUEUE-01',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Muhammad Queue',
            'kelas' => 'XI-SIJA 2',
        ]);

        Presensi::create([
            'kd_presensi' => 'QUEUE-BAD-001',
            'sesi_id' => null,
            'tanggal' => '2026-06-07x',
            'kd_jp' => 'JP-QUEUE-BAD',
            'jam_masuk' => null,
            'status' => 'Alpa',
            'NIS' => $siswa->NIS,
            'updated_at' => '2026-06-07 08:00:00',
        ]);

        $response = $this->actingAs($admin)->get('/admin/presensi-siswa?kelas=&q=MUH&tanggal_mulai=2026-06-01&tanggal_akhir=2026-06-08');

        $response->assertOk();
        $response->assertSee('Muhammad Queue');
        $response->assertSee('07 Jun 2026');
    }

    public function test_presensi_siswa_index_falls_back_when_filter_dates_are_malformed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/presensi-siswa?kelas=&q=MUH&tanggal_mulai=bukan-tanggal&tanggal_akhir=juga-bukan');

        $response->assertOk();
        $response->assertSee('Presensi Siswa');
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
