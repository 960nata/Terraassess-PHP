<?php

namespace App\Models;

use App\Models\User;
use App\Models\TugasQuiz;
use App\Models\TugasMultiple;
// use App\Models\TugasJawabanMultiple;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TugasJawabanMultiple extends Model
{
    use HasFactory;

    protected $fillable = [
        "tugas_multiple_id",
        "tugas_quiz_id",
        "user_id",
        "user_jawaban",
        "nilai",
        "koreksi",
        "tugas_kelompok_quiz_id",
    ];

    protected $guarded = [
        "id"
    ];

    public function TugasMultiple()
    {
        return $this->belongsTo(TugasMultiple::class);
    }

    public function TugasQuiz()
    {
        return $this->belongsTo(TugasQuiz::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function TugasKelompokQuiz()
    {
        return $this->belongsTo(TugasKelompokQuiz::class);
    }
}
