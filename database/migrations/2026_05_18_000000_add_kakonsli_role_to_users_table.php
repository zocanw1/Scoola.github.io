<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'siswa', 'kakonsli') DEFAULT 'admin'");

        // Create default kakonsli user for testing
        User::firstOrCreate(
            ['email' => 'kakonsli@scoola.id'],
            [
                'name'     => 'Kakonsli Scoola',
                'password' => Hash::make('kakonsli123'),
                'role'     => 'kakonsli',
            ]
        );
    }

    public function down(): void
    {
        User::where('email', 'kakonsli@scoola.id')->delete();
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'siswa') DEFAULT 'admin'");
    }
};
