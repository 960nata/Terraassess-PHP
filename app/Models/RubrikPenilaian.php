<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RubrikPenilaian extends Model
{
    use HasFactory;

    protected $table = 'rubrik_penilaian';
    
    protected $fillable = [
        'tugas_id',
        'aspek',
        'bobot',
        'deskripsi'
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function userTugasRubrik()
    {
        return $this->hasMany(UserTugasRubrik::class, 'rubrik_id');
    }
}