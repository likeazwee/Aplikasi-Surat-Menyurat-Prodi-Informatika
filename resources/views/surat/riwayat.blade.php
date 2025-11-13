<x-app-layout>
    <div class="min-h-screen bg-white text-gray-800 px-6 py-10 font-sans relative overflow-x-hidden">

        <!-- 🔹 Header -->
        <div class="max-w-7xl mx-auto mb-10 text-center animate-fadeInSlow">
            <h1 class="text-5xl font-extrabold mb-3 tracking-tight text-blue-900">
                <i class="fa-solid fa-clock-rotate-left text-blue-700 mr-2"></i> Riwayat Pengajuan Surat
            </h1>
            <p class="text-gray-600 text-base">
                Pantau semua riwayat pengajuan suratmu secara 
                <span class="text-blue-700 font-semibold">real-time</span> dan terintegrasi
            </p>
        </div>

        <!-- 🔹 Statistik (Tetap Putih) -->
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-14 animate-slideIn">
            @php
                $stats = [
                    ['label' => 'Total Surat', 'count' => $totalSurat, 'color' => 'blue', 'icon' => 'fa-folder-open', 'status' => 'all'],
                    ['label' => 'Disetujui', 'count' => $disetujui, 'color' => 'green', 'icon' => 'fa-circle-check', 'status' => 'disetujui'],
                    ['label' => 'Ditolak', 'count' => $ditolak, 'color' => 'red', 'icon' => 'fa-circle-xmark', 'status' => 'ditolak'],
                    ['label' => 'Menunggu', 'count' => $menunggu, 'color' => 'yellow', 'icon' => 'fa-hourglass-half', 'status' => 'pending'],
                ];
            @endphp

            @foreach ($stats as $item)
                <a href="{{ route('surat.riwayat', ['status' => $item['status']]) }}"
                   class="group p-6 rounded-2xl bg-white border border-{{ $item['color'] }}-500/30 
                          shadow-md hover:shadow-{{ $item['color'] }}-400/40 transition-all transform hover:-translate-y-1 duration-300 
                          {{ $status === $item['status'] || ($item['status'] === 'all' && !$status) ? 'ring-2 ring-'.$item['color'].'-400' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-gray-600 font-medium group-hover:text-{{ $item['color'] }}-600 transition">
                            {{ $item['label'] }}
                        </h3>
                        <i class="fa-solid {{ $item['icon'] }} text-{{ $item['color'] }}-500 text-2xl"></i>
                    </div>
                    <p class="text-4xl font-bold text-{{ $item['color'] }}-600 mt-1 group-hover:scale-105 transition">
                        {{ $item['count'] }}
                    </p>
                </a>
            @endforeach
        </div>

        <!-- 🔹 Daftar Surat (Card Navy Profesional) -->
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($pengajuanSurats as $surat)
                <div class="p-6 rounded-2xl bg-gradient-to-br from-blue-950 to-blue-800 text-white 
                            shadow-md hover:shadow-lg transition-all transform hover:-translate-y-1 duration-300 animate-fadeIn border border-blue-900/40">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-semibold text-lg leading-tight">
                            {{ $surat->jenisSurat->nama_surat ?? 'Jenis Surat Tidak Ditemukan' }}
                        </h3>
                        <span class="px-3 py-1 rounded-full text-xs font-medium uppercase tracking-wide
                            {{ $surat->status === 'disetujui' ? 'bg-green-200 text-green-800' : 
                               ($surat->status === 'ditolak' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                            {{ ucfirst($surat->status) }}
                        </span>
                    </div>

                    <p class="text-sm text-blue-200 mb-1">📅 Tanggal Pengajuan</p>
                    <p class="font-medium mb-3">
                        {{ $surat->created_at->translatedFormat('d F Y') }}
                    </p>

                    @if ($surat->file_path)
                        <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                           class="inline-flex items-center gap-2 text-sm font-medium text-blue-200 hover:text-white transition-all">
                            <i class="fa-solid fa-paperclip"></i> Lihat File
                        </a>
                    @else
                        <p class="text-sm text-blue-300 italic">Tidak ada file</p>
                    @endif
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-400 py-10 text-lg">
                    <i class="fa-solid fa-inbox text-3xl mb-2"></i><br>
                    Belum ada surat untuk kategori ini.
                </div>
            @endforelse
        </div>

        <!-- 🔹 Pagination -->
        @if ($pengajuanSurats->hasPages())
            <div class="mt-16 flex flex-col md:flex-row items-center justify-between gap-4 text-gray-700">
                <p class="text-sm">
                    Menampilkan 
                    <span class="font-semibold text-blue-700">{{ $pengajuanSurats->firstItem() }}</span> 
                    sampai 
                    <span class="font-semibold text-blue-700">{{ $pengajuanSurats->lastItem() }}</span> 
                    dari 
                    <span class="font-semibold text-blue-700">{{ $pengajuanSurats->total() }}</span> hasil
                </p>

                <div class="flex items-center bg-white border border-blue-200 rounded-2xl shadow-sm overflow-hidden translate-y-1">
                    @if ($pengajuanSurats->onFirstPage())
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 cursor-not-allowed">‹</span>
                    @else
                        <a href="{{ $pengajuanSurats->previousPageUrl() }}" class="px-4 py-2 text-blue-600 hover:bg-blue-100 transition">‹</a>
                    @endif

                    @foreach ($pengajuanSurats->links()->elements[0] ?? [] as $page => $url)
                        @if ($page == $pengajuanSurats->currentPage())
                            <span class="px-4 py-2 bg-gradient-to-r from-blue-700 to-indigo-600 text-white font-semibold shadow-inner scale-105">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 text-gray-600 hover:bg-blue-50 hover:text-blue-700 transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($pengajuanSurats->hasMorePages())
                        <a href="{{ $pengajuanSurats->nextPageUrl() }}" class="px-4 py-2 text-blue-600 hover:bg-blue-100 transition">›</a>
                    @else
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 cursor-not-allowed">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- ✨ Animations -->
    <style>
        @keyframes fadeIn { from {opacity: 0; transform: translateY(10px);} to {opacity: 1; transform: translateY(0);} }
        @keyframes fadeInSlow { from {opacity: 0; transform: translateY(20px);} to {opacity: 1; transform: translateY(0);} }
        @keyframes slideIn { from {opacity: 0; transform: translateX(-20px);} to {opacity: 1; transform: translateX(0);} }
        .animate-fadeIn { animation: fadeIn 0.6s ease forwards; }
        .animate-fadeInSlow { animation: fadeInSlow 0.8s ease forwards; }
        .animate-slideIn { animation: slideIn 0.6s ease forwards; }
    </style>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a2d9d5a64a.js" crossorigin="anonymous"></script>
</x-app-layout>
