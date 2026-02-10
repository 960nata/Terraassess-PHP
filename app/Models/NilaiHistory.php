<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiHistory extends Model
{
    use HasFactory;

    protected $table = 'nilai_history';
    
    protected $fillable = [
        'user_tugas_id',
        'nilai_lama',
        'nilai_baru',
        'komentar_lama',
        'komentar_baru',
        'diubah_oleh',
        'alasan_revisi',
        'diubah_pada'
    ];

    protected $casts = [
        'diubah_pada' => 'datetime'
    ];

    public function userTugas()
    {
        return $this->belongsTo(UserTugas::class);
    }

    public function pengubah()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
