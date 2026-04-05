<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use App\Models\Presensi;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PresensiController extends Controller
{
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

        $kelasList = Siswa::distinct()->pluck('kelas')->sort()->toArray();

        // Ambil semua sesi aktif untuk melihat siapa saja yang sedang mengajar
        $allActiveSessions = SesiPresensi::where('status', 'aktif')
                                         ->with('guru')
                                         ->get()
                                         ->groupBy('kelas');

        return view('guru.presensi.pilih-kelas', compact('kelasList', 'allActiveSessions'));
    }

    /* =========================
       2. BUKA KELAS (Start Session)
    ========================= */
    public function bukaKelas(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string',
        ]);

        // Akhiri semua sesi aktif milik guru ini (Hanya boleh 1 sesi aktif per guru)
        SesiPresensi::where('guru_id', auth()->id())
            ->where('status', 'aktif')
            ->update(['status' => 'selesai']);
        
        // Buat sesi baru
        $kodeUnik = strtoupper(Str::random(6));

        $sesi = SesiPresensi::create([
            'guru_id' => auth()->id(),
            'kelas' => $request->kelas,
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
        $sesi = SesiPresensi::findOrFail($id);

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

        return view('guru.presensi.ruang-kelas', compact('sesi', 'siswaKelas', 'presensiHariIni', 'otherActiveSessions'));
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

        // Sesi sekarang sudah berstatus selesai, sehingga data presensi akan tetap tersimpan 
        // untuk direkap oleh Admin dan tidak lagi dihapus.

        return redirect()->route('guru.dashboard')
            ->with('success', 'Sesi Kelas ' . $sesi->kelas . ' telah diakhiri. Data presensi pada sesi ini telah direset.');
    }
}