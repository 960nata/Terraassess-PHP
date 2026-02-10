<?php

namespace App\Models;

use App\Models\Tugas;
use App\Models\TugasJawabanMultiple;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TugasQuiz extends Model
{
    use HasFactory;

    protected $table = 'tugas_quizzes';

    protected $fillable = [
        'tugas_id',
        'soal',
        'poin',
        'kategori',
    ];

    protected $guarded = [
        'id'
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function tugasJawabanMultiple()
    {
        return $this->hasMany(TugasJawabanMultiple::class);
    }
}
