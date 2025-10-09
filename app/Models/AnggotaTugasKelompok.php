<?php

namespace App\Models;

use App\Models\User;
use App\Models\TugasKelompok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnggotaTugasKelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        "tugas_kelompok_id",
        "user_id",
        "isKetua",
        "tugas_id",
    ];

    protected $guarded = [
        "id"
    ];

    public function TugasKelompok()
    {
        return $this->belongsTo(TugasKelompok::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function Tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
