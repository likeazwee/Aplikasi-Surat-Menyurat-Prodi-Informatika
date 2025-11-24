@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">

    {{-- 1. Statistik Ringkas --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col">
            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total</span>
            <span class="text-3xl font-bold text-blue-600">{{ $statusCounts->total }}</span>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col">
            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Pending</span>
            <span class="text-3xl font-bold text-yellow-500">{{ $statusCounts->pending }}</span>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col">
            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Disetujui</span>
            <span class="text-3xl font-bold text-green-500">{{ $statusCounts->disetujui }}</span>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col">
            <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">Ditolak</span>
            <span class="text-3xl font-bold text-red-500">{{ $statusCounts->ditolak }}</span>
        </div>
    </div>

    {{-- 2. BAGIAN: MENUNGGU PERSETUJUAN (PENDING) --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-1 h-6 bg-yellow-500 rounded-full"></div>
            <h2 class="text-xl font-bold text-gray-800">Menunggu Persetujuan</h2>
            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full">
                {{ $suratPending->count() }}
            </span>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($suratPending as $surat)
                @php
                    // 🔥 LOGIKA WARNA KARTU 🔥
                    // Jika belum dibaca: Merah Muda. Jika sudah: Putih.
                    $isUnread = !$surat->is_read; 
                    
                    $cardClass = $isUnread 
                        ? 'bg-red-50 border-red-500 shadow-md' // Style Belum Dibaca
                        : 'bg-white border-yellow-400 shadow-sm'; // Style Sudah Dibaca (Normal)
                    
                    $badgeClass = $isUnread 
                        ? 'bg-red-100 text-red-700 animate-pulse' 
                        : 'bg-yellow-100 text-yellow-700';
                    
                    $statusText = $isUnread ? 'BARU MASUK' : 'PENDING';
                @endphp

                <div class="relative {{ $cardClass }} border-l-4 rounded-xl hover:shadow-lg transition border-y border-r border-gray-200 overflow-hidden group">
                    
                    {{-- 🔴 Indikator Titik Merah (Ping Animation) untuk Surat Baru --}}
                    @if($isUnread)
                        <span class="absolute top-3 right-3 flex h-3 w-3 z-20 pointer-events-none">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                        </span>
                    @endif

                    {{-- Link ke Detail --}}
                    <a href="{{ route('surat.show', $surat->id) }}" class="block p-5 hover:opacity-90 transition cursor-pointer">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-gray-800 text-lg group-hover:text-blue-600 transition">{{ $surat->jenisSurat->nama_surat }}</h3>
                            <span class="{{ $badgeClass }} text-[10px] font-bold px-2 py-1 rounded uppercase">
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-800">{{ $surat->user->name }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <span>{{ $surat->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="mt-2 text-xs text-blue-500 font-medium flex items-center gap-1">
                                👁️ Klik untuk cek surat
                            </div>
                        </div>
                    </a>

                    {{-- Tombol Aksi Cepat --}}
                    <div class="px-5 pb-5 grid grid-cols-2 gap-3 relative z-10">
                        <form action="{{ route('admin.surat.approve', $surat->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="w-full bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 font-semibold py-2 rounded-lg text-xs transition">
                                ✓ Setujui
                            </button>
                        </form>
                        <form action="{{ route('admin.surat.reject', $surat->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="w-full bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 font-semibold py-2 rounded-lg text-xs transition">
                                ✕ Tolak
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-green-50 border border-green-100 p-6 rounded-xl text-center">
                    <p class="text-green-800 font-medium text-sm">🎉 Tidak ada pengajuan pending. Semua aman!</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- 3. BAGIAN: RIWAYAT SURAT (DISETUJUI & DITOLAK) --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="w-1 h-6 bg-blue-600 rounded-full"></div>
            <h2 class="text-xl font-bold text-gray-800">Riwayat Surat</h2>
        </div>

        {{-- Filter Form --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form id="filterForm" method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col sm:flex-row gap-4">
                <input type="text" name="search_nama" value="{{ request('search_nama') }}" placeholder="Cari nama mahasiswa..." 
                       class="flex-1 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                
                <select name="status" onchange="document.getElementById('filterForm').submit()" 
                        class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                    <option value="">Semua Riwayat</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    Cari
                </button>
            </form>
        </div>

        {{-- Grid Riwayat --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($riwayatSurat as $surat)
                <div class="bg-[#1e3a8a] rounded-xl shadow-sm border border-blue-900 hover:shadow-md transition overflow-hidden flex flex-col h-full group relative">
                    
                    <a href="{{ route('surat.show', $surat->id) }}" class="block p-5 flex-1 text-white hover:bg-blue-800 transition cursor-pointer">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-white text-lg group-hover:text-blue-200 transition">{{ $surat->jenisSurat->nama_surat }}</h3>
                            @if($surat->status == 'disetujui')
                                <span class="bg-green-400 text-green-900 text-[10px] font-bold px-2 py-1 rounded uppercase">Selesai</span>
                            @else
                                <span class="bg-red-400 text-red-900 text-[10px] font-bold px-2 py-1 rounded uppercase">Ditolak</span>
                            @endif
                        </div>
                        <p class="text-sm text-blue-100 mb-4">Diajukan oleh <span class="font-semibold text-white">{{ $surat->user->name }}</span></p>
                        
                        <div class="text-xs text-blue-200 space-y-1 opacity-80">
                            <p>Diajukan: {{ $surat->created_at->format('d M Y') }}</p>
                            <p>Diproses: {{ $surat->updated_at->format('d M Y') }}</p>
                            <p class="mt-2 text-yellow-300 font-semibold underline decoration-dashed">👉 Klik untuk lihat detail</p>
                        </div>
                    </a>
                    
                    @if($surat->status == 'disetujui')
                        <div class="bg-blue-900/50 p-3 border-t border-blue-800 relative z-10">
                            <a href="{{ route('admin.surat.download', $surat->id) }}" class="flex items-center justify-center gap-2 w-full text-white text-sm font-semibold hover:text-blue-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Surat
                            </a>
                        </div>
                    @else
                         <div class="bg-red-900/20 p-3 border-t border-red-900/30 text-center relative z-10">
                            <span class="text-red-200 text-xs font-medium">Surat telah ditolak</span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-gray-400 italic bg-white rounded-xl border border-gray-100">
                    Tidak ada riwayat surat yang cocok dengan filter.
                </div>
            @endforelse
        </div>
    </div>

    {{-- 4. Pagination --}}
    @if ($riwayatSurat->hasPages())
        <div class="flex justify-end">
            {{ $riwayatSurat->links() }}
        </div>
    @endif

    {{-- 5. Chart Grafik --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Bulanan</h3>
        <div class="relative h-72 w-full">
            <canvas id="suratChart"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('suratChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: { 
                labels: @json($bulanLabels ?? []), 
                datasets: @json($chartDatasets ?? []) 
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    });
</script>
@endpush