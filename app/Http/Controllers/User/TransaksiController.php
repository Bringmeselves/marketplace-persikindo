<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Menampilkan semua transaksi milik user pembeli
    public function index()
    {
        $transaksi = Transaksi::with(['produk', 'pengiriman', 'pembayaran'])
            ->where('user_id', Auth::id()) // user pembeli
            ->latest()
            ->get();

        return view('user.transaksi.index', compact('transaksi'));
    }

    // Menampilkan detail satu transaksi milik pembeli
    public function show($id)
    {
        $transaksi = Transaksi::with(['produk', 'pengiriman', 'pembayaran'])
            ->where('user_id', Auth::id()) // user pembeli
            ->findOrFail($id);

        return view('user.transaksi.show', compact('transaksi'));
    }

    // Membuat transaksi baru dari data checkout (oleh pembeli)
    public function store($checkoutId)
    {
        $checkout = Checkout::with(['pengiriman', 'pembayaran'])
            ->where('id', $checkoutId)
            ->where('user_id', Auth::id()) // pastikan milik pembeli
            ->firstOrFail();

        $pengiriman = $checkout->pengiriman;
        $pembayaran = $checkout->pembayaran;

        if (!$pengiriman || !$pembayaran) {
            return redirect()->route('dashboard')->with('error', 'Data pengiriman atau pembayaran belum lengkap.');
        }

        // Cek jika transaksi sudah pernah dibuat
        if (Transaksi::where('checkout_id', $checkout->id)->exists()) {
            return redirect()->route('dashboard')->with('info', 'Transaksi sudah dibuat.');
        }

        Transaksi::create([
            'user_id'       => Auth::id(),                 // pembeli
            'produk_id'     => $checkout->produk_id,
            'checkout_id'   => $checkout->id,
            'pengiriman_id' => $pengiriman->id,
            'pembayaran_id' => $pembayaran->id,
            'status'        => 'diproses',
            'resi'          => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil dibuat.');
    }

    // Menampilkan daftar penjualan untuk user penjual (anggota)
    public function penjualan()
    {
        $transaksi = Transaksi::with(['produk', 'user', 'pengiriman', 'pembayaran'])
            ->whereHas('produk', function ($query) {
                $query->where('user_id', Auth::id()); // produk milik penjual
            })
            ->latest()
            ->get();

        return view('user.transaksi.penjualan', compact('transaksi'));
    }
}
