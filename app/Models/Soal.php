<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_id',
        'pertanyaan',
        'jawaban_a',
        'jawaban_b',
        'jawaban_c',
        'jawaban_d',
        'jawaban_benar',
        'poin'
    ];

    // Relationships
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    // Accessors
    public function getJawabanOptionsAttribute()
    {
        return [
            'A' => $this->jawaban_a,
            'B' => $this->jawaban_b,
            'C' => $this->jawaban_c,
            'D' => $this->jawaban_d
        ];
    }

    public function getCorrectAnswerTextAttribute()
    {
        $options = $this->jawaban_options;
        return $options[$this->jawaban_benar] ?? '';
    }

    // Methods
    public function isCorrectAnswer($answer)
    {
        return $this->jawaban_benar === $answer;
    }

    public function getAnswerKey($answer)
    {
        $options = $this->jawaban_options;
        foreach ($options as $key => $value) {
            if ($value === $answer) {
                return $key;
            }
        }
        return null;
    }
}
