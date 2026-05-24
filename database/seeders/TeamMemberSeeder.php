<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeamMember;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeamMember::create([
            'name' => 'Muhammad Rafif',
            'role' => 'TEAM LEAD',
            'kelas' => 'XI-SIJA 2',
            'nis' => '18433/108/065',
            'birthplace' => 'Malang',
            'birthdate' => '2008-10-06',
            'phone' => '08xx-xxxx-xxxx',
            'description' => 'Siswa yang penuh semangat dan ambisius untuk mengeksplorasi teknologi.',
            'jobdesk' => 'Bertanggung jawab dalam mengelola alur pengembangan proyek, memastikan integrasi kode berjalan lancar, serta melakukan pengawasan terhadap kualitas UI/UX yang dihasilkan tim.',
            'photo' => 'rafiff.jpg',
            'skills' => json_encode(['WEB DEV', 'DESIGN']),
            'sticker_bg' => '#FDCB6E',
            'img_bg' => '#6C5CE7',
            'role_color' => '#FF7675',
        ]);

        TeamMember::create([
            'name' => 'Selky Callista Retnadi',
            'role' => 'MEMBER',
            'kelas' => 'XI-SIJA 2',
            'nis' => '18451/126/065',
            'birthplace' => 'Malang',
            'birthdate' => '2009-01-26',
            'phone' => '08xx-xxxx-xxxx',
            'description' => 'Siap membantu dengan kreativitas dan fokus pada hasil yang maksimal.',
            'jobdesk' => 'Fokus pada perancangan antarmuka pengguna yang intuitif, melakukan riset kebutuhan pengguna, serta memastikan dokumentasi administrasi proyek tersusun dengan rapi.',
            'photo' => 'selky.jpg',
            'skills' => json_encode(['UI/UX', 'ADMIN']),
            'sticker_bg' => '#00CEC9',
            'img_bg' => '#00CEC9',
            'role_color' => '#6C5CE7',
        ]);

        TeamMember::create([
            'name' => 'Pradita Galuh Sendry Pratiwi',
            'role' => 'MEMBER',
            'kelas' => 'XI-SIJA 2',
            'nis' => '18442/117/065',
            'birthplace' => 'Malang',
            'birthdate' => '2009-05-27',
            'phone' => '08xx-xxxx-xxxx',
            'description' => 'Selalu belajar hal baru dan memberikan ide segar dalam setiap diskusi.',
            'jobdesk' => 'Menganalisis sistem yang akan dibangun agar berjalan efisien, serta memberikan dukungan teknis (troubleshooting) selama masa pengembangan proyek berlangsung.',
            'photo' => 'pradita.jpg',
            'skills' => json_encode(['ANALIS', 'SUPPORT']),
            'sticker_bg' => '#FF7675',
            'img_bg' => '#FF7675',
            'role_color' => '#00CEC9',
        ]);
    }
}
