<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanSurat::with('user', 'jenisSurat');

        // 🔍 Filter nama mahasiswa
        if ($request->filled('search_nama')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_nama . '%');
            });
        }

        // 📨 Filter jenis surat
        if ($request->filled('search_jenis')) {
            $query->whereHas('jenisSurat', function ($q) use ($request) {
                $q->where('nama_surat', 'like', '%' . $request->search_jenis . '%');
            });
        }

        // ⚙️ Filter status surat
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination 12 data per halaman
        $pengajuanSurats = $query->latest()->paginate(12);

        // ✅ Tambahkan appends agar pagination tetap bawa filter
        $pengajuanSurats->appends($request->all());

        return view('kaprodi.dashboard', [
            'pengajuanSurats' => $pengajuanSurats,
        ]);
    }
}
