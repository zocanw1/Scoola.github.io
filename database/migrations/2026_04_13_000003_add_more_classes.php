<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $newKelas = [
            ['nama_kelas' => 'X-SIJA 2'],
            ['nama_kelas' => 'XII-SIJA 1'],
            ['nama_kelas' => 'XII-SIJA 2'],
        ];

        foreach ($newKelas as $kelas) {
            DB::table('kelas')->updateOrInsert(
                ['nama_kelas' => $kelas['nama_kelas']],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('kelas')->whereIn('nama_kelas', ['X-SIJA 2', 'XII-SIJA 1', 'XII-SIJA 2'])->delete();
    }
};
