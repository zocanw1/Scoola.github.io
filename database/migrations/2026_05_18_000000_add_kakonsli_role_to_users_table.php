<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function syncPostgresConstraint(array $roles): void
    {
        $quotedRoles = implode(',', array_map(
            static fn (string $role) => "'".str_replace("'", "''", $role)."'",
            $roles
        ));

        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        DB::statement('ALTER TABLE users ALTER COLUMN role TYPE VARCHAR(255)');
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ({$quotedRoles}))");
        DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'admin'");
    }

    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'siswa', 'kakonsli') DEFAULT 'admin'");
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            $this->syncPostgresConstraint(['admin', 'guru', 'siswa', 'kakonsli']);
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'siswa') DEFAULT 'admin'");
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            $this->syncPostgresConstraint(['admin', 'guru', 'siswa']);
        }
    }
};
