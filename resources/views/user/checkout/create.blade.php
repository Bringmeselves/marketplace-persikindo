@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900">Checkout</h2>

    {{-- SECTION: Detail Produk --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <i data-lucide="shopping-bag" class="w-5 h-5 text-indigo-500"></i>
            <h3 class="text-xl font-semibold text-gray-900">Detail Produk</h3>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-3 text-base">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama Produk</span>
                    <span class="font-medium">{{ $produk->nama }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Varian</span>
                    <span class="font-medium">{{ $varian->nama }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jumlah</span>
                    <span class="font-medium">{{ $jumlah }}</span>
                </div>
                <div class="flex justify-between border-t pt-3">
                    <span class="text-gray-500">Total Harga</span>
                    <span class="font-semibold text-indigo-600">
                        Rp{{ number_format($checkout->total_harga, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="flex justify-center md:justify-end">
                <div class="w-40 h-40 rounded-xl overflow-hidden shadow">
                    <img src="{{ asset('storage/' . $checkout->gambar) }}" alt="Produk" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION: Toko --}}
    <div class="bg-white border rounded-2xl p-6 flex items-center gap-6 shadow-sm hover:shadow-md transition">
        <img src="{{ asset('storage/' . $produk->toko->foto_toko) }}" alt="Foto Toko"
             class="w-20 h-20 object-cover rounded-full border shadow-sm">
        <div class="text-sm space-y-1">
            <h4 class="text-lg font-semibold text-gray-900">{{ $produk->toko->nama_toko }}</h4>
            <p class="text-gray-600">{{ $produk->toko->alamat }}</p>
        </div>
    </div>

    {{-- SECTION: Alamat Pengiriman --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
                <i data-lucide="map-pin" class="w-5 h-5 text-indigo-500"></i>
                <h3 class="text-xl font-semibold text-gray-900">Alamat Pengiriman</h3>
            </div>
            <a href="{{ $checkout->pengiriman
                ? route('user.pengiriman.alamat.edit', $checkout->id)
                : route('user.pengiriman.alamat.create', $checkout->id) }}"
               class="text-sm font-medium text-indigo-600 hover:underline">
                {{ $checkout->pengiriman ? 'Ubah Alamat' : 'Tambah Alamat' }}
            </a>
        </div>

        @if ($checkout->pengiriman)
            <div class="space-y-1 text-base text-gray-700">
                <p class="font-semibold">{{ $checkout->pengiriman->nama_lengkap }}</p>
                <p>{{ $checkout->pengiriman->alamat_penerima }}</p>
                <p>{{ $checkout->pengiriman->city_name }}, {{ $checkout->pengiriman->kode_pos }}</p>
                <p>WA: {{ $checkout->pengiriman->nomor_wa }}</p>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Belum ada alamat pengiriman.</p>
        @endif
    </div>

    {{-- SECTION: Kurir --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
                <i data-lucide="truck" class="w-5 h-5 text-indigo-500"></i>
                <h3 class="text-xl font-semibold text-gray-900">Jasa Pengiriman</h3>
            </div>
            @if ($checkout->pengiriman)
                <a href="{{ route('user.pengiriman.kurir.edit', $checkout->id) }}"
                   class="text-sm font-medium text-indigo-600 hover:underline">
                    {{ $checkout->pengiriman->kurir ? 'Ubah Kurir' : 'Pilih Kurir' }}
                </a>
            @endif
        </div>

        @if ($checkout->pengiriman && $checkout->pengiriman->kurir)
            <div class="space-y-2 text-base">
                <div class="flex justify-between">
                    <span class="text-gray-500">Kurir</span>
                    <span>{{ strtoupper($checkout->pengiriman->kurir) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Layanan</span>
                    <span>{{ $checkout->pengiriman->layanan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Ongkir</span>
                    <span>Rp{{ number_format($checkout->pengiriman->ongkir, 0, ',', '.') }}</span>
                </div>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Kurir belum dipilih.</p>
        @endif
    </div>

    {{-- Tombol Aksi --}}
    <div class="pt-6 text-center md:text-right">
        @if ($checkout->pengiriman && $checkout->pengiriman->kurir)
            <form action="{{ route('user.pembayaran.create', $checkout->id) }}" method="GET">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-xl shadow transition">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                    Lanjut ke Pembayaran
                </button>
            </form>
        @else
            <p class="text-sm text-red-500">Silakan lengkapi alamat dan kurir terlebih dahulu.</p>
        @endif
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
