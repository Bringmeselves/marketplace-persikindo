@extends('layouts.app') 

@section('title', 'Daftar Transaksi') 

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8 text-gray-800">
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">Daftar Transaksi</h2>

    @if ($transaksiList->isEmpty())
        {{-- Jika tidak ada transaksi, tampilkan pesan kosong --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 text-gray-600">
            Belum ada transaksi.
        </div>
    @else
        {{-- Menampilkan daftar transaksi jika ada --}}
        <div class="space-y-6">
            @foreach ($transaksiList as $transaksi)
                {{-- Kartu untuk setiap transaksi --}}
                <div class="bg-white shadow-lg rounded-2xl p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        {{-- Judul dan status transaksi --}}
                        <h3 class="text-xl font-semibold text-gray-900">Transaksi #{{ $transaksi->id }}</h3>
                        <span class="text-sm px-3 py-1 rounded-full bg-blue-100 text-blue-700 capitalize">
                            {{ $transaksi->status }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        @php
                            $total = 0; // Inisialisasi total transaksi
                        @endphp

                        {{-- Loop setiap item dalam checkout --}}
                        @if ($transaksi->checkout && $transaksi->checkout->item)
                            @foreach ($transaksi->checkout->item as $item)
                                @php
                                    // Hitung subtotal dan total
                                    $harga = $item->produk->harga ?? 0;
                                    $jumlah = $item->jumlah ?? 0;
                                    $subtotal = $harga * $jumlah;
                                    $total += $subtotal;
                                @endphp

                                {{-- Tampilan info produk --}}
                                <div class="flex flex-col md:flex-row gap-4 border-b pb-3">
                                    {{-- Gambar Produk --}}
                                    <div class="w-24 h-24 flex-shrink-0 rounded overflow-hidden bg-gray-100">
                                        <img src="{{ asset('storage/' . ($item->varian->gambar ?? $item->produk->gambar ?? 'img/default.png')) }}"
                                             alt="{{ $item->produk->nama ?? 'Produk' }}"
                                             class="object-cover w-full h-full">
                                    </div>

                                    {{-- Info Produk --}}
                                    <div class="flex-grow">
                                        <p class="font-semibold text-gray-900">
                                            {{ $item->produk->nama ?? 'Produk tidak ditemukan' }}
                                            @if($item->varian)
                                                {{-- Tampilkan nama varian jika ada --}}
                                                <span class="text-sm text-gray-500">({{ $item->varian->nama }})</span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-600">Jumlah: {{ $jumlah }}</p>
                                        <p class="text-sm text-gray-600">Harga: Rp{{ number_format($harga, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-800 font-medium">Subtotal: Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Informasi total pembayaran dan tanggal --}}
                        <div class="pt-2 text-sm text-gray-700 space-y-1">
                            <div class="flex justify-between">
                                <span class="font-medium">Total Pembayaran:</span>
                                <span>
                                    Rp{{ number_format($transaksi->pembayaran->total ?? $total, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Tanggal:</span>
                                <span>{{ $transaksi->created_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="flex flex-wrap gap-2 justify-end pt-2">
                        {{-- Tombol Lihat Detail --}}
                        <form action="{{ route('user.transaksi.show', $transaksi->id) }}" method="GET">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-100 text-indigo-800 hover:bg-indigo-200 text-sm font-semibold">
                                <i data-lucide="eye" class="w-4 h-4"></i> Lihat Detail
                            </button>
                        </form>

                        {{-- Tombol Beri Penilaian, jika status selesai --}}
                        @if ($transaksi->status === 'selesai')
                            <form action="{{ route('user.penilaian.create', ['transaksi' => $transaksi->id]) }}" method="GET">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-100 text-green-800 hover:bg-green-200 text-sm font-semibold">
                                    <i data-lucide="star" class="w-4 h-4"></i> Beri Penilaian
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons(); // Inisialisasi ikon dari Lucide
    });
</script>
@endsection
