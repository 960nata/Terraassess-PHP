<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\KelasMapel;
use App\Models\DataSiswa;
use App\Models\Tugas;
use App\Models\Materi;
use Illuminate\Support\Facades\Hash;

class DataImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create multiple classes
        $kelas1 = Kelas::firstOrCreate(['name' => 'X IPA 1']);
        $kelas2 = Kelas::firstOrCreate(['name' => 'X IPA 2']);
        $kelas3 = Kelas::firstOrCreate(['name' => 'XI IPA 1']);
        $kelas4 = Kelas::firstOrCreate(['name' => 'XI IPA 2']);
        $kelas5 = Kelas::firstOrCreate(['name' => 'XII IPA 1']);

        // Create multiple subjects
        $mapels = [
            'Fisika', 'Kimia', 'Biologi', 'Matematika', 'Bahasa Indonesia',
            'Bahasa Inggris', 'Sejarah', 'Geografi', 'Ekonomi', 'Sosiologi'
        ];

        foreach ($mapels as $mapelName) {
            Mapel::firstOrCreate(['name' => $mapelName]);
        }

        // Create multiple teachers
        $teachers = [
            ['name' => 'Dr. Ahmad Fauzi', 'email' => 'ahmad.fauzi@terraassessment.com'],
            ['name' => 'Siti Nurhaliza, S.Pd', 'email' => 'siti.nurhaliza@terraassessment.com'],
            ['name' => 'Budi Santoso, M.Pd', 'email' => 'budi.santoso@terraassessment.com'],
            ['name' => 'Dewi Kartika, S.Pd', 'email' => 'dewi.kartika@terraassessment.com'],
            ['name' => 'Eko Prasetyo, M.Pd', 'email' => 'eko.prasetyo@terraassessment.com']
        ];

        foreach ($teachers as $teacher) {
            User::firstOrCreate(['email' => $teacher['email']], [
                'name' => $teacher['name'],
                'password' => Hash::make('guru123'),
                'roles_id' => 2,
                'kelas_id' => $kelas1->id
            ]);
        }

        // Create multiple students
        $students = [
            ['name' => 'Andi Wijaya', 'email' => 'andi.wijaya@terraassessment.com'],
            ['name' => 'Bella Sari', 'email' => 'bella.sari@terraassessment.com'],
            ['name' => 'Candra Putra', 'email' => 'candra.putra@terraassessment.com'],
            ['name' => 'Dina Maharani', 'email' => 'dina.maharani@terraassessment.com'],
            ['name' => 'Eko Pratama', 'email' => 'eko.pratama@terraassessment.com'],
            ['name' => 'Fira Amalia', 'email' => 'fira.amalia@terraassessment.com'],
            ['name' => 'Gita Permata', 'email' => 'gita.permata@terraassessment.com'],
            ['name' => 'Hadi Kurniawan', 'email' => 'hadi.kurniawan@terraassessment.com'],
            ['name' => 'Indah Sari', 'email' => 'indah.sari@terraassessment.com'],
            ['name' => 'Joko Susilo', 'email' => 'joko.susilo@terraassessment.com']
        ];

        foreach ($students as $student) {
            User::firstOrCreate(['email' => $student['email']], [
                'name' => $student['name'],
                'password' => Hash::make('siswa123'),
                'roles_id' => 4,
                'kelas_id' => $kelas1->id
            ]);
        }

        // Create class-subject relationships
        $allKelas = Kelas::all();
        $allMapels = Mapel::all();

        foreach ($allKelas as $kelas) {
            foreach ($allMapels as $mapel) {
                KelasMapel::firstOrCreate([
                    'kelas_id' => $kelas->id,
                    'mapel_id' => $mapel->id
                ]);
            }
        }

        // Create sample materials
        $materis = [
            ['name' => 'Pengenalan Fisika Dasar', 'deskripsi' => 'Materi dasar tentang konsep fisika'],
            ['name' => 'Struktur Atom', 'deskripsi' => 'Pembahasan tentang struktur atom dan tabel periodik'],
            ['name' => 'Sistem Pencernaan', 'deskripsi' => 'Materi tentang sistem pencernaan manusia'],
            ['name' => 'Aljabar Linear', 'deskripsi' => 'Konsep dasar aljabar linear'],
            ['name' => 'Tata Bahasa Indonesia', 'deskripsi' => 'Materi tentang tata bahasa Indonesia']
        ];

        foreach ($materis as $materi) {
            Materi::firstOrCreate([
                'name' => $materi['name']
            ], [
                'deskripsi' => $materi['deskripsi'],
                'kelas_mapel_id' => KelasMapel::first()->id,
                'content' => 'Konten materi ' . $materi['name']
            ]);
        }

        // Create sample assignments
        $tugas = [
            ['name' => 'Latihan Fisika Dasar', 'content' => 'Kerjakan soal-soal fisika dasar'],
            ['name' => 'Laporan Praktikum Kimia', 'content' => 'Buat laporan praktikum struktur atom'],
            ['name' => 'Presentasi Biologi', 'content' => 'Presentasikan tentang sistem pencernaan'],
            ['name' => 'Soal Matematika', 'content' => 'Kerjakan soal aljabar linear'],
            ['name' => 'Essay Bahasa Indonesia', 'content' => 'Tulis essay dengan tata bahasa yang benar']
        ];

        foreach ($tugas as $assignment) {
            Tugas::firstOrCreate([
                'name' => $assignment['name']
            ], [
                'content' => $assignment['content'],
                'kelas_mapel_id' => KelasMapel::first()->id,
                'due' => now()->addDays(7),
                'tipe' => 1
            ]);
        }

        $this->command->info('Data import completed successfully!');
        $this->command->info('Created: ' . Kelas::count() . ' classes, ' . Mapel::count() . ' subjects');
        $this->command->info('Created: ' . User::where('roles_id', 2)->count() . ' teachers, ' . User::where('roles_id', 4)->count() . ' students');
        $this->command->info('Created: ' . Materi::count() . ' materials, ' . Tugas::count() . ' assignments');
    }
}
