<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Pembayaran;
use App\Http\Controllers\User\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Menampilkan form pembayaran berdasarkan checkout ID
     */
    public function create($checkoutId)
    {
        // Ambil data checkout beserta relasi produk, toko, varian, dan pengiriman
        $checkout = Checkout::with(['produk.toko', 'varian', 'pengiriman'])
            ->where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.pembayaran.create', [
            'checkout' => $checkout,
            'produk' => $checkout->produk,
            'varian' => $checkout->varian,
        ]);
    }

    /**
     * Menyimpan data pembayaran tanpa bukti, update status checkout
     */
    public function store(Request $request, $checkoutId)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string|max:50',
        ]);

        $checkout = Checkout::with(['produk', 'varian', 'pengiriman'])
            ->where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hitung total produk
        $hargaSatuan = $checkout->varian->harga ?? $checkout->produk->harga;
        $totalProduk = $hargaSatuan * $checkout->jumlah;

        // Ambil ongkos kirim
        $ongkir = $checkout->pengiriman->ongkir ?? 0;

        // Total bayar = total produk + ongkir
        $totalBayar = $totalProduk + $ongkir;

        // Cek dan kurangi stok
        $jumlah = $checkout->jumlah;
        $produk = $checkout->produk;

        if ($checkout->varian_id) {
            $varian = $checkout->varian;

            if ($varian->stok < $jumlah) {
                return redirect()->back()->with('error', 'Stok varian tidak mencukupi saat pembayaran.');
            }

            if ($produk->stok < $jumlah) {
                return redirect()->back()->with('error', 'Stok produk utama tidak mencukupi saat pembayaran.');
            }

            // Kurangi stok varian dan produk utama
            $varian->decrement('stok', $jumlah);
            $produk->decrement('stok', $jumlah);
        } else {
            if ($produk->stok < $jumlah) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi saat pembayaran.');
            }

            $produk->decrement('stok', $jumlah);
        }

        // Simpan data pembayaran
        Pembayaran::create([
            'user_id' => Auth::id(),
            'checkout_id' => $checkout->id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_bayar' => $totalBayar,
            'status_pembayaran' => 'pending',
        ]);

        // Update status checkout
        $checkout->update(['status' => 'menunggu_pembayaran']);

        // Buat transaksi otomatis
        app(TransaksiController::class)->store($checkout->id);

        return redirect()->route('user.transaksi.index')->with('success', 'Pembayaran berhasil dibuat. Transaksi juga telah dibuat.');
    }
}
