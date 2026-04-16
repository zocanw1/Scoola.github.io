<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ambil semua kelas unik dari tabel siswa dan masukkan ke tabel kelas
        $kelasUnik = DB::table('siswa')
            ->select('kelas')
            ->distinct()
            ->pluck('kelas');

        foreach ($kelasUnik as $kelas) {
            DB::table('kelas')->insertOrIgnore([
                'nama_kelas'  => $kelas,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Data migration — nothing to reverse structurally
    }
};
