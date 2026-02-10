<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPenilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'tugas_kelompok_id',
        'penilai_kelompok_id',
        'tugas_id',
        'nilai_kerjasama',
        'nilai_kualitas',
        'nilai_presentasi',
        'nilai_inovasi',
        'komentar',
        'status',
    ];

    public function tugasKelompok()
    {
        return $this->belongsTo(TugasKelompok::class);
    }

    public function penilaiKelompok()
    {
        return $this->belongsTo(TugasKelompok::class, 'penilai_kelompok_id');
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    // Method untuk menghitung total nilai
    public function getTotalNilaiAttribute()
    {
        $total = 0;
        $count = 0;
        
        if ($this->nilai_kerjasama) {
            $total += $this->nilai_kerjasama;
            $count++;
        }
        if ($this->nilai_kualitas) {
            $total += $this->nilai_kualitas;
            $count++;
        }
        if ($this->nilai_presentasi) {
            $total += $this->nilai_presentasi;
            $count++;
        }
        if ($this->nilai_inovasi) {
            $total += $this->nilai_inovasi;
            $count++;
        }
        
        return $count > 0 ? round($total / $count, 2) : 0;
    }
}