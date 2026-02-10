<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasMapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'mapel_id'
    ];

    // Relationships
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function pengajar()
    {
        return $this->belongsToMany(User::class, 'editor_accesses', 'kelas_mapel_id', 'user_id')
                    ->where('users.roles_id', 3); // Only get users with teacher role
    }

    public function editorAccess()
    {
        return $this->hasMany(EditorAccess::class);
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }

    // Accessors
    public function getNamaKelasAttribute()
    {
        return $this->kelas->name ?? 'Unknown';
    }

    public function getNamaMapelAttribute()
    {
        return $this->mapel->name ?? 'Unknown';
    }

    public function getNamaPengajarAttribute()
    {
        return $this->pengajar->first()->name ?? 'Belum ditentukan';
    }

    public function getTotalMateriAttribute()
    {
        return $this->materi->count();
    }

    public function getTotalTugasAttribute()
    {
        return $this->tugas->count();
    }

    public function getTotalUjianAttribute()
    {
        return $this->ujian->count();
    }

    // Scopes
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByMapel($query, $mapelId)
    {
        return $query->where('mapel_id', $mapelId);
    }

    public function scopeByPengajar($query, $pengajarId)
    {
        return $query->whereHas('pengajar', function($q) use ($pengajarId) {
            $q->where('user_id', $pengajarId);
        });
    }

    // Methods
    public function getActiveMateri()
    {
        return $this->materi()->where('isHidden', false)->get();
    }

    public function getActiveTugas()
    {
        return $this->tugas()->where('isHidden', false)->get();
    }

    public function getActiveUjian()
    {
        return $this->ujian()->where('isHidden', false)->get();
    }

    public function getRecentMateri($limit = 5)
    {
        return $this->materi()
            ->where('isHidden', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRecentTugas($limit = 5)
    {
        return $this->tugas()
            ->where('isHidden', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getRecentUjian($limit = 5)
    {
        return $this->ujian()
            ->where('isHidden', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getUpcomingTugas()
    {
        return $this->tugas()
            ->where('isHidden', false)
            ->where('due', '>', now())
            ->orderBy('due', 'asc')
            ->get();
    }

    public function getUpcomingUjian()
    {
        return $this->ujian()
            ->where('isHidden', false)
            ->where('due', '>', now())
            ->orderBy('due', 'asc')
            ->get();
    }

    public function getUrgentTugas()
    {
        return $this->tugas()
            ->where('isHidden', false)
            ->where('due', '<=', now()->addDay())
            ->where('due', '>', now())
            ->orderBy('due', 'asc')
            ->get();
    }

    public function getUrgentUjian()
    {
        return $this->ujian()
            ->where('isHidden', false)
            ->where('due', '<=', now()->addDay())
            ->where('due', '>', now())
            ->orderBy('due', 'asc')
            ->get();
    }

    public function getProgressStats()
    {
        $totalMateri = $this->materi->count();
        $totalTugas = $this->tugas->count();
        $totalUjian = $this->ujian->count();

        return [
            'materi' => $totalMateri,
            'tugas' => $totalTugas,
            'ujian' => $totalUjian,
            'total' => $totalMateri + $totalTugas + $totalUjian
        ];
    }
}