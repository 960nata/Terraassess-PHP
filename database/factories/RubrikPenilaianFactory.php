<?php

namespace Database\Factories;

use App\Models\RubrikPenilaian;
use App\Models\Tugas;
use Illuminate\Database\Eloquent\Factories\Factory;

class RubrikPenilaianFactory extends Factory
{
    protected $model = RubrikPenilaian::class;

    public function definition()
    {
        $aspekOptions = [
            'Isi & Analisis',
            'Struktur & Organisasi', 
            'Bahasa & Ejaan',
            'Kreativitas',
            'Ketepatan Waktu',
            'Presentasi',
            'Kerja Sama',
            'Pemahaman Konsep'
        ];

        return [
            'tugas_id' => Tugas::factory(),
            'aspek' => $this->faker->randomElement($aspekOptions),
            'bobot' => $this->faker->numberBetween(10, 50),
            'deskripsi' => $this->faker->sentence(10),
        ];
    }
}
