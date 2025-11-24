<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    // 🔥 PENTING: Karena nama tabel di database Anda 'komentar' (bukan komentars)
    protected $table = 'komentar';

    protected $fillable = [
        'pengajuan_surat_id', 
        'user_id', 
        'body' // Sesuaikan dengan kolom di screenshot Anda ($table->text('body'))
    ];

    // Relasi ke User (Siapa yang nulis komentar)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Surat
    public function pengajuanSurat()
    {
        return $this->belongsTo(PengajuanSurat::class);
    }
}