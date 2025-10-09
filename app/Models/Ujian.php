<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ujian extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'kelas_mapel_id',
        'due',
        'time',
        'isHidden',
        'tipe',
        'max_score'
    ];

    protected $casts = [
        'due' => 'datetime',
        'isHidden' => 'boolean'
    ];

    // Relationships
    public function kelasMapel()
    {
        return $this->belongsTo(KelasMapel::class);
    }

    public function soalMultiples()
    {
        return $this->hasMany(SoalUjianMultiple::class, 'ujian_id');
    }

    public function soalEssays()
    {
        return $this->hasMany(SoalUjianEssay::class, 'ujian_id');
    }

    // Combined soal count
    public function getTotalSoalCountAttribute()
    {
        return $this->soalMultiples->count() + $this->soalEssays->count();
    }

    public function userUjian()
    {
        return $this->hasMany(UserUjian::class);
    }

    public function progress()
    {
        return $this->hasMany(UjianProgress::class);
    }

    public function feedback()
    {
        return $this->hasMany(UjianFeedback::class);
    }

    // Accessors
    public function getStatusAttribute()
    {
        if ($this->isHidden) {
            return 'inactive';
        }

        $now = Carbon::now();
        $deadline = Carbon::parse($this->due);

        if ($now->gt($deadline)) {
            return 'expired';
        }

        if ($now->diffInDays($deadline) <= 1) {
            return 'urgent';
        }

        return 'active';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'inactive' => 'Tidak Aktif',
            'expired' => 'Sudah Berakhir',
            'urgent' => 'Mendesak',
            'active' => 'Aktif'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'inactive' => 'secondary',
            'expired' => 'danger',
            'urgent' => 'warning',
            'active' => 'success'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getTotalSoalAttribute()
    {
        return $this->soalMultiples->count() + $this->soalEssays->count();
    }

    public function getTotalPoinAttribute()
    {
        // Untuk sementara, return 0 karena tabel soal tidak memiliki kolom poin
        // Bisa disesuaikan dengan struktur database yang ada
        return 0;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('isHidden', false);
    }

    public function scopeExpired($query)
    {
        return $query->where('due', '<', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('due', '>', now());
    }

    public function scopeUrgent($query)
    {
        return $query->where('due', '<=', now()->addDay())
                    ->where('due', '>', now());
    }

    // Methods
    public function isExpired()
    {
        return Carbon::now()->gt(Carbon::parse($this->due));
    }

    public function isUrgent()
    {
        return Carbon::now()->diffInDays(Carbon::parse($this->due)) <= 1 && !$this->isExpired();
    }

    public function getTimeRemaining()
    {
        if ($this->isExpired()) {
            return 'Sudah berakhir';
        }

        return Carbon::now()->diffForHumans(Carbon::parse($this->due), true);
    }

    public function getDurationFormatted()
    {
        if (!$this->time) {
            return 'Tidak terbatas';
        }

        $hours = floor($this->time / 60);
        $minutes = $this->time % 60;

        if ($hours > 0) {
            return $hours . ' jam ' . $minutes . ' menit';
        }

        return $minutes . ' menit';
    }

    public function getParticipantCount()
    {
        return $this->userUjian->count();
    }

    public function getAverageScore()
    {
        $scores = $this->userUjian->where('status', 'completed')->pluck('nilai');
        
        if ($scores->isEmpty()) {
            return 0;
        }

        return round($scores->avg(), 2);
    }

    public function getCompletionRate()
    {
        $total = $this->userUjian->count();
        $completed = $this->userUjian->where('status', 'completed')->count();

        if ($total == 0) {
            return 0;
        }

        return round(($completed / $total) * 100, 2);
    }
}