<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwal_pelajaran', 'ruangan')) {
                $table->string('ruangan', 50)->nullable()->after('kelas');
            }
            if (!Schema::hasColumn('jadwal_pelajaran', 'jam_mulai')) {
                $table->integer('jam_mulai')->nullable()->after('hari');
            }
            if (!Schema::hasColumn('jadwal_pelajaran', 'jam_selesai')) {
                $table->integer('jam_selesai')->nullable()->after('jam_mulai');
            }
            if (Schema::hasColumn('jadwal_pelajaran', 'jam_ke')) {
                $table->dropColumn('jam_ke');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->dropColumn(['ruangan', 'jam_mulai', 'jam_selesai']);
        });
    }
};
