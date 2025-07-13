<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksiList = Transaksi::with([
                'checkout',
                'checkout.item.produk',
                'checkout.item.varian',
                'checkout.item.produk.toko',
                'checkout.pengiriman',
                'checkout.pembayaran',
                'pengiriman',
                'pembayaran',
                'user',
            ])
            ->latest()
            ->paginate(10);

        return view('admin.transaksi.index', compact('transaksiList'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with([
                'checkout',
                'checkout.item.produk',
                'checkout.item.varian',
                'checkout.item.produk.toko',
                'checkout.pengiriman',
                'checkout.pembayaran',
                'pengiriman',
                'pembayaran',
                'user',
            ])
            ->findOrFail($id);

        return view('admin.transaksi.show', compact('transaksi'));
    }
}
