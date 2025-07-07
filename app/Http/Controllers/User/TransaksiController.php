<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Checkout;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Menampilkan semua transaksi milik user yang login.
     */
    public function index()
    {
        $transaksiList = Transaksi::with([
                'checkout',
                'checkout.item',
                'checkout.item.produk',
                'checkout.item.varian',
                'checkout.item.produk.toko',
                'checkout.pengiriman',
                'checkout.pembayaran',
                'pengiriman',
                'pembayaran',
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        if ($transaksiList->isEmpty()) {
            return view('user.transaksi.index', ['transaksiList' => $transaksiList])
                ->with('info', 'Belum ada transaksi.');
        }

        return view('user.transaksi.index', compact('transaksiList'));
    }

    /**
     * Menampilkan detail dari satu transaksi.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with([
                'checkout',
                'checkout.item',
                'checkout.item.produk',
                'checkout.item.varian',
                'checkout.item.produk.toko',
                'checkout.pengiriman',
                'checkout.pembayaran',
                'pengiriman',
                'pembayaran',
            ])
            ->where('user_id', Auth::id())
            ->find($id);

        if (!$transaksi) {
            return redirect()->route('user.transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('user.transaksi.show', compact('transaksi'));
    }

    /**
     * Membuat transaksi baru setelah pembayaran berhasil.
     */
    public function store($checkoutId)
    {
        $user = Auth::user();

        // Cek apakah transaksi sudah ada
        $existing = Transaksi::where('checkout_id', $checkoutId)->first();
        if ($existing) {
            return redirect()->route('user.transaksi.show', $existing->id)
                ->with('info', 'Transaksi untuk checkout ini sudah dibuat.');
        }

        // Ambil data checkout dengan relasi lengkap
        $checkout = Checkout::with([
                'item.produk',
                'item.varian',
                'pengiriman',
                'pembayaran',
            ])
            ->where('id', $checkoutId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Validasi kelengkapan pengiriman & pembayaran
        if (!$checkout->pengiriman) {
            return redirect()->route('user.pengiriman.create', $checkoutId)
                ->with('error', 'Data pengiriman belum lengkap.');
        }

        if (!$checkout->pembayaran) {
            return redirect()->route('user.pembayaran.create', $checkoutId)
                ->with('error', 'Data pembayaran belum tersedia.');
        }

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'user_id'       => $user->id,
            'checkout_id'   => $checkout->id,
            'pengiriman_id' => $checkout->pengiriman->id,
            'pembayaran_id' => $checkout->pembayaran->id,
            'produk_id' => $checkout->item->first()->produk_id,
            'status'        => 'diproses',
        ]);

        return redirect()->route('user.transaksi.index')
            ->with('success', 'Transaksi berhasil dibuat.');
    }

    public function inputResi(Request $request, $id)
    {
        $request->validate([
            'resi' => 'required|string|max:255',
        ]);

        $transaksi = Transaksi::with('produk.toko')->findOrFail($id);

        $toko = $transaksi->produk->toko ?? null;

        // Pastikan yang menginput adalah pemilik toko
        if (!$toko || $toko->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menginput resi untuk transaksi ini.');
        }

        $transaksi->resi = $request->resi;
        $transaksi->status = 'dikirim';
        $transaksi->save();

        return back()->with('success', 'Resi berhasil diinput dan status diubah menjadi dikirim.');
    }

    public function selesai($id)
    {
        $transaksi = Transaksi::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($transaksi->status !== 'dikirim') {
            return back()->with('error', 'Transaksi belum bisa diselesaikan.');
        }

        $transaksi->status = 'selesai';
        $transaksi->save();

        return redirect()->route('user.transaksi.show', $transaksi->id)
            ->with('success', 'Transaksi berhasil diselesaikan.');
    }
}
