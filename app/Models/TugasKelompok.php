<?php

namespace App\Models;

use App\Models\Tugas;
use App\Models\fileKelompok;
use App\Models\KelompokNilai;
use App\Models\AnggotaTugasKelompok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TugasKelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        "tugas_id",
        "name",
        "nilai",
        "status",
    ];

    protected $guarded = [
        "id"
    ];

    public function Tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
    public function KelompokNilai()
    {
        return $this->hasMany(KelompokNilai::class);
    }

    public function AnggotaTugasKelompok()
    {
        return $this->hasMany(AnggotaTugasKelompok::class);
    }

    public function fileKelompok()
    {
        return $this->hasMany(fileKelompok::class);
    }
    public function TugasKelompokQuizJawaban()
    {
        return $this->hasMany(TugasKelompokQuizJawaban::class);
    }
}
