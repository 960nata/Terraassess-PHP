<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasFeedback extends Model
{
    use HasFactory;

    protected $table = 'tugas_feedbacks';

    protected $fillable = [
        'tugas_id',
        'user_id',
        'guru_id',
        'feedback',
        'rating',
        'status',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}