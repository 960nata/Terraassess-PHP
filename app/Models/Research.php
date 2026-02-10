<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'kelas_id',
        'pengajar_id',
        'start_date',
        'end_date',
        'status',
        'research_type',
        'objectives',
        'methodology',
        'expected_results',
        'actual_results',
        'conclusion',
        'recommendations',
        'attachments'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'attachments' => 'array',
    ];

    // Relasi dengan kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi dengan pengajar
    public function pengajar()
    {
        return $this->belongsTo(User::class, 'pengajar_id');
    }

    // Relasi dengan data sensor IoT
    public function iotData()
    {
        return $this->hasMany(IotSensorData::class, 'research_id');
    }

    // Relasi dengan hasil penelitian siswa
    public function studentResults()
    {
        return $this->hasMany(ResearchStudentResult::class, 'research_id');
    }

    // Scope untuk penelitian aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope untuk penelitian berdasarkan kelas
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Scope untuk penelitian berdasarkan pengajar
    public function scopeByPengajar($query, $pengajarId)
    {
        return $query->where('pengajar_id', $pengajarId);
    }

    // Accessor untuk status penelitian
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'planning' => 'Perencanaan',
            'active' => 'Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'on_hold' => 'Ditunda',
            default => 'Tidak Diketahui'
        };
    }

    // Accessor untuk durasi penelitian
    public function getDurationAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date);
        }
        return null;
    }

    // Accessor untuk progress penelitian
    public function getProgressAttribute()
    {
        if ($this->status === 'completed') {
            return 100;
        } elseif ($this->status === 'planning') {
            return 0;
        } elseif ($this->start_date && $this->end_date) {
            $totalDays = $this->start_date->diffInDays($this->end_date);
            $elapsedDays = $this->start_date->diffInDays(now());
            return min(100, max(0, ($elapsedDays / $totalDays) * 100));
        }
        return 0;
    }
}
