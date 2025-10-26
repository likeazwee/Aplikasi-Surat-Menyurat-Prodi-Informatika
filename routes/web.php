<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\Kaprodi\DashboardController as KaprodiDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
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
    })->name('dashboard'); // <-- Ini akan menjadi route 'mahasiswa.dashboard' secara otomatis karena redirect

    // Rute untuk pengajuan surat
    Route::get('/surat/ajukan', [PengajuanSuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [PengajuanSuratController::class, 'store'])->name('surat.store');
});


// --- ROUTE KHUSUS UNTUK KAPRODI ---
Route::middleware(['auth', 'verified', 'role:kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
    Route::get('/dashboard', [KaprodiDashboardController::class, 'index'])->name('dashboard');
    Route::get('/kaprodi/profile', [ProfileController::class, 'edit'])->name('kaprodi.profile.edit');
});


// --- ROUTE KHUSUS UNTUK ADMIN ---
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/surat/{surat}/approve', [AdminDashboardController::class, 'approve'])->name('surat.approve');
    Route::patch('/surat/{surat}/reject', [AdminDashboardController::class, 'reject'])->name('surat.reject');
    Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
});


require __DIR__.'/auth.php';
