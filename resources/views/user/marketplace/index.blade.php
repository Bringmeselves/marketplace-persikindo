@extends('layouts.app')

@section('content')
<!-- Header dengan background gambar full-width dan overlay gelap -->
<div class="relative w-full">
    <div class="h-80 bg-cover bg-center relative" style="background-image: url('{{ asset('images/warung.jpg') }}')">
        <!-- Overlay hitam transparan -->
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <!-- Judul di tengah, dengan shadow agar teks jelas -->
        <div class="absolute inset-0 flex items-center justify-center">
            <h1 class="text-5xl font-extrabold text-white drop-shadow-lg select-none">Marketplace</h1>
        </div>
    </div>
</div>

<!-- Container utama -->
<div class="container mx-auto px-4 mt-8">

    <!-- Baris Search & Dropdown kategori, responsif dan rapi -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <!-- Form Pencarian -->
        <form action="{{ route('user.marketplace.index') }}" method="GET" class="flex-grow max-w-md">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari produk..."
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

    <!-- Grid produk responsif dengan hover effect modern -->
    @if ($produk->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach ($produk as $item)
            <div
                class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300
                       flex flex-col overflow-hidden group"
            >
                <!-- Gambar produk dengan efek zoom saat hover -->
                <div class="relative w-full aspect-[4/3] overflow-hidden rounded-t-2xl bg-gray-100">
                    <img
                        src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-produk.png') }}"
                        alt="{{ $item->nama }}"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                    />
                </div>

                <!-- Informasi produk -->
                <div class="p-4 flex flex-col flex-1 justify-between">
                    <div>
                        <h2
                            class="font-semibold text-lg text-gray-900 truncate"
                            title="{{ $item->nama }}"
                        >
                            {{ $item->nama }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                            {{ $item->deskripsi ?? '-' }}
                        </p>
                    </div>

                    <!-- Nama toko dengan icon -->
                    <div class="flex items-center gap-1 text-xs text-indigo-600 font-medium mt-4">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-indigo-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M3 7l1.664 8.32A2 2 0 006.613 17h10.774a2 2 0 001.95-1.68L21 7M5 7h14M8 10v4M12 10v4M16 10v4"
                            />
                        </svg>
                        <span title="{{ $item->toko->nama_toko ?? 'Toko tidak diketahui' }}">
                            {{ $item->toko->nama_toko ?? 'Toko tidak diketahui' }}
                        </span>
                    </div>

                    <!-- Harga produk -->
                    <p class="text-xl font-extrabold text-gray-900 mt-2">
                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Tombol beli full-width dengan efek hover -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    <form action="{{ route('user.pembelian.create', $item->id) }}" method="GET" class="w-full">
                        @csrf
                        <button
                            type="submit"
                            class="w-full bg-indigo-600 text-white font-semibold py-2 rounded-lg
                                   hover:bg-indigo-700 transition-colors duration-300"
                        >
                            Beli
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination dengan styling Tailwind -->
    <div class="mt-10 flex justify-center">
        {{ $produk->withQueryString()->links('pagination::tailwind') }}
    </div>

    @else
    <!-- Pesan kosong saat produk tidak tersedia -->
    <div class="flex items-center justify-center min-h-[300px] mt-20">
        <p class="text-gray-400 text-center text-lg italic select-none">
            Tidak ada produk tersedia di marketplace.
        </p>
    </div>
    @endif
</div>
@endsection
