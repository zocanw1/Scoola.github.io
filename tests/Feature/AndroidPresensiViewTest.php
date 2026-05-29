<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AndroidPresensiViewTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_siswa_dashboard_exposes_android_friendly_location_and_otp_markup(): void
    {
        $siswaUser = User::factory()->create(['role' => 'siswa']);

        Siswa::create([
            'NIS' => 'SISWA-ANDROID-1',
            'user_id' => $siswaUser->id,
            'nama_siswa' => 'Siswa Android',
            'kelas' => 'XI-SIJA 1',
        ]);

        $response = $this->actingAs($siswaUser)->get(route('siswa.dashboard'));

        $response->assertOk();
        $response->assertSee('retryGpsBtn', false);
        $response->assertSee('gps-shell', false);
        $response->assertSee('gpsPermissionBackdrop', false);
        $response->assertSee('grantGpsBtn', false);
        $response->assertSee('role="dialog"', false);
        $response->assertSee('autocomplete="one-time-code"', false);
        $response->assertSee('inputmode="text"', false);
    }

    public function test_guru_session_picker_renders_touch_friendly_schedule_cards(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-25 08:00:00'));

        $guruUser = User::factory()->create(['role' => 'guru']);
        $this->createJadwalForGuru($guruUser, 'JP-ANDROID-1', 'XI-SIJA 1');

        $response = $this->actingAs($guruUser)->get(route('guru.presensi.index'));

        $response->assertOk();
        $response->assertSee('schedule-choice-card', false);
        $response->assertSee('type="radio"', false);
        $response->assertSee('startSessionBtn', false);
    }

    public function test_wali_kelas_sees_presensi_siswa_menu_in_guru_sidebar(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-25 08:00:00'));

        $guruUser = User::factory()->create(['role' => 'guru']);
        $jadwal = $this->createJadwalForGuru($guruUser, 'JP-ANDROID-WALI', 'XI-SIJA 1');

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 1'])->update([
            'wali_kelas_nip' => $jadwal->guru->NIP,
        ]);

        $response = $this->actingAs($guruUser)->get(route('guru.dashboard'));

        $response->assertOk();
        $response->assertSee('Presensi Siswa');
        $response->assertSee(route('guru.presensi-siswa.index', [], false), false);
        $response->assertSee('Rekap Presensi');
        $response->assertSee(route('guru.rekap.index', [], false), false);
    }

    public function test_guru_non_wali_does_not_see_presensi_siswa_menu(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-25 08:00:00'));

        $guruUser = User::factory()->create(['role' => 'guru']);
        $this->createJadwalForGuru($guruUser, 'JP-ANDROID-NONWALI', 'XI-SIJA 2');

        $response = $this->actingAs($guruUser)->get(route('guru.dashboard'));

        $response->assertOk();
        $response->assertDontSee('Presensi Siswa');
    }

    public function test_guru_session_room_renders_mobile_student_cards(): void
    {
        $guruUser = User::factory()->create(['role' => 'guru']);
        $jadwal = $this->createJadwalForGuru($guruUser, 'JP-ANDROID-2', 'XI-SIJA 2');

        $siswaUser = User::factory()->create(['role' => 'siswa']);
        Siswa::create([
            'NIS' => 'SISWA-ANDROID-2',
            'user_id' => $siswaUser->id,
            'nama_siswa' => 'Rina Android',
            'kelas' => 'XI-SIJA 2',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 2',
            'kd_jp' => $jadwal->kd_jp,
            'kode_presensi' => 'MOBILE',
            'waktu_berlaku' => now()->addHour(),
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($guruUser)->get(route('guru.presensi.ruang', $sesi->id));

        $response->assertOk();
        $response->assertSee('attendance-mobile-list', false);
        $response->assertSee('manual-action-grid', false);
        $response->assertSee('student-presence-card', false);
    }

    public function test_admin_rekap_renders_mobile_friendly_alternate_board(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-25 08:00:00'));

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 3']);

        $mapel = Mapel::create([
            'kd_mapel' => 'BIO-ANDROID',
            'nama_mapel' => 'Biologi Android',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011099',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Android',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-ANDROID-3',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 3',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-ANDROID-3',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Dewi Android',
            'kelas' => 'XI-SIJA 3',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 3',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertSee('rekap-mobile-board', false);
        $response->assertSee('rekap-mobile-day-card', false);
        $response->assertSee('Ringkasan Minggu Ini');
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

        return JadwalPelajaran::create([
            'kd_jp' => $kodeJp,
            'hari' => $this->currentHariIndonesia(),
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => $kelas,
        ]);
    }

    private function currentHariIndonesia(): string
    {
        return [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ][date('l')];
    }
}
