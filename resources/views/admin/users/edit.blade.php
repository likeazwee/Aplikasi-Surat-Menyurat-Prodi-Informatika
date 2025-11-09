@extends('layouts.admin')

@section('header', 'Ubah Data Pengguna: ' . $user->name)

@section('content')
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl" role="alert">
                    <strong class="font-bold">Terdapat kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" type="email" name="email" value="{{ old('email', $user->email) }}" required />
                </div>

                <!-- Role -->
                <div class="mt-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="mahasiswa" @selected(old('role', $user->role) == 'mahasiswa')>Mahasiswa</option>
                        <option value="kaprodi" @selected(old('role', $user->role) == 'kaprodi')>Kaprodi</option>
                        <option value="admin" @selected(old('role', $user->role) == 'admin')>Admin</option>
                    </select>
                </div>
                
                <hr class="my-6">
                <p class="font-bold mb-2 text-gray-700">Profil Pengguna (Isi sesuai role)</p>

                <!-- NIM -->
                <div class="mt-4">
                    <label for="nim" class="block text-sm font-medium text-gray-700">NIM (untuk Mahasiswa)</label>
                    <input id="nim" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" type="text" name="nim" value="{{ old('nim', $user->profile->nim ?? '') }}" />
                </div>

                <!-- Prodi -->
                <div class="mt-4">
                    <label for="prodi" class="block text-sm font-medium text-gray-700">Program Studi (untuk Mahasiswa)</label>
                    <input id="prodi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" type="text" name="prodi" value="{{ old('prodi', $user->profile->prodi ?? '') }}" />
                </div>

                {{-- 👇 FIELD BARU DITAMBAHKAN DI SINI 👇 --}}
                <!-- Jenis Kelamin -->
                <div class="mt-4">
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin (untuk Mahasiswa)</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="" disabled @selected(old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == '')>-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" @selected(old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'Laki-laki')>Laki-laki</option>
                        <option value="Perempuan" @selected(old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'Perempuan')>Perempuan</option>
                    </select>
                </div>

                <!-- NIP -->
                <div class="mt-4">
                    <label for="nip" class="block text-sm font-medium text-gray-700">NIP (untuk Admin/Kaprodi)</label>
                    <input id="nip" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" type="text" name="nip" value="{{ old('nip', $user->profile->nip ?? '') }}" />
                </div>

                <!-- Jabatan -->
                <div class="mt-4">
                    <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan (untuk Admin/Kaprodi)</label>
                    <input id="jabatan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" type="text" name="jabatan" value="{{ old('jabatan', $user->profile->jabatan ?? '') }}" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                        {{ __('Batal') }}
                    </a>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 text-white font-semibold text-xs rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection