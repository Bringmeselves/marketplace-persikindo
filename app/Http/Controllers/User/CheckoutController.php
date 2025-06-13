<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Produk;
use App\Models\Varian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Simpan data checkout ke database
     */
    public function start(Request $request)
    {
        // Ambil data dari session
        $checkoutData = Session::get('checkout');

        if (!$checkoutData) {
            return redirect()->route('dashboard')->with('error', 'Data checkout tidak ditemukan.');
        }

        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'varian_id' => 'required|exists:varian,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $produk = Produk::with('toko')->findOrFail($request->produk_id);
        $varian = Varian::findOrFail($request->varian_id);

        // Validasi varian milik produk
        if ($varian->produk_id !== $produk->id) {
            return back()->with('error', 'Varian tidak sesuai dengan produk.');
        }

        // Validasi stok cukup
        if ($varian->stok < $request->jumlah) {
            return back()->with('error', 'Stok varian tidak mencukupi.');
        }

        // Hitung harga
        $hargaSatuan = $varian->harga ?? $produk->harga;
        $totalHarga = $hargaSatuan * $request->jumlah;

        // Simpan data ke database
        $checkout = Checkout::create([
            'user_id' => Auth::id(),
            'produk_id' => $produk->id,
            'varian_id' => $varian->id,
            'toko_id' => $produk->toko->id,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $hargaSatuan,
            'gambar' => $varian->gambar ?? $produk->gambar,
            'total_harga' => $totalHarga,
            'status' => 'pending',
        ]);

        // Redirect ke halaman checkout create dengan parameter ID
        return redirect()->route('user.checkout.create', $checkout->id);
    }

    /**
     * Tampilkan halaman checkout berdasarkan ID checkout
     */
    public function create($id)
    {
        $checkout = Checkout::with(['produk.toko', 'varian'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.checkout.create', [
            'produk' => $checkout->produk,
            'varian' => $checkout->varian,
            'jumlah' => $checkout->jumlah,
            'checkout' => $checkout,
        ]);
    }
}
