@extends('layouts.admin')

@section('header', 'Kelola Pengguna')

@section('content')
<div class="py-10 px-6">
    <h2 class="text-4xl font-bold text-white mb-8 text-center drop-shadow-md"
    style="
        -webkit-text-stroke-width: 1px; /* Ketebalan garis tepi */
        -webkit-text-stroke-color: black; /* Warna garis tepi */
        color: white; /* Warna isi teks tetap putih */
    ">
    Daftar Pengguna Sistem
</h2>


    <!-- Notifikasi -->
    @if (session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Formulir Pencarian -->
<div class="backdrop-blur-md bg-blue-900/60 border border-white/10 rounded-2xl shadow-lg p-6 mb-10">
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div class="flex flex-wrap items-end gap-4 justify-center">
            <div class="w-full sm:w-1/2">
                <label for="search" class="block text-sm font-medium text-gray-200">Cari Nama atau Email</label>
                <input type="text" name="search" id="search" placeholder="Nama atau Email..."
                    class="mt-1 block w-full bg-white/10 text-white placeholder-gray-300 border border-white/20 rounded-lg shadow-sm focus:border-blue-400 focus:ring-blue-400"
                    value="{{ request('search') }}">
            </div>
            <button type="submit"
                class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-900 to-blue-700 text-white font-semibold text-sm rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                Cari
            </button>
        </div>
    </form>
</div>


    <!-- Grid Card Pengguna -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($users as $user)
            <div 
                onclick="openModal('{{ $user->id }}')"
                class="relative backdrop-blur-md bg-blue-900/60 border border-white/10 text-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02] cursor-pointer p-6">
                
                <!-- Foto Profil -->
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-blue-500/60 flex items-center justify-center text-white font-bold text-xl shadow-md">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-300">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Detail -->
                <div class="flex justify-between items-center">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        @if($user->role == 'admin') bg-red-500/30 text-red-200
                        @elseif($user->role == 'kaprodi') bg-yellow-500/30 text-yellow-200
                        @else bg-green-500/30 text-green-200 @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="text-sm text-gray-200">
                        {{ $user->profile->nim ?? $user->profile->nip ?? '-' }}
                    </span>
                </div>
            </div>

            <!-- Modal Aksi -->
            <div id="modal-{{ $user->id }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
                <div class="bg-blue-950/90 text-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center relative border border-white/10 transform scale-95 opacity-0 transition-all duration-300 modal-content">
                    <button onclick="closeModal('{{ $user->id }}')" class="absolute top-3 right-4 text-gray-400 hover:text-gray-200 text-lg">✕</button>
                    <h3 class="text-lg font-semibold mb-2">{{ $user->name }}</h3>
                    <p class="text-gray-300 text-sm mb-6">{{ $user->email }}</p>

                    <div class="flex justify-center gap-4">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="px-4 py-2 bg-blue-600/80 hover:bg-blue-700 text-white rounded-full transition-all duration-200">
                           Ubah
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600/80 hover:bg-red-700 text-white rounded-full transition-all duration-200">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-black-300 py-10">
                Tidak ada data pengguna.
            </div>
        @endforelse
    </div>

    @if ($users->hasPages())
    <div class="flex justify-end mt-10">
        <div class="flex items-center space-x-2 bg-[#2a2a2a]/60 border border-gray-700 rounded-full px-4 py-2 shadow-lg backdrop-blur-sm">
            {{-- Tombol Sebelumnya --}}
            @if ($users->onFirstPage())
                <span class="px-3 py-1 text-gray-500/70 rounded-full text-sm">‹</span>
            @else
                <a href="{{ $users->previousPageUrl() }}"
                   class="px-3 py-1 bg-gray-600 text-white rounded-full text-sm hover:bg-gray-500 transition">‹</a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                @if ($page == $users->currentPage())
                    <span class="px-3 py-1 bg-gray-400 text-black rounded-full text-sm font-semibold shadow-md">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-1 bg-[#1e1e1e] text-gray-300 rounded-full text-sm border border-gray-600 hover:bg-gray-700 transition">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Tombol Berikutnya --}}
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}"
                   class="px-3 py-1 bg-gray-600 text-white rounded-full text-sm hover:bg-gray-500 transition">›</a>
            @else
                <span class="px-3 py-1 text-gray-500/70 rounded-full text-sm">›</span>
            @endif
        </div>
    </div>
@endif


<script>
function openModal(id) {
    const modal = document.getElementById(`modal-${id}`);
    modal.classList.remove('hidden');
    setTimeout(() => modal.querySelector('.modal-content').classList.remove('scale-95', 'opacity-0'), 10);
}
function closeModal(id) {
    const modal = document.getElementById(`modal-${id}`);
    const content = modal.querySelector('.modal-content');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
}
</script>
@endsection
