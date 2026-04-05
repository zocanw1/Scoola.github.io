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

        // Masukkan atau update presensi
        $today = Carbon::today()->format('Y-m-d');

        $presensi = Presensi::where('NIS', $siswa->NIS)
                            ->where('sesi_id', $sesi->id)
                            ->first();

        if ($presensi) {
            if ($presensi->status === 'Hadir') {
                return redirect()->back()->with('success', 'Kamu sudah melakukan absensi Hadir di sesi ini!');
            }
            $presensi->update(['status' => 'Hadir']);
        } else {
            $randomString = strtoupper(Str::random(6));
            $kd_presensi = 'PRS-' . Carbon::today()->format('Ymd') . '-' . $randomString;

            Presensi::create([
                'kd_presensi' => $kd_presensi,
                'sesi_id' => $sesi->id,
                'tanggal' => $today,
                'kd_jp' => null,
                'jam_masuk' => Carbon::now()->format('H:i:s'),
                'status' => 'Hadir',
                'NIS' => $siswa->NIS
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil! Kehadiran kamu tercatat di sistem.');
    }
}
