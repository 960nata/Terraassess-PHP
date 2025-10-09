<?php

namespace App\Models;

use App\Models\TugasKelompok;
use App\Models\TugasKelompokQuiz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TugasKelompokQuizJawaban extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "tugas_kelompok_quiz_id",
        "tugas_kelompok_id",
        "jawaban",
        "nilai",
    ];

    public function TugasKelompokQuiz()
    {
        return $this->belongsTo(TugasKelompokQuiz::class);
    }
    public function TugasKelompok()
    {
        $this->belongsTo(TugasKelompok::class);
    }
}
