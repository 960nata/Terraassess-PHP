<?php

namespace App\Models;

use App\Models\KelompokNilai;
use App\Models\TugasJawabanMultiple;
use Illuminate\Database\Eloquent\Model;
use App\Models\TugasKelompokQuizJawaban;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TugasKelompokQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "tugas_id",
        "soal",
        "jawaban",
    ];

    public function KelompokNilai()
    {
        return $this->hasMany(KelompokNilai::class);
    }

    public function TugasJawabanMultiple()
    {
        return $this->hasMany(TugasJawabanMultiple::class);
    }

    public function TugasKelompokQuizJawaban()
    {
        return $this->hasMany(TugasKelompokQuizJawaban::class);
    }
}
