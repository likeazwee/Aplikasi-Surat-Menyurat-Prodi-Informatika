<x-app-layout>
    {{-- 🌫️ Background Animasi Silver Classy --}}
    <style>
        @keyframes shimmer {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .animated-bg {
            background: linear-gradient(
                -45deg,
                
                #b2b2b2ff,
    
                #b2adadff
            );
            background-size: 400% 400%;
            animation: shimmer 15s ease infinite;
        }

        .card-silver {
            background: linear-gradient(145deg, #2f2f2f, #3d3d3d);
            border: 1px solid #555555ff;
        }

        .card-silver:hover {
            transform: translateY(-3px);
            background: linear-gradient(145deg, #404040, #505050);
        }

        .text-silver {
            color: #e5e5e5;
        }

        .btn-silver {
            background: linear-gradient(145deg, #7f7f7f, #bcbcbc);
            color: #1a1a1a;
        }

        .btn-silver:hover {
            background: linear-gradient(145deg, #bcbcbc, #e0e0e0);
        }
    </style>

    <div class="min-h-screen animated-bg py-10 text-white transition-all duration-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 🔍 Formulir Pencarian & Filter --}}
<div class="bg-blue-900/80 backdrop-blur-md border border-blue-300/20 rounded-2xl shadow-md mb-8">
    <div class="p-6 text-white rounded-2xl">
        <form action="{{ route('kaprodi.dashboard') }}" method="GET"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            {{-- 🔎 Nama Mahasiswa --}}
            <div>
                <label for="search_nama" class="block text-sm font-semibold text-blue-100 mb-1">
                    Nama Mahasiswa
                </label>
                <input type="text" name="search_nama" id="search_nama"
                       value="{{ request('search_nama') }}"
                       placeholder="Cari nama mahasiswa..."
                       class="w-full px-4 py-2 bg-blue-950/40 border border-blue-400/30 text-white rounded-lg 
                              placeholder-blue-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            </div>

            {{-- 📨 Jenis Surat --}}
            <div>
                <label for="search_jenis" class="block text-sm font-semibold text-blue-100 mb-1">
                    Jenis Surat
                </label>
                <input type="text" name="search_jenis" id="search_jenis"
                       value="{{ request('search_jenis') }}"
                       placeholder="Cari jenis surat..."
                       class="w-full px-4 py-2 bg-blue-950/40 border border-blue-400/30 text-white rounded-lg 
                              placeholder-blue-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
            </div>

            {{-- ⚙ Filter Status --}}
            <div>
                <label for="status" class="block text-sm font-semibold text-blue-100 mb-1">
                    Filter Status
                </label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 bg-blue-950/40 border border-blue-400/30 text-white rounded-lg 
                               focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- 🔘 Tombol Cari --}}
            <div class="flex sm:col-span-2 lg:col-span-1 items-end">
                <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-lg 
                               shadow-md transition-all duration-300 transform hover:scale-[1.02]">
                    Cari
                </button>
            </div>
        </form>
    </div>
</div>


            {{-- 📦 Kartu Data Pengajuan --}}
<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($pengajuanSurats as $surat)
        <div class="group rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 
                    bg-blue-900/80 backdrop-blur-md border border-blue-300/20 hover:bg-blue-900/90">
            <div class="p-5 text-white">
                <div class="flex justify-between items-start mb-3">
                    <h2 class="text-lg font-bold text-white group-hover:text-blue-100 transition">
                        {{ $surat->jenisSurat->nama_surat }}
                    </h2>
                    <span class="text-sm text-blue-200">{{ $surat->created_at->format('d M Y') }}</span>
                </div>

                <div class="space-y-1 mb-4">
                    <p class="font-medium">👤 {{ $surat->user->name }}</p>
                    <p class="text-sm truncate">📎
                        @if ($surat->file_path)
                            <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                               class="text-blue-100 hover:text-white font-medium underline">
                                Lihat Lampiran
                            </a>
                        @else
                            <span class="text-blue-200/70 italic">Tidak ada lampiran</span>
                        @endif
                    </p>
                </div>

                {{-- 🔘 Status Surat --}}
                <div>
                    @if($surat->status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 animate-pulse">
                            ⏳ Pending
                        </span>
                    @elseif($surat->status == 'disetujui')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
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
        <div class="col-span-full bg-blue-900/40 border border-blue-700/30 rounded-2xl shadow-sm p-8 text-center text-blue-100 italic">
            Tidak ada data yang cocok dengan filter Anda.
        </div>
    @endforelse
</div>



            {{-- 📄 Pagination --}}
            @if ($pengajuanSurats->hasPages())
                <div class="flex justify-end mt-10">
                    <div class="flex items-center space-x-2 bg-[#2a2a2a]/60 border border-gray-700 rounded-full px-4 py-2 shadow-lg backdrop-blur-sm">
                        @if ($pengajuanSurats->onFirstPage())
                            <span class="px-3 py-1 text-gray-500/70 rounded-full text-sm">‹</span>
                        @else
                            <a href="{{ $pengajuanSurats->previousPageUrl() }}"
                               class="px-3 py-1 bg-gray-600 text-white rounded-full text-sm hover:bg-gray-500 transition">
                                ‹
                            </a>
                        @endif

                        @foreach ($pengajuanSurats->getUrlRange(1, $pengajuanSurats->lastPage()) as $page => $url)
                            @if ($page == $pengajuanSurats->currentPage())
                                <span class="px-3 py-1 bg-gray-400 text-black rounded-full text-sm font-semibold shadow-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 bg-[#1e1e1e] text-gray-300 rounded-full text-sm border border-gray-600 hover:bg-gray-700 transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($pengajuanSurats->hasMorePages())
                            <a href="{{ $pengajuanSurats->nextPageUrl() }}"
                               class="px-3 py-1 bg-gray-600 text-white rounded-full text-sm hover:bg-gray-500 transition">
                                ›
                            </a>
                        @else
                            <span class="px-3 py-1 text-gray-500/70 rounded-full text-sm">›</span>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
