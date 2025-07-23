@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">

    {{-- Kartu Utama --}}
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-8 border border-gray-100">

        {{-- Header --}}
        <div class="flex justify-between items-center border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Pembayaran #{{ $checkout->id }}</h2>
                <p class="text-sm text-gray-500">Silakan pilih metode pembayaran dan selesaikan transaksi Anda.</p>
            </div>
            <span class="text-sm px-3 py-1 rounded-full bg-green-100 text-green-700 capitalize font-semibold">
                {{ $checkout->status ?? 'menunggu' }}
            </span>
        </div>

        {{-- Info Pembeli --}}
        <div class="grid sm:grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-gray-500 mb-1">Nama Pembeli</p>
                <p class="text-gray-900 font-semibold">{{ Auth::user()->name }}</p>
            </div>
            <div>
                <p class="text-gray-500 mb-1">Tanggal Checkout</p>
                <p class="text-gray-900 font-semibold">{{ $checkout->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        {{-- Ringkasan Biaya --}}
        <div class="bg-blue-50 rounded-xl p-4 space-y-2 text-sm border border-blue-100">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Produk</span>
                <span class="font-semibold text-gray-800">Rp{{ number_format($checkout->item->sum('total_harga'), 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Ongkir</span>
                <span class="font-semibold text-gray-800">Rp{{ number_format($checkout->pengiriman->ongkir ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="border-t pt-3 flex justify-between font-bold text-base text-blue-700">
                <span>Total Bayar</span>
                <span>Rp{{ number_format($checkout->item->sum('total_harga') + ($checkout->pengiriman->ongkir ?? 0), 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Jenis Metode Pembayaran</h3>
            <div class="grid sm:grid-cols-2 gap-4 text-sm">

                @php
                    $payments = [
                        ['icon' => 'credit-card', 'label' => 'Kartu Kredit / Debit'],
                        ['icon' => 'banknote', 'label' => 'Transfer Bank'],
                        ['icon' => 'wallet', 'label' => 'Virtual Account'],
                        ['icon' => 'smartphone', 'label' => 'E-Wallet'],
                        ['icon' => 'qr-code', 'label' => 'QRIS'],
                        ['icon' => 'store', 'label' => 'Indomaret / Alfamart'],
                    ];
                @endphp

                @foreach ($payments as $method)
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 bg-white hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer group">
                        <div class="w-12 h-12 rounded-full bg-blue-100 group-hover:bg-blue-600 flex items-center justify-center transition">
                            <i data-lucide="{{ $method['icon'] }}" class="w-6 h-6 text-blue-600 group-hover:text-white transition"></i>
                        </div>
                        <span class="text-gray-800 font-medium group-hover:text-blue-800">{{ $method['label'] }}</span>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- Tombol Bayar --}}
        <div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <button id="pay-button"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-xl shadow-md transition">
                <i data-lucide="check-circle" class="w-5 h-5"></i> Bayar Sekarang
            </button>
            <a href="{{ route('user.checkout.create', $checkout->id) }}" class="text-sm text-gray-500 hover:underline hover:text-blue-600">
                ← Kembali ke Checkout
            </a>
        </div>

    </div>
</div>

{{-- Midtrans Snap.js --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

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

{{-- Lucide --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();

        Swal.fire({
            title: '⚠️ Perhatian Sebelum Membayar',
            html: `
                <div style="font-size:14px; color:#4b5563; line-height:1.6; text-align: center;">
                    <p>Periksa kembali barang pembelian Anda sebelum melakukan pembayaran.</p>
                    <p>Pastikan jumlah, varian produk, sudah sesuai dengan pesanan Anda.</p>
                    <p style="margin-bottom:0;">Pembayaran yang sudah dilakukan tidak dapat dibatalkan.</p>
                </div>
            `,
            icon: 'warning',
            iconColor: '#f59e0b',
            background: '#ffffff',
            confirmButtonText: 'Saya Sudah Memeriksa',
            confirmButtonColor: '#f59e0b',
            width: '100%',
            padding: '1.5rem 2rem',
            showCloseButton: true,
            customClass: {
                popup: 'swal-wide-popup',
                title: 'swal-attractive-title',
                confirmButton: 'swal-attractive-button'
            }
        });
    });
</script>

<style>
    .swal-wide-popup {
        max-width: 800px;
        width: 95%;
        border-radius: 1rem;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', sans-serif;
        text-align: center; /* Pastikan isi popup seluruhnya rata tengah */
    }

    .swal-attractive-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
        text-align: center;
    }

    .swal-attractive-button {
        font-size: 14px !important;
        font-weight: 600;
        padding: 10px 20px !important;
        border-radius: 8px;
        transition: background 0.3s ease;
    }

    .swal-attractive-button:hover {
        filter: brightness(0.95);
    }
</style>
@endsection
