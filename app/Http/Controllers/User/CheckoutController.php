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
     * Proses awal checkout dan simpan data ke database
     */
    public function start(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'varian_id' => 'required|exists:varian,id',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $produk = Produk::with('toko')->findOrFail($request->produk_id);
        $varian = Varian::findOrFail($request->varian_id);

        if ($varian->produk_id !== $produk->id) {
            return back()->with('error', 'Varian tidak sesuai dengan produk.');
        }

        if ($varian->stok < $request->jumlah) {
            return back()->with('error', 'Stok varian tidak mencukupi.');
        }

        $hargaSatuan = $varian->harga ?? $produk->harga;
        $totalHarga = $hargaSatuan * $request->jumlah;

        $checkout = Checkout::create([
            'user_id'      => Auth::id(),
            'produk_id'    => $produk->id,
            'varian_id'    => $varian->id,
            'toko_id'      => $produk->toko->id,
            'jumlah'       => $request->jumlah,
            'harga_satuan' => $hargaSatuan,
            'gambar'       => $varian->gambar ?? $produk->gambar,
            'total_harga'  => $totalHarga,
            'status'       => 'pending',
        ]);

        return redirect()->route('user.checkout.create', $checkout->id);
    }

    /**
     * Tampilkan halaman checkout lengkap (produk + pengiriman)
     */
    public function create($id)
{
    $checkout = Checkout::with('produk', 'varian', 'pengiriman')
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $produk = $checkout->produk;
    $varian = $checkout->varian;
    $jumlah = $checkout->jumlah;
    $totalHarga = $checkout->total_harga;

    return view('user.checkout.create', compact('checkout', 'produk', 'varian', 'jumlah', 'totalHarga'));
}


    /**
     * Proses finalisasi checkout (jika diperlukan)
     */
    public function store(Request $request, $id)
    {
        $checkout = Checkout::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Tambahan validasi bisa diletakkan di sini jika kamu butuh step finalisasi lain

        return redirect()->route('user.pembayaran.create', $checkout->id)
            ->with('success', 'Checkout lengkap. Silakan lanjut ke pembayaran.');
    }
}
