<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PembelianController extends Controller
{
    /**
     * Menampilkan form pembelian produk.
     */
    public function create($produk_id)
    {
        $produk = Produk::with('toko')->findOrFail($produk_id);
        return view('user.pembelian.create', compact('produk'));
    }

    /**
     * Proses form pembelian awal, lalu arahkan ke halaman checkout/konfirmasi.
     */
    public function store(Request $request)
    {
        // Validasi input jumlah dan produk_id
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1',
            'varian_id' => 'nullable|exists:varian,id',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        // Cek apakah user mencoba membeli produk miliknya sendiri
        if ($produk->user_id == Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Kamu tidak bisa membeli produk milikmu sendiri.');
        }

        // Cek apakah jumlah yang diminta melebihi stok
        if ($request->jumlah > $produk->stok) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        // Menyimpan data sementara ke session untuk digunakan di halaman checkout
        Session::put('checkout', [
            'produk_id' => $produk->id,
            'jumlah' => $request->jumlah,
            'varian_id' => $request->varian_id, 
            'produk' => $produk,  // Simpan objek produk untuk menampilkan detail di checkout
            'toko_id' => $produk->toko_id,  
        ]);

        // Redirect ke halaman konfirmasi checkout (GET)
        return redirect()->route('user.checkout.create');
    }
}