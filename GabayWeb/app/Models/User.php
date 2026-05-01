<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'pairing_code',
        'code_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'code_expires_at' => 'datetime',
        ];
    }

    // 🔗 Relationships

    public function caregivers()
    {
        return $this->hasMany(Pairing::class, 'vi_user_id');
    }

    public function viUsers()
    {
        return $this->hasMany(Pairing::class, 'caregiver_user_id');
    }

    public function navigationSessions()
    {
        return $this->hasMany(NavigationSession::class, 'user_id');
    }
}
