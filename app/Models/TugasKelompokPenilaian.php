<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasKelompokPenilaian extends Model
{
    use HasFactory;

    protected $table = 'tugas_kelompok_penilaian';
    
    protected $fillable = [
        'tugas_id',
        'deskripsi',
        'ketua_id',
        'pertanyaan_ya_tidak',
        'poin_ya',
        'poin_tidak',
        'pertanyaan_skala',
        'poin_sangat_setuju',
        'poin_setuju',
        'poin_cukup_setuju',
        'poin_kurang_setuju'
    ];

    // Relationship dengan Tugas
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    // Relationship dengan Ketua Kelompok
    public function ketua()
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    // Relationship dengan jawaban kelompok
    public function jawaban()
    {
        return $this->hasMany(TugasKelompokJawaban::class, 'tugas_id', 'tugas_id');
    }
}