<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kaprodi</title>
    <style>
        /* Animasi Background Modern */
        .animated-background {
            background: linear-gradient(270deg, #0a192f, #1a365d, #2563eb, #0a192f);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Efek Hover Modern */
        .navbar-link:hover {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        .status-badge {
            @apply px-3 py-1 text-xs font-semibold rounded-full;
        }

        .lampiran-button {
            @apply inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm font-medium rounded-full shadow hover:bg-blue-600 transition-all duration-200;
        }
    </style>
</head>

<body class="antialiased">
<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex justify-between items-center bg-gradient-to-r from-blue-900 to-blue-700 p-6 rounded-b-xl shadow-lg transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center gap-2 text-blue-100">
                <svg class="h-6 w-6 animate-pulse-slow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6m1 0l-2 2m2-2l2-2"/>
                </svg>
                <h2 class="font-semibold text-xl navbar-link">
                    {{ __('Dashboard Kaprodi - Daftar Pengajuan Surat Mahasiswa') }}
                </h2>
            </div>

            {{-- Tombol Refresh --}}
            <a href="{{ route('kaprodi.dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-400 text-white font-semibold text-xs rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                Refresh Data
            </a>
        </div>
    </x-slot>

    {{-- BODY --}}
    <div class="relative min-h-screen animated-background overflow-hidden py-12 px-6">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Pencarian --}}
            <div class="bg-white/90 rounded-xl shadow-lg p-6 backdrop-blur-md hover:shadow-xl transition-all duration-300">
                <form action="{{ route('kaprodi.dashboard') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label for="search_nama" class="block text-sm font-medium text-blue-900">Nama Mahasiswa</label>
                            <input type="text" name="search_nama" id="search_nama" value="{{ request('search_nama') }}"
                                   class="mt-1 block w-full border border-blue-200 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400">
                        </div>
                        <div>
                            <label for="search_jenis" class="block text-sm font-medium text-blue-900">Jenis Surat</label>
                            <input type="text" name="search_jenis" id="search_jenis" value="{{ request('search_jenis') }}"
                                   class="mt-1 block w-full border border-blue-200 rounded-md shadow-sm focus:ring-blue-400 focus:border-blue-400">
                        </div>
                        <div>
                            <button type="submit"
                                    class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-700 to-blue-500 text-white font-semibold text-sm rounded-md shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                🔍 Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            

            {{-- Tabel Data --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500">
    <div class="p-6 bg-gray-100">
        <h3 class="text-lg font-medium text-gray-700 flex items-center gap-2">
            <svg class="h-6 w-6 text-gray-600 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
            </svg>
            Daftar Pengajuan Surat Mahasiswa
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Mahasiswa</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Jenis Surat</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Lampiran</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
            @forelse ($pengajuanSurats as $surat)
                <tr class="hover:bg-gray-50 transition-all duration-300">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $surat->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800 font-semibold">{{ $surat->user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $surat->jenisSurat->nama_surat }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if ($surat->file_path)
                            <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                               class="lampiran-button">
                                📎 Lihat File
                            </a>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($surat->status == 'pending')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800 animate-pulse">Pending</span>
                        @elseif($surat->status == 'disetujui')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">Disetujui</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-800">Ditolak</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">Tidak ada pengajuan surat ditemukan.</td>
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
