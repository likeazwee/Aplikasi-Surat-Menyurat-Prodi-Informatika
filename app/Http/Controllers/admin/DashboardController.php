<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\JenisSurat; // ✅ Import Model JenisSurat (PENTING)
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
        // 1. Ambil Input Filter
        $searchNama = $request->input('search_nama');
        $status = $request->input('status'); // Filter status dari dropdown

        // 2. Query Statistik (Total, Pending, Disetujui, Ditolak)
        $statusCounts = PengajuanSurat::select(
                DB::raw('count(*) as total'),
                DB::raw("sum(case when status = 'pending' then 1 else 0 end) as pending"),
                DB::raw("sum(case when status = 'disetujui' then 1 else 0 end) as disetujui"),
                DB::raw("sum(case when status = 'ditolak' then 1 else 0 end) as ditolak")
            )->first();

        // 3. Query KHUSUS PENDING (Tidak boleh terpengaruh filter apapun)
        // Ini agar bagian atas "Menunggu Persetujuan" selalu muncul
        $suratPending = PengajuanSurat::with(['user.profile', 'jenisSurat'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // 4. Query RIWAYAT SURAT (Bisa difilter)
        // Ini untuk bagian bawah dashboard "Riwayat Surat"
        $riwayatSurat = PengajuanSurat::with(['user.profile', 'jenisSurat'])
            ->where('status', '!=', 'pending') // Hanya ambil yg BUKAN pending (Disetujui/Ditolak)
            ->when($searchNama, function ($query, $searchNama) {
                return $query->whereHas('user', function ($q) use ($searchNama) {
                    $q->where('name', 'like', "%{$searchNama}%");
                });
            })
            ->when($status, function ($query, $status) {
                // Jika user memilih filter status tertentu (misal: Disetujui/Ditolak)
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(9); // Pagination khusus riwayat

        $riwayatSurat->appends($request->all());

        // 5. LOGIKA UNTUK CHART STATISTIK
        $semuaJenisSurat = JenisSurat::all();
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartDatasets = [];
        $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#6366f1'];
        
        foreach ($semuaJenisSurat as $index => $jenis) {
            $dataPerBulan = [];
            for ($m = 1; $m <= 12; $m++) {
                $count = PengajuanSurat::where('jenis_surat_id', $jenis->id)
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', $m)
                    ->count();
                $dataPerBulan[] = $count;
            }
            $chartDatasets[] = [
                'label' => $jenis->nama_surat,
                'data' => $dataPerBulan,
                'borderColor' => $colors[$index % count($colors)],
                'backgroundColor' => $colors[$index % count($colors)],
                'fill' => false,
                'tension' => 0.3
            ];
        }

        // Kirim data ke View
        return view('admin.dashboard', compact(
            'suratPending', 
            'riwayatSurat', 
            'statusCounts', 
            'chartDatasets', 
            'bulanLabels'
        ));
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