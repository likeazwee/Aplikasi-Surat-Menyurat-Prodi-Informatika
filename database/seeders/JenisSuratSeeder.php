<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Jangan lupa import DB Facade

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_surat')->insert([
            ['nama_surat' => 'Surat Pengantar PPL'],
            ['nama_surat' => 'Surat Pengantar Kerja Praktek'],
            ['nama_surat' => 'Surat Pengantar Izin Penelitian Skripsi'],
            ['nama_surat' => 'Surat Pengantar Pengambilan Data Skripsi'],
            ['nama_surat' => 'Surat Peminjaman Ruang Seminar Hasil/Seminar Proposal'],
            ['nama_surat' => 'Surat Perubahan Nilai'],
            ['nama_surat' => 'Surat Pergantian PU1/PU2/Penguji 1/Penguji 2'],
            ['nama_surat' => 'Surat Cuti Mahasiswa'],
            ['nama_surat' => 'Surat Pengantar Mahasiswa Magang Mandiri'],
            ['nama_surat' => 'Surat Pengunduran Diri Mahasiswa'],
            ['nama_surat' => 'Surat Pindah Kuliah'],
            ['nama_surat' => 'Surat Perjanjian Mahasiswa'],
            ['nama_surat' => 'Surat Pernyataan Mahasiswa'],
            ['nama_surat' => 'Surat Remedial'],
            ['nama_surat' => 'Surat Terlambat Bayar UKT'],
            ['nama_surat' => 'Surat Pengajuan UKT 50% dan 100%'],
            ['nama_surat' => 'Laporan Yudisium Koordinator Prodi'],
            ['nama_surat' => 'Surat Pengantar Tes TOEFL, Excel dan Word'],
            ['nama_surat' => 'Surat Pengantar Wisudawan dan Input Nilai'],
            ['nama_surat' => 'Surat Keterangan Lulus'],
        ]);
    }
}