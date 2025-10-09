<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AnggotaTugasKelompok;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'kelas_id',
        'roles_id',
        'password',
        'gambar',
        'deskripsi',
        'phone',
        'bio',
        'profile_photo',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_activity_at' => 'datetime',
    ];

    public function DataSiswa()
    {
        return $this->belongsTo(DataSiswa::class);
    }

    public function Role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function Contact()
    {
        return $this->hasOne(Contact::class);
    }

    public function EditorAccess()
    {
        return $this->hasMany(EditorAccess::class);
    }

    public function Kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function User()
    {
        return $this->hasMany(Tugas::class);
    }

    public function UserTugas()
    {
        return $this->hasMany(UserTugas::class);
    }

    public function UserMateri()
    {
        return $this->hasMany(UserMateri::class);
    }

    public function UserJawaban()
    {
        return $this->hasMany(UserJawaban::class);
    }

    public function UserCommit()
    {
        return $this->hasMany(UserCommit::class);
    }

    // Baru
    public function AnggotaTugasKelompok()
    {
        return $this->hasMany(AnggotaTugasKelompok::class);
    }

    public function TugasJawabanMultiple()
    {
        return $this->hasMany(TugasJawabanMultiple::class);
    }

    /**
     * Check if user is online (active within last 5 minutes)
     */
    public function isOnline()
    {
        if (!$this->last_activity_at) {
            return false;
        }
        
        return $this->last_activity_at->diffInMinutes(now()) <= 5;
    }

    /**
     * Update user's last activity timestamp
     */
    public function updateLastActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }
}
