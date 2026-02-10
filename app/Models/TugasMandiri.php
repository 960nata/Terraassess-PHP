<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasMandiri extends Model
{
    use HasFactory;

    protected $table = 'tugas_mandiri';
    
    protected $fillable = [
        'tugas_id',
        'pertanyaan',
        'poin'
    ];

    protected $casts = [
        'poin' => 'decimal:2'
    ];

    // Relationship dengan Tugas
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    // Relationship dengan jawaban siswa
    public function jawaban()
    {
        return $this->hasMany(TugasMandiriJawaban::class);
    }
}