<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class LocalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role dasar ada (menggunakan ID yang konsisten dengan UserSeeder lama)
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

        // Buat kelas default jika belum ada (untuk menghindari error foreign key pada akun siswa)
        $kelas = Kelas::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'X IPA 1',
                'description' => 'Kelas X IPA 1 - Kelas Default'
            ]
        );

        // Akun Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@terraassessment.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'roles_id' => 1,
                'status' => 'active',
            ]
        );

        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@terraassessment.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'roles_id' => 2,
                'status' => 'active',
            ]
        );

        // Akun Guru
        User::updateOrCreate(
            ['email' => 'guru@terraassessment.com'],
            [
                'name' => 'Guru Pengajar',
                'password' => Hash::make('password123'),
                'roles_id' => 3,
                'status' => 'active',
            ]
        );

        // Akun Siswa
        User::updateOrCreate(
            ['email' => 'siswa@terraassessment.com'],
            [
                'name' => 'Siswa Terdaftar',
                'password' => Hash::make('password123'),
                'roles_id' => 4,
                'status' => 'active',
                'kelas_id' => 1, // Defaulting to 1 if it exists
            ]
        );

        $this->command->info('Local users created successfully with password: password123');
    }
}
