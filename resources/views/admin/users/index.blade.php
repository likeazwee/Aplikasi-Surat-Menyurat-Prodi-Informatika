@extends('layouts.admin')

@section('header', 'Kelola Pengguna')

@section('content')
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-blue-50">
            <h3 class="text-lg font-medium text-blue-700 flex items-center gap-2">
                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Daftar Pengguna Sistem
            </h3>
        </div>

        @if (session('success'))
            <div class="m-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Formulir Pencarian -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="p-6 border-b border-blue-100">
             <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[250px]">
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Nama atau Email</label>
                    <input type="text" name="search" id="search" placeholder="Nama atau Email..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ request('search') }}">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 text-white font-semibold text-xs rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 h-10">
                    Cari
                </button>
            </div>
        </form>

        <!-- Tabel Pengguna -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-blue-100">
                <thead class="bg-blue-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">NIM/NIP</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-blue-50">
                    @forelse ($users as $user)
                        <tr class="hover:bg-blue-50/50 transition-all duration-300">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    @if($user->role == 'admin') bg-red-100 text-red-800 @endif
                                    @if($user->role == 'kaprodi') bg-yellow-100 text-yellow-800 @endif
                                    @if($user->role == 'mahasiswa') bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->profile->nim ?? $user->profile->nip ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-700 hover:text-blue-900">Ubah</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Ini tidak bisa dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 bg-white rounded-b-xl">
            {{ $users->links() }}
        </div>
    </div>
@endsection

