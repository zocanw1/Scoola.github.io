<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\JadwalPelajaran;
use App\Models\Guru;
use App\Support\PresensiStatusManager;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function __construct(private readonly PresensiStatusManager $presensiStatusManager)
    {
    }

    /* =========================
       1. PILIH KELAS
    ========================= */
    public function pilihKelas()
    {
        // Cek apakah ada sesi aktif
        $activeSesi = SesiPresensi::where('guru_id', auth()->id())
            ->where('status', 'aktif')
            ->first();

        if ($activeSesi) {
            return redirect()->route('guru.presensi.ruang', $activeSesi->id);
        }

        // Ambil jadwal guru login untuk hari ini
        $guru = Guru::where('user_id', auth()->id())->first();
        if (!$guru) {
            $jadwalHariIni = collect();
        } else {
            $hariIni = $this->convertDayToIndonesian(date('l'));
            $jadwalHariIni = JadwalPelajaran::with('mapel')
                ->where('NIP', $guru->NIP)
                ->where('hari', $hariIni)
                ->orderBy('jam_mulai')
                ->get();
        }

        // Ambil semua sesi aktif untuk melihat siapa saja yang sedang mengajar
        $allActiveSessions = SesiPresensi::where('status', 'aktif')
                                         ->with('guru', 'jadwal.mapel')
                                         ->get()
                                         ->groupBy('kelas');

        return view('guru.presensi.pilih-kelas', compact('jadwalHariIni', 'allActiveSessions'));
    }

    /* =========================
       2. BUKA KELAS (Start Session)
    ========================= */
    public function bukaKelas(Request $request)
    {
        $request->validate([
            'kd_jp' => 'required|string',
        ]);

        $guru = Guru::where('user_id', auth()->id())->first();
        if (!$guru) {
            abort(403, 'Akun guru tidak terhubung dengan data pengajar.');
        }

        $jadwal = JadwalPelajaran::where('kd_jp', $request->kd_jp)->firstOrFail();

        if ($jadwal->NIP !== $guru->NIP) {
            abort(403, 'Anda tidak berhak membuka sesi untuk jadwal guru lain.');
        }

        // Akhiri semua sesi aktif milik guru ini (Hanya boleh 1 sesi aktif per guru)
        SesiPresensi::where('guru_id', auth()->id())
            ->where('status', 'aktif')
            ->update(['status' => 'selesai']);
        
        // Buat sesi baru
        $kodeUnik = strtoupper(Str::random(6));

        $sesi = SesiPresensi::create([
            'guru_id' => auth()->id(),
            'kelas' => $jadwal->kelas,
            'kd_jp' => $jadwal->kd_jp,
            'kode_presensi' => $kodeUnik,
            'waktu_berlaku' => Carbon::now()->addHours(2), // Berlaku 2 jam
            'status' => 'aktif',
        ]);

        return redirect()->route('guru.presensi.ruang', $sesi->id);
    }

    /* =========================
       3. RUANG KELAS (Dashboard Murid)
    ========================= */
    public function ruangKelas($id)
    {
        $sesi = SesiPresensi::with('jadwal.mapel')->findOrFail($id);

        if ($sesi->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses sesi presensi ini.');
        }

        // Ambil data siswa yang terdaftar di kelas ini
        $siswaKelas = Siswa::where('kelas', $sesi->kelas)->orderBy('nama_siswa', 'asc')->get();
        
        // Ambil data presensi yang terkait dengan SESI INI
        $presensiHariIni = Presensi::where('sesi_id', $sesi->id)
                                   ->get()
                                   ->keyBy('NIS');

        // CEK TABRAK: Apakah ada guru lain yang juga sedang membuka kelas ini?
        $otherActiveSessions = SesiPresensi::where('kelas', $sesi->kelas)
                                            ->where('status', 'aktif')
                                            ->where('id', '!=', $sesi->id)
                                            ->with('guru')
                                            ->get();

        $attendanceVersion = $this->buildAttendanceVersion($sesi);

        return view('guru.presensi.ruang-kelas', compact('sesi', 'siswaKelas', 'presensiHariIni', 'otherActiveSessions', 'attendanceVersion'));
    }

    public function statusSnapshot($id)
    {
        $sesi = SesiPresensi::findOrFail($id);

        if ($sesi->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses sesi presensi ini.');
        }

        return response()->json([
            'version' => $this->buildAttendanceVersion($sesi),
        ]);
    }

    /* =========================
       4. TAMPIL KODE PROYEKTOR
    ========================= */
    public function tampilKode($id)
    {
        $sesi = SesiPresensi::findOrFail($id);

        if ($sesi->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses sesi presensi ini.');
        }

        if (!$sesi->kode_presensi) {
            return redirect()->route('guru.presensi.ruang', $sesi->id)
                ->with('error', 'Kode presensi tidak aktif. Generate kode baru terlebih dahulu.');
        }

        return view('guru.presensi.tampil-kode', compact('sesi'));
    }

    /* =========================
       5. AKHIRI SESI PRESENSI
       (Kode menjadi invalid, tapi kelas tetap aktif)
    ========================= */
    public function akhiriPresensi($id)
    {
        $sesi = SesiPresensi::findOrFail($id);

        if ($sesi->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses sesi presensi ini.');
        }

        // Kosongkan kode dan set waktu berlaku ke sekarang (kode invalid)
        $sesi->update([
            'kode_presensi' => null,
            'waktu_berlaku' => Carbon::now(),
        ]);

        return redirect()->route('guru.presensi.ruang', $sesi->id)
            ->with('success', 'Sesi presensi telah ditutup. Kode tidak bisa digunakan lagi.');
    }

    /* =========================
       6. GENERATE KODE BARU
       (Buat kode baru untuk sesi yang masih aktif)
    ========================= */
    public function generateKodeBaru($id)
    {
        $sesi = SesiPresensi::findOrFail($id);

        if ($sesi->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses sesi presensi ini.');
        }

        if ($sesi->status !== 'aktif') {
            return redirect()->route('guru.presensi.index')
                ->with('error', 'Sesi kelas sudah selesai.');
        }

        $kodeUnik = strtoupper(Str::random(6));

        $sesi->update([
            'kode_presensi' => $kodeUnik,
            'waktu_berlaku' => Carbon::now()->addHours(2),
        ]);

        return redirect()->route('guru.presensi.ruang', $sesi->id)
            ->with('success', 'Kode presensi baru berhasil dibuat: ' . $kodeUnik);
    }

    /* =========================
       7. AKHIRI KELAS
       (Sesi selesai, semua presensi siswa di-reset/hapus)
    ========================= */
    public function akhiriKelas($id)
    {
        $sesi = SesiPresensi::findOrFail($id);

        if ($sesi->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses sesi presensi ini.');
        }

        // Akhiri sesi ini
        $sesi->update([
            'status' => 'selesai',
            'kode_presensi' => null,
        ]);

        $this->presensiStatusManager->finalizeMissingStudentsAsAlpa($sesi->fresh());

        // Sesi sekarang sudah berstatus selesai, sehingga data presensi akan tetap tersimpan 
        // untuk direkap oleh Admin dan tidak lagi dihapus.

        return redirect()->route('guru.dashboard')
            ->with('success', 'Sesi Kelas ' . $sesi->kelas . ' telah diakhiri. Data presensi pada sesi ini tetap disimpan untuk rekap.');
    }

    /* =========================
       8. UPDATE STATUS PRESENSI SISWA (Manual Override oleh Guru)
    ========================= */
    public function updateStatusSiswa(Request $request, $sesiId, $nis)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa,Belum Hadir',
        ]);

        $sesi = SesiPresensi::findOrFail($sesiId);

        if ($sesi->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses sesi presensi ini.');
        }

        // Cari presensi siswa di sesi ini
        $presensi = Presensi::where('sesi_id', $sesiId)
                            ->where('NIS', $nis)
                            ->first();

        if ($presensi) {
            // Update status yang sudah ada
            $presensi->update(['status' => $request->status]);
        } else {
            // Buat record presensi baru (manual oleh guru)
            $randomString = strtoupper(Str::random(6));
            $kd_presensi = 'PRS-' . Carbon::today()->format('Ymd') . '-' . $randomString;

            Presensi::create([
                'kd_presensi' => $kd_presensi,
                'sesi_id' => $sesiId,
                'tanggal' => Carbon::today()->format('Y-m-d'),
                'kd_jp' => $sesi->kd_jp,
                'jam_masuk' => Carbon::now()->format('H:i:s'),
                'status' => $request->status,
                'NIS' => $nis,
            ]);
        }

        return redirect()->route('guru.presensi.ruang', $sesiId)
            ->with('success', 'Status presensi siswa berhasil diperbarui.');
    }

    private function convertDayToIndonesian(string $englishDay): string
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return $days[$englishDay] ?? $englishDay;
    }

    private function buildAttendanceVersion(SesiPresensi $sesi): string
    {
        $presensiState = Presensi::query()
            ->where('sesi_id', $sesi->id)
            ->orderBy('NIS')
            ->get(['NIS', 'status', 'is_dalam_radius', 'updated_at'])
            ->map(fn ($presensi) => [
                'nis' => $presensi->NIS,
                'status' => $presensi->status,
                'radius' => $presensi->is_dalam_radius,
                'updated_at' => optional($presensi->updated_at)->toJSON(),
            ])
            ->values();

        $studentState = Siswa::query()
            ->where('kelas', $sesi->kelas)
            ->orderBy('NIS')
            ->get(['NIS', 'nama_siswa', 'updated_at'])
            ->map(fn ($siswa) => [
                'nis' => $siswa->NIS,
                'name' => $siswa->nama_siswa,
                'updated_at' => optional($siswa->updated_at)->toJSON(),
            ])
            ->values();

        return sha1(json_encode([
            'sesi_status' => $sesi->status,
            'sesi_code' => $sesi->kode_presensi,
            'sesi_updated_at' => optional($sesi->updated_at)->toJSON(),
            'presensi' => $presensiState,
            'students' => $studentState,
        ]));
    }
}
