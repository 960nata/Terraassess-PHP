<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUjianEssay extends Model
{
    use HasFactory;

    protected $table = 'soal_ujian_essays';

    protected $fillable = [
        'ujian_id',
        'soal'
    ];

    // Relationships
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}