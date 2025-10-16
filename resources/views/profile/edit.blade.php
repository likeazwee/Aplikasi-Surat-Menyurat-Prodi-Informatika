@extends('layouts.profile-admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Profil Akun</h2>

    {{-- Update informasi profil --}}
    <div class="mb-10">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Informasi Pengguna</h3>
        <div class="bg-gray-50 p-6 rounded-lg border">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- Update password --}}
    <div class="mb-10">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Ubah Password</h3>
        <div class="bg-gray-50 p-6 rounded-lg border">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- Hapus akun --}}
    <div>
        <h3 class="text-lg font-semibold text-red-600 mb-3">Hapus Akun</h3>
        <div class="bg-red-50 p-6 rounded-lg border border-red-200">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>

{{-- Efek animasi & hover ringan --}}
<style>
    button {
        transition: all 0.2s ease-in-out;
    }
    button:hover {
        transform: scale(1.05);
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
