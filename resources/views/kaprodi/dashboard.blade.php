<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kaprodi</title>
    <style>
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
    </style>
</head>

<body class="antialiased">
<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 🔍 Formulir Pencarian & Filter --}}
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 p-[1px] rounded-2xl shadow-md mb-8">
                <div class="bg-white p-6 rounded-2xl">
                    <h1 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        📋 Daftar Pengajuan Surat
                    </h1>

                    <form action="{{ route('kaprodi.dashboard') }}" method="GET"
                          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="search_nama" class="block text-sm font-semibold text-gray-700 mb-1">
                                Nama Mahasiswa
                            </label>
                            <input type="text" name="search_nama" id="search_nama"
                                   value="{{ request('search_nama') }}"
                                   placeholder="Cari nama mahasiswa..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="search_jenis" class="block text-sm font-semibold text-gray-700 mb-1">
                                Jenis Surat
                            </label>
                            <input type="text" name="search_jenis" id="search_jenis"
                                   value="{{ request('search_jenis') }}"
                                   placeholder="Cari jenis surat..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">
                                Filter Status
                            </label>
                            <select name="status" id="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div class="flex sm:col-span-2 lg:col-span-1 items-end">
                            <button type="submit"
                                    class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 rounded-lg shadow-md transition-all duration-300 transform hover:scale-[1.02] flex justify-center items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 📦 Kartu Data Pengajuan --}}
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($pengajuanSurats as $surat)
                    <div class="group bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <h2 class="text-lg font-bold text-gray-800 group-hover:text-blue-800 transition">
                                    {{ $surat->jenisSurat->nama_surat }}
                                </h2>
                                <span class="text-sm text-gray-500">{{ $surat->created_at->format('d M Y') }}</span>
                            </div>

                            <div class="space-y-1 mb-4">
                                <p class="text-gray-700 font-medium">👤 {{ $surat->user->name }}</p>
                                <p class="text-gray-600 text-sm truncate">📎
                                    @if ($surat->file_path)
                                        <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                                           class="text-blue-700 hover:text-blue-900 font-medium underline">
                                            Lihat Lampiran
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada lampiran</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                @if($surat->status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 animate-pulse">
                                        ⏳ Pending
                                    </span>
                                @elseif($surat->status == 'disetujui')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        ✅ Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        ❌ Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white border border-gray-100 rounded-2xl shadow-sm p-8 text-center text-gray-500 italic">
                        Tidak ada data yang cocok dengan filter Anda.
                    </div>
                @endforelse
            </div>

            {{-- 📄 Pagination --}}
            @if ($pengajuanSurats->hasPages())
                <div class="flex justify-end mt-8">
                    <div class="flex items-center space-x-2 bg-white border border-gray-200 rounded-full px-3 py-2 shadow-sm">
                        @if ($pengajuanSurats->onFirstPage())
                            <span class="px-3 py-1 text-gray-400 rounded-full text-sm">‹</span>
                        @else
                            <a href="{{ $pengajuanSurats->previousPageUrl() }}"
                               class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 transition">‹</a>
                        @endif

                        @foreach ($pengajuanSurats->getUrlRange(1, $pengajuanSurats->lastPage()) as $page => $url)
                            @if ($page == $pengajuanSurats->currentPage())
                                <span class="px-3 py-1 bg-blue-900 text-white rounded-full text-sm font-semibold shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 bg-white text-blue-700 rounded-full text-sm border border-blue-300 hover:bg-blue-50 transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($pengajuanSurats->hasMorePages())
                            <a href="{{ $pengajuanSurats->nextPageUrl() }}"
                               class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 transition">›</a>
                        @else
                            <span class="px-3 py-1 text-gray-400 rounded-full text-sm">›</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
</body>
</html>
