<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Surat Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Jenis Surat -->
                        <div>
                            <x-input-label for="jenis_surat_id" :value="__('Pilih Jenis Surat')" />
                            <select id="jenis_surat_id" name="jenis_surat_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>-- Pilih salah satu --</option>
                                @foreach($jenisSurats as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama_surat }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('jenis_surat_id')" class="mt-2" />
                        </div>

                        <!-- Keterangan -->
                        <div class="mt-4">
                            <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                            <textarea id="keterangan" name="keterangan" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('keterangan') }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <!-- Syarat Dokumen (Bagian Dinamis Baru) -->
                        <div id="syarat-dokumen-container" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md" style="display: none;">
                            <h4 class="font-bold text-gray-800">Syarat Dokumen yang Diperlukan:</h4>
                            <ul id="syarat-dokumen-list" class="list-disc list-inside text-gray-700 mt-2">
                                <!-- List akan diisi oleh JavaScript -->
                            </ul>
                            <p class="text-sm text-gray-500 mt-2">Mohon gabungkan semua dokumen yang diperlukan menjadi satu file PDF/JPG/PNG untuk di-upload.</p>
                        </div>
                        
                        <!-- Upload Lampiran -->
                        <div class="mt-4">
                            <x-input-label for="lampiran" :value="__('Upload Lampiran (Opsional - PDF, JPG, PNG, maks 2MB)')" />
                            <input id="lampiran" name="lampiran" type="file" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <x-input-error :messages="$errors->get('lampiran')" class="mt-2" />
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            {{-- 👇 TOMBOL KEMBALI DITAMBAHKAN DI SINI 👇 --}}
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                {{ __('Kembali') }}
                            </a>

                            <x-primary-button class="ms-3">
                                {{ __('Ajukan Surat') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk menampilkan syarat dokumen secara dinamis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const syaratDokumen = @json($syaratDokumen);

            const jenisSuratSelect = document.getElementById('jenis_surat_id');
            const syaratContainer = document.getElementById('syarat-dokumen-container');
            const syaratList = document.getElementById('syarat-dokumen-list');

            jenisSuratSelect.addEventListener('change', function() {
                const selectedId = this.value;

                // Reset tampilan setiap kali pilihan berubah
                syaratList.innerHTML = '';
                syaratContainer.style.display = 'none';

                // Jika ID yang dipilih ada di dalam data syaratDokumen kita
                if (selectedId && syaratDokumen[selectedId]) {
                    const dokumens = syaratDokumen[selectedId];
                    
                    // Buat list item untuk setiap syarat dokumen
                    dokumens.forEach(function(dokumen) {
                        const li = document.createElement('li');
                        li.textContent = dokumen;
                        syaratList.appendChild(li);
                    });

                    // Tampilkan kontainer syarat dokumen
                    syaratContainer.style.display = 'block';
                }
            });
        });
    </script>
</x-app-layout>
