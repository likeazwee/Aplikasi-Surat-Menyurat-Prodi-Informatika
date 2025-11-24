<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratDetailController extends Controller
{
    public function show(PengajuanSurat $surat)
    {
        $user = Auth::user();

        // --- TAMBAHAN BARU: TANDAI SUDAH DIBACA ---
        // Jika yang membuka adalah Admin/Kaprodi DAN surat masih status unread (0)
        // Maka ubah jadi read (1)
        if (in_array($user->role, ['admin', 'kaprodi']) && !$surat->is_read) {
            $surat->update(['is_read' => true]);
        }
        // ------------------------------------------

        // ... (Sisa kode lama Anda tetap sama di bawah ini) ...
        
        $layout = in_array($user->role, ['admin', 'kaprodi']) ? 'layouts.admin' : 'layouts.app';

        if ($user->role == 'mahasiswa' && $surat->user_id != $user->id) {
            abort(403, 'AKSES DITOLAK.');
        }

        $surat->load(['user.profile', 'jenisSurat', 'komentars.user']);

        return view('surat.show', compact('surat', 'layout'));
    }

    public function storeKomentar(Request $request, PengajuanSurat $surat)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Validasi keamanan lagi (biar mahasiswa iseng gak bisa nembak API komentar ke surat orang lain)
        if ($user->role == 'mahasiswa' && $surat->user_id != $user->id) {
            abort(403, 'Tidak bisa mengomentari surat ini.');
        }

        // Simpan Komentar
        // Pastikan Model Komentar sudah dibuat seperti langkah no 1
        $surat->komentars()->create([
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Komentar terkirim.');
    }
}