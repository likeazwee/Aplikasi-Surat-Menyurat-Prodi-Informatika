<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- 👇 Style Animasi & Kustom Sekarang Ada Di Sini 👇 --}}
    <style>
        .animated-background {
            background: linear-gradient(270deg, #0a192f, #1a365d, #3182ce, #0a192f);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .lampiran-button {
            @apply inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm font-medium rounded-full shadow hover:bg-blue-600 transition-all duration-200;
        }

        .action-button-green {
            @apply text-green-600 hover:text-green-800 font-semibold transition transform hover:scale-110;
        }
        
        .action-button-red {
            @apply text-red-600 hover:text-red-800 font-semibold transition transform hover:scale-110;
        }

        .download-button {
             @apply inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition;
        }
        
        @keyframes fade-in-row {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-row {
            animation: fade-in-row 0.5s ease-out forwards;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen bg-gray-50">

        <!-- Sidebar -->
        <aside class="bg-blue-900 text-white w-64 space-y-6 py-6 px-3 hidden md:block flex-shrink-0">
            <div class="flex items-center space-x-3 px-4">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logounib.png') }}" alt="Logo UNIB" class="h-14 w-14">
                </a>
                <h1 class="text-lg font-bold">Admin Prodi</h1>
            </div>

            <nav class="mt-6">
                {{-- Link Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center py-2 px-4 rounded hover:bg-blue-800 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800' : '' }}">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span class="font-semibold">Dashboard</span>
                </a>
                
                {{-- Link Kelola Pengguna --}}
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center py-2 px-4 rounded hover:bg-blue-800 {{ request()->routeIs('admin.users.*') ? 'bg-blue-800' : '' }}">
                   <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span class="font-semibold">Kelola Pengguna</span>
                </a>
            </nav>

            <div class="border-t border-blue-700 mt-auto pt-4 px-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center py-2 px-4 w-full text-left rounded hover:bg-blue-800">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span class="font-semibold">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="flex items-center justify-between bg-white px-6 py-4 shadow">
                <h2 class="text-xl font-semibold text-gray-700">@yield('header')</h2>
                <!-- Profile Button -->
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center space-x-2 group px-3 py-2 rounded-lg transition transform hover:scale-105">
                    <img class="h-9 w-9 rounded-full border border-gray-300 transition transform group-hover:scale-110"
                         src="https://placehold.co/100x100/E2E8F0/4A5568?text={{ substr(Auth::user()->name, 0, 1) }}" alt="Profile">
                    <span class="text-gray-800 font-medium group-hover:text-blue-700 transition">
                        {{ Auth::user()->name }}
                    </span>
                </a>
            </header>

            <!-- 👇 CLASS BACKGROUND DIPINDAH KE SINI 👇 -->
            <main class="flex-1 p-6 animated-background relative overflow-hidden">
                <div class="absolute inset-0 opacity-50"></div>
                <div class="relative z-10">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Stack untuk script kustom per halaman --}}
    @stack('scripts')
</body>
</html>

