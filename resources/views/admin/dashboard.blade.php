<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Pengajuan Surat Masuk</h3>

                    {{-- Menampilkan pesan sukses --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
                            <strong class="font-bold">Sukses!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    
                    {{-- Form Pencarian --}}
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="search_nama" :value="__('Cari Nama Mahasiswa')" />
                                <x-text-input id="search_nama" class="block mt-1 w-full" type="text" name="search_nama" :value="request('search_nama')" />
                            </div>
                            <div>
                                <x-input-label for="search_jenis" :value="__('Cari Jenis Surat')" />
                                <x-text-input id="search_jenis" class="block mt-1 w-full" type="text" name="search_jenis" :value="request('search_jenis')" />
                            </div>
                            <div class="flex items-end">
                                <x-primary-button>
                                    {{ __('Cari') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel Riwayat --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lampiran</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pengajuanSurats as $surat)
                                    <tr>
                                        {{-- PERBAIKAN: Tambahkan pemeriksaan null --}}
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $surat->user->name ?? 'Pengguna Dihapus' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $surat->jenisSurat->nama_surat ?? 'Jenis Dihapus' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $surat->created_at->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($surat->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif($surat->status == 'disetujui')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if ($surat->file_path)
                                                <a href="{{ Storage::url($surat->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Lihat Lampiran</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{-- Tampilkan tombol hanya jika status masih 'pending' --}}
                                            @if ($surat->status == 'pending')
                                                <div class="flex items-center space-x-2">
                                                    {{-- Tombol Setujui --}}
                                                    <form action="{{ route('admin.surat.approve', $surat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui surat ini?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-green-600 hover:text-green-900">Setujui</button>
                                                    </form>
                                                    {{-- Tombol Tolak --}}
                                                    <form action="{{ route('admin.surat.reject', $surat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak surat ini?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Tolak</button>
                                                    </form>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            Tidak ada data pengajuan surat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>