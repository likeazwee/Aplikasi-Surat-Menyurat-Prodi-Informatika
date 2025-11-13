<x-app-layout>
    <div class="h-screen w-full flex justify-center items-start bg-gradient-to-b from-white to-blue-50 text-gray-800 overflow-hidden pt-24">
        <div class="bg-white shadow-2xl rounded-3xl p-12 max-w-5xl w-full flex flex-col md:flex-row items-center justify-between gap-10 animate-fadeIn border border-blue-100">

            <!-- 🏫 Bagian Kiri -->
            <div class="flex-1 space-y-6 text-center md:text-left">
                <h1 class="text-5xl font-extrabold text-blue-900 leading-tight">
                    Hubungi <span class="text-blue-600">Kami</span>
                </h1>
                <p class="text-gray-600 text-lg leading-relaxed max-w-md mx-auto md:mx-0">
                    Kami dari <strong>Program Studi Informatika</strong> siap membantu Anda dalam urusan surat menyurat, akademik, maupun informasi terkait kegiatan kampus.
                </p>

                <div class="space-y-4 pt-2">
                    <div class="flex items-start justify-center md:justify-start space-x-3">
                        <span class="text-blue-600 text-xl">📍</span>
                        <p class="text-gray-700 font-medium">
                            Jl. WR Supratman, Kandang Limun, Bengkulu 38371
                        </p>
                    </div>

                    <div class="flex items-start justify-center md:justify-start space-x-3">
                        <span class="text-blue-600 text-xl">📧</span>
                        <p class="text-gray-700 font-medium">informatika@unib.ac.id</p>
                    </div>

                    <div class="flex items-start justify-center md:justify-start space-x-3">
                        <span class="text-blue-600 text-xl">📞</span>
                        <p class="text-gray-700 font-medium">+62 736 21170</p>
                    </div>

                    <div class="flex items-start justify-center md:justify-start space-x-3">
                        <span class="text-blue-600 text-xl">🌐</span>
                        <a href="https://informatika.unib.ac.id" target="_blank"
                           class="text-blue-600 hover:text-blue-800 font-medium underline transition">
                            informatika.unib.ac.id
                        </a>
                    </div>
                </div>
            </div>

            <!-- 💡 Bagian Kanan -->
            <div class="flex-1 flex justify-center">
                <div class="relative bg-gradient-to-br from-blue-700 to-blue-900 text-white p-10 rounded-2xl shadow-lg w-[340px] h-[340px] flex flex-col justify-center items-center overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 rounded-2xl backdrop-blur-sm"></div>
                    <div class="relative z-10 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-20 h-20 mx-auto mb-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5A2.25 2.25 0 012.25 17.25V6.75M21.75 6.75l-9.75 6.75L2.25 6.75" />
                        </svg>
                        <h2 class="text-2xl font-semibold mb-2">Program Studi Informatika</h2>
                        <p class="text-blue-100 text-sm leading-relaxed">
                            Fakultas Sains dan Teknologi<br>Universitas Bengkulu
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Hilangkan scroll dan pusatkan konten */
        html, body {
            overflow: hidden !important;
            height: 100%;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.7s ease-out forwards;
        }
    </style>
</x-app-layout>
