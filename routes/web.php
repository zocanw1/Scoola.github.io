<?php

use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminAkunController;
use App\Http\Controllers\Admin\PresensiSiswaController;
use App\Http\Controllers\AdminWaliKelasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\KakonsliController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaPresensiController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response('ok', 200));



/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/portfolio', [PortfolioController::class, 'show'])->name('portfolio');

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
    | ADMIN & KAKONSLI SHARED
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('role:admin,kakonsli')->group(function () {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/presensi-siswa', [PresensiSiswaController::class, 'index'])->name('admin.presensi-siswa.index');
        Route::get('/presensi-siswa/{nis}', [PresensiSiswaController::class, 'show'])->where('nis', '.*')->name('admin.presensi-siswa.show');

        /* ===================== REKAP PRESENSI ===================== */
        Route::get('/rekap-presensi', [\App\Http\Controllers\Admin\RekapPresensiController::class, 'index'])->name('admin.rekap.index');
        Route::get('/rekap-presensi/export', [\App\Http\Controllers\Admin\RekapPresensiController::class, 'export'])->name('admin.rekap.export');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::post('/presensi-siswa/{nis}/status', [PresensiSiswaController::class, 'updateStatus'])
            ->where('nis', '.*')
            ->name('admin.presensi-siswa.update-status');

        /* ===================== SISWA (MANAGE: ADMIN ONLY) ===================== */
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::get('/siswa/edit/{nis}', [SiswaController::class, 'edit'])->where('nis', '.*')->name('siswa.edit');
        Route::put('/siswa/{nis}', [SiswaController::class, 'update'])->where('nis', '.*')->name('siswa.update');

        /* ===================== GURU ===================== */
        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::post('/guru/import', [GuruController::class, 'import'])->name('guru.import');
        Route::get('/guru/{nip}/edit', [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{nip}', [GuruController::class, 'update'])->name('guru.update');

        /* ===================== ADMIN AKUN ===================== */
        Route::get('/admin', [AdminAkunController::class, 'index'])->name('admin.akun.index');
        Route::get('/admin/create', [AdminAkunController::class, 'create'])->name('admin.akun.create');
        Route::post('/admin', [AdminAkunController::class, 'store'])->name('admin.akun.store');
        Route::get('/admin/{id}/edit', [AdminAkunController::class, 'edit'])->name('admin.akun.edit');
        Route::put('/admin/{id}', [AdminAkunController::class, 'update'])->name('admin.akun.update');

        /* ===================== KAKONSLI AKUN ===================== */
        Route::get('/kakonsli', [KakonsliController::class, 'index'])->name('admin.kakonsli.index');
        Route::get('/kakonsli/create', [KakonsliController::class, 'create'])->name('admin.kakonsli.create');
        Route::post('/kakonsli', [KakonsliController::class, 'store'])->name('admin.kakonsli.store');
        Route::get('/kakonsli/{id}/edit', [KakonsliController::class, 'edit'])->name('admin.kakonsli.edit');
        Route::put('/kakonsli/{id}', [KakonsliController::class, 'update'])->name('admin.kakonsli.update');

        /* ===================== LOG AKTIVITAS ===================== */
        Route::get('/logs', [ActivityLogController::class, 'index'])->name('admin.logs.index');

        /* ===================== KELAS ===================== */
        Route::get('/kelas', [KelasController::class, 'index'])->name('admin.kelas.index');
        Route::get('/kelas/{kelas}', [KelasController::class, 'show'])->name('admin.kelas.show');

        /* ===================== WALI KELAS ===================== */
        Route::get('/walikelas', [AdminWaliKelasController::class, 'index'])->name('admin.walikelas.index');
        Route::get('/walikelas/create', [AdminWaliKelasController::class, 'create'])->name('admin.walikelas.create');
        Route::post('/walikelas', [AdminWaliKelasController::class, 'store'])->name('admin.walikelas.store');
        Route::get('/walikelas/{id}/edit', [AdminWaliKelasController::class, 'edit'])->name('admin.walikelas.edit');
        Route::put('/walikelas/{id}', [AdminWaliKelasController::class, 'update'])->name('admin.walikelas.update');



        /* ===================== MAPEL ===================== */
        Route::resource('mapel', MapelController::class)->except(['show', 'destroy']);

        /* ===================== JADWAL PELAJARAN ===================== */
        Route::get('/jadwal/kelas/{kelas}/{hari?}', [JadwalPelajaranController::class, 'kelas'])->name('jadwal.kelas');
        Route::get('/jadwal/get-guru-by-mapel/{kd_mapel}', [JadwalPelajaranController::class, 'getGuruByMapel'])->name('jadwal.get-guru');
        Route::resource('jadwal', JadwalPelajaranController::class)
            ->parameters(['jadwal' => 'kd_jp'])
            ->except(['show', 'destroy']);
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
        Route::get('/presensi-siswa', [PresensiSiswaController::class, 'index'])
            ->name('guru.presensi-siswa.index');
        Route::get('/presensi-siswa/{nis}', [PresensiSiswaController::class, 'show'])->where('nis', '.*')
            ->name('guru.presensi-siswa.show');
        Route::get('/rekap-presensi', [\App\Http\Controllers\Admin\RekapPresensiController::class, 'index'])
            ->name('guru.rekap.index');
        Route::get('/rekap-presensi/export', [\App\Http\Controllers\Admin\RekapPresensiController::class, 'export'])
            ->name('guru.rekap.export');
        Route::post('/presensi/buka', [PresensiController::class, 'bukaKelas'])
            ->name('guru.presensi.buka');
        Route::get('/presensi/ruang/{id}', [PresensiController::class, 'ruangKelas'])
            ->name('guru.presensi.ruang');
        Route::get('/presensi/ruang/{id}/snapshot', [PresensiController::class, 'statusSnapshot'])
            ->name('guru.presensi.snapshot');
        Route::get('/presensi/kode/{id}', [PresensiController::class, 'tampilKode'])
            ->name('guru.presensi.tampil');
        Route::post('/presensi/akhiri-presensi/{id}', [PresensiController::class, 'akhiriPresensi'])
            ->name('guru.presensi.akhiri');
        Route::post('/presensi/generate-kode/{id}', [PresensiController::class, 'generateKodeBaru'])
            ->name('guru.presensi.generate-kode');
        Route::post('/presensi/akhiri-kelas/{id}', [PresensiController::class, 'akhiriKelas'])
            ->name('guru.presensi.akhiri-kelas');
        Route::post('/presensi/ruang/{sesiId}/update-status/{nis}', [PresensiController::class, 'updateStatusSiswa'])
            ->where('nis', '.*')
            ->name('guru.presensi.update-status');




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
