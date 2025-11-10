<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Surat Baru</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* 🔹 Animasi Background Modern */
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

        /* 🔹 Efek Tombol */
        .form-button {
            @apply inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-700 to-blue-500 text-white font-semibold text-sm rounded-full shadow hover:shadow-lg transform hover:scale-105 transition-all duration-300;
        }

        /* 🔹 Animasi Fade-in */
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        .animate-fade-in-slow { animation: fadeIn 1.2s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 🔹 Tombol Putih Elegan */
        .white-button {
            @apply inline-flex items-center px-5 py-2 bg-white text-blue-700 font-semibold text-sm rounded-full shadow hover:bg-blue-50 hover:shadow-lg transform hover:scale-105 transition-all duration-300;
        }
    </style>
</head>

<body class="antialiased">

    <!-- 🔷 NAVBAR -->
    <nav class="bg-gradient-to-r from-[#0a1a3f] to-[#122b73] shadow-md">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
            <div class="flex justify-between h-20 items-center">

                <!-- 🟡 Logo + Dashboard Text (NOT clickable) + Dashboard Link (clickable) -->
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2 cursor-default select-none">
                        <img src="{{ asset('images/logounib.png') }}" alt="Logo" class="h-12 w-auto" />
                        <span class="text-white font-semibold text-lg">Surat Menyurat</span>
                    </div>
                    <a href="{{ route('dashboard') }}"
                       class="relative text-white text-sm font-medium transition-all duration-300 group">
                        Dashboard
                        <span class="absolute left-0 bottom-0 w-0 h-[2px] bg-white group-hover:w-full transition-all duration-300"></span>
                    </a>
                </div>

                <!-- 👤 User Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-600 rounded-md transition">
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="ml-2 w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')"> {{ __('Profile') }} </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </nav>

    <!-- 🔹 MAIN CONTENT -->
    <div class="relative min-h-screen animated-background overflow-hidden py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-500 hover:shadow-3xl animate-fade-in-slow">
                
                <!-- Header -->
                <div class="flex justify-between items-center bg-gradient-to-r from-blue-900 to-blue-700 p-6 rounded-t-2xl">
                    <div class="flex items-center text-white">
                        <svg class="h-6 w-6 mr-2 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6" />
                        </svg>
                        <h2 class="text-lg md:text-xl font-semibold tracking-wide">Ajukan Surat Baru</h2>
                    </div>
                    <a href="{{ route('dashboard') }}"
   class="inline-flex items-center px-5 py-2 bg-white text-blue-700 font-semibold text-sm rounded-full shadow hover:shadow-lg transform hover:scale-105 transition-all duration-300">
    ← Kembali ke Dashboard
</a>

                </div>

                <!-- Form Section -->
                <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" class="p-8 space-y-6 animate-fade-in">
                    @csrf

                    <!-- Jenis Surat -->
                    <div>
                        <x-input-label for="jenis_surat_id" value="{{ __('Pilih Jenis Surat') }}" />
                        <select id="jenis_surat_id" name="jenis_surat_id"
                            class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-300 hover:border-blue-600" required>
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
                        <textarea id="keterangan" name="keterangan" rows="4"
                            class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-300 hover:border-blue-600">{{ old('keterangan') }}</textarea>
                        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                    </div>

                    <!-- Syarat Dokumen -->
                    <div id="syarat-dokumen-container" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md hidden">
                        <h4 class="font-bold text-blue-700">Syarat Dokumen yang Diperlukan:</h4>
                        <ul id="syarat-dokumen-list" class="list-disc list-inside text-blue-600 mt-2"></ul>
                        <p class="text-sm text-blue-500 mt-2">Mohon gabungkan semua dokumen menjadi satu file PDF/JPG/PNG untuk diunggah.</p>
                    </div>

                    <!-- Upload -->
                    <div>
                        <x-input-label for="lampiran" value="{{ __('Upload Lampiran (Opsional - PDF, JPG, PNG, maks 2MB)') }}" />
                        <input id="lampiran" name="lampiran" type="file"
                            class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                        <x-input-error :messages="$errors->get('lampiran')" class="mt-2" />
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center justify-end pt-4">
                        <x-primary-button class="form-button">
                            {{ __('Ajukan Surat') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const syaratDokumen = @json($syaratDokumen);
            const jenisSuratSelect = document.getElementById('jenis_surat_id');
            const syaratContainer = document.getElementById('syarat-dokumen-container');
            const syaratList = document.getElementById('syarat-dokumen-list');

            jenisSuratSelect.addEventListener('change', function() {
                const selectedId = this.value;
                syaratList.innerHTML = '';
                syaratContainer.classList.add('hidden');

                if (selectedId && syaratDokumen[selectedId]) {
                    const dokumens = syaratDokumen[selectedId];
                    dokumens.forEach(function(dokumen) {
                        const li = document.createElement('li');
                        li.textContent = dokumen;
                        syaratList.appendChild(li);
                    });
                    syaratContainer.classList.remove('hidden');
                }
            });
        });
    </script>

</body>
</html>
