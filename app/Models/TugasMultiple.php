<?php

namespace App\Models;

use App\Models\Tugas;
use App\Models\TugasJawabanMultiple;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TugasMultiple extends Model
{
    use HasFactory;

    protected $fillable = [
        "tugas_id",
        "soal",
        "a",
        "b",
        "c",
        "d",
        "e",
        "jawaban",
        "poin",
        "kategori",
    ];

    protected $guarded = [
        "id"
    ];

    public function TugasJawabanMultiple()
    {
        return $this->hasMany(TugasJawabanMultiple::class);
    }

    public function Tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
