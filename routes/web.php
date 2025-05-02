<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\RegistrasiController as UserRegistrasiController;
use App\Http\Controllers\Admin\RegistrasiController as AdminRegistrasiController;
use App\Http\Controllers\User\AnggotaController as UserAnggotaController;
use App\Http\Controllers\Admin\AnggotaController as AdminAnggotaController;
use App\Http\Controllers\User\TokoController as UserTokoController;
use App\Http\Controllers\Admin\TokoController as AdminTokoController;
use App\Http\Controllers\User\ProdukController as UserProdukController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\User\KategoriController as UserKategoriController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Welcome
Route::get('/', function () {
    return view('welcome');
});

// Halaman Dashboard (harus login dan verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile (harus login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================
// 📥 Registrasi Anggota - User
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/registrasi', [UserRegistrasiController::class, 'create'])->name('user.registrasi.create');
    Route::post('/registrasi', [UserRegistrasiController::class, 'store'])->name('user.registrasi.store');
});

// ==========================
// 🔐 Registrasi, Anggota & Toko - Admin
// ==========================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/registrasi', [AdminRegistrasiController::class, 'index'])->name('registrasi.index');
    Route::get('/registrasi/{id}', [AdminRegistrasiController::class, 'show'])->name('registrasi.show');
    Route::post('/registrasi/{id}/status', [AdminRegistrasiController::class, 'updateStatus'])->name('registrasi.updateStatus');

    Route::get('/anggota', [AdminAnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/create', [AdminAnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota', [AdminAnggotaController::class, 'store'])->name('anggota.store');
    Route::delete('/anggota/{id}', [AdminAnggotaController::class, 'destroy'])->name('anggota.destroy');

    Route::get('/toko', [AdminTokoController::class, 'index'])->name('toko.index');
    Route::delete('/toko/{id}', [AdminTokoController::class, 'destroy'])->name('toko.destroy');

    Route::get('/produk', [AdminProdukController::class, 'index'])->name('produk.index');
    Route::delete('/produk/{id}', [AdminProdukController::class, 'destroy'])->name('produk.destroy');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
});

// ==========================
// 👤 Routes User untuk Anggota, Toko, Produk
// ==========================
Route::prefix('user')->middleware(['auth', 'role:user'])->name('user.')->group(function () {
    Route::get('/anggota/create', [UserAnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota', [UserAnggotaController::class, 'store'])->name('anggota.store');

    Route::get('/toko/create', [UserTokoController::class, 'create'])->name('toko.create');
    Route::post('/toko', [UserTokoController::class, 'store'])->name('toko.store');

    Route::get('/produk', [UserProdukController::class, 'index'])->name('produk.index');
    Route::post('/produk', [UserProdukController::class, 'store'])->name('produk.store');
    Route::put('/produk/{id}', [UserProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [UserProdukController::class, 'destroy'])->name('produk.destroy');

    Route::get('/kategori', [UserKategoriController::class, 'index'])->name('kategori.index');
    Route::get('/produk/{produkId}/form-kategori', [UserKategoriController::class, 'showForm'])->name('produk.form_kategori');
    Route::post('/produk/{produkId}/kategori', [UserKategoriController::class, 'store'])->name('produk.store_kategori');
});

// 🔐 Route otentikasi Laravel Breeze / Fortify
require __DIR__.'/auth.php';
