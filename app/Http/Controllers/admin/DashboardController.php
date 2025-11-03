<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // <-- Import DB Facade

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Admin dengan daftar pengajuan surat.
     */
public function index(Request $request)
{
    $searchNama = $request->input('search_nama');
    $searchJenis = $request->input('search_jenis');

    // 🔹 Query 1: Statistik (tanpa memodifikasi query utama)
    $statusCounts = PengajuanSurat::select(
        DB::raw('count(*) as total'),
        DB::raw("sum(case when status = 'pending' then 1 else 0 end) as pending"),
        DB::raw("sum(case when status = 'disetujui' then 1 else 0 end) as disetujui"),
        DB::raw("sum(case when status = 'ditolak' then 1 else 0 end) as ditolak")
    )->first();

    // 🔹 Query 2: Data utama untuk tabel
    $pengajuanSurats = PengajuanSurat::with(['user.profile', 'jenisSurat'])
        ->when($searchNama, function ($query, $searchNama) {
            return $query->whereHas('user', function($q) use ($searchNama) {
                $q->where('name', 'like', "%{$searchNama}%");
            });
        })
        ->when($searchJenis, function ($query, $searchJenis) {
            return $query->whereHas('jenisSurat', function($q) use ($searchJenis) {
                $q->where('nama_surat', 'like', "%{$searchJenis}%");
            });
        })
        ->latest()
        ->paginate(10);

    return view('admin.dashboard', compact('pengajuanSurats', 'statusCounts'));
}

    /**
     * Menyetujui pengajuan surat.
     */
    public function approve(PengajuanSurat $surat)
    {
        $surat->update(['status' => 'disetujui', 'approver_id' => auth()->id(), 'tanggal_diproses' => now()]);
        return redirect()->route('admin.dashboard')->with('success', 'Surat berhasil disetujui.');
    }

    /**
     * Menolak pengajuan surat.
     */
    public function reject(PengajuanSurat $surat)
    {
        $surat->update(['status' => 'ditolak', 'approver_id' => auth()->id(), 'tanggal_diproses' => now()]);
        return redirect()->route('admin.dashboard')->with('success', 'Surat berhasil ditolak.');
    }

    /**
     * Generate dan download surat dalam format DOCX.
     */
    public function downloadSurat(PengajuanSurat $surat)
    {
        // 1. Validasi
        if ($surat->status !== 'disetujui') {
            return redirect()->route('admin.dashboard')->with('error', 'Hanya surat yang disetujui yang bisa diunduh.');
        }

        // 2. Load data
        $surat->load(['user.profile', 'jenisSurat']);
        $user = $surat->user;
        $profile = $user->profile;
        $jenisSurat = $surat->jenisSurat;

        // 3. Tentukan path template
        $templatePath = storage_path('app/templates/surat_template.docx'); 
        if (!Storage::disk('local')->exists('templates/surat_template.docx')) {
             return redirect()->route('admin.dashboard')->with('error', 'Template surat tidak ditemukan.');
        }

        // 4. Proses template
        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            // Ganti placeholder
            $templateProcessor->setValue('nama_mahasiswa', $user->name ?? '');
            $templateProcessor->setValue('nim', $profile->nim ?? '');
            $templateProcessor->setValue('prodi', $profile->prodi ?? '');
            $templateProcessor->setValue('jenis_surat', $jenisSurat->nama_surat ?? '');
            $templateProcessor->setValue('tanggal_pengajuan', $surat->created_at->format('d F Y') ?? '');
            
            // 5. Generate nama file
            $fileName = 'Surat_' . str_replace(' ', '_', $jenisSurat->nama_surat) . '_' . str_replace(' ', '_', $user->name) . '.docx';
            $tempFilePath = storage_path('app/temp/' . $fileName);
            $templateProcessor->saveAs($tempFilePath);

            // 7. Kirim file sebagai download response
            return response()->download($tempFilePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            report($e); 
            return redirect()->route('admin.dashboard')->with('error', 'Terjadi kesalahan saat membuat surat: ' . $e->getMessage());
        }
    }
}