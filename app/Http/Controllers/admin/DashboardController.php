<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard Admin dengan daftar pengajuan surat.
     */
    public function index(Request $request)
    {
        $searchNama = $request->input('search_nama');
        $searchJenis = $request->input('search_jenis');

        // Query 1: Dijalankan terpisah HANYA untuk menghitung total statistik
        $statusCounts = PengajuanSurat::select(
                DB::raw('count(*) as total'),
                DB::raw("sum(case when status = 'pending' then 1 else 0 end) as pending"),
                DB::raw("sum(case when status = 'disetujui' then 1 else 0 end) as disetujui"),
                DB::raw("sum(case when status = 'ditolak' then 1 else 0 end) as ditolak")
            )
            ->first();

        // Query 2: Dijalankan terpisah untuk mengambil data tabel utama
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

    public function downloadSurat(PengajuanSurat $surat)
    {
        // 1. Validasi
        if ($surat->status !== 'disetujui') {
            return redirect()->route('admin.dashboard')->with('error', 'Hanya surat yang disetujui yang bisa diunduh.');
        }

        // 2. Load semua data relasi yang dibutuhkan
        $surat->load(['user.profile', 'jenisSurat']);
        $user = $surat->user;
        $profile = $user->profile;
        $jenisSurat = $surat->jenisSurat;

        // 3. Tentukan path template 
        $templateFileName = $jenisSurat->template_file; 
        
        if (empty($templateFileName)) {
            return redirect()->route('admin.dashboard')->with('error', 'Template DOCX untuk jenis surat ini belum diatur di database.');
        }
        
        $templatePath = storage_path('app/templates/' . $templateFileName); 
        
        if (!Storage::disk('local')->exists('templates/' . $templateFileName)) {
             return redirect()->route('admin.dashboard')->with('error', "File template '{$templateFileName}' tidak ditemukan di storage.");
        }

        // 4. Proses template
        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            // Ambil tanggal diproses, format ke Bahasa Indonesia
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

            // B. Siapkan data dinamis (dari form manual)
            $extraData = $surat->extra_data ?? [];

            // C. Gabungkan data. Data dari $extraData (input manual) AKAN MENIMPA data standar.
            // Ini MEMASTIKAN input manual ${npm} digunakan.
            $finalData = array_merge($data, $extraData);

            // D. Isi template dengan data final
            foreach ($finalData as $key => $value) {
                // Cek jika valuenya adalah tanggal
                if (in_array($key, ['tanggal_ujian', 'tanggal_mulai_magang', 'tanggal_selesai_magang', 'tanggal_peminjaman', 'tanggal_seminar_hasil', 'tanggal_batas_skripsi'])) {
                    try {
                        $templateProcessor->setValue($key, Carbon::parse($value)->locale('id')->translatedFormat('d F Y'));
                    } catch (\Exception $e) {
                        $templateProcessor->setValue($key, $value ?? '');
                    }
                } else {
                    // Isi biasa
                    $templateProcessor->setValue($key, $value ?? '');
                }

                // KHUSUS UNTUK NPM/NIM: Pastikan kedua placeholder terisi
                if ($key == 'npm') {
                    $templateProcessor->setValue('nim', $value ?? '');
                }
                if ($key == 'nim') {
                    $templateProcessor->setValue('npm', $value ?? '');
                }
            }
            
            // 5. Generate nama file output
            $safeNamaSurat = preg_replace('/[^A-Za-z0-9\-]/', '_', $jenisSurat->nama_surat);
            $safeNamaMhs = preg_replace('/[^A-Za-z0-9\-]/', '_', $user->name);
            $fileName = 'Surat_' . $safeNamaSurat . '_' . $safeNamaMhs . '.docx';
            
            // 6. Simpan file sementara
            $tempFilePath = storage_path('app/temp/' . $fileName);
            $templateProcessor->saveAs($tempFilePath);

            // 7. Kirim file sebagai download response ke user, lalu hapus file sementaranya
            return response()->download($tempFilePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat memproses template (cth: placeholder tidak ditemukan)
            report($e); 
            return redirect()->route('admin.dashboard')->with('error', 'Terjadi kesalahan saat membuat surat: ' . $e->getMessage());
        }
    }
}