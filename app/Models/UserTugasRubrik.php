<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTugasRubrik extends Model
{
    use HasFactory;

    protected $table = 'user_tugas_rubrik';
    
    protected $fillable = [
        'user_tugas_id',
        'rubrik_id',
        'nilai',
        'komentar_aspek'
    ];

    public function userTugas()
    {
        return $this->belongsTo(UserTugas::class);
    }

    public function rubrik()
    {
        return $this->belongsTo(RubrikPenilaian::class, 'rubrik_id');
    }
}
