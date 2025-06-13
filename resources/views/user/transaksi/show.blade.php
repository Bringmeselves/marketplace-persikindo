@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6 space-y-8">

    <h2 class="text-3xl font-bold text-gray-800">Detail Transaksi</h2>

    {{-- ============================ CHECKOUT ============================ --}}
    <div class="bg-white shadow-md rounded-2xl p-6">
        <h3 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-5">Checkout</h3>

        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            {{-- Kolom teks --}}
            <div class="md:w-2/3 space-y-2">
                <p><strong>Nama Produk:</strong> {{ $transaksi->produk->nama }}</p>
                <p><strong>Jumlah:</strong> {{ $transaksi->checkout->jumlah }}</p>
                <p>
                    <strong>Total Produk:</strong>
                    Rp{{ number_format($transaksi->checkout->jumlah * ($transaksi->checkout->varian->harga ?? $transaksi->produk->harga), 0, ',', '.') }}
                </p>

                @if($transaksi->checkout->varian)
                    <p><strong>Varian:</strong> {{ $transaksi->checkout->varian->nama }}</p>
                    <p><strong>Harga Varian:</strong> Rp{{ number_format($transaksi->checkout->varian->harga, 0, ',', '.') }}</p>
                @endif
            </div>

            {{-- Kolom gambar --}}
            @if($transaksi->checkout->varian && $transaksi->checkout->varian->gambar)
                <div class="md:w-1/3 flex justify-center md:justify-end">
                    <div class="w-40 h-40 md:w-48 md:h-48 rounded-xl overflow-hidden shadow">
                        <img src="{{ asset('storage/' . $transaksi->checkout->varian->gambar) }}"
                             alt="Gambar Varian"
                             class="w-full h-full object-cover">
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ============================ TOKO PENGIRIM ============================ --}}
    <a href="{{ route('user.toko.show', $transaksi->produk->toko->id) }}" class="block">
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex gap-4 shadow-sm items-center hover:shadow-md transition-shadow duration-200">
            {{-- Foto toko --}}
            @if($transaksi->produk->toko->foto_toko)
                <img src="{{ asset('storage/' . $transaksi->produk->toko->foto_toko) }}" alt="Foto Toko"
                    class="w-20 h-20 object-cover rounded-full shadow">
            @else
                <div class="w-20 h-20 flex items-center justify-center bg-gray-100 text-gray-500 italic rounded-full shadow text-xs text-center">
                    Tidak ada<br>foto toko
                </div>
            @endif

            {{-- Detail toko --}}
            <div>
                <h4 class="text-lg font-semibold text-gray-800">{{ $transaksi->produk->toko->nama_toko }}</h4>
                <p class="text-sm text-gray-600">{{ $transaksi->produk->toko->alamat }}</p>
                <p class="text-sm text-gray-600">Kota: {{ $transaksi->produk->toko->city_name ?? 'Tidak tersedia' }}</p>
                <p class="text-sm text-gray-600">
                    WhatsApp: 
                    <span class="text-blue-600">{{ $transaksi->produk->toko->nomer_wa }}</span>
                </p>
            </div>
        </div>
    </a>

    {{-- ============================ PENGIRIMAN ============================ --}}
    <div class="bg-white shadow-md rounded-2xl p-6">
        <h3 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-5">Pengiriman</h3>

        <div class="space-y-2">
            <p><strong>Kurir:</strong> {{ $transaksi->pengiriman->kurir }}</p>
            <p><strong>Layanan:</strong> {{ $transaksi->pengiriman->layanan }}</p>
            <p><strong>Ongkir:</strong> Rp{{ number_format($transaksi->pengiriman->ongkir, 0, ',', '.') }}</p>
            <p><strong>Alamat:</strong> {{ $transaksi->pengiriman->alamat_penerima }}</p>
        </div>
    </div>

    {{-- ============================ PEMBAYARAN ============================ --}}
    <div class="bg-white shadow-md rounded-2xl p-6">
        <h3 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-5">Pembayaran</h3>

        <div class="space-y-2">
            <p><strong>Metode:</strong> {{ $transaksi->pembayaran->metode_pembayaran }}</p>
            <p>
                <strong>Status:</strong>
                <span class="px-2 py-1 rounded text-sm font-semibold
                    {{ $transaksi->pembayaran->status_pembayaran === 'berhasil' ? 'bg-green-100 text-green-700' :
                       ($transaksi->pembayaran->status_pembayaran === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                       'bg-red-100 text-red-700') }}">
                    {{ ucfirst($transaksi->pembayaran->status_pembayaran) }}
                </span>
            </p>
            <p><strong>Total Bayar:</strong> Rp{{ number_format($transaksi->pembayaran->total_bayar, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- ============================ TOMBOL KEMBALI ============================ --}}
    <div class="text-right">
        <a href="{{ route('user.transaksi.index') }}"
        class="inline-block w-full bg-indigo-600 text-white py-4 rounded-2xl font-semibold text-xl hover:bg-indigo-700 transition-shadow shadow-md hover:shadow-lg text-center">
            Kembali
        </a>
    </div>
@endsection
