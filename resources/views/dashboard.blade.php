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

        /* Efek Hover Modern untuk Navbar */
        .navbar-link:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* Styling untuk Lampiran Button */
        .lampiran-button {
            @apply inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm font-medium rounded-full shadow hover:bg-blue-600 transition-all duration-200;
        }

        /* Animasi untuk baris tabel */
        @keyframes fade-in-row {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-row {
            animation: fade-in-row 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="antialiased">
    <x-app-layout>
        <x-slot name="header">
            {{-- Bagian header baru Anda sudah bagus, tidak perlu diubah --}}
            <div class="flex justify-between items-center bg-gradient-to-r from-blue-900 to-blue-700 p-6 rounded-b-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <!-- Bagian Kiri: Judul Halaman -->
                <div class="flex items-center pl-4 text-left w-full md:w-auto">
                    <svg class="h-6 w-6 text-blue-100 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6m1 0l-2 2m2-2l2-2" />
                    </svg>
                    <h2 class="font-semibold text-xl text-blue-100 leading-tight navbar-link">
                        {{ __('Dashboard') }}
                    </h2>
                </div>
                
                <!-- Bagian Kanan: Tombol Ajukan Surat Baru -->
                <a href="{{ route('surat.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 text-white font-semibold text-xs rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                    Ajukan Surat Baru
                </a>
            </div>
        </x-slot>

        <div class="relative min-h-screen animated-background overflow-hidden" x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true; }, 300)">
            <div class="absolute inset-0 opacity-50"></div>
            <div class="relative z-10 py-12" x-show="isLoaded" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                    <!-- Menampilkan pesan sukses -->
                    @if (session('success'))
                        <div x-data="{ showSuccess: true }" x-show="showSuccess" 
                             x-transition:enter="transition ease-out duration-500" 
                             x-transition:enter-start="opacity-0 -translate-y-5" 
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded-xl shadow-md relative transition-all duration-300 hover:shadow-lg">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <strong class="font-bold text-blue-800">Sukses!</strong>
                                <span class="ml-2 text-blue-700">{{ session('success') }}</span>
                            </div>
                            <button @click="showSuccess = false" class="absolute top-2 right-3 text-blue-500 hover:text-blue-700 transition-colors duration-200">✕</button>
                        </div>
                    @endif

                    <!-- Card Statistik (Logika Anda sudah benar, ini akan berfungsi otomatis) -->
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="p-4 bg-gradient-to-br from-blue-800 to-blue-600 text-white rounded-xl shadow-lg transform hover:scale-105 transition-all duration-500 hover:shadow-xl flex-1 min-w-[200px]">
                            <h3 class="text-md font-semibold flex items-center gap-2">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3" />
                                </svg>
                                Total Surat
                            </h3>
                            <p class="text-2xl font-bold mt-1">{{ $pengajuanSurats->count() }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-xl shadow-lg transform hover:scale-105 transition-all duration-500 hover:shadow-xl flex-1 min-w-[200px]">
                            <h3 class="text-md font-semibold flex items-center gap-2">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Disetujui
                            </h3>
                            <p class="text-2xl font-bold mt-1">{{ $pengajuanSurats->where('status', 'disetujui')->count() }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-600 to-blue-400 text-white rounded-xl shadow-lg transform hover:scale-105 transition-all duration-500 hover:shadow-xl flex-1 min-w-[200px]">
                            <h3 class="text-md font-semibold flex items-center gap-2">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Ditolak
                            </h3>
                            <p class="text-2xl font-bold mt-1">{{ $pengajuanSurats->where('status', 'ditolak')->count() }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-300 text-white rounded-xl shadow-lg transform hover:scale-105 transition-all duration-500 hover:shadow-xl flex-1 min-w-[200px]">
                            <h3 class="text-md font-semibold flex items-center gap-2">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Menunggu
                            </h3>
                            <p class="text-2xl font-bold mt-1">{{ $pengajuanSurats->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>

                    <!-- Tabel Riwayat dengan Lampiran yang Diperbaiki -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-500 hover:shadow-xl">
                        <div class="p-6 bg-blue-50">
                            <h3 class="text-lg font-medium text-blue-700 flex items-center gap-2">
                                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
                                        {{-- 👇 SAYA UBAH HEADERNYA MENJADI 'LAMPIRAN' AGAR COCOK 👇 --}}
                                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-blue-50">
                                    @forelse($pengajuanSurats as $surat)
                                        <tr class="hover:bg-blue-50/50 transition-all duration-300 animate-fade-in-row" style="animation-delay: {{ $loop->iteration * 100 }}ms">
                                            <td class="px-6 py-4 text-sm text-blue-600">{{ $loop->iteration }}</td>
                                            
                                            {{-- 👇 SAYA TAMBAHKAN LINK DETAIL DI SINI 👇 --}}
                                            <td class="px-6 py-4 text-sm font-medium text-blue-700">
                                                <a href="{{ route('surat.show', $surat) }}" class="text-blue-700 hover:text-blue-900 transition-colors duration-200">
                                                    {{ $surat->jenisSurat->nama_surat ?? 'Jenis Dihapus' }}
                                                </a>
                                            </td>

                                            <td class="px-6 py-4 text-sm text-blue-600">{{ $surat->created_at->format('d F Y') }}</td>
                                            <td class="px-6 py-4 text-sm">
                                                @if($surat->status == 'pending')
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 animate-pulse">Menunggu</span>
                                                @elseif($surat->status == 'disetujui')
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Disetujui</span>
                                                @else
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                @if($surat->file_path)
                                                    <a href="{{ Storage::url($surat->file_path) }}" target="_blank" class="lampiran-button flex items-center max-w-max">
                                                        {{-- 👇 SAYA UBAH IKONNYA MENJADI 'LINK' AGAR LEBIH SESUAI 👇 --}}
                                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.102 1.101" />
                                                        </svg>
                                                        Lihat File
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
        </div>
    </x-app-layout>
</body>
</html>

