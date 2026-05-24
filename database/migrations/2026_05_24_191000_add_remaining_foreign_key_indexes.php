<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('user_id', 'activity_logs_user_id_idx');
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->index('kd_mapel', 'guru_kd_mapel_idx');
        });

        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->index('kd_mapel', 'jadwal_pelajaran_kd_mapel_idx');
            $table->index('NIP', 'jadwal_pelajaran_nip_idx');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->index('wali_kelas_nip', 'kelas_wali_kelas_nip_idx');
        });

        Schema::table('sesi_presensis', function (Blueprint $table) {
            $table->index('guru_id', 'sesi_presensis_guru_id_idx');
        });
    }

    public function down(): void
    {
        Schema::table('sesi_presensis', function (Blueprint $table) {
            $table->dropIndex('sesi_presensis_guru_id_idx');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropIndex('kelas_wali_kelas_nip_idx');
        });

        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->dropIndex('jadwal_pelajaran_nip_idx');
            $table->dropIndex('jadwal_pelajaran_kd_mapel_idx');
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->dropIndex('guru_kd_mapel_idx');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_user_id_idx');
        });
    }
};
