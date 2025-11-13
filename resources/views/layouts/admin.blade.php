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

    {{-- 🎨 Style Admin Layout --}}
    <style>
        /* 🩶 Background Silver Classy (tanpa animasi) */
        .main-background {
            background: linear-gradient(135deg, #d7d7d7 0%, #bcbcbc 40%, #9a9a9a 80%, #7c7c7c 100%);
            background-attachment: fixed;
            background-size: cover;
            min-height: 100vh;
            color: #1a1a1a;
            position: relative;
        }

        /* ✨ Lapisan kilau lembut */
        .main-background::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.3), transparent 60%),
                        radial-gradient(circle at 80% 80%, rgba(255,255,255,0.2), transparent 70%);
            pointer-events: none;
            mix-blend-mode: overlay;
        }

        /* 🧊 Sidebar dengan kontras biru tua */
        aside {
            background: linear-gradient(180deg, #5d75acff 0%, #231e84ff 100%);
            box-shadow: inset -2px 0 6px rgba(0,0,0,0.25);
        }

        aside a {
            transition: all 0.25s ease;
        }
        aside a:hover {
            transform: translateX(3px);
        }

        /* 💎 Tombol dan elemen interaktif */
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

        /* ✨ Efek Fade-In Halus */
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
<body class="font-sans antialiased text-gray-900 relative">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="text-white w-64 space-y-6 py-6 px-3 hidden md:block flex-shrink-0 relative z-20">
            <div class="flex items-center space-x-3 px-4">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logounib.png') }}" alt="Logo UNIB" class="h-14 w-14">
                </a>
                <h1 class="text-lg font-bold">Admin Prodi</h1>
            </div>

            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center py-2 px-4 rounded hover:bg-blue-800 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800' : '' }}">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-semibold">Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center py-2 px-4 rounded hover:bg-blue-800 {{ request()->routeIs('admin.users.*') ? 'bg-blue-800' : '' }}">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-semibold">Kelola Pengguna</span>
                </a>
            </nav>

            <a href="{{ route('profile.edit') }}"
               class="flex items-center py-2 px-4 rounded hover:bg-blue-800 transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'bg-blue-800' : '' }}">
                <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A10.97 10.97 0 0112 15c2.137 0 4.11.625 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-semibold">Profil</span>
            </a>

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

        <!-- Konten Utama -->
        <div class="flex-1 flex flex-col relative">
            <main class="flex-1 p-6 main-background relative overflow-hidden">
                <div class="absolute inset-0 opacity-60"></div>
                <div class="relative z-10">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
