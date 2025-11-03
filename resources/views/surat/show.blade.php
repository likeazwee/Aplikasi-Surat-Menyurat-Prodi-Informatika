{{-- Halaman ini sekarang menerima layout dari controller --}}
@extends($layout)

{{-- Judul Halaman --}}
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detail Pengajuan Surat
    </h2>
@endsection

{{-- Konten Halaman --}}
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Grid untuk Detail dan Komentar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Kolom Detail Surat -->
            <div class="md:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Surat</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Pemohon</dt>
                                <dd class="text-md text-gray-900 font-semibold">{{ $surat->user->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">NIM</dt>
                                <dd class="text-md text-gray-900 font-semibold">{{ $surat->user->profile->nim ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jenis Surat</dt>
                                <dd class="text-md text-gray-900 font-semibold">{{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
                                <dd class="text-md text-gray-900 font-semibold">{{ $surat->created_at->format('d F Y, H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-md font-semibold">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        @if($surat->status == 'disetujui') bg-green-100 text-green-800 
                                        @elseif($surat->status == 'ditolak') bg-red-100 text-red-800 
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($surat->status) }}
                                    </span>
                                </dd>
                            </div>
                            @if($surat->keterangan)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Keterangan Mahasiswa</dt>
                                <dd class="text-md text-gray-900 bg-gray-50 p-3 rounded-md">{{ $surat->keterangan }}</dd>
                            </div>
                            @endif
                             @if($surat->file_path)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Lampiran</dt>
                                <dd class="text-md mt-1">
                                    <a href="{{ Storage::url($surat->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm font-medium rounded-full shadow hover:bg-blue-600 transition-all duration-200">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.102 1.101" /></svg>
                                        Lihat Lampiran
                                    </a>
                                </dd>
                            </div>
                            @endif
                             <div class="mt-4 pt-4 border-t">
                                {{-- Tombol kembali yang dinamis --}}
                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali ke Dashboard</a>
                                @elseif(Auth::user()->role == 'kaprodi')
                                    <a href="{{ route('kaprodi.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali ke Dashboard</a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali ke Dashboard</a>
                                @endif
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Kolom Komentar -->
            <div class="md:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Komentar</h3>
                        
                        <form action="{{ route('surat.komentar.store', $surat) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="body" class="block text-sm font-medium text-gray-700">Tulis Komentar Baru</label>
                                <textarea id="body" name="body" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Tulis komentar..."></textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Kirim Komentar
                                </button>
                            </div>
                        </form>

                        <hr class="my-6">

                        <!-- Daftar Komentar yang Ada -->
                        <h4 class="text-md font-medium text-gray-800 mb-4">Riwayat Komentar</h4>
                        <div class="space-y-4">
                            @forelse ($surat->komentars->sortBy('created_at') as $komentar) {{-- Diurutkan dari yang terlama ke terbaru --}}
                                <div class="p-4 rounded-lg {{ $komentar->user->id == $surat->user_id ? 'bg-gray-50' : 'bg-blue-50' }}">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-gray-900">
                                            {{ $komentar->user->name }}
                                            @if(in_array($komentar->user->role, ['admin', 'kaprodi']))
                                                <span class="ml-2 text-xs font-medium text-blue-700">({{ ucfirst($komentar->user->role) }})</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $komentar->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700 mt-2">{{ $komentar->body }}</p>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Belum ada komentar.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

