<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        $types = ['info', 'success', 'warning', 'error'];
        $relatedTypes = ['tugas', 'ujian', 'materi', 'kelas'];
        
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->randomElement([
                'Tugas Dinilai',
                'Tugas Baru',
                'Pengumuman',
                'Reminder Tugas',
                'Nilai Diperbarui'
            ]),
            'message' => $this->faker->sentence(12),
            'type' => $this->faker->randomElement($types),
            'is_read' => $this->faker->boolean(30), // 30% chance of being read
            'related_type' => $this->faker->optional(0.7)->randomElement($relatedTypes),
            'related_id' => $this->faker->optional(0.7)->numberBetween(1, 100),
        ];
    }

    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_read' => false,
            ];
        });
    }

    public function read()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_read' => true,
            ];
        });
    }
}
