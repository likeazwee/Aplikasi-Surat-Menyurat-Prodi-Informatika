<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Surat Baru</title>
    <style>
        /* Animasi Background Modern */
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

        /* Efek Hover untuk Tombol */
        .form-button {
            @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-700 to-blue-500 text-white font-semibold text-sm rounded-full shadow hover:shadow-lg transform hover:scale-105 transition-all duration-300;
        }
    </style>
</head>
<body class="antialiased">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center bg-gradient-to-r from-blue-900 to-blue-700 p-6 rounded-b-xl shadow-lg transition-all duration-300 hover:shadow-xl animate-fade-in">
                <!-- Bagian Kiri: Ajukan Surat Baru -->
                <div class="flex items-center pl-4 text-left w-full md:w-auto">
                    <svg class="h-6 w-6 text-blue-100 mr-2 animate-pulse-slow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6m1 0l-2 2m2-2l2-2" />
                    </svg>
                    <h2 class="font-semibold text-xl text-blue-100 leading-tight">
                        {{ __('Ajukan Surat Baru') }}
                    </h2>
                </div>
                
                <!-- Tombol Kembali ke Dashboard dengan Teks Putih -->
                <a href="{{ route('dashboard') }}" class="form-button text-white">
                    Kembali ke Dashboard
                </a>
            </div>
        </x-slot>

        <div class="relative min-h-screen animated-background overflow-hidden" x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true; }, 300)">
            <div class="absolute inset-0 opacity-50"></div>
            <div class="relative z-10 py-12" x-show="isLoaded" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">  <!-- Diperkecil dari max-w-7xl menjadi max-w-6xl -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-500 hover:shadow-xl animate-fade-in-slow">
                        <div class="p-6 bg-blue-50">
                            <h3 class="text-lg font-medium text-blue-700 flex items-center gap-2">
                                <svg class="h-6 w-6 text-blue-600 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Form Ajukan Surat Baru
                            </h3>
                        </div>
                        <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" class="p-6 space-y-6 animate-fade-in">
                            @csrf

                            <!-- Jenis Surat -->
                            <div>
                                <x-input-label for="jenis_surat_id" value="{{ __('Pilih Jenis Surat') }}" />
                                <select id="jenis_surat_id" name="jenis_surat_id" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-300 hover:border-blue-600" required>
                                    <option value="" disabled selected>-- Pilih salah satu --</option>
                                    @foreach($jenisSurats as $jenis)
                                        <option value="{{ $jenis->id }}">{{ $jenis->nama_surat }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('jenis_surat_id')" class="mt-2" />
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <x-input-label for="keterangan" value="{{ __('Keterangan (Opsional)') }}" />
                                <textarea id="keterangan" name="keterangan" rows="4" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-300 hover:border-blue-600">{{ old('keterangan') }}</textarea>
                                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                            </div>

                            <!-- Syarat Dokumen -->
                            <div id="syarat-dokumen-container" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md" style="display: none;">
                                <h4 class="font-bold text-blue-700">Syarat Dokumen yang Diperlukan:</h4>
                                <ul id="syarat-dokumen-list" class="list-disc list-inside text-blue-600 mt-2">
                                    <!-- List akan diisi oleh JavaScript -->
                                </ul>
                                <p class="text-sm text-blue-500 mt-2">Mohon gabungkan semua dokumen yang diperlukan menjadi satu file PDF/JPG/PNG untuk di-upload.</p>
                            </div>
                            
                            <!-- Upload Lampiran -->
                            <div>
                                <x-input-label for="lampiran" value="{{ __('Upload Lampiran (Opsional - PDF, JPG, PNG, maks 2MB)') }}" />
                                <input id="lampiran" name="lampiran" type="file" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                                <x-input-error :messages="$errors->get('lampiran')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="form-button text-white">
                                    {{ __('Ajukan Surat') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const syaratDokumen = @json($syaratDokumen);
                const jenisSuratSelect = document.getElementById('jenis_surat_id');
                const syaratContainer = document.getElementById('syarat-dokumen-container');
                const syaratList = document.getElementById('syarat-dokumen-list');

                jenisSuratSelect.addEventListener('change', function() {
                    const selectedId = this.value;
                    syaratList.innerHTML = '';
                    syaratContainer.style.display = 'none';
                    if (selectedId && syaratDokumen[selectedId]) {
                        const dokumens = syaratDokumen[selectedId];
                        dokumens.forEach(function(dokumen) {
                            const li = document.createElement('li');
                            li.textContent = dokumen;
                            syaratList.appendChild(li);
                        });
                        syaratContainer.style.display = 'block';
                    }
                });
            });
        </script>
    </x-app-layout>
</body>
</html>
