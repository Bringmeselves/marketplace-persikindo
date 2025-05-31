@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h2 class="text-3xl font-extrabold mb-8 text-center text-gray-900">Form Pembelian Produk</h2>

    <div class="bg-white shadow-lg rounded-xl p-8 max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Gambar Produk -->
            <div class="flex justify-center items-start">
                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}"
                    class="rounded-xl object-cover w-full max-w-md shadow-md transition-transform hover:scale-105 duration-300">
            </div>

            <!-- Informasi Produk & Form -->
            <div class="flex flex-col justify-between space-y-8">
                <div>
                    <h3 class="text-3xl font-semibold text-gray-800 mb-4">{{ $produk->nama }}</h3>

                    <ul class="space-y-4 text-gray-700">
                        <li class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c-1.657 0-3 1.343-3 3v1a3 3 0 003 3h0a3 3 0 003-3v-1a3 3 0 00-3-3z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3v1m0 16v1m8.485-8.485l-.707.707M4.222 4.222l-.707.707M21 12h-1M4 12H3m16.263 4.243l-.707-.707M4.222 19.778l-.707-.707" />
                            </svg>
                            <span><strong>Harga:</strong> Rp{{ number_format($produk->harga, 0, ',', '.') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <rect stroke-linecap="round" stroke-linejoin="round" width="18" height="18" x="3" y="3" />
                                <rect stroke-linecap="round" stroke-linejoin="round" width="10" height="10" x="7" y="7" />
                            </svg>
                            <span><strong>Stok:</strong> {{ $produk->stok }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 9l1.5 1.5M21 9l-1.5 1.5M4 10v6a1 1 0 001 1h14a1 1 0 001-1v-6" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 22V12m-4 0h8" />
                            </svg>
                            <span><strong>Nama Toko:</strong> {{ $produk->toko->nama_toko }}</span>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="4" y="4" width="16" height="16" rx="2" ry="2" />
                                <path d="M4 9h16M9 4v16" />
                            </svg>
                            Deskripsi Produk
                        </h4>
                        <p class="text-gray-600 whitespace-pre-line leading-relaxed">{{ $produk->deskripsi }}</p>
                    </div>
                </div>

                <!-- Form Pembelian -->
                <form action="{{ route('user.pembelian.store') }}" method="POST" class="space-y-6 mt-6">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembelian</label>
                        <input type="number" name="jumlah" id="jumlah" min="1" max="{{ $produk->stok }}" required
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold text-lg hover:bg-blue-700 transition">
                        Lanjut ke Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
