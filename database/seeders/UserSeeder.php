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

        $this->command->info('Role berhasil dibuat!');

        // Buat users default
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@terraassessment.com',
                'password' => Hash::make('superadmin123'),
                'roles_id' => 1,
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@terraassessment.com',
                'password' => Hash::make('admin123'),
                'roles_id' => 2,
            ],
            [
                'name' => 'Guru',
                'email' => 'guru@terraassessment.com',
                'password' => Hash::make('guru123'),
                'roles_id' => 3,
            ],
            [
                'name' => 'Siswa',
                'email' => 'siswa@terraassessment.com',
                'password' => Hash::make('siswa123'),
                'roles_id' => 4,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Users berhasil dibuat!');
    }
}