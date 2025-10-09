<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
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
}
