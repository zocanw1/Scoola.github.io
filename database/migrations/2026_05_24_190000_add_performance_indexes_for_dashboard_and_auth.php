<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->index(['tanggal', 'status'], 'presensi_tanggal_status_idx');
            $table->index(['sesi_id', 'status'], 'presensi_sesi_status_idx');
            $table->index(['tanggal', 'created_at'], 'presensi_tanggal_created_idx');
        });

        Schema::table('sesi_presensis', function (Blueprint $table) {
            $table->index(['created_at', 'kelas'], 'sesi_created_kelas_idx');
            $table->index(['status', 'created_at'], 'sesi_status_created_idx');
        });

        Schema::table('guru_mapel', function (Blueprint $table) {
            $table->index(['kd_mapel', 'NIP'], 'guru_mapel_kd_nip_idx');
            $table->unique(['NIP', 'kd_mapel'], 'guru_mapel_nip_kd_unique');
        });
    }

    public function down(): void
    {
        Schema::table('guru_mapel', function (Blueprint $table) {
            $table->dropUnique('guru_mapel_nip_kd_unique');
            $table->dropIndex('guru_mapel_kd_nip_idx');
        });

        Schema::table('sesi_presensis', function (Blueprint $table) {
            $table->dropIndex('sesi_created_kelas_idx');
            $table->dropIndex('sesi_status_created_idx');
        });

        Schema::table('presensi', function (Blueprint $table) {
            $table->dropIndex('presensi_tanggal_status_idx');
            $table->dropIndex('presensi_sesi_status_idx');
            $table->dropIndex('presensi_tanggal_created_idx');
        });
    }
};

