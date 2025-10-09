<?php

namespace App\Models;

use App\Models\TugasKelompok;
use App\Models\TugasKelompokQuiz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KelompokNilai extends Model
{

    protected $fillable = [
        "tugas_kelompok_id",
        "to_kelompok",
        "nilai",
    ];

    protected $guarded = [
        "id"
    ];

    public function TugasKelompok()
    {
        return $this->belongsTo(TugasKelompok::class);
    }
    public function TugasKelompokQuiz()
    {
        return $this->belongsTo(TugasKelompokQuiz::class);
    }
    use HasFactory;
}
