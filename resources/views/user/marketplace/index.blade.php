@extends('layouts.app')

@section('content')
<!-- Header dengan background gambar full-width dan overlay gelap -->
<div class="relative w-full h-[70vh] overflow-hidden rounded-lg mb-10">
    <img src="{{ asset('images/warung.jpg') }}" alt="Marketplace Hero" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
    <div class="absolute inset-0 flex items-center justify-center">
        <h1 class="text-5xl font-extrabold text-white drop-shadow-lg select-none text-center px-4">
            Marketplace
        </h1>
    </div>
</div>

<!-- Container utama -->
<div class="container mx-auto px-4 mt-8">

    <!-- Baris Search & Dropdown kategori -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <!-- Form Pencarian -->
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

        <!-- Form Dropdown Kategori -->
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

    <!-- Grid Produk -->
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

                            <!-- Info Toko -->
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
