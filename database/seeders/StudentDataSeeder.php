<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\KelasMapel;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\UserTugas;
use App\Models\UserUjian;
use App\Models\IotReading;
use Illuminate\Support\Facades\Hash;

class StudentDataSeeder extends Seeder
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

        // Create sample materials
        $materi1 = Materi::firstOrCreate([
            'name' => 'Hukum Newton'
        ], [
            'content' => 'Materi tentang hukum-hukum Newton dalam fisika',
            'deskripsi' => 'Materi tentang hukum-hukum Newton dalam fisika',
            'kelas_mapel_id' => $kelasMapel1->id,
            'file_materi' => null
        ]);

        $materi2 = Materi::firstOrCreate([
            'name' => 'Struktur Atom'
        ], [
            'content' => 'Materi tentang struktur atom dan konfigurasi elektron',
            'deskripsi' => 'Materi tentang struktur atom dan konfigurasi elektron',
            'kelas_mapel_id' => $kelasMapel2->id,
            'file_materi' => null
        ]);

        // Create sample assignments
        $tugas1 = Tugas::firstOrCreate([
            'name' => 'Latihan Hukum Newton'
        ], [
            'content' => 'Kerjakan soal-soal tentang hukum Newton',
            'kelas_mapel_id' => $kelasMapel1->id,
            'due' => now()->addDays(7),
            'tipe' => 1
        ]);

        $tugas2 = Tugas::firstOrCreate([
            'name' => 'Laporan Praktikum Kimia'
        ], [
            'content' => 'Buat laporan praktikum tentang reaksi kimia',
            'kelas_mapel_id' => $kelasMapel2->id,
            'due' => now()->addDays(5),
            'tipe' => 1
        ]);

        // Create sample exams
        $ujian1 = Ujian::firstOrCreate([
            'name' => 'Ujian Tengah Semester Fisika'
        ], [
            'kelas_mapel_id' => $kelasMapel1->id,
            'due' => now()->addDays(3),
            'time' => 120,
            'tipe' => 'multiple_choice'
        ]);

        // Create sample questions
        $soal1 = \App\Models\SoalUjianMultiple::firstOrCreate([
            'soal' => 'Apa yang dimaksud dengan hukum Newton pertama?'
        ], [
            'ujian_id' => $ujian1->id,
            'a' => 'F = ma',
            'b' => 'Benda yang diam akan tetap diam',
            'c' => 'Aksi = Reaksi',
            'd' => 'Energi tidak dapat diciptakan',
            'jawaban' => 'B'
        ]);

        $soal2 = \App\Models\SoalUjianMultiple::firstOrCreate([
            'soal' => 'Rumus hukum Newton kedua adalah?'
        ], [
            'ujian_id' => $ujian1->id,
            'a' => 'F = ma',
            'b' => 'Benda yang diam akan tetap diam',
            'c' => 'Aksi = Reaksi',
            'd' => 'Energi tidak dapat diciptakan',
            'jawaban' => 'A'
        ]);

        // Create sample user assignments
        UserTugas::firstOrCreate([
            'user_id' => $student->id,
            'tugas_id' => $tugas1->id
        ], [
            'status' => 'submitted'
        ]);

        UserTugas::firstOrCreate([
            'user_id' => $student->id,
            'tugas_id' => $tugas2->id
        ], [
            'status' => 'completed',
            'nilai' => 85
        ]);

        // Create sample user exams
        UserUjian::firstOrCreate([
            'user_id' => $student->id,
            'ujian_id' => $ujian1->id
        ], [
            'nilai' => 100,
            'status' => 'completed'
        ]);

        // Create sample IoT readings
        for ($i = 0; $i < 20; $i++) {
            IotReading::create([
                'student_id' => $student->id,
                'class_id' => $kelas->id,
                'soil_temperature' => rand(150, 350) / 10, // 15.0 - 35.0
                'soil_humus' => rand(30, 80) / 10, // 3.0 - 8.0
                'soil_moisture' => rand(300, 700) / 10, // 30.0 - 70.0
                'device_id' => 'IoT_DEVICE_001',
                'location' => 'Kebun Sekolah',
                'notes' => 'Pengukuran rutin mingguan',
                'timestamp' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))
            ]);
        }

        $this->command->info('Sample student data created successfully!');
    }
}
