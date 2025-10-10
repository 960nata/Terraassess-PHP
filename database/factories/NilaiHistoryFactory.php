<?php

namespace Database\Factories;

use App\Models\NilaiHistory;
use App\Models\UserTugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NilaiHistoryFactory extends Factory
{
    protected $model = NilaiHistory::class;

    public function definition()
    {
        $nilaiLama = $this->faker->numberBetween(60, 90);
        $nilaiBaru = $this->faker->numberBetween(70, 95);

        return [
            'user_tugas_id' => UserTugas::factory(),
            'nilai_lama' => $nilaiLama,
            'nilai_baru' => $nilaiBaru,
            'komentar_lama' => $this->faker->optional(0.8)->sentence(6),
            'komentar_baru' => $this->faker->optional(0.8)->sentence(6),
            'diubah_oleh' => User::factory(),
            'alasan_revisi' => $this->faker->randomElement([
                'Koreksi penilaian',
                'Perbaikan setelah review',
                'Penyesuaian standar',
                'Konsultasi dengan guru lain',
                'Permintaan siswa'
            ]),
            'diubah_pada' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
