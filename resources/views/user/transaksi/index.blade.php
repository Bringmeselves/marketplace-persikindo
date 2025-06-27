@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8 text-gray-800">
    {{-- Judul Halaman --}}
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">Daftar Transaksi</h2>

    {{-- Jika tidak ada transaksi --}}
    @if ($transaksiList->isEmpty())
        <div class="bg-white shadow-lg rounded-2xl p-6 text-gray-600">
            Belum ada transaksi.
        </div>
    @else
        {{-- Daftar Transaksi --}}
        <div class="space-y-6">
            @foreach ($transaksiList as $transaksi)
                @php
                    // Hitung total produk
                    $totalProduk = 0;
                @endphp

                <div class="bg-white shadow-lg rounded-2xl p-6 space-y-4">
                    {{-- Header Transaksi --}}
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900">Transaksi #{{ $transaksi->id }}</h3>
                        <span class="text-sm px-3 py-1 rounded-full bg-blue-100 text-blue-700 capitalize">
                            {{ $transaksi->status }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        {{-- Tampilkan semua item dalam transaksi --}}
                        @if ($transaksi->checkout && $transaksi->checkout->item)
                            @foreach ($transaksi->checkout->item as $item)
                                @php
                                    $produk = $item->produk;
                                    $varian = $item->varian;
                                    $jumlah = (int) $item->jumlah;
                                    $harga = $varian->harga ?? $produk->harga ?? 0;
                                    $subtotal = $jumlah * $harga;
                                    $totalProduk += $subtotal;
                                @endphp

                                {{-- Item Produk --}}
                                <div class="flex flex-col md:flex-row gap-4 border-b pb-3">
                                    {{-- Gambar --}}
                                    <div class="w-24 h-24 flex-shrink-0 rounded overflow-hidden bg-gray-100">
                                        <img src="{{ asset('storage/' . ($varian->gambar ?? $produk->gambar ?? 'img/default.png')) }}"
                                             alt="{{ $produk->nama ?? 'Produk' }}"
                                             class="object-cover w-full h-full">
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-grow">
                                        <p class="font-semibold text-gray-900">
                                            {{ $produk->nama ?? 'Produk tidak ditemukan' }}
                                            @if ($varian)
                                                <span class="text-sm text-gray-500">({{ $varian->nama }})</span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-600">Jumlah: {{ $jumlah }}</p>
                                        <p class="text-sm text-gray-600">Harga: Rp{{ number_format($harga, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-800 font-medium">Subtotal: Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-600">Toko: {{ $produk->toko->nama_toko ?? '-' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Informasi Total --}}
                        <div class="pt-2 text-sm text-gray-700 space-y-1">
                            <div class="flex justify-between">
                                <span class="font-medium">Total Produk:</span>
                                <span>Rp{{ number_format($totalProduk, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Ongkir:</span>
                                <span>Rp{{ number_format($transaksi->pengiriman->ongkir ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-gray-900">
                                <span>Total Pembayaran:</span>
                                <span>
                                    Rp{{ number_format($transaksi->pembayaran->total ?? ($totalProduk + ($transaksi->pengiriman->ongkir ?? 0)), 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Tanggal:</span>
                                <span>{{ $transaksi->created_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-wrap gap-2 justify-end pt-2">
                        <a href="{{ route('user.transaksi.show', $transaksi->id) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-100 text-indigo-800 hover:bg-indigo-200 text-sm font-semibold">
                            <i data-lucide="eye" class="w-4 h-4"></i> Lihat Detail
                        </a>

                        @if ($transaksi->status === 'selesai')
                            <a href="{{ route('user.penilaian.create', ['transaksi' => $transaksi->id]) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-100 text-green-800 hover:bg-green-200 text-sm font-semibold">
                                <i data-lucide="star" class="w-4 h-4"></i> Beri Penilaian
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Inisialisasi ikon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
