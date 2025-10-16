<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-white">

    <div class="flex min-h-screen">

        <!-- Bagian kiri (biru) -->
        <div class="hidden md:flex w-1/2 bg-gradient-to-br from-blue-700 to-blue-500 text-white items-center justify-center p-12 relative overflow-hidden rounded-r-[60px] shadow-2xl">
            <div class="max-w-md z-10">
                <img src="{{ asset('images/logounib.png') }}" alt="Logo" class="w-20 h-20 mb-6">
                <h1 class="text-4xl font-bold mb-4">Halo, Informatika! 👋</h1>
                <p class="text-sm text-blue-100 leading-relaxed">
                    Selamat datang di sistem <b>Surat Menyurat Program Studi Informatika</b>.  
                    Kelola semua surat akademikmu dengan mudah dan cepat di satu tempat!
                </p>
                <p class="text-xs text-blue-200 mt-16">© {{ date('Y') }} Informatika UNIB. All rights reserved.</p>
            </div>

            <!-- dekorasi latar belakang -->
            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/20 to-transparent"></div>
        </div>

        <!-- Bagian kanan (login form) -->
        <div class="flex w-full md:w-1/2 items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800">Welcome Back!</h2>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </div>

</body>
</html>
