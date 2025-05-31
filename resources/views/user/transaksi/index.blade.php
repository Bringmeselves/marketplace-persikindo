@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-6">Daftar Transaksi Anda</h2>

    @if ($transaksi->isEmpty())
        <p class="text-gray-600">Belum ada transaksi.</p>
    @else
        <div class="grid gap-6">
            @foreach ($transaksi as $item)
                <div class="p-6 border rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-2">Produk: {{ $item->produk->nama ?? 'Tidak tersedia' }}</h3>
                    <p><strong>Total Bayar:</strong> Rp{{ number_format($item->pembayaran->total_bayar ?? 0, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
                    <a href="{{ route('user.transaksi.show', $item->id) }}" class="text-blue-600 hover:underline mt-3 inline-block">Lihat Detail</a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
