<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Role;

class StudentsXAIpaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel roles dan kelas ada
        $this->ensureRolesExist();
        $this->ensureKelasExist();
        
        // Data siswa laki-laki
        $maleStudents = [
            'Andreas Gema Juan Felix',
            'Anindya Rasyid',
            'Danang Sri Budhana',
            'Fadlurahman Hanshori',
            'M. Aditya Saputra',
            'M. Aldiansyah',
            'M. Fahry Dirgantara',
            'Mario Ryan Raditya',
            'Rahmat Adi Kusuma',
            'Revaldy Dehan Permana',
            'Sultan Fakhri Akbar',
            'Tegar Cahya Ramadani'
        ];

        // Data siswa perempuan
        $femaleStudents = [
            'Adzra Golliyah Fahren',
            'Amira Putri Sakinah',
            'Annisa Fauzia',
            'Aurell Maydecha Ferdian',
            'Batrisya Afiqah Syahira',
            'Birgitta Palmadhea',
            'Cinta Airin Putri Wibowo',
            'Citra Rossari Indah Esti',
            'Clara Della Cantika',
            'Dinda Yaningsih',
            'Elvira Khoirunnisa',
            'Fitri Suryaningtyas',
            'Fitriana Oktavia',
            'Frida Alaika Syifani',
            'Irca Citra Puspita Sari',
            'Izza Khoirurrohma',
            'Khalifa Aztia Riza',
            'Maulida Nurul Farikha',
            'Naila Ulya Azizah',
            'Naina Azmalika Ryu Rajwa',
            'Rossi Hanna Agustina Tampubolon',
            'Seroja Kanya Sifa',
            'Vanesha Candra Buana',
            'Zivia Nazhuwa Azzahra'
        ];

        // Hapus data siswa X A IPA yang sudah ada (opsional)
        User::where('kelas_id', 5)->where('roles_id', 4)->delete();

        // Insert siswa laki-laki
        $this->insertStudents($maleStudents, 200);

        // Insert siswa perempuan  
        $this->insertStudents($femaleStudents, 300);

        $this->command->info('✅ 35 siswa X A IPA berhasil diimport!');
        $this->command->info('📧 Email format: nama.lengkap@terraassessment.com');
        $this->command->info('🔐 Password: password123');
    }

    private function ensureRolesExist()
    {
        $roles = [
            ['id' => 1, 'name' => 'Super Admin'],
            ['id' => 2, 'name' => 'Admin'],
            ['id' => 3, 'name' => 'Guru'],
            ['id' => 4, 'name' => 'Siswa']
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                array_merge($role, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }

    private function ensureKelasExist()
    {
        $kelas = [
            ['id' => 1, 'name' => 'X IPA 1', 'description' => 'Kelas X IPA 1'],
            ['id' => 2, 'name' => 'X IPA 2', 'description' => 'Kelas X IPA 2'],
            ['id' => 3, 'name' => 'XI IPA 1', 'description' => 'Kelas XI IPA 1'],
            ['id' => 4, 'name' => 'XII IPA 1', 'description' => 'Kelas XII IPA 1'],
            ['id' => 5, 'name' => 'X A IPA', 'description' => 'Kelas X A IPA - Kelas Baru']
        ];

        foreach ($kelas as $k) {
            DB::table('kelas')->updateOrInsert(
                ['id' => $k['id']],
                array_merge($k, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }

    private function insertStudents($students, $startId)
    {
        foreach ($students as $index => $name) {
            $id = $startId + $index;
            $email = strtolower(str_replace(' ', '.', $name)) . '@terraassessment.com';
            
            User::updateOrCreate(
                ['email' => $email],
                [
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'roles_id' => 4, // Siswa
                    'kelas_id' => 5,  // X A IPA
                    'deskripsi' => 'Siswa X A IPA',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
