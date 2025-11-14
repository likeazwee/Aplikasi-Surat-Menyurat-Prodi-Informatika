<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Admin dengan daftar pengajuan surat.
     */
    public function index(Request $request)
    {
        // Ambil semua input filter dari request
        $searchNama = $request->input('search_nama');
        $searchJenis = $request->input('search_jenis');
        $status = $request->input('status'); // ✅ Tambahan untuk filter status

        // Query untuk statistik status (tidak diubah)
        $statusCounts = PengajuanSurat::select(
                DB::raw('count(*) as total'),
                DB::raw("sum(case when status = 'pending' then 1 else 0 end) as pending"),
                DB::raw("sum(case when status = 'disetujui' then 1 else 0 end) as disetujui"),
                DB::raw("sum(case when status = 'ditolak' then 1 else 0 end) as ditolak")
            )
            ->first();

        // Query utama untuk menampilkan data surat
        $pengajuanSurats = PengajuanSurat::with(['user.profile', 'jenisSurat'])
            ->when($searchNama, function ($query, $searchNama) {
                return $query->whereHas('user', function ($q) use ($searchNama) {
                    $q->where('name', 'like', "%{$searchNama}%");
                });
            })
            ->when($searchJenis, function ($query, $searchJenis) {
                return $query->whereHas('jenisSurat', function ($q) use ($searchJenis) {
                    $q->where('nama_surat', 'like', "%{$searchJenis}%");
                });
            })
            ->when($status, function ($query, $status) {
                // ✅ Tambahan: filter status
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(12);

        // ✅ Pastikan pagination tetap bawa parameter filter
        $pengajuanSurats->appends($request->all());

        return view('admin.dashboard', compact('pengajuanSurats', 'statusCounts'));
    }

    /**
     * Menyetujui pengajuan surat.
     */
    public function approve(PengajuanSurat $surat)
    {
        $surat->update([
            'status' => 'disetujui',
            'approver_id' => auth()->id(),
            'tanggal_diproses' => now()
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Surat berhasil disetujui.');
    }

    public function reject(PengajuanSurat $surat)
    {
        $surat->update([
            'status' => 'ditolak',
            'approver_id' => auth()->id(),
            'tanggal_diproses' => now()
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Surat berhasil ditolak.');
    }

    /**
     * Mengunduh surat yang sudah disetujui.
     */
    public function downloadSurat(PengajuanSurat $surat)
    {
        if ($surat->status !== 'disetujui') {
            return redirect()->route('admin.dashboard')->with('error', 'Hanya surat yang disetujui yang bisa diunduh.');
        }

        $surat->load(['user.profile', 'jenisSurat']);
        $user = $surat->user;
        $profile = $user->profile;
        $jenisSurat = $surat->jenisSurat;

        $templateFileName = $jenisSurat->template_file;
        if (empty($templateFileName)) {
            return redirect()->route('admin.dashboard')->with('error', 'Template DOCX belum diatur.');
        }

        $templatePath = storage_path('app/templates/' . $templateFileName);
        if (!Storage::disk('local')->exists('templates/' . $templateFileName)) {
            return redirect()->route('admin.dashboard')->with('error', "File template '{$templateFileName}' tidak ditemukan.");
        }

        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            $tanggalPersetujuan = $surat->tanggal_diproses
                ? $surat->tanggal_diproses->locale('id')->translatedFormat('d F Y')
                : Carbon::now()->locale('id')->translatedFormat('d F Y');

            $data = [
                'nama_mahasiswa' => $user->name ?? '',
                'jenis_kelamin' => $profile->jenis_kelamin ?? '',
                'prodi' => $profile->prodi ?? 'Informatika',
                'jenis_surat' => $jenisSurat->nama_surat ?? '',
                'tanggal_pengajuan' => $surat->created_at->format('d F Y') ?? '',
                'tempat_terbit' => 'Bengkulu',
                'tanggal_persetujuan' => $tanggalPersetujuan,
                'nim' => $profile->nim ?? '',
                'npm' => $profile->nim ?? '',
            ];

            $extraData = $surat->extra_data ?? [];
            $finalData = array_merge($data, $extraData);

            foreach ($finalData as $key => $value) {
                if (in_array($key, [
                    'tanggal_ujian', 'tanggal_mulai_magang', 'tanggal_selesai_magang',
                    'tanggal_peminjaman', 'tanggal_seminar_hasil', 'tanggal_batas_skripsi'
                ])) {
                    try {
                        $templateProcessor->setValue($key, Carbon::parse($value)->locale('id')->translatedFormat('d F Y'));
                    } catch (\Exception $e) {
                        $templateProcessor->setValue($key, $value ?? '');
                    }
                } else {
                    $templateProcessor->setValue($key, $value ?? '');
                }

                if ($key == 'npm') $templateProcessor->setValue('nim', $value ?? '');
                if ($key == 'nim') $templateProcessor->setValue('npm', $value ?? '');
            }

            $safeNamaSurat = preg_replace('/[^A-Za-z0-9\-]/', '_', $jenisSurat->nama_surat);
            $safeNamaMhs = preg_replace('/[^A-Za-z0-9\-]/', '_', $user->name);
            $fileName = 'Surat_' . $safeNamaSurat . '_' . $safeNamaMhs . '.docx';

            $tempFilePath = storage_path('app/temp/' . $fileName);
            $templateProcessor->saveAs($tempFilePath);

            return response()->download($tempFilePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            report($e);
            return redirect()->route('admin.dashboard')->with('error', 'Terjadi kesalahan saat membuat surat: ' . $e->getMessage());
        }
    }
}
