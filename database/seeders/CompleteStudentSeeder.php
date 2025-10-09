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
        $this->command->info('Complete student data seeder - no dummy data created');
    }
}
