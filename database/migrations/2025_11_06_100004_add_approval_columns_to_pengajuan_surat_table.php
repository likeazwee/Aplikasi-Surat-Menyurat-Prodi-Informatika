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
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            // Kolom untuk menyimpan ID admin/kaprodi yang memproses
            $table->foreignId('approver_id')->nullable()->constrained('users')->after('user_id');
            
            // Kolom untuk menyimpan waktu surat diproses
            $table->timestamp('tanggal_diproses')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropForeign(['approver_id']);
            $table->dropColumn('approver_id');
            $table->dropColumn('tanggal_diproses');
        });
    }
};