<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\User\MarketplaceController as UserMarketplaceController;
use App\Http\Controllers\User\AnggotaController as UserAnggotaController;
use App\Http\Controllers\Admin\AnggotaController as AdminAnggotaController;
use App\Http\Controllers\User\TokoController as UserTokoController;
use App\Http\Controllers\Admin\TokoController as AdminTokoController;
use App\Http\Controllers\User\ProdukController as UserProdukController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\PenarikanController as AdminPenarikanController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController; 
use App\Http\Controllers\User\KeranjangController as UserKeranjangController;
use App\Http\Controllers\User\PembelianController as UserPembelianController;
use App\Http\Controllers\User\CheckoutController as UserCheckoutController;
use App\Http\Controllers\User\PengirimanController as UserPengirimanController;
use App\Http\Controllers\User\PembayaranController as UserPembayaranController;
use App\Http\Controllers\User\TransaksiController as UserTransaksiController;
use App\Http\Controllers\User\PenilaianController as UserPenilaianController;
use App\Http\Controllers\User\PenilaianTokoController as UserPenilaianTokoController;
use App\Http\Controllers\User\ChatController as UserChatController;
use App\Http\Controllers\User\PenarikanController as UserPenarikanController;
/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
*/


Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');


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
    Route::get('toko/riwayat-transaksi', [UserTokoController::class, 'riwayatTransaksi'])->name('toko.riwayat'); // Riwayat transaksi toko
    Route::get('toko/{id}/kelola', [UserTokoController::class, 'kelola'])->name('toko.kelola'); // Kelola toko
    Route::get('toko/{id}/edit', [UserTokoController::class, 'edit'])->name('toko.edit'); // Form edit toko
    Route::get('toko/{id}', [UserTokoController::class, 'show'])->name('toko.show'); // Show toko
    Route::get('toko/{id}/reviews', [UserTokoController::class, 'reviews'])->name('toko.reviews');
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

    // Keranjang
    Route::get('/keranjang', [UserKeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang', [UserKeranjangController::class, 'store'])->name('keranjang.store');
    Route::post('/keranjang/checkout', [UserKeranjangController::class, 'checkout'])->name('keranjang.checkout');
    Route::put('/keranjang/{key}', [UserKeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{key}', [UserKeranjangController::class, 'destroy'])->name('keranjang.destroy');

    // Checkout
    Route::post('checkout/start', [UserCheckoutController::class, 'start'])->name('checkout.start');
    Route::get('checkout/{id}', [UserCheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{id}', [UserCheckoutController::class, 'store'])->name('checkout.store');
   
    Route::get('{checkout}/item/{item}/edit', [UserCheckoutController::class, 'editItem'])->name('checkout.item.edit');
    Route::put('{checkout}/item/{item}', [UserCheckoutController::class, 'updateItem'])->name('checkout.item.update');
    Route::delete('{checkout}/item/{item}', [UserCheckoutController::class, 'destroyItem'])->name('checkout.item.destroy');

    // ALAMAT
    Route::get('/pengiriman/{checkout}/alamat', [UserPengirimanController::class, 'alamatCreate'])->name('pengiriman.alamat.create');
    Route::get('/pengiriman/{checkout}/alamat/edit', [UserPengirimanController::class, 'alamatEdit'])->name('pengiriman.alamat.edit');
    Route::post('/pengiriman/{checkout}/alamat', [UserPengirimanController::class, 'alamatStore'])->name('pengiriman.alamat.store');
    Route::put('/pengiriman/{checkout}/alamat', [UserPengirimanController::class, 'alamatStore'])->name('pengiriman.alamat.update');

    // KURIR
    Route::get('/pengiriman/{checkout}/kurir/edit', [UserPengirimanController::class, 'kurirEdit'])->name('pengiriman.kurir.edit');
    Route::put('/pengiriman/{checkout}/kurir', [UserPengirimanController::class, 'kurirUpdate'])->name('pengiriman.kurir.update');

    // CEK ONGKIR (AJAX)
    Route::post('/pengiriman/cek-ongkir', [UserPengirimanController::class, 'cekOngkir'])->name('pengiriman.cekOngkir');

    // API: AMBIL DAFTAR KOTA (untuk dropdown)
    Route::get('pengiriman/cities', [UserPengirimanController::class, 'getCities'])->name('pengiriman.kota');

    // API: CEK ONGKIR
    Route::post('/pengiriman/cek-ongkir', [UserPengirimanController::class, 'cekOngkir'])->name('pengiriman.cekOngkir');

    // Transaksi
    Route::get('/transaksi', [UserTransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/store/{checkoutId}', [UserTransaksiController::class, 'store'])->name('transaksi.store');
    Route::post('/transaksi/{id}/input-resi', [UserTransaksiController::class, 'inputResi'])->name('transaksi.inputResi');
    Route::get('/transaksi/{id}', [UserTransaksiController::class, 'show'])->name('transaksi.show');
    Route::patch('/transaksi/{transaksi}/selesai', [UserTransaksiController::class, 'selesai'])->name('transaksi.selesai');

    // Penilaian
    Route::get('/penilaian/{produk}/create', [UserPenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('/penilaian', [UserPenilaianController::class, 'store'])->name('penilaian.store');
    Route::delete('/penilaian/{id}', [UserPenilaianController::class, 'destroy'])->name('penilaian.destroy');
    Route::get('/penilaian/produk/{produk}', [UserPenilaianController::class, 'showByProduk'])->name('penilaian.show');

    // Penilaian Toko
    Route::get('/toko/{toko}/penilaian', [UserPenilaianTokoController::class, 'create'])->name('penilaian-toko.create');
    Route::post('/toko/penilaian', [UserPenilaianTokoController::class, 'store'])->name('penilaian-toko.store');

    // Chat
    Route::get('/chat', [UserChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/mulai/{tokoId}', [UserChatController::class, 'mulaiChat'])->name('chat.mulai');
    Route::get('/chat/{id}', [UserChatController::class, 'tampil'])->name('chat.tampil');
    Route::post('/chat/{id}/kirim', [UserChatController::class, 'kirimPesan'])->name('kirimPesan');
    Route::post('/chat/keluhan/{transaksi}', [UserChatController::class, 'ajukanKeluhan'])->name('chat.ajukanKeluhan');

    // Pembayaran
    Route::get('/{checkoutId}', [UserPembayaranController::class, 'create'])->name('pembayaran.create');
    Route::get('/midtrans/{checkoutId}', [UserPembayaranController::class, 'midtransPay'])->name('pembayaran.midtrans');
    Route::get('/success/{checkoutId}', [UserPembayaranController::class, 'success'])->name('pembayaran.success');
    Route::get('/pending/{checkoutId}', [UserPembayaranController::class, 'pending'])->name('pembayaran.pending');

    // Penarikan
    Route::get('/penarikan/create', [UserPenarikanController::class, 'create'])->name('penarikan.create');
    Route::post('/penarikan', [UserPenarikanController::class, 'store'])->name('penarikan.store');
    Route::get('/penarikan/index', [UserPenarikanController::class, 'index'])->name('penarikan.index');
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

    // Penarikan
    Route::get('/penarikan', [AdminPenarikanController::class, 'index'])->name('penarikan.index');
    Route::get('/penarikan/{id}', [AdminPenarikanController::class, 'show'])->name('penarikan.show');
    Route::put('/penarikan/{id}', [AdminPenarikanController::class, 'update'])->name('penarikan.update');
    Route::post('/penarikan/{id}/reject', [AdminPenarikanController::class, 'reject'])->name('penarikan.reject');

    // Transaksi
    Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');

});

// 🔐 Route otentikasi Laravel Breeze / Fortify
require __DIR__.'/auth.php';