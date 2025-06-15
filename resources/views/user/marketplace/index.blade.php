@extends('layouts.app')

@section('content')
<!-- Hero Section Modern -->
<div class="relative w-full h-[80vh] overflow-hidden rounded-3xl shadow-xl mb-12">
    <!-- Background Image -->
    <img src="{{ asset('images/bg2.jpg') }}" alt="Marketplace Hero"
         class="absolute inset-0 w-full h-full object-cover scale-110 transition-transform duration-700">

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent backdrop-blur-sm"></div>

    <!-- Hero Content -->
    <div class="relative z-10 flex flex-col items-start justify-center h-full px-6 sm:px-16 lg:px-24 max-w-7xl mx-auto">
        <h1 class="text-white text-4xl sm:text-6xl font-extrabold leading-tight drop-shadow-md mb-6">
            Belanja Produk Terbaik,<br class="hidden sm:block"> Dari Toko Terpercaya!
        </h1>
        <p class="text-white text-base sm:text-lg md:text-xl max-w-2xl mb-8 leading-relaxed drop-shadow-sm">
            Jelajahi berbagai produk dan jasa berkualitas dari penjual pilihan di seluruh Indonesia — semua dalam satu tempat.
        </p>
        <a href="#produk"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-full transition transform hover:scale-105 duration-300 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h11M9 21V3m11 11l-4 4m0-8l4 4"/>
            </svg>
            Mulai Belanja
        </a>
    </div>
</div>

<!-- Main Container -->
<div class="container mx-auto px-4 mt-8" id="produk">
    <!-- Search & Category Dropdown -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <!-- Search Form -->
        <form action="{{ route('user.marketplace.index') }}" method="GET" class="flex-grow max-w-md">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari produk atau toko..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-700 text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
            />
        </form>

        <!-- Category Dropdown -->
        <form action="{{ route('user.marketplace.index') }}" method="GET" class="w-48">
            <select
                name="kategori"
                onchange="this.form.submit()"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
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

    <!-- Produk Grid -->
    @if ($produk->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($produk as $item)
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 flex flex-col overflow-hidden group">
                    <div class="relative w-full aspect-[4/3] overflow-hidden rounded-t-2xl bg-gray-100">
                        <img
                            src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-produk.png') }}"
                            alt="{{ $item->nama }}"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                        />
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h2 class="font-semibold text-lg text-gray-900 truncate" title="{{ $item->nama }}">
                                {{ $item->nama }}
                            </h2>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                {{ $item->deskripsi ?? '-' }}
                            </p>

                            <!-- Toko Info -->
                            @if ($item->toko)
                            <div class="mt-4 text-xs text-black-600">
                                <div class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7l1.664 8.32A2 2 0 006.613 17h10.774a2 2 0 001.95-1.68L21 7M5 7h14M8 10v4M12 10v4M16 10v4" />
                                    </svg>
                                    <span>{{ $item->toko->nama_toko }}</span>
                                </div>
                                <div class="ml-5 text-gray-500">
                                    <span>{{ $item->toko->city_name }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <p class="text-xl font-extrabold text-gray-900 mt-3">
                            Rp{{ number_format($item->harga, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                        <form action="{{ route('user.pembelian.create', $item->id) }}" method="GET">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700 transition">
                                Beli
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10 flex justify-center">
            {{ $produk->withQueryString()->links('pagination::tailwind') }}
        </div>
    @else
        <div class="flex items-center justify-center min-h-[300px] mt-20">
            <p class="text-gray-400 text-center text-lg italic select-none">
                Tidak ada produk tersedia di marketplace.
            </p>
        </div>
    @endif
</div>
@endsection
