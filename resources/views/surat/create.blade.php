<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Surat Baru</title>
</head>
<body class="antialiased">
    <x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center bg-gradient-to-r from-blue-900 to-blue-700 p-6 rounded-b-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center pl-4 text-left w-full md:w-auto">
                    <svg class="h-6 w-6 text-blue-100 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6h4v6m1 0l-2 2m2-2l2-2" />
                    </svg>
                    <h2 class="font-semibold text-xl text-blue-100 leading-tight">{{ __('Ajukan Surat Baru') }}</h2>
                </div>
                <a href="{{ route('dashboard') }}" class="form-button text-white">Kembali ke Dashboard</a>
            </div>
        </x-slot>

        <div class="py-12" x-data="{ isLoaded: false }" x-init="setTimeout(() => { isLoaded = true; }, 300)">
            <div x-show="isLoaded" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-500 hover:shadow-xl">
                        <div class="p-6 bg-blue-50">
                            <h3 class="text-lg font-medium text-blue-700 flex items-center gap-2">
                                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Form Ajukan Surat Baru
                            </h3>
                        </div>
                        <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" class="p-6 space-y-6" novalidate>
                            @csrf
                            <div>
                                <x-input-label for="jenis_surat_id" value="{{ __('Pilih Jenis Surat') }}" />
                                <select id="jenis_surat_id" name="jenis_surat_id" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm transition-all duration-300 hover:border-blue-600">
                                    <option value="" disabled selected>-- Pilih salah satu --</option>
                                    @foreach($jenisSurats as $jenis)
                                        <option value="{{ $jenis->id }}" {{ old('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_surat }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('jenis_surat_id')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="keterangan" value="{{ __('Keterangan (Opsional)') }}" />
                                <textarea id="keterangan" name="keterangan" rows="4" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" placeholder="Tambahkan keterangan...">{{ old('keterangan') }}</textarea>
                                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                            </div>
                            <div id="syarat-dokumen-container" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md" style="display: none;">
                                <h4 class="font-bold text-blue-700">Syarat Dokumen:</h4>
                                <ul id="syarat-dokumen-list" class="list-disc list-inside text-blue-600 mt-2"></ul>
                                <p class="text-sm text-blue-500 mt-2">Gabungkan dokumen jadi satu file (PDF/JPG/PNG/DOCX).</p>
                            </div>

                            <div id="dynamic-fields-container" class="mt-4 space-y-4">
                                <div id="fields_for_1" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Perubahan Nilai</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Nama MK" /><x-text-input type="text" name="extra_data[nama_matakuliah]" :value="old('extra_data.nama_matakuliah')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Kode MK" /><x-text-input type="text" name="extra_data[kode_matakuliah]" :value="old('extra_data.kode_matakuliah')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="SKS" /><x-text-input type="number" name="extra_data[sks]" :value="old('extra_data.sks')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Dosen Pengampu" /><x-text-input type="text" name="extra_data[nama_dosen_matakuliah]" :value="old('extra_data.nama_dosen_matakuliah')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Nilai Lama" /><x-text-input type="text" name="extra_data[nilai_lama]" :value="old('extra_data.nilai_lama')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Nilai Baru" /><x-text-input type="text" name="extra_data[nilai_baru]" :value="old('extra_data.nilai_baru')" class="block mt-1 w-full" /></div>
                                </div>
                                <div id="fields_for_2" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Input Nilai Skripsi</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Nilai Skripsi" /><x-text-input type="text" name="extra_data[nilai]" :value="old('extra_data.nilai')" class="block mt-1 w-full" /></div>
                                </div>
                                <div id="fields_for_3" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Keterlambatan SPP</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /><x-input-error :messages="$errors->get('extra_data.nim')" class="mt-2" /></div>
                                </div>
                                <div id="fields_for_5" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Pengajuan Cuti</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Semester Cuti" /><x-text-input type="text" name="extra_data[semester_cuti]" :value="old('extra_data.semester_cuti')" class="block mt-1 w-full" /></div>
                                </div>
                                <div id="fields_for_6" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Pengambilan Data</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                </div>
                                <div id="fields_for_7" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Pengantar Magang</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Nama Instansi" /><x-text-input type="text" name="extra_data[nama_instansi]" :value="old('extra_data.nama_instansi')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Tanggal Mulai" /><x-text-input type="date" name="extra_data[tanggal_mulai_magang]" :value="old('extra_data.tanggal_mulai_magang')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Tanggal Selesai" /><x-text-input type="date" name="extra_data[tanggal_selesai_magang]" :value="old('extra_data.tanggal_selesai_magang')" class="block mt-1 w-full" /></div>
                                </div>
                                <div id="fields_for_8" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Pengantar TOEFL</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Tanggal Seminar Hasil" /><x-text-input type="date" name="extra_data[tanggal_seminar_hasil]" :value="old('extra_data.tanggal_seminar_hasil')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Periode Wisuda" /><x-text-input type="text" name="extra_data[periode_wisuda]" :value="old('extra_data.periode_wisuda')" class="block mt-1 w-full" /></div>
                                </div>
                                <div id="fields_for_9" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Pengunduran Diri</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Semester" /><x-text-input type="text" name="extra_data[semester]" :value="old('extra_data.semester')" class="block mt-1 w-full" /></div>
                                </div>
                                <div id="fields_for_10" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
    <h4 class="font-bold text-gray-800">Data Surat Perjanjian</h4>
    
    <div>
        <x-input-label for="nim_10" :value="__('NPM / NIM')" />
        <x-text-input id="nim_10" class="block mt-1 w-full" type="text" name="extra_data[nim]" :value="old('extra_data.nim')" />
        <x-input-error :messages="$errors->get('extra_data.nim')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tanggal_awal_10" :value="__('Tanggal Mulai (Menyelesaikan Skripsi)')" />
        <x-text-input id="tanggal_awal_10" class="block mt-1 w-full" type="date" name="extra_data[tanggal_awal]" :value="old('extra_data.tanggal_awal')" />
        <x-input-error :messages="$errors->get('extra_data.tanggal_awal')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tanggal_akhir_10" :value="__('Tanggal Akhir (Paling Lambat)')" />
        <x-text-input id="tanggal_akhir_10" class="block mt-1 w-full" type="date" name="extra_data[tanggal_akhir]" :value="old('extra_data.tanggal_akhir')" />
        <x-input-error :messages="$errors->get('extra_data.tanggal_akhir')" class="mt-2" />
    </div>
</div>
                                <div id="fields_for_11" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Surat Pernyataan</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                </div>
                                <div id="fields_for_12" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Pindah Kuliah</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Semester Saat Ini" /><x-text-input type="number" name="extra_data[semester]" :value="old('extra_data.semester')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Prodi Tujuan" /><x-text-input type="text" name="extra_data[prodi_tujuan]" :value="old('extra_data.prodi_tujuan')" class="block mt-1 w-full" /></div>
                                </div>
<div id="fields_for_13" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
    <h4 class="font-bold text-gray-800">Data Remedial</h4>
    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
    <div><x-input-label value="Nama MK Remedial" /><x-text-input type="text" name="extra_data[nama_matakuliah]" :value="old('extra_data.nama_matakuliah')" class="block mt-1 w-full" /></div>
    <div><x-input-label value="Kode MK" /><x-text-input type="text" name="extra_data[kode_matakuliah]" :value="old('extra_data.kode_matakuliah')" class="block mt-1 w-full" /></div>
    <div><x-input-label value="SKS" /><x-text-input type="number" name="extra_data[sks]" :value="old('extra_data.sks')" class="block mt-1 w-full" /></div>
    
    <div>
        <x-input-label value="Dosen Remedial" />
        <x-text-input type="text" name="extra_data[dosen_remedial]" :value="old('extra_data.dosen_remedial')" class="block mt-1 w-full" placeholder="Nama Dosen Remedial" />
    </div>
</div>
                                <div id="fields_for_14" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Usulan UKT 50%</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Semester Pengajuan" /><x-text-input type="text" name="extra_data[semester]" :value="old('extra_data.semester')" class="block mt-1 w-full" /></div>
                                    <div><x-input-label value="Total SKS Diambil" /><x-text-input type="number" name="extra_data[jumlah_sks_diambil]" :value="old('extra_data.jumlah_sks_diambil')" class="block mt-1 w-full" /></div>
                                </div>
                                <div id="fields_for_15" class="dynamic-field-wrapper p-4 bg-gray-50 border border-gray-200 rounded-md space-y-4" style="display: none;">
                                    <h4 class="font-bold text-gray-800">Data Surat Keterangan Lulus</h4>
                                    <div><x-input-label value="NPM / NIM" /><x-text-input type="text" name="extra_data[nim]" :value="old('extra_data.nim')" class="block mt-1 w-full" /><x-input-error :messages="$errors->get('extra_data.nim')" class="mt-2" /></div>
                                    <div>
                                        <x-input-label value="Jenis Kelamin" />
                                        <select name="extra_data[jenis_kelamin]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="" disabled selected>-- Pilih --</option>
                                            <option value="Laki-laki" {{ old('extra_data.jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('extra_data.jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('extra_data.jenis_kelamin')" class="mt-2" />
                                    </div>
                                    <div><x-input-label value="Judul Skripsi" /><textarea name="extra_data[judul_skripsi]" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('extra_data.judul_skripsi') }}</textarea><x-input-error :messages="$errors->get('extra_data.judul_skripsi')" class="mt-2" /></div>
                                    <div><x-input-label value="Pembimbing 1" /><x-text-input type="text" name="extra_data[pembimbing_1]" :value="old('extra_data.pembimbing_1')" class="block mt-1 w-full" /><x-input-error :messages="$errors->get('extra_data.pembimbing_1')" class="mt-2" /></div>
                                    <div><x-input-label value="Pembimbing 2" /><x-text-input type="text" name="extra_data[pembimbing_2]" :value="old('extra_data.pembimbing_2')" class="block mt-1 w-full" /><x-input-error :messages="$errors->get('extra_data.pembimbing_2')" class="mt-2" /></div>
                                    <div><x-input-label value="Tanggal Ujian" /><x-text-input type="date" name="extra_data[tanggal_ujian]" :value="old('extra_data.tanggal_ujian')" class="block mt-1 w-full" /><x-input-error :messages="$errors->get('extra_data.tanggal_ujian')" class="mt-2" /></div>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="lampiran" value="{{ __('Upload Lampiran (Opsional)') }}" />
                                <input id="lampiran" name="lampiran" type="file" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                                <x-input-error :messages="$errors->get('lampiran')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-end mt-8">
    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 ease-in-out transform hover:scale-105">
        {{ __('Ajukan Surat') }}
    </button>
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
                const allWrappers = document.querySelectorAll('.dynamic-field-wrapper');

                function updateUI(selectedId) {
                    syaratList.innerHTML = '';
                    if (selectedId && syaratDokumen[selectedId]) {
                        syaratDokumen[selectedId].forEach(function(doc) {
                            const li = document.createElement('li'); li.textContent = doc; syaratList.appendChild(li);
                        });
                        syaratContainer.style.display = 'block';
                    } else {
                        syaratContainer.style.display = 'none';
                    }

                    allWrappers.forEach(function(wrapper) {
                        if (wrapper.id === 'fields_for_' + selectedId) {
                            wrapper.style.display = 'block';
                            wrapper.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
                        } else {
                            wrapper.style.display = 'none';
                            wrapper.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                        }
                    });
                }

                jenisSuratSelect.addEventListener('change', function() { updateUI(this.value); });
                updateUI(jenisSuratSelect.value);
            });
        </script>
    </x-app-layout>
</body>
</html>