<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom id jika belum ada
        if (!Schema::hasColumn('presensi', 'id')) {
            Schema::table('presensi', function (Blueprint $table) {
                $table->uuid('id')->nullable()->first();
            });
        }

        // 2. Isi data UUID jika kosong
        DB::table('presensi')->whereNull('id')->get()->each(function ($p) {
            DB::table('presensi')->where('kd_presensi', $p->kd_presensi)->update(['id' => (string) Str::uuid()]);
        });

        // 3. Set Primary Key
        // Gunakan try-catch atau cek manual agar tidak error duplicate
        try {
            Schema::table('presensi', function (Blueprint $table) {
                $table->dropPrimary();
                $table->uuid('id')->nullable(false)->change();
                $table->primary('id');
                $table->unique('kd_presensi');
            });
        } catch (\Exception $e) {
            // Jika sudah terlanjur berubah, abaikan
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('presensi', function (Blueprint $table) {
                $table->dropPrimary();
                $table->dropColumn('id');
                $table->dropUnique(['kd_presensi']);
                $table->primary('kd_presensi');
            });
        } catch (\Exception $e) {}
    }
};
