<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Menampilkan form pembayaran berdasarkan checkout ID
     */
    public function create($checkoutId)
    {
        $checkout = Checkout::with(['item.produk', 'item.varian', 'pengiriman'])
            ->where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.pembayaran.create', compact('checkout'));
    }

    /**
     * Menyimpan data pembayaran tanpa bukti, update status checkout
     */
    public function store(Request $request, $checkoutId)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string|max:50',
        ]);

        $checkout = Checkout::with(['item.produk', 'item.varian', 'pengiriman'])
            ->where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hitung total produk dari semua item
        $totalProduk = $checkout->item->sum('total_harga');
        $ongkir = $checkout->pengiriman->ongkir ?? 0;
        $totalBayar = $totalProduk + $ongkir;

        // Cek dan kurangi stok setiap item
        foreach ($checkout->item as $item) {
            $produk = $item->produk;
            $varian = $item->varian;

            // Cegah pembelian produk sendiri
            if ($produk->user_id == Auth::id()) {
                return back()->with('error', "Anda tidak dapat membeli produk milik sendiri: {$produk->nama}.");
            }

            // Cek stok
            if ($varian && $varian->stok < $item->jumlah) {
                return back()->with('error', "Stok varian untuk {$produk->nama} tidak mencukupi.");
            }

            if ($produk->stok < $item->jumlah) {
                return back()->with('error', "Stok produk {$produk->nama} tidak mencukupi.");
            }

            // Kurangi stok
            if ($varian) {
                $varian->decrement('stok', $item->jumlah);
            }

            $produk->decrement('stok', $item->jumlah);
        }

        // Simpan data pembayaran
        Pembayaran::create([
            'user_id'           => Auth::id(),
            'checkout_id'       => $checkout->id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_bayar'       => $totalBayar,
            'status_pembayaran' => 'pending',
        ]);

        // Update status checkout
        $checkout->update(['status' => 'menunggu_pembayaran']);

        // Buat transaksi otomatis
        app(TransaksiController::class)->store($checkout->id);

        return redirect()->route('user.transaksi.index')
            ->with('success', 'Pembayaran berhasil dibuat. Transaksi juga telah tercatat.');
    }
}
