@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10" id="produk">

    {{-- Hero Section --}}
    <div class="relative w-full h-[80vh] overflow-hidden rounded-2xl shadow-lg mb-16">
        <img src="{{ asset('images/bg2.jpg') }}" alt="Marketplace Hero"
             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
        <div class="relative z-10 flex flex-col items-start justify-center h-full px-6 sm:px-12 lg:px-20 max-w-6xl mx-auto">
            <h1 class="text-white text-4xl sm:text-5xl font-bold mb-4 leading-snug">
                Belanja Produk Terbaik,<br class="hidden sm:block"> Dari Toko Terpercaya!
            </h1>
            <p class="text-white text-base sm:text-lg max-w-2xl mb-6 leading-relaxed">
                Temukan beragam produk dan jasa unggulan dari penjual terpercaya di seluruh Indonesia.
            </p>
            <a href="#produk" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-3 rounded-xl transition">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                Mulai Belanja
            </a>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="flex flex-col sm:flex-row gap-4">
        <form action="{{ route('user.marketplace.index') }}" method="GET" class="flex-grow">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari produk atau toko..."
                class="w-full rounded-xl border-gray-300 px-4 py-2 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            />
        </form>
        <form action="{{ route('user.marketplace.index') }}" method="GET" class="w-full sm:w-60">
            <select
                name="kategori"
                onchange="this.form.submit()"
                class="w-full rounded-xl border-gray-300 px-3 py-2 text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            >
                <option value="">Pilih Kategori</option>
                @foreach ($kategori as $item)
                    <option value="{{ $item->id }}" {{ request('kategori') == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Produk List --}}
    @if ($produk->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($produk as $item)
                <div class="bg-white rounded-2xl shadow hover:shadow-md transition flex flex-col overflow-hidden">
                    {{-- Gambar --}}
                    <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                        <img
                            src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-produk.png') }}"
                            alt="{{ $item->nama }}"
                            class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                        />
                    </div>

                    {{-- Konten Produk --}}
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900 truncate" title="{{ $item->nama }}">
                                {{ $item->nama }}
                            </h3>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $item->deskripsi ?? '-' }}</p>

                            {{-- Info Toko --}}
                            @if ($item->toko)
                                <div class="mt-2 text-sm text-gray-600 space-y-1">
                                    <div class="flex items-center gap-1">
                                        <i data-lucide="store" class="w-4 h-4 text-indigo-500"></i>
                                        {{ $item->toko->nama_toko }}
                                    </div>
                                    <div class="ml-5 text-gray-500 text-xs">
                                        <div>{{ $item->toko->city_name ?? 'Tidak diketahui' }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Harga --}}
                        <div class="text-black-600 font-bold text-lg">
                            Rp{{ number_format($item->harga, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Tombol Beli --}}
                    <div class="border-t bg-gray-50 px-5 py-3">
                        <form action="{{ route('user.pembelian.create', $item->id) }}" method="GET">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700 transition flex justify-center items-center gap-2">
                                <i data-lucide="shopping-cart" class="w-4 h-4"></i> Beli
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10 flex justify-center">
            {{ $produk->withQueryString()->links('pagination::tailwind') }}
        </div>
    @else
        <div class="text-center py-24 text-gray-400 text-lg italic">
            Tidak ada produk tersedia di marketplace.
        </div>
    @endif
</div>

{{-- Lucide Icon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
@endsection
