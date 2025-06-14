<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\User\MarketplaceController as UserMarketplaceController;
use App\Http\Controllers\User\AnggotaController as UserAnggotaController;
use App\Http\Controllers\Admin\AnggotaController as AdminAnggotaController;
use App\Http\Controllers\User\TokoController as UserTokoController;
use App\Http\Controllers\Admin\TokoController as AdminTokoController;
use App\Http\Controllers\User\ProdukController as UserProdukController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\User\PembelianController as UserPembelianController;
use App\Http\Controllers\User\CheckoutController as UserCheckoutController;
use App\Http\Controllers\User\PengirimanController as UserPengirimanController;
use App\Http\Controllers\User\PembayaranController as UserPembayaranController;
use App\Http\Controllers\User\TransaksiController as UserTransaksiController;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
*/

// Halaman Welcome
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Halaman Dashboard (harus login dan verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile (harus login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================
// 👤 Routes User untuk Anggota, Toko, Produk
// ==========================
Route::prefix('user')->middleware('auth')->name('user.')->group(function () {
    // Anggota
    Route::get('/anggota/create', [UserAnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota', [UserAnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/anggota/dashboard', [UserAnggotaController::class, 'dashboard'])->name('anggota.dashboard');

    // Marketplace
    Route::get('/marketplace', [UserMarketplaceController::class, 'index'])->name('marketplace.index'); // Marketplace

    // Toko
    Route::get('toko', [UserTokoController::class, 'index'])->name('toko.index'); // Daftar toko
    Route::get('toko/create', [UserTokoController::class, 'create'])->name('toko.create'); // Form buat toko
    Route::post('toko', [UserTokoController::class, 'store'])->name('toko.store'); // Simpan toko baru
    Route::get('toko/{id}/kelola', [UserTokoController::class, 'kelola'])->name('toko.kelola'); // Kelola toko
    Route::get('toko/{id}/edit', [UserTokoController::class, 'edit'])->name('toko.edit'); // Form edit toko
    Route::get('toko/{id}', [UserTokoController::class, 'show'])->name('toko.show'); // Show toko
    Route::put('toko/{id}', [UserTokoController::class, 'update'])->name('toko.update'); // Update toko
    Route::delete('toko/{id}', [UserTokoController::class, 'destroy'])->name('toko.destroy'); // Hapus toko

    // Produk
    Route::get('/produk', [UserProdukController::class, 'index'])->name('produk.index'); // Daftar produk
    Route::get('/produk/create/{toko_id}', [UserProdukController::class, 'create'])->name('produk.create'); // Form buat produk
    Route::post('/produk', [UserProdukController::class, 'store'])->name('produk.store'); // Simpan produk baru
    Route::get('/produk/{id}/edit', [UserProdukController::class, 'edit'])->name('produk.edit'); // Form edit produk
    Route::put('/produk/{id}', [UserProdukController::class, 'update'])->name('produk.update'); // Update produk
    Route::delete('/produk/{id}', [UserProdukController::class, 'destroy'])->name('produk.destroy'); // Hapus produk

    // Pembelian
    Route::get('produk/{produk_id}/beli', [UserPembelianController::class, 'create'])->name('pembelian.create');
    Route::post('produk/beli', [UserPembelianController::class, 'store'])->name('pembelian.store');

    // Checkout
    Route::post('checkout/start', [UserCheckoutController::class, 'start'])->name('checkout.start');
    Route::get('checkout/{id}', [UserCheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{id}', [UserCheckoutController::class, 'store'])->name('checkout.store');
   
    // Pengiriman
    Route::get('/pengiriman', [UserPengirimanController::class, 'create'])->name('pengiriman.create');
    Route::post('/pengiriman', [UserPengirimanController::class, 'store'])->name('pengiriman.store');
 
    // Pembayaran
    Route::get('/pembayaran/{checkout}/buat', [UserPembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran/{id}', [UserPembayaranController::class, 'store'])->name('pembayaran.store');

    // Transaksi
    Route::get('/transaksi', [UserTransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{id}', [UserTransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/store/{checkoutId}', [UserTransaksiController::class, 'store'])->name('transaksi.store');

    // Penjualan
    Route::get('/penjualan', [UserTransaksiController::class, 'penjualan'])->name('transaksi.penjualan');
});

// ==========================
// 🔐 Registrasi, Anggota & Toko - Admin
// ==========================
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    // Anggota
    Route::get('/anggota', [AdminAnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/{id}', [AdminAnggotaController::class, 'show'])->name('anggota.show');
    Route::post('anggota/{id}/verify', [AdminAnggotaController::class, 'verify'])->name('anggota.verify');
    Route::post('anggota/{id}/reject', [AdminAnggotaController::class, 'reject'])->name('anggota.reject');
    Route::delete('anggota/{id}', [AdminAnggotaController::class, 'destroy'])->name('anggota.destroy');

    // Toko
    Route::get('/toko', [AdminTokoController::class, 'index'])->name('toko.index');
    Route::delete('/toko/{id}', [AdminTokoController::class, 'destroy'])->name('toko.destroy');

    // Produk
    Route::get('/produk', [AdminProdukController::class, 'index'])->name('produk.index');
    Route::delete('/produk/{id}', [AdminProdukController::class, 'destroy'])->name('produk.destroy');

    // Kategori
    Route::get('/kategori', [AdminKategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [AdminKategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [AdminKategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{kategori}/edit', [AdminKategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{kategori}', [AdminKategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [AdminKategoriController::class, 'destroy'])->name('kategori.destroy');
});

// 🔐 Route otentikasi Laravel Breeze / Fortify
require __DIR__.'/auth.php';