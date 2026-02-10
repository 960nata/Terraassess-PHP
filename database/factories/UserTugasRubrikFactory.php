<?php

namespace Database\Factories;

use App\Models\UserTugasRubrik;
use App\Models\UserTugas;
use App\Models\RubrikPenilaian;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTugasRubrikFactory extends Factory
{
    protected $model = UserTugasRubrik::class;

    public function definition()
    {
        return [
            'user_tugas_id' => UserTugas::factory(),
            'rubrik_id' => RubrikPenilaian::factory(),
            'nilai' => $this->faker->numberBetween(0, 100),
            'komentar_aspek' => $this->faker->optional(0.7)->sentence(8),
        ];
    }
}
