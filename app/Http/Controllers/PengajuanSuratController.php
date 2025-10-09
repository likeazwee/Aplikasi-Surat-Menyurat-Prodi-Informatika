<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    /**
     * Menampilkan formulir untuk membuat pengajuan surat baru.
     */
    public function create()
    {
        $jenisSurats = JenisSurat::orderBy('nama_surat', 'asc')->get();

        // Data dummy untuk syarat dokumen yang diperlukan per jenis surat
        $syaratDokumen = [
            '1' => ['Scan Kartu Tanda Mahasiswa (KTM)', 'Scan Kartu Rencana Studi (KRS) Terbaru'],
            '2' => ['Scan KTM', 'Proposal Kerja Praktek yang sudah disetujui Dosen Pembimbing'],
            '3' => ['Scan KRS terbaru', 'Transkrip Nilai Sementara', 'Outline Skripsi yang Disetujui'],
            '4' => ['Formulir Pengambilan Data dari Program Studi'],
            '8' => ['Scan KTM', 'Surat Permohonan Cuti yang ditandatangani Orang Tua/Wali'],
            '15' => ['Bukti pembayaran UKT semester sebelumnya'],
        ];

        // PERBAIKAN: Kirim array PHP-nya langsung, jangan di-encode ke JSON di sini
        return view('surat.create', compact('jenisSurats', 'syaratDokumen'));
    }

    /**
     * Menyimpan pengajuan surat baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'keterangan' => 'nullable|string|max:1000',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;

        if ($request->hasFile('lampiran')) {
            $filePath = $request->file('lampiran')->store('lampiran_surat', 'public');
        }

        PengajuanSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat_id' => $request->jenis_surat_id,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Surat berhasil diajukan dan sedang menunggu persetujuan.');
    }
}