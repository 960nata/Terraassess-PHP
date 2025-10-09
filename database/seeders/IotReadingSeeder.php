<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IotReading;
use App\Models\User;
use App\Models\Kelas;
use Carbon\Carbon;

class IotReadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('IoT reading seeder - no dummy data created');
    }
}