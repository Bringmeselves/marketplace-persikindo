@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">Pembayaran</h2>

    {{-- Rincian Produk --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-4">
        <h3 class="text-xl font-semibold text-gray-900">Rincian Produk</h3>
        <div class="space-y-6">
            @foreach ($checkout->item as $item)
                <div class="flex flex-col md:flex-row gap-6 border-b pb-4">
                    <div class="w-full md:w-40 h-40 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Produk" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-grow space-y-2">
                        <h4 class="text-lg font-semibold text-gray-900">
                            {{ $item->produk->nama ?? 'Produk tidak ditemukan' }}
                            @if($item->varian)
                                <span class="text-sm text-gray-500">({{ $item->varian->nama }})</span>
                            @endif
                        </h4>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Jumlah</span><span class="font-medium text-gray-800">{{ $item->jumlah }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-gray-900 border-t pt-2">
                            <span>Subtotal</span>
                            <span>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Info Pengiriman --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-4">
        <h3 class="flex items-center gap-2 text-xl font-semibold text-gray-900">
            <i data-lucide="truck" class="w-5 h-5 text-indigo-500"></i> Pengiriman
        </h3>
        @if ($checkout->pengiriman)
            <div class="space-y-1 text-sm text-gray-700">
                <div class="flex justify-between"><span>Kurir</span><span>{{ strtoupper($checkout->pengiriman->jasa_kurir) }} ({{ $checkout->pengiriman->layanan }})</span></div>
                <div class="flex justify-between"><span>Alamat</span><span class="text-right">{{ $checkout->pengiriman->alamat_lengkap }}</span></div>
                <div class="flex justify-between"><span>Ongkir</span><span>Rp{{ number_format($checkout->pengiriman->ongkir, 0, ',', '.') }}</span></div>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Belum memilih pengiriman.</p>
        @endif
    </div>

    {{-- Total Bayar --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-4 text-gray-900">
        @php
            $totalProduk = $checkout->item->sum('total_harga');
            $ongkir = $checkout->pengiriman->ongkir ?? 0;
            $totalBayar = $totalProduk + $ongkir;
        @endphp
        <div class="flex justify-between text-sm">
            <span class="text-gray-700 font-medium">Total Produk</span>
            <span>Rp{{ number_format($totalProduk, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-700 font-medium">Ongkir</span>
            <span>Rp{{ number_format($ongkir, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between mt-2 text-lg font-bold border-t pt-4">
            <span>Total Bayar</span>
            <span class="text-indigo-600">Rp{{ number_format($totalBayar, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Form Metode Pembayaran --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-6">
        <h3 class="text-xl font-semibold text-gray-900">Pilih Metode Pembayaran</h3>

        <form action="{{ route('user.pembayaran.store', $checkout->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-300">
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="transfer_bank">Transfer Bank</option>
                    <option value="e-wallet">E-Wallet</option>
                    <option value="cod">Cash on Delivery</option>
                </select>
                @error('metode_pembayaran')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-right pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition">
                    <i data-lucide="wallet" class="w-5 h-5"></i> Bayar Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Lucide --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
    });
</script>
@endsection
