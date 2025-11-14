<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    public function create()
    {
        $jenisSurats = JenisSurat::orderBy('nama_surat', 'asc')->get();

        // Syarat Dokumen (masih rancu ditambahkan atau tidak)
        $syaratDokumen = [
            '3'  => ['Scan Kartu Tanda Mahasiswa (KTM)', 'Scan Kartu Rencana Studi (KRS) Terbaru'],
            '6'  => ['Scan KTM', 'Outline Skripsi/Proposal KP/Proposal PPL yang Disetujui'],
            '7'  => ['Scan KTM', 'Proposal Magang (jika ada)'],
            '15' => ['Bukti pembayaran UKT semester sebelumnya'],
            '16' => ['Scan KTM', 'Transkrip Nilai Sementara', 'Bukti Lulus Ujian Komprehensif'],
        ];

        // Dokumentasi Dynamic Fields (SEMUA npm DIGANTI nim)
        $dynamicFields = [
            '1' => [ // Perubahan Nilai
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'nama_matakuliah', 'label' => 'Nama Mata Kuliah', 'type' => 'text'],
                ['name' => 'kode_matakuliah', 'label' => 'Kode Mata Kuliah', 'type' => 'text'],
                ['name' => 'sks', 'label' => 'SKS', 'type' => 'number'],
                ['name' => 'nama_dosen_matakuliah', 'label' => 'Dosen Pengampu', 'type' => 'text'],
                ['name' => 'nilai_lama', 'label' => 'Nilai Lama', 'type' => 'text'],
                ['name' => 'nilai_baru', 'label' => 'Nilai Baru', 'type' => 'text'],
            ],
            '2' => [ // Input Nilai
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'nilai', 'label' => 'Nilai Skripsi', 'type' => 'text'],
            ],
            '3' => [ // Keterlambatan SPP
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
            ],
            '4' => [ // Peminjaman Ruangan
                ['name' => 'nama_kegiatan', 'label' => 'Nama Kegiatan', 'type' => 'text'],
                ['name' => 'tanggal_peminjaman', 'label' => 'Tanggal Peminjaman', 'type' => 'date'],
                ['name' => 'waktu_mulai', 'label' => 'Waktu Mulai', 'type' => 'text'],
                ['name' => 'waktu_selesai', 'label' => 'Waktu Selesai', 'type' => 'text'],
            ],
            '5' => [ // Pengajuan Cuti
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'semester_cuti', 'label' => 'Semester Cuti', 'type' => 'text'],
            ],
            '6' => [ // Pengambilan Data
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
            ],
            '7' => [ // Pengantar Magang
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'nama_instansi', 'label' => 'Nama Instansi', 'type' => 'text'],
                ['name' => 'tanggal_mulai_magang', 'label' => 'Tanggal Mulai', 'type' => 'date'],
                ['name' => 'tanggal_selesai_magang', 'label' => 'Tanggal Selesai', 'type' => 'date'],
            ],
            '8' => [ // Pengantar TOEFL
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'tanggal_seminar_hasil', 'label' => 'Tanggal Seminar Hasil', 'type' => 'date'],
                ['name' => 'periode_wisuda', 'label' => 'Periode Wisuda', 'type' => 'text'],
            ],
            '9' => [ // Pengunduran Diri
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'semester', 'label' => 'Semester', 'type' => 'number'],
            ],
            '10' => [ // Surat Perjanjian
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'tanggal_awal', 'label' => 'Tanggal Mulai', 'type' => 'date'],
                ['name' => 'tanggal_akhir', 'label' => 'Tanggal Akhir (Batas)', 'type' => 'date'],
            ],
            '11' => [ // Surat Pernyataan
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
            ],
            '12' => [ // Pindah Kuliah
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'semester', 'label' => 'Semester', 'type' => 'number'],
                ['name' => 'prodi_tujuan', 'label' => 'Prodi Tujuan', 'type' => 'text'],
            ],
            '13' => [ // Remedial
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'nama_matakuliah', 'label' => 'Nama Mata Kuliah', 'type' => 'text'],
                ['name' => 'kode_matakuliah', 'label' => 'Kode Mata Kuliah', 'type' => 'text'],
                ['name' => 'sks', 'label' => 'SKS', 'type' => 'number'],
                ['name' => 'dosen_remedial', 'label' => 'Dosen Remedial', 'type' => 'text'], // TAMBAHAN BARU
            ],
            '14' => [ // Usulan UKT
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'semester', 'label' => 'Semester Pengajuan', 'type' => 'text'],
                ['name' => 'jumlah_sks_diambil', 'label' => 'SKS Diambil', 'type' => 'number'],
            ],
            '15' => [ // SKL
                ['name' => 'nim', 'label' => 'NPM / NIM', 'type' => 'text'],
                ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select'],
                ['name' => 'judul_skripsi', 'label' => 'Judul Skripsi', 'type' => 'textarea'],
                ['name' => 'pembimbing_1', 'label' => 'Pembimbing 1', 'type' => 'text'],
                ['name' => 'pembimbing_2', 'label' => 'Pembimbing 2', 'type' => 'text'],
                ['name' => 'tanggal_ujian', 'label' => 'Tanggal Ujian', 'type' => 'date'],
            ],
        ];

        return view('surat.create', compact('jenisSurats', 'syaratDokumen', 'dynamicFields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'keterangan'     => 'nullable|string|max:1000',
            'lampiran'       => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        switch ($request->jenis_surat_id) {
            case '1': // Perubahan Nilai
                $request->validate([
                    'extra_data.nim'                => 'required|string',
                    'extra_data.nama_matakuliah'    => 'required|string',
                    'extra_data.kode_matakuliah'    => 'required|string',
                    'extra_data.sks'                => 'required|numeric',
                ]);
                break;
            case '2': // Input Nilai
                $request->validate([
                    'extra_data.nim'   => 'required|string',
                    'extra_data.nilai' => 'required|string'
                ]);
                break;
            case '3': // Keterlambatan SPP
                $request->validate(['extra_data.nim' => 'required|string']);
                break;
            case '4': // Peminjaman Ruangan
                $request->validate([
                    'extra_data.nama_kegiatan'      => 'required|string',
                    'extra_data.tanggal_peminjaman' => 'required|date'
                ]);
                break;
            case '5': // Cuti
                $request->validate([
                    'extra_data.nim'           => 'required|string',
                    'extra_data.semester_cuti' => 'required|string'
                ]);
                break;
            case '6': // Pengambilan Data
                $request->validate([
                    'extra_data.nim'          => 'required|string',
                    'extra_data.tujuan_surat' => 'required|string'
                ]);
                break;
            case '7': // Magang
                $request->validate([
                    'extra_data.nim'           => 'required|string',
                    'extra_data.nama_instansi' => 'required|string'
                ]);
                break;
            case '8': // TOEFL
                $request->validate(['extra_data.nim' => 'required|string']);
                break;
            case '9': // Pengunduran Diri
                $request->validate(['extra_data.nim' => 'required|string']);
                break;
            case '10': // Surat Perjanjian
                $request->validate([
                    'extra_data.nim'           => 'required|string',
                    'extra_data.tanggal_awal'  => 'required|date',  // Sesuai placeholder ${tanggal_awal}
                    'extra_data.tanggal_akhir' => 'required|date',  // Sesuai placeholder ${tanggal_akhir}
                ], [
                    'extra_data.nim.required'           => 'NIM wajib diisi.',
                    'extra_data.tanggal_awal.required'  => 'Tanggal awal wajib diisi.',
                    'extra_data.tanggal_akhir.required' => 'Tanggal akhir wajib diisi.',
                ]);
                break;
            case '11': // Pernyataan
                $request->validate(['extra_data.nim' => 'required|string']);
                break;
            case '12': // Pindah Kuliah
                $request->validate([
                    'extra_data.nim'      => 'required|string',
                    'extra_data.semester' => 'required|numeric'
                ]);
                break;
            case '13': // Remedial
                $request->validate([
                    'extra_data.nim'                => 'required|string',
                    'extra_data.nama_matakuliah'    => 'required|string',
                    'extra_data.kode_matakuliah'    => 'required|string',
                    'extra_data.sks'                => 'required|numeric',
                    'extra_data.dosen_remedial'     => 'required|string', // TAMBAHAN BARU
                ]);
                break;
            case '14': // Usulan UKT
                $request->validate([
                    'extra_data.nim'      => 'required|string',
                    'extra_data.semester' => 'required|string'
                ]);
                break;
            case '15': // SKL
                $request->validate([
                    'extra_data.nim'           => 'required|string',
                    'extra_data.jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                    'extra_data.judul_skripsi' => 'required|string',
                    'extra_data.pembimbing_1'  => 'required|string',
                    'extra_data.pembimbing_2'  => 'required|string',
                    'extra_data.tanggal_ujian' => 'required|date',
                ]);
                break;
        }

        $filePath = null;
        if ($request->hasFile('lampiran')) {
            $filePath = $request->file('lampiran')->store('lampiran_surat', 'public');
        }

        PengajuanSurat::create([
            'user_id'        => Auth::id(),
            'jenis_surat_id' => $request->jenis_surat_id,
            'keterangan'     => $request->keterangan,
            'file_path'      => $filePath,
            'status'         => 'pending',
            'extra_data'     => $request->extra_data,
        ]);

        return redirect()->route('dashboard')->with('success', 'Surat berhasil diajukan dan sedang menunggu persetujuan.');
    }
}