<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // Simpan data awal ke session, lalu arahkan ke halaman checkout lengkap
    public function start(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if ($produk->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Simpan data ke session untuk dipakai di halaman checkout
        Session::put('checkout', [
            'produk_id' => $produk->id,
            'toko_id' => $produk->toko_id,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('user.checkout.create');
    }

    // Tampilkan halaman checkout lengkap (produk + form pengiriman)
    public function create()
    {
        $checkoutData = Session::get('checkout');

        if (!$checkoutData) {
            return redirect()->route('dashboard')->with('error', 'Data checkout tidak ditemukan.');
        }

        $produk = Produk::with('toko')->findOrFail($checkoutData['produk_id']);

        return view('user.checkout.create', [
            'produk' => $produk,
            'jumlah' => $checkoutData['jumlah'],
        ]);
    }
}
