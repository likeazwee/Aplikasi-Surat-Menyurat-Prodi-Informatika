<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            // Default 0 (false) artinya Belum Dibaca
            // Diletakkan setelah kolom 'status' agar rapi di database
            $table->boolean('is_read')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
};