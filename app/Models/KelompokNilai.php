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
        "komentar",
        "dinilai_oleh",
        "dinilai_pada",
    ];

    protected $guarded = [
        "id"
    ];

    protected $casts = [
        'dinilai_pada' => 'datetime'
    ];

    public function TugasKelompok()
    {
        return $this->belongsTo(TugasKelompok::class);
    }
    public function TugasKelompokQuiz()
    {
        return $this->belongsTo(TugasKelompokQuiz::class);
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'dinilai_oleh');
    }
    
    use HasFactory;
}
