<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanSurat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengajuan_surat'; // <-- Sesuaikan dengan nama tabel singular

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'jenis_surat_id',
        'keterangan',
        'status',
        'file_path', // <-- TAMBAHKAN BARIS INI
        'approver_id', // <-- Tambahkan ini juga untuk masa depan
        'tanggal_diproses', // <-- Tambahkan ini juga
    ];

    /**
     * Mendapatkan data user (mahasiswa) yang membuat pengajuan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendapatkan data jenis surat dari pengajuan.
     */
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    /**
     * Mendefinisikan relasi: Satu PengajuanSurat bisa memiliki banyak Komentar.
     */
    public function komentars()
    {
        return $this->hasMany(Komentar::class, 'pengajuan_surat_id');
    }
}

