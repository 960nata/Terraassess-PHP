<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\KelasMapel;
use Illuminate\Support\Facades\Hash;

class SimpleStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample class
        $kelas = Kelas::firstOrCreate([
            'name' => 'X IPA 1'
        ]);

        // Create sample subjects
        $mapel1 = Mapel::firstOrCreate([
            'name' => 'Fisika'
        ]);

        $mapel2 = Mapel::firstOrCreate([
            'name' => 'Kimia'
        ]);

        $mapel3 = Mapel::firstOrCreate([
            'name' => 'Biologi'
        ]);

        // Create sample teacher
        $teacher = User::firstOrCreate([
            'email' => 'guru@example.com'
        ], [
            'name' => 'Guru Contoh',
            'password' => Hash::make('password'),
            'roles_id' => 2,
            'kelas_id' => $kelas->id
        ]);

        // Create sample student
        $student = User::firstOrCreate([
            'email' => 'siswa@example.com'
        ], [
            'name' => 'Siswa Contoh',
            'password' => Hash::make('password'),
            'roles_id' => 4,
            'kelas_id' => $kelas->id
        ]);

        // Create class-subject relationships
        $kelasMapel1 = KelasMapel::firstOrCreate([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel1->id
        ]);

        $kelasMapel2 = KelasMapel::firstOrCreate([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel2->id
        ]);

        $kelasMapel3 = KelasMapel::firstOrCreate([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel3->id
        ]);

        $this->command->info('Sample student data created successfully!');
        $this->command->info('Student email: siswa@example.com');
        $this->command->info('Student password: password');
    }
}
