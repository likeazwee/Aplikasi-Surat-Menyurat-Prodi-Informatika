<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\Kaprodi\DashboardController as KaprodiDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SuratDetailController;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// --- ROUTE UNTUK SEMUA PENGGUNA TEROTENTIKASI ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 👇 RUTE SPESIFIK MAHASISWA (HARUS DI ATAS RUTE DINAMIS) 👇
    Route::get('/surat/ajukan', [PengajuanSuratController::class, 'create'])
         ->middleware(['verified', 'role:mahasiswa'])
         ->name('surat.create');
    Route::post('/surat', [PengajuanSuratController::class, 'store'])
         ->middleware(['verified', 'role:mahasiswa'])
         ->name('surat.store');

    // 👇 RUTE DINAMIS (HARUS DI BAWAH RUTE SPESIFIK) 👇
    // Rute ini menangani tampilan halaman detail
    Route::get('/surat/{surat}', [SuratDetailController::class, 'show'])->name('surat.show');
    // Rute ini menangani penyimpanan komentar baru
    Route::post('/surat/{surat}/komentar', [SuratDetailController::class, 'storeKomentar'])->name('surat.komentar.store');
});


// --- ROUTE KHUSUS UNTUK MAHASISWA ---
Route::middleware(['auth', 'verified', 'role:mahasiswa'])->group(function () {
    // Rute utama dashboard mahasiswa
    Route::get('/dashboard', function () {
        $pengajuanSurats = PengajuanSurat::with('jenisSurat', 'user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('dashboard', compact('pengajuanSurats'));
    })->name('dashboard');

    // Rute pengajuan surat dipindahkan ke grup 'auth' utama di atas
    // agar tidak bertabrakan dengan /surat/{surat}
});


// --- ROUTE KHUSUS UNTUK KAPRODI ---
Route::middleware(['auth', 'verified', 'role:kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
    Route::get('/dashboard', [KaprodiDashboardController::class, 'index'])->name('dashboard');
});


// --- ROUTE KHUSUS UNTUK ADMIN ---
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/surat/{surat}/approve', [AdminDashboardController::class, 'approve'])->name('surat.approve');
    Route::patch('/surat/{surat}/reject', [AdminDashboardController::class, 'reject'])->name('surat.reject');
    Route::get('/surat/{surat}/download', [AdminDashboardController::class, 'downloadSurat'])->name('surat.download');
    Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
});


require __DIR__.'/auth.php';

