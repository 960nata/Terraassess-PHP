<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'deskripsi',
        'gambar',
        'kategori',
        'code',
        'is_active'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function kelasMapel()
    {
        return $this->hasMany(KelasMapel::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mapels', 'mapel_id', 'kelas_id');
    }

    public function materi()
    {
        return $this->hasManyThrough(Materi::class, KelasMapel::class);
    }

    public function tugas()
    {
        return $this->hasManyThrough(Tugas::class, KelasMapel::class);
    }

    public function ujian()
    {
        return $this->hasManyThrough(Ujian::class, KelasMapel::class);
    }

    // Accessors
    // public function getGambarUrlAttribute()
    // {
    //     if (!$this->gambar) {
    //         return asset('asset/img/placeholder-3.jpg');
    //     }

    //     return asset('storage/mapel/' . $this->gambar);
    // }

    // public function getShortDeskripsiAttribute()
    // {
    //     return \Str::limit($this->deskripsi, 100);
    // }

    // Scopes
    // public function scopeActive($query)
    // {
    //     return $query->where('is_active', true);
    // }

    // Methods
    public function getTotalKelas()
    {
        return $this->kelasMapel->count();
    }

    public function getTotalMateri()
    {
        return $this->materi->count();
    }

    public function getTotalTugas()
    {
        return $this->tugas->count();
    }

    public function getTotalUjian()
    {
        return $this->ujian->count();
    }

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