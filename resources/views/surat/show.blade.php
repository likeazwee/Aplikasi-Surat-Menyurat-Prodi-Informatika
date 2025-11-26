{{-- Menentukan layout berdasarkan role user yang sedang login --}}
@extends(in_array(Auth::user()->role, ['admin', 'kaprodi']) ? 'layouts.admin' : 'layouts.app')

@section('header', 'Detail Pengajuan Surat')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- 1. Header & Navigasi --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            {{-- Tombol Kembali Dinamis --}}
            @php
                $backRoute = in_array(Auth::user()->role, ['admin', 'kaprodi']) ? route('admin.dashboard') : route('dashboard');
            @endphp
            <a href="{{ $backRoute }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-700 transition mb-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $surat->jenisSurat->nama_surat }}</h1>
            <p class="text-sm text-gray-500">Diajukan pada {{ $surat->created_at->translatedFormat('l, d F Y, H:i') }}</p>
        </div>

        {{-- Badge Status --}}
        <div>
            @if($surat->status == 'disetujui')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    DISETUJUI
                </span>
                
                {{-- Tombol Download (Hanya Admin/Kaprodi) --}}
                @if(in_array(Auth::user()->role, ['admin', 'kaprodi']))
                    <a href="{{ route('admin.surat.download', $surat->id) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-full shadow-md transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download
                    </a>
                @endif

            @elseif($surat->status == 'ditolak')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    DITOLAK
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 shadow-sm animate-pulse">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    MENUNGGU KONFIRMASI
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- 2. Kolom Kiri: Detail Surat --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Data Pengajuan
                    </h3>
                </div>
                
                <div class="p-6 space-y-5">
                    {{-- Info User --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Nama Mahasiswa</span>
                            <p class="text-gray-900 font-semibold">{{ $surat->user->name }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">NIM</span>
                            <p class="text-gray-900 font-semibold">{{ $surat->user->profile->nim ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Data Dinamis (Extra Data) --}}
                    @if(!empty($surat->extra_data))
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                            @foreach($surat->extra_data as $key => $value)
                                <div class="border-b border-gray-100 pb-2">
                                    <span class="block text-xs font-bold text-gray-500 uppercase mb-1">
                                        {{ str_replace('_', ' ', $key) }}
                                    </span>
                                    <span class="text-gray-800">
                                        @if(strtotime($value) && strlen($value) == 10) 
                                            {{ \Carbon\Carbon::parse($value)->translatedFormat('d F Y') }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Keterangan Tambahan --}}
                    @if($surat->keterangan)
                        <div class="mt-4">
                            <span class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan Tambahan</span>
                            <div class="bg-gray-50 p-3 rounded-lg text-sm text-gray-700 italic border border-gray-200">
                                "{{ $surat->keterangan }}"
                            </div>
                        </div>
                    @endif

                    {{-- Lampiran --}}
                    <div class="pt-4 border-t border-gray-100">
                        <span class="block text-xs font-bold text-gray-500 uppercase mb-2">Lampiran Dokumen</span>
                        @if ($surat->file_path)
                            <a href="{{ Storage::url($surat->file_path) }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition shadow-sm group">
                                <svg class="w-5 h-5 mr-2 text-blue-500 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                <span class="font-medium text-sm">Lihat File Lampiran</span>
                            </a>
                        @else
                            <span class="text-gray-400 text-sm italic">Tidak ada file lampiran.</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Area Download & Pesan untuk Mahasiswa (Jika Disetujui) --}}
            @if($surat->status == 'disetujui')
                @if(in_array(Auth::user()->role, ['admin', 'kaprodi']))
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 text-center text-white">
                        <h3 class="font-bold text-lg mb-2">🎉 Surat Telah Terbit!</h3>
                        <p class="text-blue-100 text-sm mb-4">Dokumen surat ini sudah selesai diproses dan siap untuk diunduh.</p>
                        <a href="{{ route('admin.surat.download', $surat->id) }}" class="inline-flex items-center px-6 py-3 bg-white text-blue-800 rounded-xl font-bold hover:bg-blue-50 transition shadow-md transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Dokumen Surat
                        </a>
                    </div>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                        <h3 class="font-bold text-lg text-green-800 mb-2">✅ Surat Telah Disetujui</h3>
                        <p class="text-green-700 text-sm">
                            Pengajuan Anda telah disetujui oleh Admin Prodi. <br>
                            Silakan cek informasi lebih lanjut di Prodi atau hubungi Admin jika diperlukan.
                        </p>
                    </div>
                @endif
            @endif

        </div>

        {{-- 3. Kolom Kanan: Diskusi / Komentar --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-[600px]">
                <div class="bg-gray-50 px-5 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Diskusi & Revisi
                    </h3>
                </div>

                {{-- List Chat --}}
                <div class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4 scrollbar-thin scrollbar-thumb-gray-300">
                    @forelse ($surat->komentars->sortBy('created_at') as $komentar)
                        @php
                            $isMe = $komentar->user_id == Auth::id();
                            $isAdmin = in_array($komentar->user->role, ['admin', 'kaprodi']);
                        @endphp

                        <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                            <div class="max-w-[85%] px-4 py-2 rounded-2xl text-sm shadow-sm 
                                {{ $isMe 
                                    ? 'bg-blue-600 text-white rounded-br-none' 
                                    : ($isAdmin ? 'bg-yellow-100 text-yellow-900 border border-yellow-200 rounded-bl-none' : 'bg-white text-gray-800 border border-gray-200 rounded-bl-none') 
                                }}">
                                
                                {{-- Nama Pengirim --}}
                                @if(!$isMe)
                                    <p class="text-[10px] font-bold mb-1 {{ $isAdmin ? 'text-yellow-700' : 'text-gray-500' }} uppercase">
                                        {{ $komentar->user->name }} {{ $isAdmin ? '(Admin)' : '' }}
                                    </p>
                                @endif

                                <p class="leading-relaxed whitespace-pre-wrap">{{ $komentar->body }}</p>
                            </div>
                            <span class="text-[10px] text-gray-400 mt-1 px-1">
                                {{ $komentar->created_at->format('H:i') }} • {{ $komentar->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <svg class="w-12 h-12 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <p class="text-sm">Belum ada pesan.</p>
                        </div>
                    @endforelse
                </div>

                {{-- 🔥 FORM INPUT (Hanya muncul untuk Admin/Kaprodi) 🔥 --}}
                @if($surat->status != 'disetujui')
                    @if(in_array(Auth::user()->role, ['admin', 'kaprodi']))
                        <div class="p-4 bg-white border-t border-gray-200 rounded-b-2xl">
                            <form action="{{ route('surat.komentar.store', $surat) }}" method="POST" class="relative">
                                @csrf
                                <textarea name="body" rows="1" required placeholder="Tulis catatan/revisi..." 
                                          class="w-full pl-4 pr-12 py-3 bg-gray-50 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm resize-none overflow-hidden"
                                          oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                                
                                <button type="submit" class="absolute right-2 bottom-2 p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                                    <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- TAMPILAN UNTUK MAHASISWA (Read Only) --}}
                        <div class="p-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl text-center">
                            <p class="text-xs text-gray-500 italic flex justify-center items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Menunggu respon dari Admin Prodi.
                            </p>
                        </div>
                    @endif
                @else
                    <div class="p-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl text-center">
                        <p class="text-xs text-gray-500 italic">Diskusi ditutup karena surat telah disetujui.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection