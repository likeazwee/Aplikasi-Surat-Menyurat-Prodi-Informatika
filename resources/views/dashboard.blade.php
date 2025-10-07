<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan pesan sukses jika ada --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium">Selamat Datang, {{ Auth::user()->name }}!</h3>
                        
                        {{-- Tombol untuk menuju halaman formulir --}}
                        <a href="{{ route('surat.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Ajukan Surat Baru
                        </a>
                    </div>
                    
                    <hr class="my-6">

                    {{-- Di sini nanti kita akan menampilkan riwayat pengajuan surat --}}
                    <h4 class="text-md font-medium mb-4">Riwayat Pengajuan Surat Anda:</h4>
                    <p class="text-gray-500">Belum ada data pengajuan surat.</p>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
