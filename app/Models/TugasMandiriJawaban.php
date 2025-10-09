<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasMandiriJawaban extends Model
{
    use HasFactory;

    protected $table = 'tugas_mandiri_jawaban';
    
    protected $fillable = [
        'tugas_mandiri_id',
        'user_id',
        'jawaban',
        'nilai',
        'feedback',
        'status',
        'submitted_at',
        'graded_at'
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime'
    ];

    // Relationship dengan TugasMandiri
    public function tugasMandiri()
    {
        return $this->belongsTo(TugasMandiri::class);
    }

    // Relationship dengan User (Siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}