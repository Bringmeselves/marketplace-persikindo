@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    {{-- Judul halaman --}}
    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
        <i data-lucide="credit-card" class="w-6 h-6 text-indigo-500"></i>
        Konfirmasi Pembayaran
    </h1>

    {{-- SECTION: Produk dan Checkout --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <i data-lucide="package" class="w-5 h-5 text-indigo-500"></i>
            <h2 class="text-xl font-semibold text-gray-900">Produk</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            {{-- Info produk dan varian --}}
            <div class="md:col-span-2 space-y-3 text-base">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama Produk</span>
                    <span class="font-medium">{{ $checkout->produk->nama }}</span>
                </div>
                @if($checkout->varian)
                <div class="flex justify-between">
                    <span class="text-gray-500">Varian</span>
                    <span class="font-medium">{{ $checkout->varian->nama }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Harga Varian</span>
                    <span class="font-medium">Rp{{ number_format($checkout->varian->harga, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500">Jumlah</span>
                    <span class="font-medium">{{ $checkout->jumlah }}</span>
                </div>

                {{-- Hitung total produk --}}
                <div class="flex justify-between border-t pt-3">
                    <span class="text-gray-500">Total Produk</span>
                    @php
                        $hargaSatuan = $checkout->varian->harga ?? $checkout->produk->harga;
                        $jumlah = $checkout->jumlah;
                        $totalProduk = $hargaSatuan * $jumlah;
                    @endphp
                    <span class="font-semibold text-indigo-600">
                        Rp{{ number_format($totalProduk, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Gambar produk/varian --}}
            <div class="flex justify-center md:justify-end">
                <div class="w-40 h-40 rounded-xl overflow-hidden shadow">
                    <img src="{{ asset('storage/' . ($checkout->varian->gambar ?? $checkout->produk->gambar)) }}"
                         alt="{{ $checkout->produk->nama }}"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION: Toko --}}
    <a href="{{ route('user.toko.show', $checkout->produk->toko->id) }}" class="block">
        <div
            class="bg-white border rounded-2xl p-6 flex items-center gap-6 shadow-sm hover:shadow-md transition">
            @if($checkout->produk->toko->foto_toko)
                <img src="{{ asset('storage/' . $checkout->produk->toko->foto_toko) }}" alt="Foto Toko"
                     class="w-20 h-20 object-cover rounded-full border shadow-sm">
            @else
                <div
                    class="w-20 h-20 flex items-center justify-center bg-gray-100 text-gray-500 rounded-full text-xs text-center">
                    Tidak ada<br>foto toko
                </div>
            @endif

            <div class="text-sm space-y-1">
                <p class="text-lg font-semibold text-gray-900">{{ $checkout->produk->toko->nama_toko }}</p>
                <p class="text-gray-600">{{ $checkout->produk->toko->keterangan ?? $checkout->produk->toko->alamat }}</p>

                {{-- kota dengan icon lokasi --}}
                <div class="flex items-center text-gray-600 gap-1">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>{{ $checkout->produk->toko->city_name ?? 'Tidak tersedia' }}</span>
                </div>
            </div>
        </div>
    </a>

    {{-- SECTION: Pengiriman --}}
    @if($checkout->pengiriman)
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-4">
            <i data-lucide="truck" class="w-5 h-5 text-indigo-500"></i>
            <h2 class="text-xl font-semibold text-gray-900">Pengiriman</h2>
        </div>

        <div class="space-y-2 text-base">
            <div class="flex justify-between">
                <span class="text-gray-500">Kurir</span>
                <span>{{ $checkout->pengiriman->kurir }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Layanan</span>
                <span>{{ $checkout->pengiriman->layanan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Ongkir</span>
                <span>Rp{{ number_format($checkout->pengiriman->ongkir ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="pt-2">
                <p class="text-gray-500">Alamat</p>
                <p class="text-gray-800">{{ $checkout->pengiriman->alamat }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- SECTION: Total Bayar --}}
    <div class="bg-gray-50 rounded-2xl shadow-inner p-5 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="wallet" class="w-5 h-5 text-gray-800"></i>
            <h2 class="text-base font-semibold text-gray-800">Total Bayar</h2>
        </div>
        @php
            $ongkir = $checkout->pengiriman->ongkir ?? 0;
            $totalBayar = $totalProduk + $ongkir;
        @endphp
        <p class="text-xl font-bold text-gray-900">Rp{{ number_format($totalBayar, 0, ',', '.') }}</p>
    </div>

    {{-- SECTION: Form Pembayaran --}}
    <form action="{{ route('user.pembayaran.store', $checkout->id) }}" method="POST"
          class="bg-white shadow-lg rounded-2xl p-6 md:p-8 space-y-6">
        @csrf

        <div>
            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
            <select name="metode_pembayaran" id="metode_pembayaran" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Pilih Metode</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="COD">Bayar di Tempat (COD)</option>
                <option value="E-Wallet">E-Wallet</option>
            </select>
            @error('metode_pembayaran')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-xl shadow hover:bg-indigo-700 transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            Konfirmasi & Lanjutkan
        </button>
    </form>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
