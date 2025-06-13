@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container mx-auto px-4 py-8 font-sans text-gray-900 leading-relaxed">
    <h2 class="text-3xl font-bold mb-6">Daftar Transaksi Anda</h2>

    @if ($transaksiList->isEmpty())
        <p class="text-gray-600">Belum ada transaksi.</p>
    @else
        <div class="grid gap-6">
            @foreach ($transaksiList as $item)
                <div class="flex gap-4 p-6 border rounded-lg shadow hover:shadow-lg transition">
                    {{-- Foto produk --}}
                    <div class="w-40 h-40 flex-shrink-0">
                        @if (!empty($item->produk->gambar))
                            <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="Foto Produk" class="w-full h-full object-cover rounded">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 text-sm rounded">
                                Tidak ada foto
                            </div>
                        @endif
                    </div>

                    {{-- Detail transaksi --}}
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-2">
                            {{ $item->produk->nama ?? 'Produk tidak tersedia' }}
                        </h3>
                        <p class="mb-1"><strong>Checkout ID:</strong> {{ $item->checkout_id ?? '-' }}</p>
                        <p class="mb-1"><strong>Toko:</strong> {{ $item->produk->toko->nama_toko ?? 'Tidak tersedia' }}</p>
                        <p class="mb-1"><strong>Total Bayar:</strong> Rp{{ number_format($item->pembayaran->total_bayar ?? 0, 0, ',', '.') }}</p>
                        <p class="mb-3"><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
                        <a href="{{ route('user.transaksi.show', $item->id) }}" class="text-blue-600 hover:underline">Lihat Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
