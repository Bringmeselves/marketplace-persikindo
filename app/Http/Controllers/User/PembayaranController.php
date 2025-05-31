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
        // Ambil data checkout milik user saat ini
        $checkout = Checkout::where('id', $checkoutId)
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

        $checkout = Checkout::where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Pembayaran::create([
            'user_id' => Auth::id(),
            'checkout_id' => $checkout->id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_bayar' => $checkout->total_harga,
            'status_pembayaran' => 'pending',
        ]);

         // Update status checkout jadi menunggu pembayaran
        $checkout->update(['status' => 'menunggu_pembayaran']);

        // Kurangi stok produk setelah pembayaran dibuat (atau bisa di sini cek status bayar sudah final)
        $produk = $checkout->produk;
        $jumlah = $checkout->jumlah;

        if ($produk->stok < $jumlah) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi saat pembayaran.');
        }

        $produk->decrement('stok', $jumlah);
            
        // Buat transaksi otomatis setelah pembayaran berhasil
        app(TransaksiController::class)->store($checkout->id);

        // Redirect ke halaman daftar transaksi
        return redirect()->route('user.transaksi.index')->with('success', 'Pembayaran berhasil dibuat. Transaksi juga telah dibuat.');
    }
}
