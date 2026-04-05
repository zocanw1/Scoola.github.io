<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel Siswa
        Schema::create('siswa', function (Blueprint $table) {
            $table->string('NIS', 20)->primary();
            $table->string('nama_siswa', 100);
            $table->string('kelas', 10);
            $table->timestamps();

            $table->index('kelas');
        });

        // Tabel Mata Pelajaran
        Schema::create('mapel', function (Blueprint $table) {
            $table->string('kd_mapel', 10)->primary();
            $table->string('nama_mapel', 100);
            $table->timestamps();
        });

        // Tabel Guru
        Schema::create('guru', function (Blueprint $table) {
            $table->string('NIP', 20)->primary();
            $table->string('nama_guru', 100);
            $table->string('kd_mapel', 10)->nullable();
            $table->timestamps();

            $table->foreign('kd_mapel')
                  ->references('kd_mapel')
                  ->on('mapel')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });

        // Tabel Jadwal Pelajaran
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
            $table->string('kd_jp', 20)->primary();
            $table->string('hari', 10);
            $table->integer('jam_ke');
            $table->string('kd_mapel', 10)->nullable();
            $table->string('NIP', 20)->nullable();
            $table->string('kelas', 10);
            $table->timestamps();

            $table->foreign('kd_mapel')
                  ->references('kd_mapel')
                  ->on('mapel')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('NIP')
                  ->references('NIP')
                  ->on('guru')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->index('hari');
            $table->index('kelas');
        });

        // Tabel Presensi
        Schema::create('presensi', function (Blueprint $table) {
            $table->string('kd_presensi', 20)->primary();
            $table->date('tanggal');
            $table->string('kd_jp', 20)->nullable();
            $table->time('jam_masuk')->nullable();
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpa']);
            $table->string('NIS', 20)->nullable();
            $table->timestamps();

            $table->foreign('kd_jp')
                  ->references('kd_jp')
                  ->on('jadwal_pelajaran')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('NIS')
                  ->references('NIS')
                  ->on('siswa')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->index('tanggal');
            $table->index('NIS');
            $table->index('kd_jp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
        Schema::dropIfExists('jadwal_pelajaran');
        Schema::dropIfExists('guru');
        Schema::dropIfExists('mapel');
        Schema::dropIfExists('siswa');
    }
};