<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // pastikan role masuk ke fillable
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
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi satu ke satu dengan UserProfile
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Relasi satu ke banyak dengan Komentar
     */
    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }

    /**
     * Helper untuk cek role user
     * Bisa dipakai seperti $user->hasRole('admin') atau $user->hasRole('admin', 'kaprodi')
     *
     * @param  mixed ...$roles
     * @return bool
     */
    public function hasRole(...$roles)
    {
        return in_array($this->role, $roles);
    }
}
