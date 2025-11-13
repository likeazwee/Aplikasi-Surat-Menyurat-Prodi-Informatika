<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\DashboardController; // Mahasiswa
use App\Http\Controllers\Kaprodi\DashboardController as KaprodiDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SuratDetailController;
use Illuminate\Support\Facades\Route;

// --- ROUTE DEFAULT ---
Route::get('/', function () {
    return redirect('/login');
});

// --- ROUTE HALAMAN INFORMASI UMUM (bisa diakses tanpa login) ---
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// --- ROUTE UNTUK SEMUA PENGGUNA TEROTENTIKASI ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // RUTE SPESIFIK MAHASISWA
    Route::middleware(['role:mahasiswa'])->group(function () {
        Route::get('/surat/ajukan', [PengajuanSuratController::class, 'create'])->name('surat.create');
        Route::post('/surat', [PengajuanSuratController::class, 'store'])->name('surat.store');

        // Dashboard utama mahasiswa
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Riwayat pengajuan surat mahasiswa
        Route::get('/surat/riwayat', [DashboardController::class, 'riwayat'])->name('surat.riwayat');
    });

    // RUTE DINAMIS (bisa diakses oleh semua role yang sudah auth)
    Route::get('/surat/{surat}', [SuratDetailController::class, 'show'])->name('surat.show');
    Route::post('/surat/{surat}/komentar', [SuratDetailController::class, 'storeKomentar'])->name('surat.komentar.store');
});

// --- ROUTE KHUSUS UNTUK KAPRODI ---
Route::middleware(['auth', 'verified', 'role:kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(function () {
        Route::get('/dashboard', [KaprodiDashboardController::class, 'index'])->name('dashboard');
    });

// --- ROUTE KHUSUS UNTUK ADMIN ---
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::patch('/surat/{surat}/approve', [AdminDashboardController::class, 'approve'])->name('surat.approve');
        Route::patch('/surat/{surat}/reject', [AdminDashboardController::class, 'reject'])->name('surat.reject');
        Route::get('/surat/{surat}/download', [AdminDashboardController::class, 'downloadSurat'])->name('surat.download');
        Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
    });

require __DIR__ . '/auth.php';
