@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Daftar Pengajuan Surat Masuk</h3>

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form pencarian --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input-label for="search_nama" :value="__('Cari Nama Mahasiswa')" />
                <x-text-input id="search_nama" class="block mt-1 w-full" type="text"
                    name="search_nama" :value="request('search_nama')" />
            </div>
            <div>
                <x-input-label for="search_jenis" :value="__('Cari Jenis Surat')" />
                <x-text-input id="search_jenis" class="block mt-1 w-full" type="text"
                    name="search_jenis" :value="request('search_jenis')" />
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                    {{ __('Cari') }}
                </button>
            </div>
        </div>
    </form>

    {{-- Tabel pengajuan surat --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Mahasiswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Jenis Surat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Lampiran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($pengajuanSurats as $surat)
                    <tr>
                        <td class="px-6 py-4">{{ $surat->user->name ?? 'Pengguna Dihapus' }}</td>
                        <td class="px-6 py-4">{{ $surat->jenisSurat->nama_surat ?? 'Jenis Dihapus' }}</td>
                        <td class="px-6 py-4">{{ $surat->created_at->format('d-m-Y') }}</td>
                        <td class="px-6 py-4">
                            @if($surat->status == 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($surat->status == 'disetujui')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($surat->file_path)
                                <a href="{{ Storage::url($surat->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Lampiran</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            @if ($surat->status == 'pending')
                                <div class="flex items-center space-x-3">
                                    <form action="{{ route('admin.surat.approve', $surat->id) }}" method="POST" class="form-approve">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-800 font-semibold transition transform hover:scale-110">
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.surat.reject', $surat->id) }}" method="POST" class="form-reject">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold transition transform hover:scale-110">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
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
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
@endsection
