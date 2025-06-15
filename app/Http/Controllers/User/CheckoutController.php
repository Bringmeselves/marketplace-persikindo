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
        // Validasi input
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'varian_id' => 'required|exists:varian,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Simpan data sementara ke dalam session
        Session::put('checkout', [
            'produk_id' => $request->produk_id,
            'varian_id' => $request->varian_id,
            'jumlah' => $request->jumlah,
        ]);

        // Ambil data dari request
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

        // Kosongkan session setelah data disimpan
        Session::forget('checkout');

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

    /**
     * Proses konfirmasi checkout
     */
    public function store(Request $request, $id)
    {
        // Validasi input dari form checkout lanjutan (misal alamat, catatan, dll)
        $request->validate([
            'catatan' => 'nullable|string|max:255',
            'alamat_pengiriman' => 'required|string|max:255',
            // Tambahkan validasi lain sesuai kebutuhan
        ]);

        // Ambil data checkout yang dimaksud
        $checkout = Checkout::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Update checkout dengan data tambahan
        $checkout->update([
            'catatan' => $request->catatan,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'status' => 'pending',
        ]);

        return redirect()->route('user.pembayaran.create', $checkout->id)
            ->with('success', 'Checkout berhasil dikonfirmasi. Silakan pilih metode pengiriman.');
    }
}
