<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\KelasMapel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CompleteStudentSeeder extends Seeder
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

        // Create sample materials
        DB::table('materis')->insertOrIgnore([
            [
                'name' => 'Hukum Newton',
                'content' => 'Materi tentang hukum-hukum Newton dalam fisika',
                'kelas_mapel_id' => $kelasMapel1->id,
                'isHidden' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Struktur Atom',
                'content' => 'Materi tentang struktur atom dan konfigurasi elektron',
                'kelas_mapel_id' => $kelasMapel2->id,
                'isHidden' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sistem Pencernaan',
                'content' => 'Materi tentang sistem pencernaan manusia',
                'kelas_mapel_id' => $kelasMapel3->id,
                'isHidden' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Create sample assignments
        DB::table('tugas')->insertOrIgnore([
            [
                'name' => 'Latihan Hukum Newton',
                'content' => 'Kerjakan soal-soal tentang hukum Newton',
                'kelas_mapel_id' => $kelasMapel1->id,
                'due' => now()->addDays(7),
                'isHidden' => 0,
                'tipe' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Laporan Praktikum Kimia',
                'content' => 'Buat laporan praktikum tentang reaksi kimia',
                'kelas_mapel_id' => $kelasMapel2->id,
                'due' => now()->addDays(5),
                'isHidden' => 0,
                'tipe' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Observasi Sel',
                'content' => 'Lakukan observasi sel tumbuhan dan hewan',
                'kelas_mapel_id' => $kelasMapel3->id,
                'due' => now()->addDays(3),
                'isHidden' => 0,
                'tipe' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Create sample exams
        DB::table('ujians')->insertOrIgnore([
            [
                'name' => 'Ujian Tengah Semester Fisika',
                'kelas_mapel_id' => $kelasMapel1->id,
                'due' => now()->addDays(3),
                'time' => 120,
                'isHidden' => 0,
                'tipe' => 'ujian',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Ujian Kimia Dasar',
                'kelas_mapel_id' => $kelasMapel2->id,
                'due' => now()->addDays(5),
                'time' => 90,
                'isHidden' => 0,
                'tipe' => 'quiz',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Create sample IoT readings
        for ($i = 0; $i < 15; $i++) {
            DB::table('iot_readings')->insertOrIgnore([
                'student_id' => $student->id,
                'class_id' => $kelas->id,
                'soil_temperature' => rand(150, 350) / 10, // 15.0 - 35.0
                'soil_humus' => rand(30, 80) / 10, // 3.0 - 8.0
                'soil_moisture' => rand(300, 700) / 10, // 30.0 - 70.0
                'device_id' => 'IoT_DEVICE_001',
                'location' => 'Kebun Sekolah',
                'notes' => 'Pengukuran rutin mingguan',
                'timestamp' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create sample user assignments
        $tugasIds = DB::table('tugas')->pluck('id');
        foreach ($tugasIds as $tugasId) {
            DB::table('user_tugas')->insertOrIgnore([
                'user_id' => $student->id,
                'tugas_id' => $tugasId,
                'status' => 'submitted',
                'nilai' => rand(70, 95),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create sample user exams
        $ujianIds = DB::table('ujians')->pluck('id');
        foreach ($ujianIds as $ujianId) {
            DB::table('user_ujians')->insertOrIgnore([
                'user_id' => $student->id,
                'ujian_id' => $ujianId,
                'status' => 'completed',
                'nilai' => rand(80, 100),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->command->info('Complete student data created successfully!');
        $this->command->info('Student email: siswa@example.com');
        $this->command->info('Student password: password');
        $this->command->info('Created: ' . count($tugasIds) . ' tugas, ' . count($ujianIds) . ' ujian, 3 materi, 15 IoT readings');
    }
}
