<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\SesiPresensi;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;

class SiswaPresensiController extends Controller
{
    // Koordinat Sekolah: Jl. Ki Ageng Gribig No. 28, Madyopuro, Kedungkandang, Malang
    private const SCHOOL_LAT = -7.974867815619122;
    private const SCHOOL_LNG = 112.67166658058967;
    private const MAX_RADIUS_METERS = 50; // Radius toleransi dalam meter

    public function dashboard()
    {
        $siswa = Siswa::where('user_id', auth()->id())->first();

        if ($siswa) {
            $riwayat = Presensi::where('NIS', $siswa->NIS)
                ->orderBy('tanggal', 'desc')
                ->take(5)
                ->get();
        } else {
            $riwayat = collect();
        }

        return view('siswa.dashboard', compact('siswa', 'riwayat'));
    }

    public function absenMandiri(Request $request)
    {
        $request->validate([
            'kode_presensi' => 'required|string|size:6',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Rate limiting — max 10 attempts per minute per user
        $throttleKey = 'presensi:' . auth()->id();
        $maxAttempts = config('scoola.presensi_max_attempts', 10);
        $decayMinutes = config('scoola.presensi_decay_minutes', 1);

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect()->back()->with('error', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
        }

        RateLimiter::hit($throttleKey, $decayMinutes * 60);

        $kode = strtoupper($request->kode_presensi);

        // Cari sesi yang aktif
        $sesi = SesiPresensi::where('kode_presensi', $kode)
                            ->where('status', 'aktif')
                            ->first();

        if (!$sesi) {
            return redirect()->back()->with('error', 'Kode presensi tidak valid atau sesi sudah ditutup.');
        }

        // Cek validitas waktu
        if (Carbon::now()->greaterThan($sesi->waktu_berlaku)) {
            $sesi->update(['status' => 'selesai']);
            return redirect()->back()->with('error', 'Masa berlaku kode presensi ini sudah habis.');
        }

        // Cari data siswa login
        $siswa = Siswa::where('user_id', auth()->id())->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan di sistem.');
        }

        // Cocokkan kelas
        if ($siswa->kelas !== $sesi->kelas) {
            return redirect()->back()->with('error', 'Gagal absen. Sesi ini untuk kelas ' . $sesi->kelas . ', sedangkan kamu berada di ' . $siswa->kelas . '.');
        }

        // ===== Validasi GPS =====
        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;
        $jarak = $this->hitungJarak($latitude, $longitude, self::SCHOOL_LAT, self::SCHOOL_LNG);
        $isDalamRadius = $jarak <= self::MAX_RADIUS_METERS;

        // Tentukan status berdasarkan lokasi
        $statusPresensi = $isDalamRadius ? 'Hadir' : 'Ditolak';

        // Masukkan atau update presensi
        $today = Carbon::today()->format('Y-m-d');

        $presensi = Presensi::where('NIS', $siswa->NIS)
                            ->where('sesi_id', $sesi->id)
                            ->first();

        if ($presensi) {
            if ($presensi->status === 'Hadir') {
                return redirect()->back()->with('success', 'Kamu sudah melakukan absensi Hadir di sesi ini!');
            }
            $presensi->update([
                'status' => $statusPresensi,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'is_dalam_radius' => $isDalamRadius,
            ]);
        } else {
            $randomString = strtoupper(Str::random(6));
            $kd_presensi = 'PRS-' . Carbon::today()->format('Ymd') . '-' . $randomString;

            Presensi::create([
                'kd_presensi' => $kd_presensi,
                'sesi_id' => $sesi->id,
                'tanggal' => $today,
                'kd_jp' => $sesi->kd_jp,
                'jam_masuk' => Carbon::now()->format('H:i:s'),
                'status' => $statusPresensi,
                'NIS' => $siswa->NIS,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'is_dalam_radius' => $isDalamRadius,
            ]);
        }

        // Return message berdasarkan status
        if (!$isDalamRadius) {
            $jarakFormatted = round($jarak);
            return redirect()->back()->with('error', "Presensi ditolak! Kamu berada {$jarakFormatted}m dari sekolah (maks " . self::MAX_RADIUS_METERS . "m). Hubungi guru untuk mengubah status.");
        }

        return redirect()->back()->with('success', 'Berhasil! Kehadiran kamu tercatat di sistem.');
    }

    /**
     * Menghitung jarak antara dua titik koordinat menggunakan Haversine Formula.
     * 
     * @return float Jarak dalam meter
     */
    private function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
