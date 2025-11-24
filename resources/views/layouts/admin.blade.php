<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="flex min-h-screen">

        <aside class="w-64 bg-[#1e3a8a] text-white hidden md:flex flex-col fixed h-full z-20 shadow-xl border-r border-blue-900">
            <div class="flex items-center gap-3 px-6 py-6 mb-6 border-b border-blue-800">
                <img src="{{ asset('images/logounib.png') }}" alt="Logo" class="h-10 w-10 drop-shadow-md">
                <div>
                    <h1 class="text-lg font-bold leading-tight">Admin Prodi</h1>
                    <p class="text-xs text-blue-200">Informatika</p>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 shadow-inner border-l-4 border-blue-400' : 'hover:bg-blue-800 hover:translate-x-1' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 
                   {{ request()->routeIs('admin.users.*') ? 'bg-blue-700 shadow-inner border-l-4 border-blue-400' : 'hover:bg-blue-800 hover:translate-x-1' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="font-medium">Kelola Pengguna</span>
                </a>
            </nav>

            <div class="p-4 bg-[#172e6e]">
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 mb-2 text-sm text-blue-200 rounded-lg hover:bg-blue-800 transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil Saya
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-300 rounded-lg hover:bg-red-900/30 hover:text-red-100 transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 md:ml-64 p-8 overflow-y-auto h-screen bg-gray-50">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>