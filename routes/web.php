<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\Kaprodi\DashboardController as KaprodiDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

// Grup route yang memerlukan login
Route::middleware(['auth', 'verified'])->group(function () {

    // --- ROUTE UNTUK MAHASISWA ---
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/dashboard', function () {
            $pengajuanSurats = PengajuanSurat::with('jenisSurat', 'user')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
            return view('dashboard', compact('pengajuanSurats'));
        })->name('dashboard');
        
        Route::get('/surat/ajukan', [PengajuanSuratController::class, 'create'])->name('surat.create');
        Route::post('/surat', [PengajuanSuratController::class, 'store'])->name('surat.store');
    });

    // --- ROUTE UNTUK KAPRODI ---
    Route::middleware('role:kaprodi')->prefix('kaprodi')->name('kaprodi.')->group(function () {
        Route::get('/dashboard', [KaprodiDashboardController::class, 'index'])->name('dashboard');
    });

    // --- ROUTE UNTUK ADMIN ---
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::patch('/surat/{id}/approve', [AdminDashboardController::class, 'approve'])->name('surat.approve');
        Route::patch('/surat/{id}/reject', [AdminDashboardController::class, 'reject'])->name('surat.reject');
    });

    // Route untuk profil (bisa diakses semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';