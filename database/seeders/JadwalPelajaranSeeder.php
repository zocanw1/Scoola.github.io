<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\JadwalPelajaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class JadwalPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Mata Pelajaran
        $mapels = [
            ['kd_mapel' => 'KK-1', 'nama_mapel' => 'Kompetensi Keahlian 1'],
            ['kd_mapel' => 'KK-2', 'nama_mapel' => 'Kompetensi Keahlian 2'],
            ['kd_mapel' => 'KK-3', 'nama_mapel' => 'Kompetensi Keahlian 3'],
            ['kd_mapel' => 'PJOK', 'nama_mapel' => 'Penjasorkes'],
            ['kd_mapel' => 'KDIG', 'nama_mapel' => 'Konten Digital'],
            ['kd_mapel' => 'PKK',  'nama_mapel' => 'Produk Kreatif & Kewirausahaan'],
            ['kd_mapel' => 'PABP', 'nama_mapel' => 'Pend. Agama & Budi Pekerti'],
            ['kd_mapel' => 'BIN',  'nama_mapel' => 'Bahasa Indonesia'],
            ['kd_mapel' => 'BIG',  'nama_mapel' => 'Bahasa Inggris'],
            ['kd_mapel' => 'SEJ',  'nama_mapel' => 'Sejarah'],
            ['kd_mapel' => 'MAT',  'nama_mapel' => 'Matematika'],
            ['kd_mapel' => 'PAN',  'nama_mapel' => 'Pancasila'],
            ['kd_mapel' => 'IPAS', 'nama_mapel' => 'IPAS'],
            ['kd_mapel' => 'SS',   'nama_mapel' => 'Soft Skills'],
            ['kd_mapel' => 'BK',   'nama_mapel' => 'BK'],
            ['kd_mapel' => 'SB',   'nama_mapel' => 'Seni Budaya'],
            ['kd_mapel' => 'BD',   'nama_mapel' => 'Basis Data'],
        ];

        foreach ($mapels as $m) {
            Mapel::updateOrCreate(['kd_mapel' => $m['kd_mapel']], $m);
        }

        // 2. Data Guru
        $gurus = [
            ['NIP' => 'G-HALIM',   'nama' => 'HALIM',    'mapel' => 'KK-3'],
            ['NIP' => 'G-DINDA',   'nama' => 'DINDA',    'mapel' => 'KK-1'],
            ['NIP' => 'G-HERMAN',  'nama' => 'HERMAN',   'mapel' => 'KK-2'],
            ['NIP' => 'G-AGUS',    'nama' => 'Agus A',   'mapel' => 'PJOK'],
            ['NIP' => 'G-RENDY',   'nama' => 'RENDY',    'mapel' => 'KDIG'],
            ['NIP' => 'G-LAILI',   'nama' => 'LAILI',    'mapel' => 'PKK'],
            ['NIP' => 'G-ZAKY',    'nama' => 'A. Zaky',  'mapel' => 'PABP'],
            ['NIP' => 'G-LILIK',   'nama' => 'Lilik W',  'mapel' => 'BIN'],
            ['NIP' => 'G-SRIN',    'nama' => 'Sri N',    'mapel' => 'BIG'],
            ['NIP' => 'G-SUSIN',   'nama' => 'Susi N',   'mapel' => 'BD'],
            ['NIP' => 'G-TITINT',  'nama' => 'Titin T',  'mapel' => 'MAT'],
            ['NIP' => 'G-ANDRIH',  'nama' => 'Andri H',  'mapel' => 'PAN'],
            ['NIP' => 'G-ANDAYANI', 'nama' => 'Andayani', 'mapel' => 'BIG'],
            ['NIP' => 'G-SUDAR',   'nama' => 'Sudarwati','mapel' => 'SEJ'],
            ['NIP' => 'G-KURNITA', 'nama' => 'Kurnita',  'mapel' => 'SS'],
            ['NIP' => 'G-ET',      'nama' => 'ET',       'mapel' => 'BK'],
            ['NIP' => 'G-FARIN',   'nama' => 'Faringga', 'mapel' => 'SB'],
            ['NIP' => 'G-ROCHMAN', 'nama' => 'Rochman',  'mapel' => 'IPAS'],
            ['NIP' => 'G-NINING',  'nama' => 'Nining',   'mapel' => 'IPAS'],
            ['NIP' => 'G-RADIN',   'nama' => 'Radin',    'mapel' => 'BIG'],
        ];

        foreach ($gurus as $g) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '', $g['nama'])) . '@scoola.com'],
                [
                    'name' => $g['nama'],
                    'password' => Hash::make('password'),
                    'role' => 'guru'
                ]
            );

            Guru::updateOrCreate(
                ['NIP' => $g['NIP']],
                [
                    'nama_guru' => $g['nama'],
                    'kd_mapel' => $g['mapel'],
                    'user_id' => $user->id
                ]
            );
        }

        // 3. Jadwal XI SIJA 1
        $jadwal_sija1 = [
            // Senin
            ['hari' => 'Senin', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 1, 'jam_selesai' => 4, 'mapel' => 'KK-3', 'nip' => 'G-HALIM', 'ruangan' => 'Lab 4.0'],
            ['hari' => 'Senin', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 5, 'jam_selesai' => 7, 'mapel' => 'KK-1', 'nip' => 'G-DINDA', 'ruangan' => 'Lab RPL C (G1.2)'],
            ['hari' => 'Senin', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 8, 'jam_selesai' => 10, 'mapel' => 'KK-2', 'nip' => 'G-HERMAN', 'ruangan' => 'Lab RPL C (G1.2)'],
            ['hari' => 'Senin', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 11, 'jam_selesai' => 11, 'mapel' => 'PJOK', 'nip' => 'G-AGUS', 'ruangan' => '-'],
            ['hari' => 'Senin', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 12, 'jam_selesai' => 12, 'mapel' => 'KDIG', 'nip' => 'G-RENDY', 'ruangan' => '-'],
            
            // Selasa
            ['hari' => 'Selasa', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 1, 'jam_selesai' => 4, 'mapel' => 'PKK', 'nip' => 'G-LAILI', 'ruangan' => 'Lab RPL TS'],
            ['hari' => 'Selasa', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 5, 'jam_selesai' => 8, 'mapel' => 'PABP', 'nip' => 'G-ZAKY', 'ruangan' => '-'],
            ['hari' => 'Selasa', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 9, 'jam_selesai' => 10, 'mapel' => 'BIN', 'nip' => 'G-LILIK', 'ruangan' => 'B2.9'],
            ['hari' => 'Selasa', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 11, 'jam_selesai' => 12, 'mapel' => 'BIG', 'nip' => 'G-SRIN', 'ruangan' => '-'],
            
            // Rabu
            ['hari' => 'Rabu', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 1, 'jam_selesai' => 4, 'mapel' => 'BIN', 'nip' => 'G-LILIK', 'ruangan' => 'B2.9'], // Matching Rabu BIN
            ['hari' => 'Rabu', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 5, 'jam_selesai' => 8, 'mapel' => 'MAT', 'nip' => 'G-TITINT', 'ruangan' => '-'],
            ['hari' => 'Rabu', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 9, 'jam_selesai' => 10, 'mapel' => 'PAN', 'nip' => 'G-ANDRIH', 'ruangan' => '-'],
            ['hari' => 'Rabu', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 11, 'jam_selesai' => 12, 'mapel' => 'BIG', 'nip' => 'G-ANDAYANI', 'ruangan' => '-'],

            // Kamis (Based on 2nd image)
            ['hari' => 'Kamis', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 1, 'jam_selesai' => 2, 'mapel' => 'BIN', 'nip' => 'G-LILIK', 'ruangan' => '-'],
            ['hari' => 'Kamis', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 3, 'jam_selesai' => 4, 'mapel' => 'SB', 'nip' => 'G-FARIN', 'ruangan' => '-'],
            ['hari' => 'Kamis', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 5, 'jam_selesai' => 6, 'mapel' => 'BIG', 'nip' => 'G-RADIN', 'ruangan' => '-'],
            ['hari' => 'Kamis', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 7, 'jam_selesai' => 12, 'mapel' => 'IPAS', 'nip' => 'G-ROCHMAN', 'ruangan' => '-'],

            // Jumat
            ['hari' => 'Jumat', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 1, 'jam_selesai' => 4, 'mapel' => 'SS', 'nip' => 'G-KURNITA', 'ruangan' => 'Lab RPL TS'],
            ['hari' => 'Jumat', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 5, 'jam_selesai' => 6, 'mapel' => 'PKK', 'nip' => 'G-LAILI', 'ruangan' => '-'],
            ['hari' => 'Jumat', 'kelas' => 'XI-SIJA 1', 'jam_mulai' => 7, 'jam_selesai' => 7, 'mapel' => 'BK', 'nip' => 'G-ET', 'ruangan' => '-'],
        ];

        foreach ($jadwal_sija1 as $j) {
            JadwalPelajaran::updateOrCreate(
                [
                    'hari' => $j['hari'],
                    'kelas' => $j['kelas'],
                    'jam_mulai' => $j['jam_mulai'],
                ],
                [
                    'kd_jp' => 'JP-' . strtoupper(Str::random(8)),
                    'jam_selesai' => $j['jam_selesai'],
                    'kd_mapel' => $j['mapel'],
                    'NIP' => $j['nip'],
                    'ruangan' => $j['ruangan']
                ]
            );
        }

        // 4. Jadwal XI SIJA 2 (Simplified copy for demo)
        $jadwal_sija2 = [
            ['hari' => 'Senin', 'kelas' => 'XI-SIJA 2', 'jam_mulai' => 1, 'jam_selesai' => 4, 'mapel' => 'KK-2', 'nip' => 'G-HERMAN', 'ruangan' => 'Lab RPL C (G1.2)'],
            ['hari' => 'Senin', 'kelas' => 'XI-SIJA 2', 'jam_mulai' => 5, 'jam_selesai' => 7, 'mapel' => 'KK-3', 'nip' => 'G-HALIM', 'ruangan' => 'Lab 4.0'],
            ['hari' => 'Selasa', 'kelas' => 'XI-SIJA 2', 'jam_mulai' => 1, 'jam_selesai' => 2, 'mapel' => 'BIG', 'nip' => 'G-SRIN', 'ruangan' => '-'],
            ['hari' => 'Selasa', 'kelas' => 'XI-SIJA 2', 'jam_mulai' => 3, 'jam_selesai' => 4, 'mapel' => 'SEJ', 'nip' => 'G-SUDAR', 'ruangan' => '-'],
        ];

        foreach ($jadwal_sija2 as $j) {
            JadwalPelajaran::updateOrCreate(
                [
                    'hari' => $j['hari'],
                    'kelas' => $j['kelas'],
                    'jam_mulai' => $j['jam_mulai'],
                ],
                [
                    'kd_jp' => 'JP-' . strtoupper(Str::random(8)),
                    'jam_selesai' => $j['jam_selesai'],
                    'kd_mapel' => $j['mapel'],
                    'NIP' => $j['nip'],
                    'ruangan' => $j['ruangan']
                ]
            );
        }
    }
}
