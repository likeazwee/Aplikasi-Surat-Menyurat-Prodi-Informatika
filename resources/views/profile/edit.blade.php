@extends('layouts.profile-admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="relative backdrop-blur-md bg-blue-900/60 border rounded-2xl shadow-xl p-10 border border-gray-300 backdrop-blur-md ">
        <h2 class="text-3xl font-bold text-white -900 mb-8 border-b-4 border-blue-800 pb-4 tracking-wide">
            Profil Akun
        </h2>

        {{-- Update informasi profil --}}
        <div class="mb-10">
            <h3 class="text-lg font-semibold text-white -900 mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A10.97 10.97 0 0112 15c2.137 0 4.11.625 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Informasi Pengguna
            </h3>
            <div class="bg-gradient-to-br from-gray-50 to-gray-200 p-6 rounded-xl border border-gray-300 shadow-inner hover:shadow-md transition">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update password --}}
        <div class="mb-10">
            <h3 class="text-lg font-semibold text-white -900 mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 11c0-1.105.895-2 2-2s2 .895 2 2v2m-8 0v-2a2 2 0 114 0v2m-2 4h.01M5 12h14" />
                </svg>
                Ubah Password
            </h3>
            <div class="bg-gradient-to-br from-gray-50 to-gray-200 p-6 rounded-xl border border-gray-300 shadow-inner hover:shadow-md transition">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Hapus akun --}}
        <div>
            <h3 class="text-lg font-semibold text-red-700 mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus Akun
            </h3>
            <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl border border-red-300 shadow-inner hover:shadow-md transition">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>

{{-- ✨ Animasi hover tombol & efek halus --}}
<style>
    button {
        transition: all 0.2s ease-in-out;
    }
    button:hover {
        transform: scale(1.05);
        filter: brightness(1.05);
    }
</style>

{{-- SweetAlert untuk notifikasi sukses --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = "{{ session('status') }}";
    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage,
            confirmButtonColor: '#1e3a8a',
            confirmButtonText: 'OK'
        });
    }
});
</script>
@endsection
