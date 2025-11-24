<x-app-layout>
    <div class="h-screen w-full flex items-center justify-center bg-white text-gray-800 relative overflow-hidden">

        <!-- ✨ Background Aksen Lembut -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-[-200px] left-[-200px] w-[600px] h-[600px] bg-blue-100 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-250px] right-[-250px] w-[700px] h-[700px] bg-blue-50 rounded-full blur-3xl"></div>
        </div>

        <!-- 🧠 Konten Utama -->
        <div class="z-10 flex flex-col md:flex-row items-center justify-between gap-10 px-10 md:px-20 w-full max-w-7xl h-full">

            <!-- 📝 Teks -->
            <div class="flex-1 flex flex-col justify-center space-y-8 animate-fade-in">
                <h1 class="text-5xl md:text-6xl font-extrabold leading-tight tracking-tight">
                    <span class="block text-gray-900">Sistem Pengajuan</span>
                    <span class="block text-blue-700">Surat Menyurat Informatika</span>
                </h1>

                <p class="text-lg text-gray-600 leading-relaxed max-w-lg">
                    Selamat datang, <span class="font-semibold text-blue-700">{{ auth()->user()->name }}</span>!  
                    Nikmati kemudahan mengajukan dan memantau suratmu secara digital di  
                    <span class="text-blue-700">Program Studi Informatika</span>.
                </p>

                <!-- Tombol utama -->
                <a href="{{ route('surat.create') }}"
                   class="inline-flex items-center gap-3 bg-blue-700 text-white font-semibold px-8 py-4 rounded-xl 
                          shadow-md hover:bg-blue-800 hover:scale-105 transition-transform duration-300">
                    <i class="fa-solid fa-paper-plane text-lg"></i>
                    Ajukan Surat Baru
                </a>

                <!-- Ikon info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6">
                    <div class="flex items-center gap-4 group hover:translate-x-1 transition-transform">
                        <div class="bg-blue-100 p-4 rounded-2xl group-hover:bg-blue-200 transition">
                            <i class="fa-solid fa-bolt text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Respons Cepat</p>
                            <p class="text-sm text-gray-500">Proses surat otomatis dan real-time.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 group hover:translate-x-1 transition-transform">
                        <div class="bg-blue-100 p-4 rounded-2xl group-hover:bg-blue-200 transition">
                            <i class="fa-solid fa-diagram-project text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Terintegrasi</p>
                            <p class="text-sm text-gray-500">Satu sistem untuk semua kebutuhan surat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🧑‍💻 Ilustrasi -->
            <div class="flex-1 flex justify-center relative animate-float">
                <div class="bg-blue-50 p-6 rounded-3xl shadow-xl">
                    <img src="{{ asset('images/illustration-surat.png') }}" 
                         alt="Ilustrasi surat menyurat"
                         class="w-80 md:w-[420px] rounded-2xl">
                </div>
                <div class="absolute -top-10 -right-10 bg-blue-200 w-32 h-32 rounded-full blur-2xl"></div>
            </div>
        </div>

        <!-- ⚙️ Footer kecil -->
        <footer class="absolute bottom-4 text-gray-500 text-sm">
            © {{ date('Y') }} Sistem Pengajuan Surat - Program Studi Informatika
        </footer>
    </div>

    <!-- 🌈 Animasi -->
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-float { animation: float 4s ease-in-out infinite; }
        .animate-fade-in { animation: fade-in 1.2s ease-out forwards; }

        /* 🔒 Nonaktifkan scroll */
        html, body {
            overflow: hidden;
            height: 100%;
        }
    </style>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a2d9d5a64a.js" crossorigin="anonymous"></script>
</x-app-layout>
