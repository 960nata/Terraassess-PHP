<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\KelasMapel;
use App\Models\EditorAccess;

class EditorAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create default class
        $kelas = Kelas::firstOrCreate([
            'name' => 'X IPA 1'
        ], [
            'description' => 'Kelas X IPA 1 - Kelas Default'
        ]);

        // Get or create sample subjects
        $mapel1 = Mapel::firstOrCreate([
            'name' => 'Fisika'
        ]);

        $mapel2 = Mapel::firstOrCreate([
            'name' => 'Kimia'
        ]);

        $mapel3 = Mapel::firstOrCreate([
            'name' => 'Biologi'
        ]);

        // Get teacher user
        $teacher = User::where('email', 'guru@terraassessment.com')->first();
        
        if (!$teacher) {
            $this->command->warn('Teacher user not found. Please run UserSeeder first.');
            return;
        }

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

        $this->command->info('EditorAccess data created successfully!');
        $this->command->info('Teacher: ' . $teacher->name . ' (' . $teacher->email . ')');
        $this->command->info('Assigned to class: ' . $kelas->name);
        $this->command->info('Assigned subjects: ' . $mapel1->name . ', ' . $mapel2->name . ', ' . $mapel3->name);
    }
}

