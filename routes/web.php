<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AdminAkunController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaPresensiController;

/*
|--------------------------------------------------------------------------
| SETUP & AUTH
|--------------------------------------------------------------------------
*/
Route::get('/scoola-setup', [AuthController::class, 'formSetup'])->name('setup');
Route::post('/scoola-setup', [AuthController::class, 'storeSetup'])->name('setup.post');

Route::get('/login', [AuthController::class, 'formLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        /* ===================== SISWA ===================== */
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{nis}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{nis}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{nis}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        /* ===================== GURU ===================== */
        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::get('/guru/{nip}/edit', [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{nip}', [GuruController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{nip}', [GuruController::class, 'destroy'])->name('guru.destroy');

        /* ===================== ADMIN AKUN ===================== */
        Route::get('/admin', [AdminAkunController::class, 'index'])->name('admin.akun.index');
        Route::get('/admin/create', [AdminAkunController::class, 'create'])->name('admin.akun.create');
        Route::post('/admin', [AdminAkunController::class, 'store'])->name('admin.akun.store');
        Route::get('/admin/{id}/edit', [AdminAkunController::class, 'edit'])->name('admin.akun.edit');
        Route::put('/admin/{id}', [AdminAkunController::class, 'update'])->name('admin.akun.update');
        Route::delete('/admin/{id}', [AdminAkunController::class, 'destroy'])->name('admin.akun.destroy');

        /* ===================== KELAS ===================== */
        Route::get('/kelas', [KelasController::class, 'index'])->name('admin.kelas.index');
        Route::get('/kelas/{kelas}', [KelasController::class, 'show'])->name('admin.kelas.show');

        /* ===================== REKAP PRESENSI ===================== */
        Route::get('/rekap', [RekapController::class, 'index'])->name('admin.rekap.index');
        Route::get('/rekap/harian', [RekapController::class, 'harian'])->name('admin.rekap.harian');
        Route::get('/rekap/{id}', [RekapController::class, 'show'])->name('admin.rekap.show');

        /* ===================== MAPEL ===================== */
        Route::resource('mapel', MapelController::class)->except(['show']);

        /* ===================== JADWAL PELAJARAN ===================== */
        Route::resource('jadwal', JadwalPelajaranController::class)
            ->parameters(['jadwal' => 'kd_jp'])
            ->except(['show']);
    });

    /*
    |--------------------------------------------------------------------------
    | GURU
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:guru')->prefix('guru')->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\GuruDashboardController::class, 'index'])
            ->name('guru.dashboard');

        Route::get('/presensi/mulai', [PresensiController::class, 'pilihKelas'])
            ->name('guru.presensi.index');
        Route::post('/presensi/buka', [PresensiController::class, 'bukaKelas'])
            ->name('guru.presensi.buka');
        Route::get('/presensi/ruang/{id}', [PresensiController::class, 'ruangKelas'])
            ->name('guru.presensi.ruang');
        Route::get('/presensi/kode/{id}', [PresensiController::class, 'tampilKode'])
            ->name('guru.presensi.tampil');
        Route::post('/presensi/akhiri-presensi/{id}', [PresensiController::class, 'akhiriPresensi'])
            ->name('guru.presensi.akhiri');
        Route::post('/presensi/generate-kode/{id}', [PresensiController::class, 'generateKodeBaru'])
            ->name('guru.presensi.generate-kode');
        Route::post('/presensi/akhiri-kelas/{id}', [PresensiController::class, 'akhiriKelas'])
            ->name('guru.presensi.akhiri-kelas');
    });

    /*
    |--------------------------------------------------------------------------
    | SISWA
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:siswa')->prefix('siswa')->group(function () {
        Route::get('/dashboard', [SiswaPresensiController::class, 'dashboard'])
            ->name('siswa.dashboard');

        Route::post('/presensi', [SiswaPresensiController::class, 'absenMandiri'])
            ->name('siswa.presensi.store');
    });
});

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));
