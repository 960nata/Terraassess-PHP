<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\KelasMapel;
use App\Models\EditorAccess;
use Illuminate\Support\Facades\Hash;

class TeacherAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample class
        $kelas = Kelas::firstOrCreate([
            'name' => 'X IPA 1'
        ], [
            'description' => 'Kelas X IPA 1'
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

        // Create teacher with correct role (roles_id = 3)
        $teacher = User::updateOrCreate([
            'email' => 'guru@example.com'
        ], [
            'name' => 'Guru Contoh',
            'password' => Hash::make('password'),
            'roles_id' => 3, // Correct role for teacher
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

        // Create EditorAccess records to assign teacher to classes and subjects
        EditorAccess::updateOrCreate([
            'user_id' => $teacher->id,
            'kelas_mapel_id' => $kelasMapel1->id
        ]);

        EditorAccess::updateOrCreate([
            'user_id' => $teacher->id,
            'kelas_mapel_id' => $kelasMapel2->id
        ]);

        EditorAccess::updateOrCreate([
            'user_id' => $teacher->id,
            'kelas_mapel_id' => $kelasMapel3->id
        ]);

        $this->command->info('Teacher assignment data created successfully!');
        $this->command->info('Teacher email: guru@example.com');
        $this->command->info('Teacher password: password');
        $this->command->info('Teacher role: 3 (Guru)');
        $this->command->info('Assigned to classes: ' . $kelas->name);
        $this->command->info('Assigned subjects: ' . $mapel1->name . ', ' . $mapel2->name . ', ' . $mapel3->name);
    }
}

