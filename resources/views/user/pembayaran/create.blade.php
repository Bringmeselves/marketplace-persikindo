@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">Pembayaran</h2>

    {{-- Notifikasi --}}
    @if(session('error'))
        <div class="flex items-center gap-3 p-4 border-l-4 border-red-500 bg-red-50 rounded shadow-sm">
            <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
            <span class="text-sm text-red-800 font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Ringkasan Checkout --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-6">
        <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
            <i data-lucide="shopping-cart" class="w-5 h-5 text-indigo-500"></i> Ringkasan Belanja
        </h3>

        @foreach ($checkout->item as $item)
            <div class="border-b pb-4 flex gap-4">
                <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Produk" class="w-full h-full object-cover">
                </div>
                <div class="flex flex-col justify-between">
                    <div>
                        <h4 class="text-base font-semibold text-gray-900">
                            {{ $item->produk->nama }}
                            @if($item->varian)
                                <span class="text-sm text-gray-500">({{ $item->varian->nama }})</span>
                            @endif
                        </h4>
                        <p class="text-sm text-gray-600">Jumlah: {{ $item->jumlah }}</p>
                    </div>
                    <div class="text-sm font-bold text-indigo-600">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</div>
                </div>
            </div>
        @endforeach

        <div class="flex justify-between pt-4 border-t font-bold text-lg text-gray-900">
            <span>Total Produk</span>
            <span>Rp{{ number_format($checkout->item->sum('total_harga'), 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Ringkasan Pengiriman --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-3">
        <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
            <i data-lucide="truck" class="w-5 h-5 text-indigo-500"></i> Pengiriman
        </h3>

        <div class="text-sm text-gray-700 space-y-1">
            <div class="flex justify-between"><span>Kurir</span><span>{{ strtoupper($checkout->pengiriman->kurir) }}</span></div>
            <div class="flex justify-between"><span>Layanan</span><span>{{ $checkout->pengiriman->layanan }}</span></div>
            <div class="flex justify-between font-bold text-gray-900 border-t pt-2">
                <span>Ongkir</span>
                <span>Rp{{ number_format($checkout->pengiriman->ongkir, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Pilih Metode Pembayaran --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-6">
        <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
            <i data-lucide="wallet" class="w-5 h-5 text-indigo-500"></i> Pilih Metode Pembayaran
        </h3>

        <form action="{{ route('user.pembayaran.store', $checkout->id) }}" method="POST" class="space-y-4">
            @csrf

            <select name="metode_pembayaran" id="metode_pembayaran" required
                class="w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-300">
                <option value="">-- Pilih Metode --</option>
                <option value="transfer_manual">Transfer Manual</option>
                <option value="cod">Bayar di Tempat (COD)</option>
                <option value="qris">QRIS</option>
            </select>
            @error('metode_pembayaran')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex justify-between font-bold text-lg text-gray-900 border-t pt-4">
                <span>Total Bayar</span>
                <span>Rp{{ number_format($checkout->item->sum('total_harga') + ($checkout->pengiriman->ongkir ?? 0), 0, ',', '.') }}</span>
            </div>

            <div class="text-right">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition">
                    <i data-lucide="check-circle" class="w-5 h-5"></i> Bayar Sekarang
                </button>
            </div>
        </form>

        <div class="text-center pt-6">
            <p class="text-gray-500 mb-3 text-sm">Atau gunakan pembayaran cepat dengan Midtrans:</p>

            <a href="{{ route('user.pembayaran.midtrans', $checkout->id) }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition">
                <i data-lucide="credit-card" class="w-5 h-5"></i> Bayar dengan Midtrans
            </a>
        </div>
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
    });
</script>
@endsection
