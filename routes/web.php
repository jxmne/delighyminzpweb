<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;

// 1. Route Beranda & Menu (Bisa diakses siapa saja - GUEST)
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/menu/search', [MenuController::class, 'search'])->name('menu.search');

// Redirect create & edit ke halaman menu dengan parameter modal
Route::get('/menu/create', function () {
    return redirect()->route('menu', ['modal' => 'tambah']);
})->middleware('auth')->name('menu.create');

Route::get('/menu/{id}/edit', function ($id) {
    return redirect()->route('menu', ['modal' => 'edit', 'id' => $id]);
})->middleware('auth')->name('menu.edit');

// 2. Route yang butuh Login (Auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/beranda', [BerandaController::class, 'index'])->middleware(['verified'])->name('beranda');

    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');
    Route::post('/pesanan/tambah/{id}', [PesananController::class, 'tambahKeKeranjang'])->name('pesanan.tambah');
    Route::post('/pesanan/update/{id}', [PesananController::class, 'updateJumlah'])->name('pesanan.update');
    Route::post('/pesanan/hapus/{id}',  [PesananController::class, 'hapusItem'])->name('pesanan.hapus');
    Route::post('/pesanan/beli',         [PesananController::class, 'beli'])->name('pesanan.beli');
    Route::post('/pesanan/reset',        [PesananController::class, 'resetKeranjang'])->name('pesanan.reset');

    Route::get('/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas');

    // Hanya store, update, destroy (create & edit sudah di-override di atas)
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::patch('/menu/{menu}', [MenuController::class, 'update']);
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/save', [PengaturanController::class, 'save'])->name('pengaturan.save');
});

require __DIR__.'/auth.php';