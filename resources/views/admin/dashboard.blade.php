@extends('layouts.admin')

@section('header', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
    {{-- Pesan Sukses/Error dengan style baru --}}
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
     @if (session('error'))
        <div x-data="{ showError: true }" x-show="showError" 
             x-transition:enter="transition ease-out duration-500" 
             x-transition:enter-start="opacity-0 -translate-y-5" 
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-red-100 border-l-4 border-red-500 p-4 rounded-xl shadow-md relative transition-all duration-300 hover:shadow-lg">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-red-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <strong class="font-bold text-red-800">Error!</strong>
                <span class="ml-2 text-red-700">{{ session('error') }}</span>
            </div>
            <button @click="showError = false" class="absolute top-2 right-3 text-red-500 hover:text-red-700 transition-colors duration-200">✕</button>
        </div>
    @endif

    <!-- Card Statistik (Menampilkan data semua surat) -->
    <div class="flex flex-wrap justify-between items-center gap-4">
         <div class="p-4 bg-gradient-to-br from-blue-800 to-blue-600 text-white rounded-xl shadow-lg transform hover:scale-105 transition-all duration-500 hover:shadow-xl flex-1 min-w-[200px]">
            <h3 class="text-md font-semibold flex items-center gap-2">Total Surat Masuk</h3>
            <p class="text-2xl font-bold mt-1">{{ $statusCounts->total ?? 0 }}</p>
        </div>
        <div class="p-4 bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-xl shadow-lg transform hover:scale-105 transition-all duration-500 hover:shadow-xl flex-1 min-w-[200px]">
            <h3 class="text-md font-semibold flex items-center gap-2">Disetujui</h3>
            <p class="text-2xl font-bold mt-1">{{ $statusCounts->disetujui ?? 0 }}</p>
        </div>
        <div class="p-4 bg-gradient-to-br from-blue-600 to-blue-400 text-white rounded-xl shadow-lg transform hover:scale-105 transition-all duration-500 hover:shadow-xl flex-1 min-w-[200px]">
            <h3 class="text-md font-semibold flex items-center gap-2">Ditolak</h3>
            <p class="text-2xl font-bold mt-1">{{ $statusCounts->ditolak ?? 0 }}</p>
        </div>
        <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-300 text-white rounded-xl shadow-lg transform hover:scale-105 transition-all duration-500 hover:shadow-xl flex-1 min-w-[200px]">
            <h3 class="text-md font-semibold flex items-center gap-2">Menunggu</h3>
            <p class="text-2xl font-bold mt-1">{{ $statusCounts->pending ?? 0 }}</p>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-500 hover:shadow-xl">
        <div class="p-6 bg-blue-50">
            <h3 class="text-lg font-medium text-blue-700 flex items-center gap-2">
                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                Daftar Pengajuan Surat Masuk
            </h3>
        </div>

        <!-- Form Pencarian dengan style baru -->
        <form method="GET" action="{{ route('admin.dashboard') }}" class="p-6 border-b border-blue-100">
            <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="search_nama" class="block text-sm font-medium text-gray-700">Cari Nama Mahasiswa</label>
                    <input id="search_nama" name="search_nama" type="text" placeholder="Nama Mahasiswa..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ request('search_nama') }}">
                </div>
                <div class="flex-1 min-w-[200px]">
                   <label for="search_jenis" class="block text-sm font-medium text-gray-700">Cari Jenis Surat</label>
                    <input id="search_jenis" name="search_jenis" type="text" placeholder="Jenis Surat..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ request('search_jenis') }}">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 text-white font-semibold text-xs rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 h-10">
                    Cari
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-blue-100">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Jenis Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Lampiran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-blue-50">
                    @forelse ($pengajuanSurats as $surat)
                        <tr class="hover:bg-blue-50/50 transition-all duration-300 animate-fade-in-row" style="animation-delay: {{ $loop->iteration * 100 }}ms">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $surat->user->name ?? 'Pengguna Dihapus' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{-- Link ke Halaman Detail --}}
                                <a href="{{ route('surat.show', ['surat' => $surat]) }}" class="text-blue-700 hover:text-blue-900 transition-colors duration-200">
                                    {{ $surat->jenisSurat->nama_surat ?? 'Jenis Dihapus' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $surat->created_at->format('d F Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($surat->status == 'pending')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 animate-pulse">Menunggu</span>
                                @elseif($surat->status == 'disetujui')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Disetujui</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($surat->file_path)
                                    <a href="{{ Storage::url($surat->file_path) }}" target="_blank" class="lampiran-button flex items-center max-w-max">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.102 1.101" />
                                        </svg>
                                        Lihat File
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if ($surat->status == 'pending')
                                    <div class="flex items-center space-x-3">
                                        <form action="{{ route('admin.surat.approve', ['surat' => $surat]) }}" method="POST" class="form-approve">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-button-green">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.surat.reject', ['surat' => $surat]) }}" method="POST" class="form-reject">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="action-button-red">Tolak</button>
                                        </form>
                                    </div>
                                @elseif ($surat->status == 'disetujui')
                                    {{-- Tombol Download --}}
                                     <a href="{{ route('admin.surat.download', ['surat' => $surat]) }}" class="download-button">
                                        Download
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data pengajuan surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Paginasi --}}
        <div class="p-6 bg-white rounded-b-xl">
            {{ $pengajuanSurats->withQueryString()->links() }}
        </div>

    </div>
</div>
</div>

{{-- SweetAlert2 --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logika SweetAlert
    document.querySelectorAll('.form-approve').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Setujui Surat?',
                text: 'Apakah Anda yakin ingin menyetujui surat ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    document.querySelectorAll('.form-reject').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Tolak Surat?',
                text: 'Apakah Anda yakin ingin menolak surat ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>
@endpush
@endsection

