<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    // Menyesuaikan dengan nama tabel singular kita
    protected $table = 'komentar';

    protected $fillable = [
        'pengajuan_surat_id',
        'user_id',
        'body',
    ];

    /**
     * Mendefinisikan relasi: Satu komentar dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi: Satu komentar dimiliki oleh satu PengajuanSurat.
     */
    public function pengajuanSurat()
    {
        return $this->belongsTo(PengajuanSurat::class);
    }
}