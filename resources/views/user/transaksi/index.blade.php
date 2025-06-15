@extends('layouts.app') {{-- Ganti sesuai layout utama kamu --}}

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Transaksi</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            {{ session('info') }}
        </div>
    @endif

    @if($transaksiList->isEmpty())
        <p class="text-gray-600">Belum ada transaksi yang dilakukan.</p>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach($transaksiList as $transaksi)
                <div class="border rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold">
                                {{ $transaksi->produk->nama ?? 'Produk tidak ditemukan' }}
                                @if($transaksi->varian)
                                    - <span class="text-sm text-gray-600">{{ $transaksi->varian->nama }}</span>
                                @endif
                            </h2>
                            <p class="text-sm text-gray-600">ID Transaksi: {{ $transaksi->id }}</p>
                            <p class="text-sm text-gray-600">
                                Status: 
                                <span class="inline-block px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs">
                                    {{ ucfirst($transaksi->status) }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-600">
                                Jumlah: {{ $transaksi->checkout->jumlah ?? 1 }} pcs
                            </p>
                            <p class="text-sm text-gray-600">
                                Harga per item: 
                                Rp{{ number_format($transaksi->varian->harga ?? $transaksi->produk->harga, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-800 font-semibold">
                                Total Bayar:
                                Rp{{ number_format(
                                    ($transaksi->varian->harga ?? $transaksi->produk->harga) 
                                    * ($transaksi->checkout->jumlah ?? 1), 0, ',', '.'
                                ) }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('user.transaksi.show', $transaksi->id) }}"
                               class="inline-block px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
