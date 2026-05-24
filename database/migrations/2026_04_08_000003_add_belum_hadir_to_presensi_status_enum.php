<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function syncPostgresConstraint(array $statuses): void
    {
        $quotedStatuses = implode(',', array_map(
            static fn (string $status) => "'".str_replace("'", "''", $status)."'",
            $statuses
        ));

        DB::statement('ALTER TABLE presensi DROP CONSTRAINT IF EXISTS presensi_status_check');
        DB::statement('ALTER TABLE presensi ALTER COLUMN status TYPE VARCHAR(255)');
        DB::statement("ALTER TABLE presensi ADD CONSTRAINT presensi_status_check CHECK (status IN ({$quotedStatuses}))");
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE presensi MODIFY COLUMN status ENUM('Hadir','Izin','Sakit','Alpa','Ditolak','Belum Hadir') NOT NULL");
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            $this->syncPostgresConstraint(['Hadir', 'Izin', 'Sakit', 'Alpa', 'Ditolak', 'Belum Hadir']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE presensi MODIFY COLUMN status ENUM('Hadir','Izin','Sakit','Alpa','Ditolak') NOT NULL");
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            $this->syncPostgresConstraint(['Hadir', 'Izin', 'Sakit', 'Alpa', 'Ditolak']);
        }
    }
};
