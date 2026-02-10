<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUjianMultiple extends Model
{
    use HasFactory;

    protected $table = 'soal_ujian_multiples';

    protected $fillable = [
        'ujian_id',
        'soal',
        'a',
        'b',
        'c',
        'd',
        'e',
        'jawaban',
        'poin',
        'kategori'
    ];

    // Relationships
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    // Accessors
    public function getJawabanOptionsAttribute()
    {
        $options = [
            'A' => $this->a,
            'B' => $this->b,
            'C' => $this->c,
        ];

        if ($this->d) {
            $options['D'] = $this->d;
        }
        if ($this->e) {
            $options['E'] = $this->e;
        }

        return $options;
    }

    public function getCorrectAnswerTextAttribute()
    {
        $options = $this->jawaban_options;
        return $options[$this->jawaban] ?? '';
    }

    // Methods
    public function isCorrectAnswer($answer)
    {
        return $this->jawaban === $answer;
    }
}