<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('siswa', 'jenis_kelamin')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('jenis_kelamin', 1)->default('L')->after('kelas');
            });
        }

        if (! Schema::hasColumn('guru', 'jenis_kelamin')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->string('jenis_kelamin', 1)->default('L')->after('nama_guru');
            });
        }

        if (Schema::hasColumn('siswa', 'jenis_kelamin')) {
            DB::table('siswa')->whereNull('jenis_kelamin')->update(['jenis_kelamin' => 'L']);
        }

        if (Schema::hasColumn('guru', 'jenis_kelamin')) {
            DB::table('guru')->whereNull('jenis_kelamin')->update(['jenis_kelamin' => 'L']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('siswa', 'jenis_kelamin')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropColumn('jenis_kelamin');
            });
        }

        if (Schema::hasColumn('guru', 'jenis_kelamin')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->dropColumn('jenis_kelamin');
            });
        }
    }
};
