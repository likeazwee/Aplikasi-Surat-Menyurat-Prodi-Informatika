<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Profile</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen bg-gray-50">

        <!-- Sidebar -->
        <aside class="bg-blue-900 text-white w-64 space-y-6 py-6 px-3 hidden md:block">
            <div class="flex items-center space-x-3 px-4">
                <img src="{{ asset('images/logounib.png') }}" alt="Logo UNIB" class="h-14 w-14">
                <h1 class="text-lg font-bold">Profile</h1>
            </div>

            @php
                use Illuminate\Support\Facades\Auth;

                $user = Auth::user();
                // Tentukan route dashboard sesuai peran user
                if ($user) {
                    switch ($user->role) {
                        case 'admin':
                            $dashboardRoute = route('admin.dashboard');
                            break;
                        case 'kaprodi':
                            $dashboardRoute = route('kaprodi.dashboard');
                            break;
                        default:
                            $dashboardRoute = route('dashboard');
                            break;
                    }
                } else {
                    $dashboardRoute = route('dashboard');
                }
            @endphp

            <nav class="mt-6">
                <a href="{{ $dashboardRoute }}"
                   class="flex items-center py-2 px-4 rounded hover:bg-blue-800 {{ request()->is('dashboard') || request()->is('admin/dashboard') || request()->is('kaprodi/dashboard') ? 'bg-blue-800' : '' }}">
                    <span class="ml-2 font-semibold">Dashboard</span>
                </a>
            </nav>

            <div class="border-t border-blue-700 mt-6 pt-4 px-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center py-2 px-4 w-full text-left rounded hover:bg-blue-800">
                        <span class="ml-2 font-semibold">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="flex items-center justify-between bg-white px-6 py-4 shadow">
                <h2 class="text-xl font-semibold text-gray-700">Atur Profil</h2>

                <!-- Profile Button -->
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center space-x-2 group px-3 py-2 rounded-lg transition transform hover:scale-105">
                    <img class="h-9 w-9 rounded-full border border-gray-300 transition transform group-hover:scale-110"
                         src="{{ asset('images/profile.logo.png') }}" alt="Profile">
                    <span class="text-gray-800 font-medium group-hover:text-blue-700 transition">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </span>
                </a>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
