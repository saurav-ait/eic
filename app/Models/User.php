<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <-- add role here
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
        ];
    }

    // --- Role helper functions ---
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'Employee';
    }

    public function isGuest(): bool
    {
        return $this->role === 'Guest';
    }
}