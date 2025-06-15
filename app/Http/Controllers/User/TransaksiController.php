<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Checkout;
use App\Models\Pengiriman;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Menampilkan semua transaksi milik user yang login.
     */
    public function index()
    {
        $transaksiList = Transaksi::with(['produk', 'varian', 'checkout', 'pengiriman', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.transaksi.index', compact('transaksiList'));
    }

    /**
     * Menampilkan detail dari satu transaksi.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['produk', 'varian', 'checkout', 'pengiriman', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.transaksi.show', compact('transaksi'));
    }

    /**
     * Membuat transaksi baru setelah pembayaran berhasil.
     */
    public function store($checkoutId)
    {
        $user = Auth::user();

        // Cek apakah transaksi sudah ada untuk checkout ini
        $existing = Transaksi::where('checkout_id', $checkoutId)->first();
        if ($existing) {
            return redirect()->route('user.transaksi.show', $existing->id)
                ->with('info', 'Transaksi untuk checkout ini sudah dibuat.');
        }

        // Ambil data checkout beserta relasinya
        $checkout = Checkout::with(['produk', 'varian', 'pengiriman'])->find($checkoutId);
        if (!$checkout || $checkout->user_id !== $user->id) {
            return redirect()->route('user.checkout.create', $checkoutId)
                ->with('error', 'Data checkout tidak valid atau tidak ditemukan.');
        }

        // Cek pengiriman & pembayaran
        $pengiriman = $checkout->pengiriman;
        if (!$pengiriman) {
            return redirect()->route('user.pembayaran.create', $checkoutId)
                ->with('error', 'Data pengiriman belum lengkap.');
        }

        $pembayaran = Pembayaran::where('checkout_id', $checkoutId)->first();
        if (!$pembayaran) {
            return redirect()->route('user.pembayaran.create', $checkoutId)
                ->with('error', 'Data pembayaran belum tersedia.');
        }

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'user_id'       => $user->id,
            'produk_id'     => $checkout->produk_id,
            'varian_id'     => $checkout->varian_id, // pastikan kolom ini ada di tabel
            'checkout_id'   => $checkout->id,
            'pengiriman_id' => $pengiriman->id,
            'pembayaran_id' => $pembayaran->id,
            'status'        => 'diproses',
        ]);
dd($transaksi);

        return redirect()->route('user.transaksi.show', $transaksi->id)
            ->with('success', 'Transaksi berhasil dibuat.');
    }
}
