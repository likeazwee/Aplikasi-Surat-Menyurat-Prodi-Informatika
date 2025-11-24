<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'user_id',
        'jenis_surat_id',
        'keterangan',
        'status',
        'file_path',
        'approver_id',
        'tanggal_diproses',
        'extra_data',
        'is_read', // ✅ WAJIB DITAMBAHKAN DI SINI
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // 👇 INI BLOK PENTING UNTUK JSON & TIPE DATA 👇
    protected $casts = [
        'extra_data' => 'array',
        'tanggal_diproses' => 'datetime',
        'is_read' => 'boolean', // ✅ Tambahkan ini biar lebih sip (0/1 jadi false/true)
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }
}