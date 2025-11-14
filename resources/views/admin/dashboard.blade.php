@extends('layouts.admin')

@section('header', 'Dashboard Admin')

@section('content')
<div class="py-10 text-white transition-all duration-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- 🔍 Pencarian dan Filter --}}
        <div class="bg-blue-900/70 backdrop-blur-md border border-blue-300/20 rounded-2xl shadow-md p-6">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="search_nama" class="block text-sm font-semibold text-blue-100 mb-1">Nama Mahasiswa</label>
                    <input type="text" name="search_nama" id="search_nama"
                        value="{{ request('search_nama') }}" placeholder="Cari nama mahasiswa..."
                        class="w-full px-4 py-2 bg-blue-950/40 border border-blue-400/30 text-white rounded-lg 
                               placeholder-blue-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                </div>
                <div>
                    <label for="search_jenis" class="block text-sm font-semibold text-blue-100 mb-1">Jenis Surat</label>
                    <input type="text" name="search_jenis" id="search_jenis"
                        value="{{ request('search_jenis') }}" placeholder="Cari jenis surat..."
                        class="w-full px-4 py-2 bg-blue-950/40 border border-blue-400/30 text-white rounded-lg 
                               placeholder-blue-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                </div>
                <div>
                    <label for="status" class="block text-sm font-semibold text-blue-100 mb-1">Status</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2 bg-blue-950/40 border border-blue-400/30 text-white rounded-lg focus:ring-2 focus:ring-blue-400">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="flex sm:col-span-2 lg:col-span-1 items-end">
                    <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-lg 
                               shadow-md transition-all duration-300 transform hover:scale-[1.02]">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        {{-- 📊 Statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-white">
            <div class="stat-card bg-gradient-to-br from-blue-800 to-blue-600">Total<br><span>{{ $statusCounts->total }}</span></div>
            <div class="stat-card bg-gradient-to-br from-green-700 to-green-500">Disetujui<br><span>{{ $statusCounts->disetujui }}</span></div>
            <div class="stat-card bg-gradient-to-br from-yellow-600 to-yellow-400">Pending<br><span>{{ $statusCounts->pending }}</span></div>
            <div class="stat-card bg-gradient-to-br from-red-700 to-red-500">Ditolak<br><span>{{ $statusCounts->ditolak }}</span></div>
        </div>

        {{-- 📨 Grid Surat --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($pengajuanSurats as $surat)
                <div 
                    @if($surat->status == 'pending') 
                        x-data="{ open: false }" 
                        @click="open = !open"
                        class="relative group rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 
                               bg-blue-900/80 backdrop-blur-md border border-blue-300/20 hover:bg-blue-900/90 cursor-pointer"
                    @else
                        class="relative group rounded-2xl shadow-md transition-all duration-300 
                               bg-blue-900/80 backdrop-blur-md border border-blue-300/20 opacity-90"
                    @endif
                >
                    <div class="p-5 text-white">
                        <div class="flex justify-between items-start mb-3">
                            <a href="{{ route('surat.show', $surat->id) }}" class="text-lg font-bold text-white group-hover:text-blue-100 transition">
                                {{ $surat->jenisSurat->nama_surat ?? '-' }}
                            </a>
                            <span class="text-sm text-blue-200">{{ $surat->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="space-y-1 mb-4">
                            <p class="font-medium">👤 {{ $surat->user->name ?? 'Mahasiswa' }}</p>
                            <p class="text-sm truncate">📎
                                @if ($surat->file_path)
                                    <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                                        class="text-blue-100 hover:text-white font-medium underline">Lihat Lampiran</a>
                                @else
                                    <span class="text-blue-200/70 italic">Tidak ada lampiran</span>
                                @endif
                            </p>
                        </div>

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

                    {{-- Overlay tindakan untuk surat pending --}}
                    @if($surat->status == 'pending')
                    <div x-show="open" x-transition
                        class="absolute inset-0 bg-[#0B132B]/95 flex flex-col justify-center items-center rounded-2xl space-y-4">
                        <p class="text-gray-300 text-sm">Pilih tindakan:</p>
                        <div class="flex gap-3">
                            <form action="{{ route('admin.surat.approve', $surat->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn-green">Setujui</button>
                            </form>
                            <form action="{{ route('admin.surat.reject', $surat->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn-red">Tolak</button>
                            </form>
                        </div>
                        <button @click.stop="open=false" class="text-sm text-gray-400 hover:text-gray-200">Batal</button>
                    </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full bg-blue-900/40 border border-blue-700/30 rounded-2xl shadow-sm p-8 text-center text-blue-100 italic">
                    Tidak ada data pengajuan surat.
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
                           class="px-3 py-1 bg-gray-600 text-white rounded-full text-sm hover:bg-gray-500 transition">‹</a>
                    @endif

                    @foreach ($pengajuanSurats->getUrlRange(1, $pengajuanSurats->lastPage()) as $page => $url)
                        @if ($page == $pengajuanSurats->currentPage())
                            <span class="px-3 py-1 bg-gray-400 text-black rounded-full text-sm font-semibold shadow-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 bg-[#1e1e1e] text-gray-300 rounded-full text-sm border border-gray-600 hover:bg-gray-700 transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($pengajuanSurats->hasMorePages())
                        <a href="{{ $pengajuanSurats->nextPageUrl() }}"
                           class="px-3 py-1 bg-gray-600 text-white rounded-full text-sm hover:bg-gray-500 transition">›</a>
                    @else
                        <span class="px-3 py-1 text-gray-500/70 rounded-full text-sm">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

{{-- 🌫️ Animasi Background dan Gaya Umum --}}
<style>
@keyframes shimmer {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animated-bg {
    background: linear-gradient(135deg, #0b1023, #111b3a, #1c2a4a);
    color: #f1f5f9;
}


.stat-card {
    text-align: center;
    border-radius: 1rem;
    padding: 1.25rem;
    font-weight: 600;
    box-shadow: inset 0 0 15px rgba(255,255,255,0.15);
}
.stat-card span { font-size: 1.8rem; font-weight: 700; display: block; }

/* 🔘 Tombol Aksi (Setujui & Tolak) */
.btn-green {
    background: linear-gradient(145deg, #22c55e, #16a34a);
    color: #ffffff;
    font-weight: 600;
    padding: 0.5rem 1.25rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(34,197,94,0.4);
}
.btn-green:hover {
    background: linear-gradient(145deg, #16a34a, #15803d);
    transform: scale(1.05);
    box-shadow: 0 3px 8px rgba(34,197,94,0.6);
}

.btn-red {
    background: linear-gradient(145deg, #ef4444, #dc2626);
    color: #ffffff;
    font-weight: 600;
    padding: 0.5rem 1.25rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(239,68,68,0.4);
}
.btn-red:hover {
    background: linear-gradient(145deg, #dc2626, #b91c1c);
    transform: scale(1.05);
    box-shadow: 0 3px 8px rgba(239,68,68,0.6);
}

</style>
@endsection
