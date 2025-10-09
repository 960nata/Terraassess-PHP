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
        $this->command->info('Student data seeder - no dummy data created');
    }
}
