<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RubrikPenilaian extends Model
{
    use HasFactory;

    protected $table = 'rubrik_penilaian';

    protected $fillable = [
        'ujian_id',
        'nama_kriteria',
        'deskripsi',
        'bobot',
        'nilai_maksimal',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
