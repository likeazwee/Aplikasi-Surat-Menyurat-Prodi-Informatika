<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // <-- TAMBAHKAN INI. INI WAJIB.

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jenis_surat_id');

            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->text('catatan_kaprodi')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();

            // KEMBALIKAN FOREIGN KEY DI SINI
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('jenis_surat_id')->references('id')->on('jenis_surat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};