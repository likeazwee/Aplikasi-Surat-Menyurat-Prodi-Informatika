<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'user_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nim',
        'prodi',
        'nip',
        'jabatan',
    ];

    /**
     * Mendefinisikan relasi "dimiliki oleh" ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
