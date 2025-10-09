<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengajuanSuratController;
use App\Models\PengajuanSurat; // <-- IMPORT MODEL
use Illuminate\Support\Facades\Auth; // <-- IMPORT AUTH
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

// PERUBAHAN: Mengambil data riwayat sebelum menampilkan dashboard
Route::get('/dashboard', function () {
    // Ambil semua data pengajuan milik user yang login, urutkan dari yang terbaru
    $pengajuanSurats = PengajuanSurat::with('jenisSurat') // Ambil juga relasi jenisSurat
        ->where('user_id', Auth::id())
        ->latest() // Urutkan dari yang paling baru
        ->get();

    return view('dashboard', compact('pengajuanSurats')); // Kirim data ke view
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk pengajuan surat
    Route::get('/surat/ajukan', [PengajuanSuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [PengajuanSuratController::class, 'store'])->name('surat.store');
});

require __DIR__.'/auth.php';