<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Mengambil nama aplikasi dari .env, default ke 'Laravel' --}}
    <title>{{ config('app.name', 'Laravel') }} - Admin</title> 

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen bg-gray-50">

        <!-- Sidebar -->
        <aside class="bg-blue-900 text-white w-64 space-y-6 py-6 px-3 hidden md:block flex-shrink-0">
            {{-- Bagian Atas Sidebar --}}
            <div class="flex items-center space-x-3 px-4 border-b border-blue-700 pb-4 mb-4">
                {{-- Logo --}}
                <img src="{{ asset('images/logounib.png') }}" alt="Logo UNIB" class="h-14 w-14"> 
                <h1 class="text-lg font-bold">Admin Prodi</h1>
            </div>

            {{-- Menu Navigasi Sidebar --}}
            <nav>
                {{-- Link Dashboard Admin --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                   {{-- Icon Dashboard --}}
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span class="font-semibold">Dashboard</span>
                </a>
                
                {{-- Link Kelola Pengguna --}}
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-blue-700 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                   {{-- Icon Kelola Pengguna --}}
                   <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span class="font-semibold">Kelola Pengguna</span>
                </a>
                {{-- Tambahkan link admin lainnya di sini jika perlu --}}
            </nav>

            {{-- Bagian Bawah Sidebar (Logout) --}}
            <div class="mt-auto pt-4 px-4 border-t border-blue-700"> 
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center py-2.5 px-4 w-full text-left rounded transition duration-200 hover:bg-blue-700">
                        {{-- Icon Logout --}}
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span class="font-semibold">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="flex items-center justify-between bg-white px-6 py-4 shadow sticky top-0 z-10">
                {{-- Judul Halaman Dinamis --}}
                <h2 class="text-xl font-semibold text-gray-700">@yield('header')</h2> 
                
                {{-- Tombol Profil Pengguna --}}
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center space-x-2 group px-3 py-1 rounded-lg transition hover:bg-gray-100">
                   {{-- Placeholder Avatar --}}
                    <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-gray-200 text-gray-500 font-semibold border border-gray-300">
                        {{ substr(Auth::user()->name, 0, 1) }} 
                    </span>
                    <span class="text-gray-800 font-medium group-hover:text-blue-700 transition hidden sm:inline">
                        {{ Auth::user()->name }}
                    </span>
                </a>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                {{-- Tempat konten spesifik halaman akan dimasukkan --}}
                @yield('content') 
            </main>
        </div>
    </div>
    {{-- Script tambahan jika ada --}}
    @stack('scripts') 
</body>
</html>

