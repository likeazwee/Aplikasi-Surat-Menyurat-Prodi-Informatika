<x-app-layout>
    <div class="min-h-screen bg-white text-gray-800 px-6 py-10 font-sans relative overflow-x-hidden">

        <div class="max-w-7xl mx-auto mb-10 text-center animate-fadeInSlow">
            <h1 class="text-5xl font-extrabold mb-3 tracking-tight text-blue-900">
                <i class="fa-solid fa-clock-rotate-left text-blue-700 mr-2"></i> Riwayat Pengajuan Surat
            </h1>
            <p class="text-gray-600 text-base">
                Pantau semua riwayat pengajuan suratmu secara 
                <span class="text-blue-700 font-semibold">real-time</span> dan terintegrasi
            </p>
        </div>

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

        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($pengajuanSurats as $surat)
                {{-- Container Relative agar link utama bisa full cover --}}
                <div class="relative p-6 rounded-2xl bg-gradient-to-br from-blue-950 to-blue-800 text-white 
                            shadow-md hover:shadow-xl transition-all transform hover:-translate-y-1 duration-300 animate-fadeIn border border-blue-900/40 group">
                    
                    {{-- 🔥 LINK UTAMA KE DETAIL SURAT (Cover seluruh kartu) --}}
                    <a href="{{ route('surat.show', $surat->id) }}" class="absolute inset-0 z-0"></a>

                    <div class="relative z-10 pointer-events-none"> {{-- pointer-events-none agar klik tembus ke link utama --}}
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-semibold text-lg leading-tight pr-2">
                                {{ $surat->jenisSurat->nama_surat ?? 'Jenis Surat Tidak Ditemukan' }}
                            </h3>
                            <span class="px-3 py-1 rounded-full text-xs font-medium uppercase tracking-wide whitespace-nowrap
                                {{ $surat->status === 'disetujui' ? 'bg-green-200 text-green-800' : 
                                   ($surat->status === 'ditolak' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                {{ ucfirst($surat->status) }}
                            </span>
                        </div>

                        <p class="text-sm text-blue-200 mb-1">📅 Tanggal Pengajuan</p>
                        <p class="font-medium mb-3">
                            {{ $surat->created_at->translatedFormat('d F Y') }}
                        </p>

                        {{-- Indikator Komentar (Jika ada) --}}
                        @if($surat->komentars->count() > 0)
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-white/10 text-yellow-300 text-xs font-semibold mb-4">
                                <i class="fa-regular fa-comments"></i> {{ $surat->komentars->count() }} Komentar
                            </div>
                        @else
                            <div class="h-6 mb-4"></div> {{-- Spacer biar tinggi kartu rata --}}
                        @endif
                    </div>

                    {{-- Footer Card --}}
                    <div class="relative z-20 flex justify-between items-center mt-2 pt-4 border-t border-blue-700/50">
                        {{-- Teks CTA ke Detail --}}
                        <span class="text-xs text-blue-300 group-hover:text-white transition underline decoration-dashed pointer-events-none">
                            Lihat Detail & Komentar
                        </span>

                        {{-- Tombol File (Z-Index tinggi agar bisa diklik terpisah) --}}
                        @if ($surat->file_path)
                            <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                               class="inline-flex items-center gap-2 text-xs font-bold bg-blue-700 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition-all shadow-sm">
                                <i class="fa-solid fa-paperclip"></i> File
                            </a>
                        @else
                            <span class="text-xs text-blue-400 italic">Tanpa File</span>
                        @endif
                    </div>

                </div>
            @empty
                <div class="col-span-3 text-center text-gray-400 py-10 text-lg">
                    <i class="fa-solid fa-inbox text-3xl mb-2"></i><br>
                    Belum ada surat untuk kategori ini.
                </div>
            @endforelse
        </div>

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

    <style>
        @keyframes fadeIn { from {opacity: 0; transform: translateY(10px);} to {opacity: 1; transform: translateY(0);} }
        @keyframes fadeInSlow { from {opacity: 0; transform: translateY(20px);} to {opacity: 1; transform: translateY(0);} }
        @keyframes slideIn { from {opacity: 0; transform: translateX(-20px);} to {opacity: 1; transform: translateX(0);} }
        .animate-fadeIn { animation: fadeIn 0.6s ease forwards; }
        .animate-fadeInSlow { animation: fadeInSlow 0.8s ease forwards; }
        .animate-slideIn { animation: slideIn 0.6s ease forwards; }
    </style>

    <script src="https://kit.fontawesome.com/a2d9d5a64a.js" crossorigin="anonymous"></script>
</x-app-layout>