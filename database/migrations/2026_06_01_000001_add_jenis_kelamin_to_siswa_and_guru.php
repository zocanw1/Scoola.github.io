<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('jenis_kelamin', 1)->default('L')->after('kelas');
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->string('jenis_kelamin', 1)->default('L')->after('nama_guru');
        });

        DB::table('siswa')->update(['jenis_kelamin' => 'L']);
        DB::table('guru')->update(['jenis_kelamin' => 'L']);
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
        });
    }
};
