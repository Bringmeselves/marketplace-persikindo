@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900">Detail Transaksi</h2>

    {{-- SECTION: Checkout --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <i data-lucide="shopping-bag" class="w-5 h-5 text-indigo-500"></i>
            <h3 class="text-xl font-semibold text-gray-900">Checkout</h3>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-3 text-base">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama Produk</span>
                    <span class="font-medium">{{ $transaksi->produk->nama }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jumlah</span>
                    <span class="font-medium">{{ $transaksi->checkout->jumlah }}</span>
                </div>
                @if($transaksi->checkout->varian)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Varian</span>
                        <span class="font-medium">{{ $transaksi->checkout->varian->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Harga Varian</span>
                        <span class="font-medium">Rp{{ number_format($transaksi->checkout->varian->harga, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between border-t pt-3">
                    <span class="text-gray-500">Total Produk</span>
                    <span class="font-semibold text-indigo-600">
                        Rp{{ number_format($transaksi->checkout->jumlah * ($transaksi->checkout->varian->harga ?? $transaksi->produk->harga), 0, ',', '.') }}
                    </span>
                </div>
            </div>

            @if($transaksi->checkout->varian && $transaksi->checkout->varian->gambar)
                <div class="flex justify-center md:justify-end">
                    <div class="w-40 h-40 rounded-xl overflow-hidden shadow">
                        <img src="{{ asset('storage/' . $transaksi->checkout->varian->gambar) }}" alt="Varian"
                             class="w-full h-full object-cover">
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION: Toko --}}
    <a href="{{ route('user.toko.show', $transaksi->produk->toko->id) }}" class="block">
        <div class="bg-white border rounded-2xl p-6 flex items-center gap-6 shadow-sm hover:shadow-md transition">
            @if($transaksi->produk->toko->foto_toko)
                <img src="{{ asset('storage/' . $transaksi->produk->toko->foto_toko) }}" alt="Foto Toko"
                     class="w-20 h-20 object-cover rounded-full border shadow-sm">
            @else
                <div class="w-20 h-20 flex items-center justify-center bg-gray-100 text-gray-500 rounded-full text-xs text-center">
                    Tidak ada<br>foto toko
                </div>
            @endif

            <div class="text-sm space-y-1">
                <h4 class="text-lg font-semibold text-gray-900">{{ $transaksi->produk->toko->nama_toko }}</h4>
                <p class="text-gray-600">{{ $transaksi->produk->toko->alamat }}</p>
                <p class="text-gray-600">Kota: {{ $transaksi->produk->toko->city_name ?? 'Tidak tersedia' }}</p>
                <p class="text-gray-600">WhatsApp: <span class="text-indigo-600 font-medium">{{ $transaksi->produk->toko->nomer_wa }}</span></p>
            </div>
        </div>
    </a>

    {{-- SECTION: Pengiriman --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-4">
            <i data-lucide="truck" class="w-5 h-5 text-indigo-500"></i>
            <h3 class="text-xl font-semibold text-gray-900">Pengiriman</h3>
        </div>

        <div class="space-y-2 text-base">
            <div class="flex justify-between">
                <span class="text-gray-500">Kurir</span>
                <span>{{ $transaksi->pengiriman->kurir }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Layanan</span>
                <span>{{ $transaksi->pengiriman->layanan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Ongkir</span>
                <span>Rp{{ number_format($transaksi->pengiriman->ongkir, 0, ',', '.') }}</span>
            </div>
            <div class="pt-2">
                <p class="text-gray-500">Alamat</p>
                <p class="text-gray-800">{{ $transaksi->pengiriman->alamat_penerima }}</p>
            </div>
        </div>
    </div>

    {{-- SECTION: Pembayaran --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-4">
            <i data-lucide="wallet" class="w-5 h-5 text-indigo-500"></i>
            <h3 class="text-xl font-semibold text-gray-900">Pembayaran</h3>
        </div>

        <div class="space-y-2 text-base">
            <div class="flex justify-between">
                <span class="text-gray-500">Metode</span>
                <span>{{ $transaksi->pembayaran->metode_pembayaran }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-500">Status</span>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                    {{ $transaksi->pembayaran->status_pembayaran == 'berhasil' ? 'bg-green-100 text-green-700' :
                       ($transaksi->pembayaran->status_pembayaran == 'pending' ? 'bg-yellow-100 text-yellow-700' :
                       'bg-red-100 text-red-700') }}">
                    {{ ucfirst($transaksi->pembayaran->status_pembayaran) }}
                </span>
            </div>
            <div class="flex justify-between border-t pt-3">
                <span class="text-gray-500">Total Bayar</span>
                <span class="font-semibold text-indigo-600">
                    Rp{{ number_format($transaksi->pembayaran->total_bayar, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="pt-6 text-center md:text-right">
        <a href="{{ route('user.transaksi.index') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali
        </a>
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
