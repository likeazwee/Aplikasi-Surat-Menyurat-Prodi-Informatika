<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // Pastikan ini 'jenis_surat'
    Schema::create('jenis_surat', function (Blueprint $table) {
        $table->engine = 'InnoDB';
        $table->id();
        $table->string('nama_surat');
        $table->timestamps();
    });
}
public function down(): void
{
    // Pastikan ini juga 'jenis_surat'
    Schema::dropIfExists('jenis_surat');
}
};
