<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'kelas_mapel_id',
        'due',
        'isHidden',
        'tipe'
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

    public function userTugas()
    {
        return $this->hasMany(UserTugas::class);
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

    public function getShortContentAttribute()
    {
        return \Str::limit($this->content, 100);
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

    public function getParticipantCount()
    {
        return $this->userTugas->count();
    }

    public function getSubmissionCount()
    {
        return $this->userTugas->where('status', '!=', 'pending')->count();
    }

    public function getCompletionRate()
    {
        $total = $this->userTugas->count();
        $completed = $this->userTugas->whereIn('status', ['completed', 'graded'])->count();

        if ($total == 0) {
            return 0;
        }

        return round(($completed / $total) * 100, 2);
    }

    public function getAverageScore()
    {
        $scores = $this->userTugas->whereIn('status', ['completed', 'graded'])->pluck('nilai');
        
        if ($scores->isEmpty()) {
            return 0;
        }

        return round($scores->avg(), 2);
    }

    public function getFileUrl()
    {
        if (!$this->file_tugas) {
            return null;
        }

        return asset('storage/' . $this->file_tugas);
    }

    public function getFileExtension()
    {
        if (!$this->file_tugas) {
            return null;
        }

        return pathinfo($this->file_tugas, PATHINFO_EXTENSION);
    }

    public function getFileSize()
    {
        if (!$this->file_tugas) {
            return null;
        }

        $path = storage_path('app/public/' . $this->file_tugas);
        
        if (!file_exists($path)) {
            return null;
        }

        $bytes = filesize($path);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}