<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\KelasMapel;
use App\Models\EditorAccess;
use Illuminate\Support\Facades\Hash;

class CompleteDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat role jika belum ada
        $roles = [
            ['id' => 1, 'name' => 'Super Admin'],
            ['id' => 2, 'name' => 'Admin'],
            ['id' => 3, 'name' => 'Guru'],
            ['id' => 4, 'name' => 'Siswa'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                ['name' => $role['name']]
            );
        }

        // Buat 3 kelas dengan angkatan berbeda
        $kelas = [
            ['id' => 1, 'name' => 'IPA 1 Demo', 'description' => 'Kelas IPA 1 Demo - Angkatan 2023', 'level' => 'X'],
            ['id' => 2, 'name' => 'IPA 2 Demo', 'description' => 'Kelas IPA 2 Demo - Angkatan 2024', 'level' => 'XI'],
            ['id' => 3, 'name' => 'IPA 3 Demo', 'description' => 'Kelas IPA 3 Demo - Angkatan 2025', 'level' => 'XII'],
        ];

        foreach ($kelas as $k) {
            Kelas::updateOrCreate(
                ['id' => $k['id']],
                [
                    'name' => $k['name'],
                    'description' => $k['description'],
                    'level' => $k['level']
                ]
            );
        }

        // Buat 7 mata pelajaran
        $mapels = [
            ['id' => 1, 'name' => 'Matematika', 'deskripsi' => 'Mata pelajaran Matematika untuk semua tingkat'],
            ['id' => 2, 'name' => 'Fisika', 'deskripsi' => 'Mata pelajaran Fisika untuk tingkat IPA'],
            ['id' => 3, 'name' => 'Kimia', 'deskripsi' => 'Mata pelajaran Kimia untuk tingkat IPA'],
            ['id' => 4, 'name' => 'Biologi', 'deskripsi' => 'Mata pelajaran Biologi untuk tingkat IPA'],
            ['id' => 5, 'name' => 'Bahasa Inggris', 'deskripsi' => 'Mata pelajaran Bahasa Inggris'],
            ['id' => 6, 'name' => 'Bahasa Indonesia', 'deskripsi' => 'Mata pelajaran Bahasa Indonesia'],
            ['id' => 7, 'name' => 'Sejarah', 'deskripsi' => 'Mata pelajaran Sejarah Indonesia'],
        ];

        foreach ($mapels as $mapel) {
            Mapel::updateOrCreate(
                ['id' => $mapel['id']],
                [
                    'name' => $mapel['name'],
                    'deskripsi' => $mapel['deskripsi']
                ]
            );
        }

        // Buat 7 guru dengan nama realistis
        $gurus = [
            ['id' => 101, 'name' => 'Budi Santoso', 'email' => 'guru1@demo.com', 'mapel' => 'Matematika'],
            ['id' => 102, 'name' => 'Siti Aminah', 'email' => 'guru2@demo.com', 'mapel' => 'Fisika'],
            ['id' => 103, 'name' => 'Ahmad Fauzi', 'email' => 'guru3@demo.com', 'mapel' => 'Kimia'],
            ['id' => 104, 'name' => 'Dewi Kartika', 'email' => 'guru4@demo.com', 'mapel' => 'Biologi'],
            ['id' => 105, 'name' => 'Rizki Pratama', 'email' => 'guru5@demo.com', 'mapel' => 'Bahasa Inggris'],
            ['id' => 106, 'name' => 'Maya Sari', 'email' => 'guru6@demo.com', 'mapel' => 'Bahasa Indonesia'],
            ['id' => 107, 'name' => 'Eko Wijaya', 'email' => 'guru7@demo.com', 'mapel' => 'Sejarah'],
        ];

        foreach ($gurus as $guru) {
            User::updateOrCreate(
                ['email' => $guru['email']],
                [
                    'id' => $guru['id'],
                    'name' => $guru['name'],
                    'email' => $guru['email'],
                    'password' => Hash::make('password'),
                    'roles_id' => 3,
                    'kelas_id' => null,
                ]
            );
        }

        // Buat 60 siswa dengan nama realistis
        $siswaNames = [
            // IPA 1 Demo (20 siswa)
            'Andi Rahman', 'Sari Dewi', 'Rizki Maulana', 'Putri Lestari', 'Dedi Kurniawan',
            'Maya Indah', 'Fajar Nugroho', 'Lina Sari', 'Hendra Wijaya', 'Rina Wulandari',
            'Bambang Sutrisno', 'Citra Maharani', 'Agus Setiawan', 'Dina Kartika', 'Joko Susilo',
            'Eka Putri', 'Tono Prasetyo', 'Nina Sari', 'Yoga Pratama', 'Sinta Dewi',
            
            // IPA 2 Demo (20 siswa)
            'Rudi Hartono', 'Wati Sari', 'Doni Kurniawan', 'Lia Maharani', 'Surya Wijaya',
            'Kiki Lestari', 'Budi Raharjo', 'Tina Sari', 'Ari Nugroho', 'Rina Kartika',
            'Hadi Susanto', 'Mira Indah', 'Cahyo Pratama', 'Dewi Lestari', 'Eko Santoso',
            'Sari Putri', 'Fajar Maulana', 'Lina Wulandari', 'Gunawan Setiawan', 'Nina Kartika',
            
            // IPA 3 Demo (20 siswa)
            'Rizki Pratama', 'Sari Dewi', 'Ahmad Wijaya', 'Maya Sari', 'Dedi Kurniawan',
            'Putri Lestari', 'Bambang Sutrisno', 'Citra Maharani', 'Hendra Wijaya', 'Rina Wulandari',
            'Joko Susilo', 'Eka Putri', 'Tono Prasetyo', 'Nina Sari', 'Yoga Pratama',
            'Sinta Dewi', 'Rudi Hartono', 'Wati Sari', 'Doni Kurniawan', 'Lia Maharani'
        ];

        $siswaId = 201;
        $kelasId = 1;
        $siswaPerKelas = 20;

        foreach ($siswaNames as $index => $name) {
            if ($index > 0 && $index % $siswaPerKelas == 0) {
                $kelasId++;
            }
            
            $email = 'siswa' . ($index + 1) . '@demo.com';
            
            User::updateOrCreate(
                ['email' => $email],
                [
                    'id' => $siswaId,
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'roles_id' => 4,
                    'kelas_id' => $kelasId,
                ]
            );
            $siswaId++;
        }

        // Buat KelasMapel (21 relasi: 3 kelas × 7 mapel)
        $kelasMapelId = 1;
        for ($kelasId = 1; $kelasId <= 3; $kelasId++) {
            for ($mapelId = 1; $mapelId <= 7; $mapelId++) {
                KelasMapel::updateOrCreate(
                    ['id' => $kelasMapelId],
                    [
                        'kelas_id' => $kelasId,
                        'mapel_id' => $mapelId
                    ]
                );
                $kelasMapelId++;
            }
        }

        // Buat EditorAccess (Assignment guru ke kelas_mapel dengan variasi)
        $assignments = [
            // Budi Santoso (guru1) - Matematika - teaches 2 classes (IPA 1 & IPA 2)
            ['user_id' => 101, 'kelas_mapel_id' => 1], // Budi - IPA 1 - Matematika
            ['user_id' => 101, 'kelas_mapel_id' => 8], // Budi - IPA 2 - Matematika

            // Siti Aminah (guru2) - Fisika - teaches 3 classes (all batches - senior teacher)
            ['user_id' => 102, 'kelas_mapel_id' => 2], // Siti - IPA 1 - Fisika
            ['user_id' => 102, 'kelas_mapel_id' => 9], // Siti - IPA 2 - Fisika
            ['user_id' => 102, 'kelas_mapel_id' => 16], // Siti - IPA 3 - Fisika

            // Ahmad Fauzi (guru3) - Kimia - teaches 2 classes (IPA 1 & IPA 3)
            ['user_id' => 103, 'kelas_mapel_id' => 3], // Ahmad - IPA 1 - Kimia
            ['user_id' => 103, 'kelas_mapel_id' => 17], // Ahmad - IPA 3 - Kimia

            // Dewi Kartika (guru4) - Biologi - teaches 3 classes (all batches)
            ['user_id' => 104, 'kelas_mapel_id' => 4], // Dewi - IPA 1 - Biologi
            ['user_id' => 104, 'kelas_mapel_id' => 11], // Dewi - IPA 2 - Biologi
            ['user_id' => 104, 'kelas_mapel_id' => 18], // Dewi - IPA 3 - Biologi

            // Rizki Pratama (guru5) - Bahasa Inggris - teaches 2 classes (IPA 2 & IPA 3)
            ['user_id' => 105, 'kelas_mapel_id' => 12], // Rizki - IPA 2 - Bahasa Inggris
            ['user_id' => 105, 'kelas_mapel_id' => 19], // Rizki - IPA 3 - Bahasa Inggris

            // Maya Sari (guru6) - Bahasa Indonesia - teaches 3 classes (all batches)
            ['user_id' => 106, 'kelas_mapel_id' => 6], // Maya - IPA 1 - Bahasa Indonesia
            ['user_id' => 106, 'kelas_mapel_id' => 13], // Maya - IPA 2 - Bahasa Indonesia
            ['user_id' => 106, 'kelas_mapel_id' => 20], // Maya - IPA 3 - Bahasa Indonesia

            // Eko Wijaya (guru7) - Sejarah - teaches 3 classes (all batches - senior teacher)
            ['user_id' => 107, 'kelas_mapel_id' => 7], // Eko - IPA 1 - Sejarah
            ['user_id' => 107, 'kelas_mapel_id' => 14], // Eko - IPA 2 - Sejarah
            ['user_id' => 107, 'kelas_mapel_id' => 21], // Eko - IPA 3 - Sejarah
        ];

        foreach ($assignments as $assignment) {
            EditorAccess::updateOrCreate(
                [
                    'user_id' => $assignment['user_id'],
                    'kelas_mapel_id' => $assignment['kelas_mapel_id']
                ],
                $assignment
            );
        }

        $this->command->info('✅ Data demo lengkap berhasil dibuat!');
        $this->command->info('');
        $this->command->info('📊 RINGKASAN DATA:');
        $this->command->info('• 3 Kelas: IPA 1 Demo (2023), IPA 2 Demo (2024), IPA 3 Demo (2025)');
        $this->command->info('• 7 Guru dengan nama realistis');
        $this->command->info('• 7 Mata Pelajaran');
        $this->command->info('• 60 Siswa (20 per kelas)');
        $this->command->info('• 21 KelasMapel (3 kelas × 7 mapel)');
        $this->command->info('• 18 Assignment guru ke kelas_mapel');
        $this->command->info('');
        $this->command->info('🔐 LOGIN INFO:');
        $this->command->info('• Password semua akun: password');
        $this->command->info('• Guru: guru1@demo.com s/d guru7@demo.com');
        $this->command->info('• Siswa: siswa1@demo.com s/d siswa60@demo.com');
        $this->command->info('');
        $this->command->info('👨‍🏫 GURU & MAPEL:');
        $this->command->info('• Budi Santoso - Matematika (2 kelas)');
        $this->command->info('• Siti Aminah - Fisika (3 kelas - senior)');
        $this->command->info('• Ahmad Fauzi - Kimia (2 kelas)');
        $this->command->info('• Dewi Kartika - Biologi (3 kelas)');
        $this->command->info('• Rizki Pratama - Bahasa Inggris (2 kelas)');
        $this->command->info('• Maya Sari - Bahasa Indonesia (3 kelas)');
        $this->command->info('• Eko Wijaya - Sejarah (3 kelas - senior)');
    }
}
