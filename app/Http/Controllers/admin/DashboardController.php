<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk Admin beserta daftar pengajuan surat.
     */
    public function index(Request $request)
    {
        // Ambil query pencarian dari request
        $searchNama = $request->input('search_nama');
        $searchJenis = $request->input('search_jenis');

        // Mulai query untuk mengambil data pengajuan surat
        $query = PengajuanSurat::with(['user', 'jenisSurat'])->latest();

        // Terapkan filter jika ada input pencarian nama mahasiswa
        if ($searchNama) {
            $query->whereHas('user', function ($q) use ($searchNama) {
                $q->where('name', 'like', '%' . $searchNama . '%');
            });
        }

        // Terapkan filter jika ada input pencarian jenis surat
        if ($searchJenis) {
            $query->whereHas('jenisSurat', function ($q) use ($searchJenis) {
                $q->where('nama_surat', 'like', '%' . $searchJenis . '%');
            });
        }

        // Ambil data hasil query
        $pengajuanSurats = $query->get();

        // Kirim data ke view
        return view('admin.dashboard', compact('pengajuanSurats'));
    }

    /**
     * Menyetujui pengajuan surat.
     */
    public function approve($id)
    {
        $surat = PengajuanSurat::findOrFail($id);
        $surat->status = 'disetujui';
        $surat->save();

        return redirect()->route('admin.dashboard')->with('success', 'Surat berhasil disetujui.');
    }

    /**
     * Menolak pengajuan surat.
     */
    public function reject($id)
    {
        $surat = PengajuanSurat::findOrFail($id);
        $surat->status = 'ditolak';
        $surat->save();

        return redirect()->route('admin.dashboard')->with('success', 'Surat berhasil ditolak.');
    }
}