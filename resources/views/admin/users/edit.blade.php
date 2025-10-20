@extends('layouts.admin')

@section('header', 'Ubah Data Pengguna: ' . $user->name)

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <strong>Terdapat kesalahan:</strong>
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
                    <x-input-label for="name" :value="__('Nama')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                </div>

                <!-- Role -->
                <div class="mt-4">
                    <x-input-label for="role" :value="__('Role')" />
                    <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="mahasiswa" @selected(old('role', $user->role) == 'mahasiswa')>Mahasiswa</option>
                        <option value="kaprodi" @selected(old('role', $user->role) == 'kaprodi')>Kaprodi</option>
                        <option value="admin" @selected(old('role', $user->role) == 'admin')>Admin</option>
                    </select>
                </div>
                
                <hr class="my-6">
                <p class="font-bold mb-2">Profil Pengguna (Isi sesuai role)</p>

                <!-- NIM -->
                <div class="mt-4">
                    <x-input-label for="nim" :value="__('NIM (untuk Mahasiswa)')" />
                    <x-text-input id="nim" class="block mt-1 w-full" type="text" name="nim" :value="old('nim', $user->profile->nim ?? '')" />
                </div>

                <!-- Prodi -->
                <div class="mt-4">
                    <x-input-label for="prodi" :value="__('Program Studi (untuk Mahasiswa)')" />
                    <x-text-input id="prodi" class="block mt-1 w-full" type="text" name="prodi" :value="old('prodi', $user->profile->prodi ?? '')" />
                </div>

                <!-- NIP -->
                <div class="mt-4">
                    <x-input-label for="nip" :value="__('NIP (untuk Admin/Kaprodi)')" />
                    <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip', $user->profile->nip ?? '')" />
                </div>

                <!-- Jabatan -->
                <div class="mt-4">
                    <x-input-label for="jabatan" :value="__('Jabatan (untuk Admin/Kaprodi)')" />
                    <x-text-input id="jabatan" class="block mt-1 w-full" type="text" name="jabatan" :value="old('jabatan', $user->profile->jabatan ?? '')" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                        {{ __('Batal') }}
                    </a>

                    <x-primary-button>
                        {{ __('Simpan Perubahan') }}
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
@endsection