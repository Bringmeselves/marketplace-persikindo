@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-xl space-y-8">
    <h1 class="text-3xl font-bold text-gray-800">💳 Konfirmasi Pembayaran</h1>

    {{-- Detail Produk --}}
    <div class="bg-gray-50 p-4 rounded-xl border flex flex-col md:flex-row gap-4 items-start">
        {{-- Gambar Produk / Varian --}}
        <div class="w-full md:w-32 h-32 flex-shrink-0 overflow-hidden rounded-lg border">
            <img src="{{ asset('storage/' . ($checkout->varian->gambar ?? $checkout->produk->gambar)) }}"
                 alt="{{ $checkout->produk->nama }}"
                 class="w-full h-full object-cover object-center">
        </div>

        {{-- Info Produk --}}
        <div class="flex-1">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">📦 Produk</h2>
            <p><strong>Nama:</strong> {{ $checkout->produk->nama }}</p>

            @php
                $hargaSatuan = $checkout->varian->harga ?? $checkout->produk->harga;
                $jumlah = $checkout->jumlah;
                $totalProduk = $hargaSatuan * $jumlah;
                $ongkir = $checkout->pengiriman->ongkir ?? 0;
                $totalBayar = $totalProduk + $ongkir;
            @endphp

            @if($checkout->varian)
                <p><strong>Varian:</strong> {{ $checkout->varian->nama }}</p>
            @endif

            <p><strong>Harga Satuan:</strong> Rp{{ number_format($hargaSatuan, 0, ',', '.') }}</p>
            <p><strong>Jumlah:</strong> {{ $jumlah }}</p>
            <p><strong>Total Produk:</strong> Rp{{ number_format($totalProduk, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Informasi Toko --}}
    <div class="bg-white p-4 rounded-xl border">
        <h2 class="font-semibold text-gray-700 mb-4">🏬 Toko</h2>
        <div class="flex items-center space-x-4 mb-3">
            @if($checkout->produk->toko->foto_toko)
                <img src="{{ asset('storage/' . $checkout->produk->toko->foto_toko) }}"
                     alt="{{ $checkout->produk->toko->nama_toko }}"
                     class="w-16 h-16 rounded-full object-cover border border-gray-300 shadow-sm">
            @else
                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-xl font-semibold border border-gray-300">
                    🏪
                </div>
            @endif

            <div class="flex flex-col justify-center space-y-1 overflow-hidden">
                <p class="text-gray-900 font-semibold text-xl leading-tight truncate">
                    {{ $checkout->produk->toko->nama_toko }}
                </p>

                @if($checkout->produk->toko->keterangan)
                    <p class="text-gray-700 text-sm leading-snug truncate">
                        {{ $checkout->produk->toko->keterangan }}
                    </p>
                @endif

                @php
                    $cityName = $checkout->produk->toko->city_name;
                @endphp

                @if($cityName)
                    <div class="flex items-center gap-2 text-indigo-600 text-sm font-medium mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 22s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z" />
                        </svg>
                        <span class="truncate">{{ $cityName }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Informasi Pengiriman --}}
    @if($checkout->pengiriman)
        <div class="bg-white p-4 rounded-xl border">
            <h2 class="font-semibold text-gray-700 mb-2">🚚 Pengiriman</h2>
            <div class="flex justify-between items-center">
                <div>
                    <p><strong>Kurir:</strong> {{ $checkout->pengiriman->kurir }}</p>
                    <p><strong>Layanan:</strong> {{ $checkout->pengiriman->layanan }}</p>
                    <p><strong>Alamat:</strong> {{ $checkout->pengiriman->alamat }}</p>
                </div>
                <span class="text-green-600 font-semibold text-lg">
                    Ongkir: Rp{{ number_format($ongkir, 0, ',', '.') }}
                </span>
            </div>
        </div>
    @endif

    {{-- Total Bayar --}}
    <div class="bg-yellow-100 p-4 rounded-xl border border-yellow-400 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">💰 Total Bayar</h2>
        <p class="text-xl font-bold text-yellow-800">
            Rp{{ number_format($totalBayar, 0, ',', '.') }}
        </p>
    </div>

    {{-- Form Pembayaran --}}
    <form action="{{ route('user.pembayaran.store', $checkout->id) }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
            <select name="metode_pembayaran" id="metode_pembayaran" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Metode</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="COD">Bayar di Tempat (COD)</option>
                <option value="E-Wallet">E-Wallet</option>
            </select>
            @error('metode_pembayaran')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            Konfirmasi & Lanjutkan
        </button>
    </form>
</div>
@endsection
