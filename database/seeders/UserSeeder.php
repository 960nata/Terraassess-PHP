<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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

        // Buat kelas default jika belum ada
        $kelas = Kelas::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'X IPA 1',
                'description' => 'Kelas X IPA 1 - Kelas Default'
            ]
        );

        // Buat akun Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@terraassessment.com'],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@terraassessment.com',
                'password' => Hash::make('superadmin123'),
                'roles_id' => 1,
                'kelas_id' => null,
            ]
        );

        // Buat akun Admin
        User::updateOrCreate(
            ['email' => 'admin@terraassessment.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@terraassessment.com',
                'password' => Hash::make('admin123'),
                'roles_id' => 2,
                'kelas_id' => null,
            ]
        );

        // Buat akun Guru
        User::updateOrCreate(
            ['email' => 'guru@terraassessment.com'],
            [
                'name' => 'Guru Pengajar',
                'email' => 'guru@terraassessment.com',
                'password' => Hash::make('guru123'),
                'roles_id' => 3,
                'kelas_id' => null,
            ]
        );

        // Buat akun Siswa
        User::updateOrCreate(
            ['email' => 'siswa@terraassessment.com'],
            [
                'name' => 'Siswa Terdaftar',
                'email' => 'siswa@terraassessment.com',
                'password' => Hash::make('siswa123'),
                'roles_id' => 4,
                'kelas_id' => 1, // Asumsi ada kelas dengan ID 1
            ]
        );

        $this->command->info('Akun default berhasil dibuat!');
        $this->command->info('Super Admin: superadmin@terraassessment.com / superadmin123');
        $this->command->info('Admin: admin@terraassessment.com / admin123');
        $this->command->info('Guru: guru@terraassessment.com / guru123');
        $this->command->info('Siswa: siswa@terraassessment.com / siswa123');
    }
}