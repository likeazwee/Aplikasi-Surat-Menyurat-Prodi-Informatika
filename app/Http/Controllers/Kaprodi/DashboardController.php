<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk Kaprodi.
     */
    public function index(Request $request)
    {
        // Memulai query builder dengan relasi yang dibutuhkan
        $query = PengajuanSurat::with('user', 'jenisSurat');

        // Filter berdasarkan nama mahasiswa jika ada input
        if ($request->filled('search_nama')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_nama . '%');
            });
        }

        // Filter berdasarkan jenis surat jika ada input
        if ($request->filled('search_jenis')) {
            $query->whereHas('jenisSurat', function ($q) use ($request) {
                $q->where('nama_surat', 'like', '%' . $request->search_jenis . '%');
            });
        }

        // Mengambil hasil query, diurutkan berdasarkan yang terbaru
        $pengajuanSurats = $query->latest()->get();

        // Mengirim data surat dan juga input pencarian (jika ada) ke view
        return view('kaprodi.dashboard', [
            'pengajuanSurats' => $pengajuanSurats,
        ]);
    }
}