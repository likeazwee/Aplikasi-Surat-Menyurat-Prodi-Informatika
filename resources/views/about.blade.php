<x-app-layout>
    <div class="h-screen flex flex-col items-center justify-start bg-white text-gray-800 overflow-hidden pt-32">
        <div class="max-w-5xl text-center space-y-6 animate-fadeIn">
            <h1 class="text-5xl font-extrabold tracking-tight text-blue-900">
                Tentang <span class="text-blue-600">Surat Menyurat Informatika</span>
            </h1>

            <p class="text-lg text-gray-600 leading-relaxed max-w-3xl mx-auto">
                Sistem <strong>Surat Menyurat Informatika</strong> adalah platform digital yang dikembangkan 
                untuk mempermudah mahasiswa dan dosen Program Studi Informatika dalam 
                melakukan pengajuan dan pelacakan surat secara cepat, efisien, dan terintegrasi.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition">
                    <div class="text-4xl mb-3 text-blue-700">⚙️</div>
                    <h3 class="font-semibold text-xl mb-2 text-blue-900">Efisiensi Digital</h3>
                    <p class="text-gray-600 text-sm">
                        Semua proses surat menyurat dilakukan secara daring, menghemat waktu dan tenaga.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition">
                    <div class="text-4xl mb-3 text-blue-700">🔒</div>
                    <h3 class="font-semibold text-xl mb-2 text-blue-900">Keamanan Data</h3>
                    <p class="text-gray-600 text-sm">
                        Setiap data surat dan pengguna dijaga dengan enkripsi dan autentikasi yang kuat.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200 hover:shadow-xl transition">
                    <div class="text-4xl mb-3 text-blue-700">📈</div>
                    <h3 class="font-semibold text-xl mb-2 text-blue-900">Transparansi</h3>
                    <p class="text-gray-600 text-sm">
                        Status pengajuan surat dapat dipantau secara real-time kapan pun dibutuhkan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        html, body {
            overflow: hidden !important;
            height: 100%;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.8s ease forwards;
        }
    </style>
</x-app-layout>
