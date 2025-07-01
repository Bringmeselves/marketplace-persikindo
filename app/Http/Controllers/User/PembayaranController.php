<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Midtrans\Config;
use Midtrans\Snap;

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

    /**
     * Midtrans Snap — menampilkan tombol bayar
     */
    public function midtransPay($checkoutId)
    {
        $checkout = Checkout::with(['item.produk', 'item.varian', 'pengiriman'])
            ->where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $totalProduk = $checkout->item->sum('total_harga');
        $ongkir = $checkout->pengiriman->ongkir ?? 0;
        $totalBayar = $totalProduk + $ongkir;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'ORDER-' . $checkout->id . '-' . Str::uuid();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalBayar,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('user.pembayaran.snap', compact('snapToken', 'checkout'));
    }

    /**
     * Callback Midtrans — sukses
     */
    public function success($checkoutId)
    {
        $checkout = Checkout::with(['item.produk', 'item.varian', 'pengiriman'])
            ->where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $totalBayar = $checkout->item->sum('total_harga') + ($checkout->pengiriman->ongkir ?? 0);

        foreach ($checkout->item as $item) {
            $produk = $item->produk;
            $varian = $item->varian;

            if ($produk->user_id == Auth::id()) {
                return back()->with('error', "Tidak bisa membeli produk sendiri: {$produk->nama}");
            }

            if ($varian && $varian->stok < $item->jumlah) {
                return back()->with('error', "Stok varian tidak cukup: {$produk->nama}");
            }

            if ($produk->stok < $item->jumlah) {
                return back()->with('error', "Stok produk tidak cukup: {$produk->nama}");
            }

            if ($varian) $varian->decrement('stok', $item->jumlah);
            $produk->decrement('stok', $item->jumlah);
        }

        Pembayaran::create([
            'user_id'           => Auth::id(),
            'checkout_id'       => $checkout->id,
            'metode_pembayaran' => 'midtrans',
            'total_bayar'       => $totalBayar,
            'status_pembayaran' => 'lunas',
        ]);

        $checkout->update(['status' => 'menunggu_pengiriman']);

        app(TransaksiController::class)->store($checkout->id);

        return redirect()->route('user.transaksi.index')->with('success', 'Pembayaran berhasil dan transaksi tercatat.');
    }

    /**
     * Callback Midtrans — pending
     */
    public function pending($checkoutId)
    {
        Pembayaran::create([
            'user_id'           => Auth::id(),
            'checkout_id'       => $checkoutId,
            'metode_pembayaran' => 'midtrans',
            'total_bayar'       => 0,
            'status_pembayaran' => 'pending',
        ]);

        Checkout::where('id', $checkoutId)->update(['status' => 'menunggu_pembayaran']);

        return redirect()->route('user.transaksi.index')->with('info', 'Pembayaran sedang diproses.');
    }
}
