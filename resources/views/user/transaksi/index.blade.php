@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900">Daftar Transaksi</h2>

    {{-- Notifikasi --}}
    @if(session('success') || session('info'))
        <div class="space-y-2">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 border-l-4 border-green-500 bg-green-50 rounded shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="flex items-center gap-3 p-4 border-l-4 border-blue-500 bg-blue-50 rounded shadow-sm">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    <span class="text-sm text-blue-800 font-medium">{{ session('info') }}</span>
                </div>
            @endif
        </div>
    @endif

    @if($transaksiList->isEmpty())
        <div class="text-center text-gray-400 py-24 space-y-3">
            <i data-lucide="shopping-bag" class="mx-auto w-8 h-8"></i>
            <p class="italic">Belum ada transaksi yang dilakukan.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($transaksiList as $transaksi)
                <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 flex flex-col md:flex-row gap-6">
                    
                    {{-- Gambar --}}
                    <div class="w-full md:w-40 h-40 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . ($transaksi->checkout->gambar ?? 'default.jpg')) }}"
                             alt="Gambar Produk"
                             class="w-full h-full object-cover">
                    </div>

                    {{-- Detail Transaksi --}}
                    <div class="flex flex-col justify-between flex-grow space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $transaksi->produk->nama ?? 'Produk tidak ditemukan' }}
                                @if($transaksi->varian)
                                    <span class="text-sm text-gray-500">({{ $transaksi->varian->nama }})</span>
                                @endif
                            </h3>

                            <ul class="mt-2 space-y-1 text-sm text-gray-600">
                                <li class="flex justify-between">
                                    <span>ID Transaksi</span>
                                    <span class="font-medium text-gray-800">{{ $transaksi->id }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Jumlah</span>
                                    <span class="font-medium text-gray-800">{{ $transaksi->checkout->jumlah ?? '-' }} pcs</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Harga/item</span>
                                    <span class="font-medium text-gray-800">
                                        Rp{{ number_format(optional($transaksi->varian)->harga ?? optional($transaksi->produk)->harga ?? 0, 0, ',', '.') }}
                                    </span>
                                </li>
                                <li class="flex justify-between font-bold text-gray-900 border-t pt-2">
                                    <span>Total</span>
                                    <span>
                                        Rp{{ number_format(
                                            (
                                                (optional($transaksi->varian)->harga ?? optional($transaksi->produk)->harga ?? 0)
                                                * ($transaksi->checkout->jumlah ?? 1)
                                            ) + (optional($transaksi->checkout->pengiriman)->ongkir ?? 0),
                                            0, ',', '.'
                                        ) }}
                                    </span>
                                </li>
                            </ul>

                            <div class="mt-3">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                    {{ $transaksi->status === 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    <i data-lucide="{{ $transaksi->status === 'selesai' ? 'check-circle' : 'clock' }}" class="w-3.5 h-3.5"></i>
                                    {{ ucfirst($transaksi->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="text-right">
                            <a href="{{ route('user.transaksi.show', $transaksi->id) }}"
                               class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
