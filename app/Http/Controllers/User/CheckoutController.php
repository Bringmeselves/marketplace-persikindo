<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\CheckoutItem;
use App\Models\Produk;
use App\Models\Varian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function start(Request $request)
    {
        // Validasi request input
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'varian_id' => 'required|exists:varian,id',
            'jumlah'    => 'required|integer|min:1',
        ]);

        // Ambil data produk beserta relasi toko
        $produk = Produk::with('toko')->findOrFail($request->produk_id);

        // Ambil data varian
        $varian = Varian::findOrFail($request->varian_id);

        // Cek apakah varian memang milik produk yang dimaksud
        if ($varian->produk_id !== $produk->id) {
            return back()->with('error', 'Varian tidak sesuai dengan produk.');
        }

        // Cek ketersediaan stok
        if ($varian->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak cukup.');
        }

        // Hitung harga satuan dan total harga item
        $hargaSatuan = $varian->harga ?? $produk->harga;
        $totalHarga  = $hargaSatuan * $request->jumlah;

        // Cek apakah sudah ada checkout pending untuk user dan toko ini
        $checkout = Checkout::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('toko_id', $produk->toko->id)
            ->first();

        // Jika belum ada, buat checkout baru
        if (!$checkout) {
            $checkout = Checkout::create([
                'user_id'     => Auth::id(),
                'toko_id'     => $produk->toko->id,
                'produk_id'   => $produk->id,
                'status'      => 'pending',
                'total_harga' => 0,
            ]);
        }

        // Validasi tambahan (jika ada error logic internal)
        if ($checkout->toko_id !== $produk->toko->id) {
            return back()->with('error', 'Checkout hanya bisa untuk satu toko dalam satu transaksi.');
        }

        // Cek apakah item dengan produk dan varian ini sudah ada di checkout
        $existingItem = $checkout->item()
            ->where('produk_id', $produk->id)
            ->where('varian_id', $varian->id)
            ->first();

        if ($existingItem) {
            // Jika sudah ada, update jumlah dan total harganya
            $existingItem->jumlah += $request->jumlah;
            $existingItem->total_harga = $existingItem->jumlah * $hargaSatuan;
            $existingItem->save();
        } else {
            // Jika belum ada, buat item baru di checkout
            $checkout->item()->create([
                'user_id'      => Auth::id(),
                'toko_id'      => $produk->toko->id,
                'produk_id'    => $produk->id,
                'varian_id'    => $varian->id,
                'jumlah'       => $request->jumlah,
                'harga_satuan' => $hargaSatuan,
                'total_harga'  => $totalHarga,
                'gambar'       => $varian->gambar ?? $produk->gambar,
            ]);
        }

        // Update total harga checkout berdasarkan semua item di dalamnya
        $checkout->total_harga = $checkout->item->sum('total_harga');
        $checkout->save();

        // Redirect ke halaman checkout dengan pesan sukses
        return redirect()->route('user.checkout.create', $checkout->id)
            ->with('success', 'Produk berhasil ditambahkan ke checkout.');
    }

    public function create($id)
    {
        $checkout = Checkout::with(['item.produk', 'item.varian', 'toko.produk.varian', 'pengiriman'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.checkout.create', compact('checkout'));
    }

    public function updateItem(Request $request, $checkoutId, $itemId)
    {
        $request->validate([
            'varian_id' => 'required|exists:varian,id',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $checkout = Checkout::where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $item = CheckoutItem::where('id', $itemId)
            ->where('checkout_id', $checkoutId)
            ->firstOrFail();

        $varian = Varian::with('produk')->findOrFail($request->varian_id);

        // Validasi kepemilikan varian terhadap produk
        if ($varian->produk_id !== $item->produk_id) {
            return back()->with('error', 'Varian tidak sesuai dengan produk.');
        }

        // Cek stok
        if ($varian->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak cukup.');
        }

        $harga = $varian->harga ?? $varian->produk->harga;
        $item->varian_id = $request->varian_id;
        $item->jumlah = $request->jumlah;
        $item->harga_satuan = $harga;
        $item->total_harga = $harga * $request->jumlah;
        $item->gambar = $varian->gambar ?? $item->produk->gambar;
        $item->save();

        // Update total harga checkout
        $checkout->total_harga = $checkout->item->sum('total_harga');
        $checkout->save();

        return redirect()->route('user.checkout.create', $checkout->id)
            ->with('success', 'Item checkout berhasil diperbarui.');
    }

    public function store(Request $request, $id)
    {
        $checkout = Checkout::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Finalisasi (validasi tambahan bila perlu)

        return redirect()->route('user.pembayaran.create', $checkout->id)
            ->with('success', 'Silakan lanjut ke pembayaran.');
    }

    public function destroyItem($checkoutId, $itemId)
    {
        $checkout = Checkout::where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $item = CheckoutItem::where('id', $itemId)
            ->where('checkout_id', $checkoutId)
            ->firstOrFail();

        $item->delete();

        // Perbarui total harga checkout
        $checkout->total_harga = $checkout->item->sum('total_harga');
        $checkout->save();

        return redirect()->route('user.checkout.create', $checkout->id)
            ->with('success', 'Item berhasil dihapus dari checkout.');
    }
}


