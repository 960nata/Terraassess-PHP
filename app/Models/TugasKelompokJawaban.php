<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasKelompokJawaban extends Model
{
    use HasFactory;

    protected $table = 'tugas_kelompok_jawaban';
    
    protected $fillable = [
        'tugas_id',
        'kelompok_id',
        'user_id',
        'jawaban_ya_tidak',
        'nilai_ya_tidak',
        'jawaban_skala',
        'nilai_skala',
        'komentar',
        'status',
        'submitted_at',
        'graded_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime'
    ];

    // Relationship dengan Tugas
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    // Relationship dengan Kelompok
    public function kelompok()
    {
        return $this->belongsTo(TugasKelompok::class, 'kelompok_id');
    }

    // Relationship dengan User (Siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}