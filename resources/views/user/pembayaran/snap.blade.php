@extends('layouts.app')

@section('title', 'Pembayaran via Midtrans')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8 text-gray-800">

    {{-- Judul --}}
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">Pembayaran</h2>

    {{-- Informasi Checkout --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-600">Checkout ID</p>
                <p class="font-semibold text-gray-900">{{ $checkout->id }}</p>
            </div>
            <div>
                <p class="text-gray-600">Nama Pembeli</p>
                <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
            </div>
            <div>
                <p class="text-gray-600">Total Produk</p>
                <p class="font-semibold text-gray-900">Rp{{ number_format($checkout->item->sum('total_harga'), 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Ongkos Kirim</p>
                <p class="font-semibold text-gray-900">Rp{{ number_format($checkout->pengiriman->ongkir ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="col-span-2 border-t pt-4">
                <p class="text-gray-700 font-semibold text-lg flex justify-between">
                    <span>Total Bayar:</span>
                    <span>Rp{{ number_format($checkout->item->sum('total_harga') + ($checkout->pengiriman->ongkir ?? 0), 0, ',', '.') }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Metode Pembayaran --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-3">
        <h3 class="text-lg font-semibold text-gray-900">Metode Pembayaran Tersedia</h3>
        <ul class="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-gray-700 text-sm">
            <li class="flex items-center gap-2"><i data-lucide="credit-card" class="w-4 h-4 text-indigo-500"></i> Kartu Kredit / Debit</li>
            <li class="flex items-center gap-2"><i data-lucide="banknote" class="w-4 h-4 text-indigo-500"></i> Transfer Bank</li>
            <li class="flex items-center gap-2"><i data-lucide="wallet" class="w-4 h-4 text-indigo-500"></i> Virtual Account</li>
            <li class="flex items-center gap-2"><i data-lucide="smartphone" class="w-4 h-4 text-indigo-500"></i> E-Wallet</li>
            <li class="flex items-center gap-2"><i data-lucide="scan-line" class="w-4 h-4 text-indigo-500"></i> QRIS</li>
            <li class="flex items-center gap-2"><i data-lucide="store" class="w-4 h-4 text-indigo-500"></i> Indomaret & Alfamart</li>
        </ul>
    </div>

    {{-- Tombol Bayar --}}
    <div class="flex justify-start items-center gap-4">
        <button id="pay-button"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl">
            <i data-lucide="check-circle" class="w-5 h-5"></i> Bayar Sekarang
        </button>
        <a href="{{ route('user.checkout.create', $checkout->id) }}" class="text-sm text-gray-600 hover:underline">
            Kembali ke Checkout
        </a>
    </div>
</div>

{{-- Snap.js dari Midtrans --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

{{-- Trigger Snap on Click --}}
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = "{{ route('user.pembayaran.success', $checkout->id) }}";
            },
            onPending: function(result) {
                window.location.href = "{{ route('user.pembayaran.pending', $checkout->id) }}";
            },
            onError: function(result) {
                alert('Pembayaran gagal! Silakan coba lagi.');
            },
            onClose: function() {
                alert('Transaksi belum selesai. Anda menutup jendela pembayaran.');
            }
        });
    });
</script>

{{-- Lucide Icon Loader --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
