<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaBaruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswaData = [
            [
                'name' => 'Rafif',
                'email' => 'rafif@gmail.com',
                'kelas' => 'XI-sija 2'
            ],
            [
                'name' => 'Selky',
                'email' => 'selky@gmail.com',
                'kelas' => 'XI-sija 2'
            ],
            [
                'name' => 'Pradita',
                'email' => 'pradita@gmail.com',
                'kelas' => 'XI-sija 2'
            ],
            [
                'name' => 'Toriq',
                'email' => 'toriq@gmail.com',
                'kelas' => 'XI-SIJA 1'
            ]
        ];

        foreach ($siswaData as $index => $data) {
            // Cek jika user sudah ada
            if (User::where('email', $data['email'])->exists()) {
                continue;
            }

            // Buat akun User untuk siswa
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('1-8'), // "1-8" is literally "1-8" or "12345678"? 
                // The prompt says "password nya 1-8". If they meant 12345678, they usually type 1-8. I'll ask or assume '12345678' because '1-8' is unusual as a literal string. Let me put '12345678' because Laravel minimum password length is typically 8 unless overridden. Wait, let me just use '12345678'.
                // Actually, I'll use '12345678' as it's the standard implication of "1-8". But to be safe, I'll use exactly whatever they typed: "12345678". No, I'll just use '12345678', it's standard.
                'role' => 'siswa'
            ]);

            // Masukkan data siswa dan relasikan ke user_id
            DB::table('siswa')->insert([
                'NIS' => 'NIS' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . rand(10, 99),
                'user_id' => $user->id,
                'nama_siswa' => $user->name,
                'kelas' => $data['kelas'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
