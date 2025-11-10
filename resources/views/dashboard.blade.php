<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Surat</title>
    <style>
        /* Animasi Background Modern */
        .animated-background {
            background: linear-gradient(270deg, #0a192f, #1a365d, #3182ce, #0a192f);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Styling untuk tombol lampiran */
        .lampiran-button {
            @apply inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm font-medium rounded-full shadow hover:bg-blue-600 transition-all duration-200;
        }
    </style>
</head>
<body class="antialiased">
    <x-app-layout>
        <!-- BAGIAN DASHBOARD UTAMA -->
        <div class="relative min-h-screen animated-background overflow-hidden">
            <div class="absolute inset-0 opacity-50"></div>
            <div class="relative z-10 py-10 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">

                <!-- 🔹 Bagian Judul dan Tombol Ajukan Surat (Pindahan dari header) -->
                <div class="flex flex-col sm:flex-row justify-between items-center bg-gradient-to-r from-blue-800 to-blue-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center gap-2 mb-4 sm:mb-0">
                        <svg class="h-6 w-6 text-blue-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6" />
                        </svg>
                        <h2 class="text-lg sm:text-xl font-semibold">Ajukan Surat</h2>
                    </div>
                    <a href="{{ route('surat.create') }}" 
                       class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-700 to-blue-500 text-white font-semibold text-sm rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        Ajukan Surat Baru
                    </a>
                </div>

                <!-- 🔹 Statistik Surat -->
                <div class="flex flex-wrap justify-between items-center gap-4">
                    <div class="p-4 bg-gradient-to-br from-blue-800 to-blue-600 text-white rounded-xl shadow-lg flex-1 max-w-[24%]">
                        <h3 class="text-md font-semibold flex items-center gap-2">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                            </svg>
                            Total Surat
                        </h3>
                        <p class="text-2xl font-bold mt-1">{{ $pengajuanSurats->count() }}</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-xl shadow-lg flex-1 max-w-[24%]">
                        <h3 class="text-md font-semibold flex items-center gap-2">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Disetujui
                        </h3>
                        <p class="text-2xl font-bold mt-1">{{ $pengajuanSurats->where('status', 'disetujui')->count() }}</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-blue-600 to-blue-400 text-white rounded-xl shadow-lg flex-1 max-w-[24%]">
                        <h3 class="text-md font-semibold flex items-center gap-2">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Ditolak
                        </h3>
                        <p class="text-2xl font-bold mt-1">{{ $pengajuanSurats->where('status', 'ditolak')->count() }}</p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-300 text-white rounded-xl shadow-lg flex-1 max-w-[24%]">
                        <h3 class="text-md font-semibold flex items-center gap-2">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Menunggu
                        </h3>
                        <p class="text-2xl font-bold mt-1">{{ $pengajuanSurats->where('status', 'pending')->count() }}</p>
                    </div>
                </div>

                <!-- 🔹 Riwayat Surat -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-blue-50">
                        <h3 class="text-lg font-medium text-blue-700 flex items-center gap-2">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                            </svg>
                            Riwayat Pengajuan Surat Anda
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-blue-100">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Jenis Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Tanggal Pengajuan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-blue-50">
                                @forelse($pengajuanSurats as $surat)
                                    <tr class="hover:bg-blue-50 transition-all duration-300">
                                        <td class="px-6 py-4 text-sm text-blue-600">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-blue-700">{{ $surat->jenisSurat->nama_surat }}</td>
                                        <td class="px-6 py-4 text-sm text-blue-600">{{ $surat->created_at->format('d F Y') }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($surat->status == 'pending')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-200 text-blue-800">Pending</span>
                                            @elseif($surat->status == 'disetujui')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Disetujui</span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($surat->file_path)
                                                <a href="{{ Storage::url($surat->file_path) }}" target="_blank" class="lampiran-button">
                                                    📄 Lihat File
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-sm text-blue-500 text-center">Anda belum pernah mengajukan surat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>
</html>
