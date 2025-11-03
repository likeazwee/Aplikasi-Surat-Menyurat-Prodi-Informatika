<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratDetailController extends Controller
{
    /**
     * Menampilkan halaman detail untuk satu pengajuan surat.
     */
    public function show(PengajuanSurat $surat)
    {
        $user = Auth::user();

        // 1. Tentukan layout berdasarkan role
        $layout = 'layouts.app'; // Default layout (mahasiswa)
        if ($user->role == 'admin') {
            $layout = 'layouts.admin';
        } elseif ($user->role == 'kaprodi') {
            $layout = 'layouts.admin'; // Asumsi kaprodi pakai layout admin
        }

        // 2. Otorisasi: Memastikan hanya user yang bersangkutan atau admin/kaprodi yang bisa melihat
        if ($user->role == 'mahasiswa' && $surat->user_id != $user->id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        // 3. Ambil surat beserta relasi yang dibutuhkan
        $surat->load(['user.profile', 'jenisSurat', 'komentars.user']);

        // 4. Kirim data dan nama layout ke view
        return view('surat.show', compact('surat', 'layout'));
    }

    /**
     * Menyimpan komentar baru dari mahasiswa atau admin.
     */
    public function storeKomentar(Request $request, PengajuanSurat $surat)
    {
        // Validasi
        $request->validate([
            'body' => 'required|string',
        ]);

        // Memastikan hanya user yang bersangkutan atau admin/kaprodi yang bisa berkomentar
        $user = Auth::user();
        if ($user->role == 'mahasiswa' && $surat->user_id != $user->id) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        // Buat komentar baru
        $surat->komentars()->create([
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        return redirect()->route('surat.show', $surat)->with('success', 'Komentar berhasil dikirim.');
    }
}

