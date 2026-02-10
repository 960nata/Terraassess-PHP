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
        // Get some students and classes
        $students = User::where('roles_id', 3)->take(5)->get();
        $classes = Kelas::take(3)->get();
        
        if ($students->isEmpty() || $classes->isEmpty()) {
            $this->command->info('No students or classes found. Please run other seeders first.');
            return;
        }

        // Generate sample IoT readings
        $readings = [];
        
        for ($i = 0; $i < 50; $i++) {
            $student = $students->random();
            $class = $classes->random();
            
            // Generate realistic soil sensor data
            $soilTemperature = rand(150, 350) / 10; // 15.0 - 35.0°C
            $soilHumus = rand(30, 80) / 10; // 3.0 - 8.0%
            $soilMoisture = rand(300, 700) / 10; // 30.0 - 70.0%
            
            $readings[] = [
                'student_id' => 'SISWA' . str_pad($student->id, 3, '0', STR_PAD_LEFT), // Generate student ID
                'class_id' => $class->id,
                'timestamp' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                'soil_temperature' => $soilTemperature,
                'soil_humus' => $soilHumus,
                'soil_moisture' => $soilMoisture,
                'device_id' => 'device_' . rand(1000, 9999),
                'created_by_role' => rand(0, 1) ? 'student' : 'teacher',
                'user_id' => $student->id,
                'location' => 'Lokasi ' . rand(1, 10),
                'notes' => 'Pengukuran ke-' . ($i + 1),
                'raw_data' => json_encode([
                    'temperature_raw' => $soilTemperature * 100,
                    'humus_raw' => $soilHumus * 100,
                    'moisture_raw' => $soilMoisture * 100,
                    'sensor_version' => '1.0',
                    'battery_level' => rand(20, 100)
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // Insert readings
        IotReading::insert($readings);
        
        $this->command->info('Created ' . count($readings) . ' IoT readings');
    }
}