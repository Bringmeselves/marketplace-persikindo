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
     * Menampilkan tombol pembayaran Midtrans
     */
    public function create($checkoutId)
    {
        $checkout = Checkout::with(['item.produk', 'item.varian', 'pengiriman'])
            ->where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $totalProduk = $checkout->item->sum('total_harga');
        $ongkir = $checkout->pengiriman->ongkir ?? 0;
        $totalBayar = $totalProduk + $ongkir;

        // Konfigurasi Midtrans
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

         // Simpan ke tabel pembayaran jika belum ada pembayaran untuk checkout ini
        Pembayaran::updateOrCreate(
            ['checkout_id' => $checkout->id],
            [
                'user_id' => Auth::id(),
                'metode_pembayaran' => 'midtrans',
                'total_bayar' => $totalBayar,
                'status_pembayaran' => 'pending',
                'order_id' => $orderId,
                'snap_token' => $snapToken,
            ]
        );
        
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

        // Cek dan kurangi stok
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

        // Simpan pembayaran
        $pembayaran = Pembayaran::where('checkout_id', $checkout->id)->first();

        if ($pembayaran) {
            $pembayaran->update([
                'status_pembayaran' => 'lunas',
            ]);
        } else {
            Pembayaran::create([
                'user_id'           => Auth::id(),
                'checkout_id'       => $checkout->id,
                'metode_pembayaran' => 'midtrans',
                'total_bayar'       => $totalBayar,
                'status_pembayaran' => 'lunas',
            ]);
        }

        // Update status
        $checkout->update(['status' => 'menunggu_pengiriman']);

        // Buat transaksi
        app(TransaksiController::class)->store($checkout->id);

        return redirect()->route('user.transaksi.index')->with('success', 'Pembayaran berhasil dan transaksi tercatat.');
    }

    /**
     * Callback Midtrans — pending
     */
    public function pending($checkoutId)
    {
        $checkout = Checkout::where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $totalBayar = $checkout->item->sum('total_harga') + ($checkout->pengiriman->ongkir ?? 0);

        Pembayaran::updateOrCreate(
            ['checkout_id' => $checkoutId],
            [
                'user_id' => Auth::id(),
                'metode_pembayaran' => 'midtrans',
                'total_bayar' => $totalBayar,
                'status_pembayaran' => 'pending',
            ]
        );

        $checkout->update(['status' => 'menunggu_pembayaran']);

        return redirect()->route('user.transaksi.index')->with('info', 'Pembayaran sedang diproses.');
    }
}
