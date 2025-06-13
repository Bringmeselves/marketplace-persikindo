<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Checkout;
use App\Models\Pengiriman;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Menampilkan semua transaksi milik user yang login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $transaksiList = Transaksi::with(['produk', 'pengiriman', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.transaksi.index', compact('transaksiList'));
    }

    /**
     * Menampilkan detail dari satu transaksi.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['produk', 'checkout', 'pengiriman', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.transaksi.show', compact('transaksi'));
    }

    /**
     * Membuat transaksi baru setelah pembayaran berhasil.
     *
     * @param  int  $checkoutId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($checkoutId)
{
    $user = Auth::user();

    // Cegah duplikasi transaksi
    if (Transaksi::where('checkout_id', $checkoutId)->exists()) {
        return; // Hentikan tanpa redirect karena dipanggil dari controller lain
    }

    // Ambil data checkout lengkap
    $checkout = Checkout::with(['produk', 'varian', 'pengiriman'])->findOrFail($checkoutId);

    $pengiriman = $checkout->pengiriman;
    $pembayaran = Pembayaran::where('checkout_id', $checkoutId)->first();

    // Validasi keberadaan data
    if (!$pengiriman || !$pembayaran) {
        return; // Hentikan tanpa redirect
    }

    // Simpan transaksi
    Transaksi::create([
        'user_id'       => $user->id,
        'produk_id'     => $checkout->produk_id,
        'checkout_id'   => $checkout->id,
        'pengiriman_id' => $pengiriman->id,
        'pembayaran_id' => $pembayaran->id,
        'status'        => 'diproses',
    ]);
}
}
