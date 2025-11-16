<x-app-layout>

    {{-- 🔵 Background Putih --}}
    <div class="min-h-screen bg-white py-10">

        <div class="max-w-7xl mx-auto px-6">

            {{-- 🔍 Form Pencarian & Filter --}}
            <div class="bg-blue-900/80 backdrop-blur-md border border-blue-300/20 rounded-2xl shadow-md mb-8">
                <div class="p-6 text-white rounded-2xl">
                    <form action="{{ route('kaprodi.dashboard') }}" method="GET"
                          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-sm font-semibold text-blue-100 mb-1">Nama Mahasiswa</label>
                            <input type="text" name="search_nama"
                                   value="{{ request('search_nama') }}"
                                   placeholder="Cari nama mahasiswa..."
                                   class="w-full px-4 py-2 bg-blue-950/40 border border-blue-400/30 
                                          text-white rounded-lg placeholder-blue-200
                                          focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                        </div>

                        {{-- Jenis Surat --}}
                        <div>
                            <label class="block text-sm font-semibold text-blue-100 mb-1">Jenis Surat</label>
                            <input type="text" name="search_jenis"
                                   value="{{ request('search_jenis') }}"
                                   placeholder="Cari jenis surat..."
                                   class="w-full px-4 py-2 bg-blue-950/40 border border-blue-400/30 
                                          text-white rounded-lg placeholder-blue-200
                                          focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-semibold text-blue-100 mb-1">Filter Status</label>
                            <select name="status"
                                    class="w-full px-4 py-2 bg-blue-950/40 border border-blue-400/30 text-white rounded-lg 
                                           focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                                <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                            </select>
                        </div>

                        {{-- Tombol --}}
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
        <div class="group bg-gradient-to-br from-blue-900 to-blue-800 border border-blue-700/40 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="p-5">

                {{-- Judul / Jenis Surat --}}
                <div class="flex justify-between items-start mb-3">
                    <h2 class="text-lg font-bold text-white group-hover:text-blue-200 transition">
                        {{ $surat->jenisSurat->nama_surat }}
                    </h2>
                    <span class="text-sm text-blue-200/80">
                        {{ $surat->created_at->format('d M Y') }}
                    </span>
                </div>

                {{-- Nama & Lampiran --}}
                <div class="space-y-1 mb-4">
                    <p class="text-blue-100 font-medium">
                        👤 {{ $surat->user->name }}
                    </p>

                    <p class="text-sm text-blue-200/90">
                        📎
                        @if ($surat->file_path)
                            <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                               class="text-blue-300 underline hover:text-blue-100 font-semibold">
                                Lihat Lampiran
                            </a>
                        @else
                            <span class="text-blue-300/60 italic">Tidak ada lampiran</span>
                        @endif
                    </p>
                </div>

                {{-- Status --}}
                <div>
                    @if($surat->status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-300 animate-pulse">
                            ⏳ Pending
                        </span>
                    @elseif($surat->status == 'disetujui')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-300">
                            ✅ Disetujui
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-300">
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


            {{-- 🔵 Pagination (SAMA PERSIS DENGAN ADMIN) --}}
            @if ($pengajuanSurats->hasPages())
                <div class="flex justify-end mt-10">
                    <div class="flex items-center space-x-2 bg-white border border-gray-200 rounded-full px-3 py-2 shadow-sm">

                        {{-- Prev --}}
                        @if ($pengajuanSurats->onFirstPage())
                            <span class="px-3 py-1 text-gray-400 rounded-full text-sm">‹</span>
                        @else
                            <a href="{{ $pengajuanSurats->previousPageUrl() }}"
                               class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 transition">‹</a>
                        @endif

                        {{-- Number --}}
                        @foreach ($pengajuanSurats->getUrlRange(1, $pengajuanSurats->lastPage()) as $page => $url)
                            @if ($page == $pengajuanSurats->currentPage())
                                <span class="px-3 py-1 bg-blue-900 text-white rounded-full text-sm font-semibold shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                   class="px-3 py-1 bg-white text-blue-700 rounded-full text-sm border border-blue-300 hover:bg-blue-50 transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next --}}
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
