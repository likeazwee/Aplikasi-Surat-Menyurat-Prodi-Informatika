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
        'extra_data', // <-- INI YANG DITAMBAHKAN
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // 👇 INI BLOK PENTING UNTUK JSON 👇
    protected $casts = [
        'extra_data' => 'array',
        'tanggal_diproses' => 'datetime',
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
        // Pastikan nama model Komentar sudah benar
        return $this->hasMany(Komentar::class, 'pengajuan_surat_id'); 
    }
}