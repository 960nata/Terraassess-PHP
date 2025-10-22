<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'level',
        'major',
        'description',
        'is_active',
    ];

    protected $guarded = [
        'id',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function dataSiswa()
    {
        return $this->hasMany(DataSiswa::class);
    }

    public function students()
    {
        return $this->hasMany(DataSiswa::class);
    }

    public function siswa()
    {
        return $this->hasMany(User::class)->where('roles_id', 4);
    }

    public function subjects()
    {
        return $this->hasMany(KelasMapel::class);
    }

    public function kelasMapel()
    {
        return $this->hasMany(KelasMapel::class);
    }

    public function researchProjects()
    {
        return $this->hasMany(ResearchProject::class);
    }

    public function research()
    {
        return $this->hasMany(Research::class);
    }

    public function tugasKelompoks()
    {
        return $this->hasMany(TugasKelompok::class);
    }
}
