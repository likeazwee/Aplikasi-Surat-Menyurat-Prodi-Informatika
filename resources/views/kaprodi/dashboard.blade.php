<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kaprodi - Daftar Pengajuan Surat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Formulir Pencarian --}}
            <div class="mb-4 p-4 bg-white shadow sm:rounded-lg">
                <form action="{{ route('kaprodi.dashboard') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label for="search_nama" class="block text-sm font-medium text-gray-700">Cari Nama Mahasiswa</label>
                            <input type="text" name="search_nama" id="search_nama" value="{{ request('search_nama') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="search_jenis" class="block text-sm font-medium text-gray-700">Cari Jenis Surat</label>
                            <input type="text" name="search_jenis" id="search_jenis" value="{{ request('search_jenis') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Tanggal</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Mahasiswa</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Jenis Surat</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Lampiran</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Status</th>
                                </tr>
                            </thead>
                    
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($pengajuanSurats as $surat)
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $surat->created_at->format('d/m/Y') }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $surat->user->name }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $surat->jenisSurat->nama_surat }}</td>
                                        <td class="whitespace-nowrap px-4 py-2">
                                            @if ($surat->file_path)
                                                <a href="{{ Storage::url($surat->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 underline text-xs">
                                                    Lihat File
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                            @if($surat->status == 'pending')
                                                <span class="inline-flex items-center justify-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-yellow-700">Pending</span>
                                            @elseif($surat->status == 'disetujui')
                                                <span class="inline-flex items-center justify-center rounded-full bg-green-100 px-2.5 py-0.5 text-green-700">Disetujui</span>
                                            @else
                                                <span class="inline-flex items-center justify-center rounded-full bg-red-100 px-2.5 py-0.5 text-red-700">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-gray-500 py-4">Tidak ada data yang cocok dengan pencarian Anda.</td>
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