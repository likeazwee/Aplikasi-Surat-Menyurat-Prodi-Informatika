<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Halaman dashboard utama mahasiswa
    public function index(Request $request)
    {
        // Ambil filter status dari query string, default 'all'
        $status = $request->get('status', 'all');

        // Query dasar: ambil pengajuan surat milik user yang login dengan relasi jenisSurat
        $query = PengajuanSurat::with('jenisSurat')
            ->where('user_id', Auth::id());

        // Jika filter status tidak 'all', tambahkan kondisi where
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Ambil data dengan pagination (12 per halaman), urut berdasarkan tanggal terbaru
        $pengajuanSurats = $query
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->appends(['status' => $status]); // supaya query status tetap terbawa saat pagination

        // Statistik jumlah surat berdasarkan status
        $totalSurat = PengajuanSurat::where('user_id', Auth::id())->count();
        $disetujui = PengajuanSurat::where('user_id', Auth::id())->where('status', 'disetujui')->count();
        $ditolak = PengajuanSurat::where('user_id', Auth::id())->where('status', 'ditolak')->count();
        $menunggu = PengajuanSurat::where('user_id', Auth::id())->where('status', 'pending')->count();

        // Kirim data ke view 'dashboard.blade.php'
        return view('dashboard', compact(
            'pengajuanSurats',
            'totalSurat',
            'disetujui',
            'ditolak',
            'menunggu',
            'status'
        ));
    }

    // Halaman riwayat pengajuan surat mahasiswa (terpisah)
    public function riwayat(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = PengajuanSurat::with('jenisSurat')
            ->where('user_id', auth()->id());

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $pengajuanSurats = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->appends(['status' => $status]);

        $totalSurat = PengajuanSurat::where('user_id', auth()->id())->count();
        $disetujui = PengajuanSurat::where('user_id', auth()->id())->where('status', 'disetujui')->count();
        $ditolak = PengajuanSurat::where('user_id', auth()->id())->where('status', 'ditolak')->count();
        $menunggu = PengajuanSurat::where('user_id', auth()->id())->where('status', 'pending')->count();

        // **PERBAIKAN PENTING: sesuaikan dengan folder view yang benar**
        return view('surat.riwayat', compact(
            'pengajuanSurats', 'totalSurat', 'disetujui', 'ditolak', 'menunggu', 'status'
        ));
    }
}
