<?php

namespace App\Models;

use App\Models\TugasKelompok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class fileKelompok extends Model
{

    protected $fillable = [
        "id",
        "tugas_kelompok_id",
        "file",
    ];

    public function TugasKelompok()
    {
        return $this->belongsTo(TugasKelompok::class);
    }
    use HasFactory;
}
