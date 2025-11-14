<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Profil Mahasiswa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- 🎨 Style Mahasiswa Layout (mirip admin) --}}
    <style>
        /* 🩶 Background Silver Classy */
        .main-background {
            background: linear-gradient(135deg, #d7d7d7 0%, #bcbcbc 40%, #9a9a9a 80%, #7c7c7c 100%);
            background-attachment: fixed;
            background-size: cover;
            min-height: 100vh;
            color: #1a1a1a;
            position: relative;
        }
        .main-background::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.3), transparent 60%),
                        radial-gradient(circle at 80% 80%, rgba(255,255,255,0.2), transparent 70%);
            pointer-events: none;
            mix-blend-mode: overlay;
        }

        /* 🧊 Sidebar biru navy */
        aside {
            background: linear-gradient(180deg, #5d75acff 0%, #231e84ff 100%);
            box-shadow: inset -2px 0 6px rgba(0,0,0,0.25);
        }

        aside a {
            transition: all 0.25s ease;
        }
        aside a:hover {
            transform: translateX(3px);
            background-color: rgba(30, 58, 138, 0.6);
        }

        /* ✨ Efek Fade-In */
        @keyframes fade-in-row {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-row { animation: fade-in-row 0.5s ease-out forwards; }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-900 relative">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="text-white w-64 space-y-6 py-6 px-3 hidden md:block flex-shrink-0 relative z-20">
            <div class="flex items-center space-x-3 px-4">
                <img src="{{ asset('images/logounib.png') }}" alt="Logo UNIB" class="h-14 w-14">
                <h1 class="text-lg font-bold">Profil</h1>
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

            <div class="border-t border-blue-700 mt-auto pt-4 px-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center py-2 px-4 w-full text-left rounded hover:bg-blue-800 transition">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-semibold">Logout</span>
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
    @stack('scripts')
</body>
</html>
