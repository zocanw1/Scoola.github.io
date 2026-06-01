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
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RekapPresensiPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_rekap_index_builds_slot_and_status_matrices_for_the_selected_class(): void
    {
        Carbon::setTestNow('2026-05-25 08:00:00');

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

    public function test_rekap_index_shows_weekly_and_student_modes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

        $response->assertOk();
        $response->assertSee('Rekap Mingguan');
        $response->assertSee('Rekap Per Siswa');
    }

    public function test_student_rekap_form_uses_student_name_input_instead_of_student_select(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA NAME']);

        Siswa::create([
            'NIS' => 'SISWA-NAME',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Nama Dicari',
            'kelas' => 'XI-SIJA NAME',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA NAME',
        ]));

        $response->assertOk();
        $response->assertSee('name="nama_siswa"', false);
        $response->assertDontSee('name="nis"', false);
        $response->assertDontSee('PILIH SISWA');
        $response->assertSee('student-live-search-list', false);
    }

    public function test_student_rekap_search_lists_matches_without_showing_detail_until_detail_clicked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA SEARCH']);

        Siswa::create([
            'NIS' => 'SEARCH-1',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Muhammad Aqil Naufal',
            'kelas' => 'XI-SIJA SEARCH',
        ]);

        Siswa::create([
            'NIS' => 'SEARCH-2',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Muhammad Rafif',
            'kelas' => 'XI-SIJA SEARCH',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA SEARCH',
            'nama_siswa' => 'muh',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertSee('Muhammad Aqil Naufal');
        $response->assertSee('Muhammad Rafif');
        $response->assertSee('Lihat Detail');
        $response->assertDontSee('Riwayat Presensi');
    }

    public function test_rekap_index_still_loads_when_siswa_table_has_no_jenis_kelamin_column(): void
    {
        if (Schema::hasColumn('siswa', 'jenis_kelamin')) {
            Schema::table('siswa', function ($table): void {
                $table->dropColumn('jenis_kelamin');
            });
        }

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 2']);

        $mapel = Mapel::create([
            'kd_mapel' => 'BING',
            'nama_mapel' => 'Bahasa Inggris',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011002',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Kelas 2',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP002',
            'hari' => 'Selasa',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 2',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-2',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa 2',
            'kelas' => 'XI-SIJA 2',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 2',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertSee('Siswa 2');
    }

    public function test_student_rekap_still_loads_when_presensi_status_history_table_is_missing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA HIST']);

        $mapel = Mapel::create([
            'kd_mapel' => 'SEJ',
            'nama_mapel' => 'Sejarah',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011099',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Histori',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP099',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA HIST',
        ]);

        $siswa = Siswa::create([
            'NIS' => 'SISWA-HIST',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Histori',
            'kelas' => 'XI-SIJA HIST',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-HIST',
            'sesi_id' => null,
            'tanggal' => '2026-05-26',
            'kd_jp' => 'JP099',
            'jam_masuk' => '07:00:00',
            'status' => 'Hadir',
            'NIS' => $siswa->NIS,
        ]);

        Schema::dropIfExists('presensi_status_histories');

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA HIST',
            'nama_siswa' => $siswa->nama_siswa,
            'nis' => $siswa->NIS,
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertSee('Riwayat Presensi');
        $response->assertSee('Siswa Histori');
    }

    public function test_rekap_uses_sesi_presensi_kd_jp_when_presensi_kd_jp_is_empty(): void
    {
        Carbon::setTestNow('2026-05-25 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 3']);

        $mapel = Mapel::create([
            'kd_mapel' => 'PKN',
            'nama_mapel' => 'Pendidikan Pancasila',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011003',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Sesi',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP003',
            'hari' => 'Senin',
            'jam_mulai' => 3,
            'jam_selesai' => 4,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 3',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-3',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa 3',
            'kelas' => 'XI-SIJA 3',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 3',
            'kd_jp' => 'JP003',
            'kode_presensi' => 'SES003',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-3',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => null,
            'jam_masuk' => '08:00:00',
            'status' => 'Hadir',
            'NIS' => 'SISWA-3',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 3',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertViewHas('statusMatrix', function (array $statusMatrix): bool {
            return ($statusMatrix['SISWA-3']['Senin'][3] ?? null) === 'Hadir'
                && ($statusMatrix['SISWA-3']['Senin'][4] ?? null) === 'Hadir';
        });
    }

    public function test_rekap_marks_students_as_alpa_when_a_finished_session_was_held_without_presensi(): void
    {
        Carbon::setTestNow('2026-05-26 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 4']);

        $mapel = Mapel::create([
            'kd_mapel' => 'BIO',
            'nama_mapel' => 'Biologi',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011004',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Kosong',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP004',
            'hari' => 'Selasa',
            'jam_mulai' => 5,
            'jam_selesai' => 6,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 4',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-4',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa 4',
            'kelas' => 'XI-SIJA 4',
        ]);

        SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 4',
            'kd_jp' => 'JP004',
            'kode_presensi' => null,
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 4',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertViewHas('statusMatrix', function (array $statusMatrix): bool {
            return ($statusMatrix['SISWA-4']['Selasa'][5] ?? null) === 'Alpa'
                && ($statusMatrix['SISWA-4']['Selasa'][6] ?? null) === 'Alpa';
        });
    }

    public function test_student_rekap_only_builds_rows_for_selected_student(): void
    {
        Carbon::setTestNow('2026-05-25 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 5']);

        $mapel = Mapel::create([
            'kd_mapel' => 'KIM',
            'nama_mapel' => 'Kimia',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011005',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Kimia',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP005',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 5',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-5A',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Dipilih',
            'kelas' => 'XI-SIJA 5',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-5B',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Lain',
            'kelas' => 'XI-SIJA 5',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 5',
            'kd_jp' => 'JP005',
            'kode_presensi' => 'SES005',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-5A',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => null,
            'jam_masuk' => '08:05:00',
            'status' => 'Hadir',
            'NIS' => 'SISWA-5A',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-5B',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => null,
            'jam_masuk' => '08:06:00',
            'status' => 'Sakit',
            'NIS' => 'SISWA-5B',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA 5',
            'nama_siswa' => 'Siswa Dipilih',
            'nis' => 'SISWA-5A',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertViewHas('studentRows', function (Collection $studentRows): bool {
            return $studentRows->count() === 1
                && $studentRows->first()['nis'] === 'SISWA-5A'
                && $studentRows->first()['status'] === 'Hadir';
        });
        $response->assertViewHas('studentTotals', function (array $studentTotals): bool {
            return $studentTotals['Hadir'] === 1
                && $studentTotals['Sakit'] === 0;
        });
    }

    public function test_student_rekap_marks_selected_student_as_alpa_when_finished_session_has_no_record(): void
    {
        Carbon::setTestNow('2026-05-27 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 6']);

        $mapel = Mapel::create([
            'kd_mapel' => 'FIS',
            'nama_mapel' => 'Fisika',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011006',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Fisika',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP006',
            'hari' => 'Rabu',
            'jam_mulai' => 3,
            'jam_selesai' => 4,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 6',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-6',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Kosong',
            'kelas' => 'XI-SIJA 6',
        ]);

        SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 6',
            'kd_jp' => 'JP006',
            'kode_presensi' => null,
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA 6',
            'nama_siswa' => 'Siswa Kosong',
            'nis' => 'SISWA-6',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertViewHas('studentRows', function (Collection $studentRows): bool {
            return $studentRows->count() === 1
                && $studentRows->first()['status'] === 'Alpa'
                && $studentRows->first()['mapel'] === 'Fisika';
        });
    }

    public function test_student_rekap_falls_back_when_legacy_record_status_is_empty(): void
    {
        Carbon::setTestNow('2026-05-28 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA LEGACY']);

        $mapel = Mapel::create([
            'kd_mapel' => 'LEGACY',
            'nama_mapel' => 'Legacy Test',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011099',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Legacy',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-LEGACY',
            'hari' => 'Rabu',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA LEGACY',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-LEGACY',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Legacy',
            'kelas' => 'XI-SIJA LEGACY',
        ]);

        DB::table('presensi')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'kd_presensi' => 'PRS-LEGACY-EMPTY',
            'sesi_id' => null,
            'tanggal' => '2026-05-28',
            'kd_jp' => 'JP-LEGACY',
            'jam_masuk' => null,
            'status' => '',
            'NIS' => 'SISWA-LEGACY',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA LEGACY',
            'nama_siswa' => 'Siswa Legacy',
            'nis' => 'SISWA-LEGACY',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertViewHas('studentRows', function (Collection $studentRows): bool {
            return $studentRows->count() === 1
                && $studentRows->first()['status'] === 'Belum Hadir';
        });
        $response->assertViewHas('studentTotals', function (array $studentTotals): bool {
            return $studentTotals['Belum Hadir'] === 1;
        });
    }

    public function test_student_rekap_export_returns_excel_for_selected_student(): void
    {
        Carbon::setTestNow('2026-05-28 08:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 7']);

        $mapel = Mapel::create([
            'kd_mapel' => 'IND',
            'nama_mapel' => 'Bahasa Indonesia',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011007',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Indonesia',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP007',
            'hari' => 'Kamis',
            'jam_mulai' => 5,
            'jam_selesai' => 6,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 7',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-7',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Export',
            'kelas' => 'XI-SIJA 7',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-7',
            'sesi_id' => null,
            'tanggal' => now()->toDateString(),
            'kd_jp' => 'JP007',
            'jam_masuk' => '10:00:00',
            'status' => 'Hadir',
            'NIS' => 'SISWA-7',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.export', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA 7',
            'nama_siswa' => 'Siswa Export',
            'nis' => 'SISWA-7',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd-ms-excel');
        $response->assertSee('REKAP PRESENSI PER SISWA');
        $response->assertSee('Siswa Export');
        $response->assertSee('Bahasa Indonesia');
        $response->assertSee('Hadir');
    }

    public function test_kakonsli_can_view_full_rekap_for_any_class(): void
    {
        $kakonsli = User::factory()->create(['role' => 'kakonsli']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 1']);
        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 2']);

        $response = $this->actingAs($kakonsli)->get(route('admin.rekap.index', [
            'kelas' => 'XI-SIJA 2',
            'tanggal' => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertViewHas('selectedKelas', 'XI-SIJA 2');
        $response->assertViewHas('kelas', function (Collection $kelas): bool {
            return $kelas->pluck('nama_kelas')->contains('XI-SIJA 1')
                && $kelas->pluck('nama_kelas')->contains('XI-SIJA 2');
        });
    }

    public function test_kakonsli_can_search_students_and_view_personal_presence_detail_from_new_menu(): void
    {
        Carbon::setTestNow('2026-05-29 08:00:00');

        $kakonsli = User::factory()->create(['role' => 'kakonsli']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 2']);

        $mapel = Mapel::create([
            'kd_mapel' => 'PWEB',
            'nama_mapel' => 'Pemrograman Web',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011301',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Web',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-KON-1',
            'hari' => 'Rabu',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 2',
        ]);

        $sultan = Siswa::create([
            'NIS' => 'SULTAN-01',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Sultan Maulana',
            'kelas' => 'XI-SIJA 2',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-LAIN',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Rafif Lain',
            'kelas' => 'XI-SIJA 2',
        ]);

        $sesiIzin = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 2',
            'kd_jp' => 'JP-KON-1',
            'kode_presensi' => 'KON-IZIN',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
            'created_at' => Carbon::parse('2026-05-14 07:00:00'),
            'updated_at' => Carbon::parse('2026-05-14 07:00:00'),
        ]);

        $sesiAlpa = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 2',
            'kd_jp' => 'JP-KON-1',
            'kode_presensi' => 'KON-ALPA',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
            'created_at' => Carbon::parse('2026-05-21 07:00:00'),
            'updated_at' => Carbon::parse('2026-05-21 07:00:00'),
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-KON-IZIN',
            'sesi_id' => $sesiIzin->id,
            'tanggal' => '2026-05-14',
            'kd_jp' => null,
            'jam_masuk' => null,
            'status' => 'Izin',
            'NIS' => $sultan->NIS,
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-KON-ALPA',
            'sesi_id' => $sesiAlpa->id,
            'tanggal' => '2026-05-21',
            'kd_jp' => null,
            'jam_masuk' => null,
            'status' => 'Alpa',
            'NIS' => $sultan->NIS,
        ]);

        $listResponse = $this->actingAs($kakonsli)->get(route('admin.presensi-siswa.index', [
            'q' => 'sultan',
            'kelas' => 'XI-SIJA 2',
        ]));

        $listResponse->assertOk();
        $listResponse->assertSee('Presensi Siswa');
        $listResponse->assertSee('Sultan Maulana');
        $listResponse->assertSee('Lihat Detail');

        $detailResponse = $this->actingAs($kakonsli)->get(route('admin.presensi-siswa.index', [
            'q' => 'sultan',
            'kelas' => 'XI-SIJA 2',
            'nis' => 'SULTAN-01',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $detailResponse->assertOk();
        $detailResponse->assertViewHas('selectedSiswaDetail', fn ($selected) => $selected?->NIS === 'SULTAN-01');
        $detailResponse->assertViewHas('detailTotals', function (array $detailTotals): bool {
            return $detailTotals['Izin'] === 1
                && $detailTotals['Alpa'] === 1;
        });
    }

    public function test_range_rekap_mode_summarizes_all_students_within_selected_dates(): void
    {
        Carbon::setTestNow('2026-05-29 08:00:00');

        $kakonsli = User::factory()->create(['role' => 'kakonsli']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 1']);

        $mapel = Mapel::create([
            'kd_mapel' => 'BDP',
            'nama_mapel' => 'Basis Data',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011302',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Basis Data',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-KON-2',
            'hari' => 'Kamis',
            'jam_mulai' => 3,
            'jam_selesai' => 4,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA 1',
        ]);

        $siswaA = Siswa::create([
            'NIS' => 'SISWA-A',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Sultan A',
            'kelas' => 'XI-SIJA 1',
        ]);

        $siswaB = Siswa::create([
            'NIS' => 'SISWA-B',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Sultan B',
            'kelas' => 'XI-SIJA 1',
        ]);

        $sesiHadir = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => 'JP-KON-2',
            'kode_presensi' => 'KON-H',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
            'created_at' => Carbon::parse('2026-05-08 08:00:00'),
            'updated_at' => Carbon::parse('2026-05-08 08:00:00'),
        ]);

        $sesiIzin = SesiPresensi::create([
            'guru_id' => $guruUser->id,
            'kelas' => 'XI-SIJA 1',
            'kd_jp' => 'JP-KON-2',
            'kode_presensi' => 'KON-I',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
            'created_at' => Carbon::parse('2026-05-15 08:00:00'),
            'updated_at' => Carbon::parse('2026-05-15 08:00:00'),
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-RANGE-A1',
            'sesi_id' => $sesiHadir->id,
            'tanggal' => '2026-05-08',
            'kd_jp' => null,
            'jam_masuk' => '08:00:00',
            'status' => 'Hadir',
            'NIS' => $siswaA->NIS,
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-RANGE-A2',
            'sesi_id' => $sesiIzin->id,
            'tanggal' => '2026-05-15',
            'kd_jp' => null,
            'jam_masuk' => null,
            'status' => 'Izin',
            'NIS' => $siswaA->NIS,
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-RANGE-B1',
            'sesi_id' => $sesiHadir->id,
            'tanggal' => '2026-05-08',
            'kd_jp' => null,
            'jam_masuk' => null,
            'status' => 'Alpa',
            'NIS' => $siswaB->NIS,
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-RANGE-B2',
            'sesi_id' => $sesiIzin->id,
            'tanggal' => '2026-05-15',
            'kd_jp' => null,
            'jam_masuk' => '08:03:00',
            'status' => 'Hadir',
            'NIS' => $siswaB->NIS,
        ]);

        $response = $this->actingAs($kakonsli)->get(route('admin.rekap.index', [
            'mode' => 'rentang',
            'kelas' => 'XI-SIJA 1',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertSee('Rekap Rentang Tanggal');
        $response->assertViewHas('rangeSummaryRows', function (Collection $rows): bool {
            $first = $rows->firstWhere('nis', 'SISWA-A');
            $second = $rows->firstWhere('nis', 'SISWA-B');

            return $rows->count() === 2
                && $first !== null
                && $second !== null
                && $first['totals']['Hadir'] === 1
                && $first['totals']['Izin'] === 1
                && $second['totals']['Hadir'] === 1
                && $second['totals']['Alpa'] === 1;
        });
    }

    public function test_range_rekap_mode_avoids_n_plus_one_queries_for_large_classes(): void
    {
        Carbon::setTestNow('2026-05-29 08:00:00');

        $kakonsli = User::factory()->create(['role' => 'kakonsli']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA PERF']);

        $mapel = Mapel::create([
            'kd_mapel' => 'PERF',
            'nama_mapel' => 'Pengujian Performa',
        ]);

        $guru = Guru::create([
            'NIP' => '198501012010011399',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Performa',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-PERF-1',
            'hari' => 'Kamis',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $guru->NIP,
            'kelas' => 'XI-SIJA PERF',
        ]);

        $students = collect(range(1, 18))->map(function (int $number): Siswa {
            return Siswa::create([
                'NIS' => sprintf('PERF-%02d', $number),
                'user_id' => User::factory()->create(['role' => 'siswa'])->id,
                'nama_siswa' => "Siswa Perf {$number}",
                'kelas' => 'XI-SIJA PERF',
            ]);
        });

        $sessionDates = [
            '2026-05-01 07:00:00',
            '2026-05-08 07:00:00',
            '2026-05-15 07:00:00',
            '2026-05-22 07:00:00',
        ];

        foreach ($sessionDates as $index => $sessionDate) {
            $session = SesiPresensi::create([
                'guru_id' => $guruUser->id,
                'kelas' => 'XI-SIJA PERF',
                'kd_jp' => 'JP-PERF-1',
                'kode_presensi' => 'PERF-' . ($index + 1),
                'waktu_berlaku' => Carbon::parse($sessionDate)->addHours(2),
                'status' => 'selesai',
                'created_at' => Carbon::parse($sessionDate),
                'updated_at' => Carbon::parse($sessionDate),
            ]);

            foreach ($students as $studentIndex => $student) {
                Presensi::create([
                    'kd_presensi' => sprintf('PRS-PERF-%02d-%02d', $index + 1, $studentIndex + 1),
                    'sesi_id' => $session->id,
                    'tanggal' => Carbon::parse($sessionDate)->toDateString(),
                    'kd_jp' => null,
                    'jam_masuk' => $studentIndex % 2 === 0 ? '07:00:00' : null,
                    'status' => $studentIndex % 3 === 0 ? 'Izin' : 'Hadir',
                    'NIS' => $student->NIS,
                ]);
            }
        }

        $queryCount = 0;
        DB::listen(function () use (&$queryCount): void {
            $queryCount++;
        });

        $response = $this->actingAs($kakonsli)->get(route('admin.rekap.index', [
            'mode' => 'rentang',
            'kelas' => 'XI-SIJA PERF',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $this->assertLessThanOrEqual(
            20,
            $queryCount,
            'Range rekap should aggregate data in batches instead of querying per student.'
        );
    }

    public function test_wali_kelas_can_view_weekly_and_student_rekap_only_for_assigned_class(): void
    {
        Carbon::setTestNow('2026-05-25 08:00:00');

        $waliUser = User::factory()->create(['role' => 'guru']);
        $otherGuruUser = User::factory()->create(['role' => 'guru']);

        $mapel = Mapel::create([
            'kd_mapel' => 'RPL',
            'nama_mapel' => 'Rekayasa Perangkat Lunak',
        ]);

        $waliGuru = Guru::create([
            'NIP' => '198501012010011101',
            'user_id' => $waliUser->id,
            'nama_guru' => 'Wali SIJA 2',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        $otherGuru = Guru::create([
            'NIP' => '198501012010011102',
            'user_id' => $otherGuruUser->id,
            'nama_guru' => 'Guru Lain',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 2'])->update(['wali_kelas_nip' => $waliGuru->NIP]);
        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 1'])->update(['wali_kelas_nip' => $otherGuru->NIP]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-WALI',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $waliGuru->NIP,
            'kelas' => 'XI-SIJA 2',
        ]);

        Siswa::create([
            'NIS' => 'SISWA-WALI',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Siswa Wali',
            'kelas' => 'XI-SIJA 2',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $waliUser->id,
            'kelas' => 'XI-SIJA 2',
            'kd_jp' => 'JP-WALI',
            'kode_presensi' => 'WALI22',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-WALI',
            'sesi_id' => $sesi->id,
            'tanggal' => now()->toDateString(),
            'kd_jp' => null,
            'jam_masuk' => '08:00:00',
            'status' => 'Hadir',
            'NIS' => 'SISWA-WALI',
        ]);

        $weeklyResponse = $this->actingAs($waliUser)->get(route('guru.rekap.index', [
            'kelas' => 'XI-SIJA 2',
            'tanggal' => now()->toDateString(),
        ]));

        $weeklyResponse->assertOk();
        $weeklyResponse->assertViewHas('selectedKelas', 'XI-SIJA 2');
        $weeklyResponse->assertViewHas('kelas', function (Collection $kelas): bool {
            return $kelas->count() === 1
                && $kelas->first()->nama_kelas === 'XI-SIJA 2';
        });
        $weeklyResponse->assertViewHas('statusMatrix', function (array $statusMatrix): bool {
            return ($statusMatrix['SISWA-WALI']['Senin'][1] ?? null) === 'Hadir'
                && ($statusMatrix['SISWA-WALI']['Senin'][2] ?? null) === 'Hadir';
        });

        $studentResponse = $this->actingAs($waliUser)->get(route('guru.rekap.index', [
            'mode' => 'siswa',
            'kelas' => 'XI-SIJA 2',
            'nama_siswa' => 'Siswa Wali',
            'nis' => 'SISWA-WALI',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $studentResponse->assertOk();
        $studentResponse->assertViewHas('studentRows', function (Collection $studentRows): bool {
            return $studentRows->count() === 1
                && $studentRows->first()['nis'] === 'SISWA-WALI'
                && $studentRows->first()['status'] === 'Hadir';
        });

        $this->actingAs($waliUser)->get(route('guru.rekap.index', [
            'kelas' => 'XI-SIJA 1',
            'tanggal' => now()->toDateString(),
        ]))->assertForbidden();
    }

    public function test_wali_kelas_can_view_scoped_presensi_siswa_page(): void
    {
        Carbon::setTestNow('2026-05-29 08:00:00');

        $waliUser = User::factory()->create(['role' => 'guru']);
        $guruUser = User::factory()->create(['role' => 'guru']);

        $mapel = Mapel::create([
            'kd_mapel' => 'WALI-PS',
            'nama_mapel' => 'Presensi Wali',
        ]);

        $waliGuru = Guru::create([
            'NIP' => '198501012010011401',
            'user_id' => $waliUser->id,
            'nama_guru' => 'Wali Presensi',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        Guru::create([
            'NIP' => '198501012010011402',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Presensi',
            'kd_mapel' => $mapel->kd_mapel,
        ]);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA WALI'])->update([
            'wali_kelas_nip' => $waliGuru->NIP,
        ]);

        JadwalPelajaran::create([
            'kd_jp' => 'JP-WALI-PS',
            'hari' => 'Senin',
            'jam_mulai' => 1,
            'jam_selesai' => 2,
            'kd_mapel' => $mapel->kd_mapel,
            'NIP' => $waliGuru->NIP,
            'kelas' => 'XI-SIJA WALI',
        ]);

        $siswa = Siswa::create([
            'NIS' => 'WALI-PS-01',
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nama_siswa' => 'Sultan Wali',
            'kelas' => 'XI-SIJA WALI',
        ]);

        $sesi = SesiPresensi::create([
            'guru_id' => $waliUser->id,
            'kelas' => 'XI-SIJA WALI',
            'kd_jp' => 'JP-WALI-PS',
            'kode_presensi' => 'WPS-1',
            'waktu_berlaku' => now()->addHours(2),
            'status' => 'selesai',
            'created_at' => Carbon::parse('2026-05-12 07:00:00'),
            'updated_at' => Carbon::parse('2026-05-12 07:00:00'),
        ]);

        Presensi::create([
            'kd_presensi' => 'PRS-WALI-PS-1',
            'sesi_id' => $sesi->id,
            'tanggal' => '2026-05-12',
            'kd_jp' => null,
            'jam_masuk' => null,
            'status' => 'Izin',
            'NIS' => $siswa->NIS,
        ]);

        $response = $this->actingAs($waliUser)->get(route('guru.presensi-siswa.index', [
            'q' => 'sultan',
            'nis' => 'WALI-PS-01',
            'tanggal_mulai' => '2026-05-01',
            'tanggal_akhir' => '2026-05-31',
        ]));

        $response->assertOk();
        $response->assertViewHas('selectedKelas', 'XI-SIJA WALI');
        $response->assertViewHas('selectedSiswaDetail', fn ($selected) => $selected?->NIS === 'WALI-PS-01');
        $response->assertViewHas('detailTotals', fn (array $detailTotals): bool => $detailTotals['Izin'] === 1);
        $response->assertSee('Presensi Siswa');
        $response->assertSee('Sultan Wali');
    }

    public function test_non_wali_guru_cannot_view_scoped_presensi_siswa_page(): void
    {
        $guruUser = User::factory()->create(['role' => 'guru']);

        Guru::create([
            'NIP' => '198501012010011403',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Biasa Presensi',
            'kd_mapel' => null,
        ]);

        $this->actingAs($guruUser)->get(route('guru.presensi-siswa.index'))->assertForbidden();
    }

    public function test_non_wali_guru_cannot_view_wali_kelas_rekap(): void
    {
        $guruUser = User::factory()->create(['role' => 'guru']);

        Guru::create([
            'NIP' => '198501012010011201',
            'user_id' => $guruUser->id,
            'nama_guru' => 'Guru Biasa',
            'kd_mapel' => null,
        ]);

        Kelas::firstOrCreate(['nama_kelas' => 'XI-SIJA 1']);

        $this->actingAs($guruUser)->get(route('guru.rekap.index', [
            'kelas' => 'XI-SIJA 1',
            'tanggal' => now()->toDateString(),
        ]))->assertForbidden();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }
}
