<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    /**
     * Menampilkan form untuk membuat pengajuan surat baru.
     */
    public function create()
    {
        // Ambil semua jenis surat dari database untuk ditampilkan di dropdown
        $jenisSurats = JenisSurat::orderBy('nama_surat', 'asc')->get();

        // Tampilkan view form dan kirim data jenis surat ke dalamnya
        return view('surat.create', compact('jenisSurats'));
    }

    /**
     * Menyimpan pengajuan surat baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form untuk memastikan data yang masuk benar
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        // 2. Buat dan simpan data pengajuan surat baru
        PengajuanSurat::create([
            'user_id' => Auth::id(), // Ambil ID user yang sedang login
            'jenis_surat_id' => $request->jenis_surat_id,
            'keterangan' => $request->keterangan,
            'status' => 'pending', // Status awal untuk setiap pengajuan baru
        ]);

        // 3. Alihkan pengguna kembali ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Surat berhasil diajukan! Silakan tunggu proses selanjutnya.');
    }
}

