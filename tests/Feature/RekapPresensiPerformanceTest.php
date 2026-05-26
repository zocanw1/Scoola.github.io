<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekapPresensiPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_rekap_index_builds_slot_and_status_matrices_for_the_selected_class(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 1']);

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

        JadwalPelajaran::create([
            'kd_jp' => 'JP001',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 1',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-1',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa 1',
            'kelas' => 'XI-SIJA 1',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-1',
            'sesi_id' => null,
            'tanggal' => now()->toDateString(),
            'kd_jp' => 'JP001',
            'jam_masuk' => '07:00:00',
            'status' => 'Hadir',
            'NIS' => 'SISWA-1',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 1',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertViewHas('slotMatrix', function (array $slotMatrix): bool {
            return ($slotMatrix['Senin'][1]['kd_jp'] ?? null) === 'JP001'
                && ($slotMatrix['Senin'][2]['kd_jp'] ?? null) === 'JP001';
        });
        $response->assertViewHas('statusMatrix', function (array $statusMatrix): bool {
            return ($statusMatrix['SISWA-1']['Senin'][1] ?? null) === 'Hadir'
                && ($statusMatrix['SISWA-1']['Senin'][2] ?? null) === 'Hadir';
        });
    }
}
