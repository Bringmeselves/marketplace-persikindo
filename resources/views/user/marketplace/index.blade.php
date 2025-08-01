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

   {{-- Search Bar --}}
    <div class="bg-white rounded-xl shadow p-4 sm:p-6 mb-10">
        <form action="{{ route('user.marketplace.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            {{-- Input Search --}}
            <div class="col-span-1 sm:col-span-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari produk atau toko..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            {{-- Dropdown Kategori --}}
            <div>
                <select name="kategori" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Origin --}}
            <div>
                <select name="origin" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Kota Asal</option>
                    @foreach ($origins as $origin)
                        <option value="{{ $origin['id'] }}" {{ request('origin') == $origin['id'] ? 'selected' : '' }}>
                            {{ $origin['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Sort --}}
            <div>
                <select name="sort" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Urutkan</option>
                    <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga Termurah</option>
                    <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                </select>
            </div>

            {{-- Tombol Cari --}}
            <div class="sm:col-span-2 lg:col-span-5 text-right">
                <button type="submit" class="mt-2 sm:mt-0 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg transition">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- Produk List --}}
    @if ($produk->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($produk as $item)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all flex flex-col overflow-hidden group">

                {{-- Gambar dengan Jumlah Terjual --}}
                <div class="relative w-full aspect-[4/3] overflow-hidden bg-gray-100">

                    {{-- Jumlah Terjual (badge di pojok kiri atas) --}}
                    <div class="absolute top-2 left-2 bg-white/90 text-gray-700 text-xs px-2 py-1 rounded shadow flex items-center gap-1 z-10">
                        <i data-lucide="shopping-bag" class="w-3.5 h-3.5 text-gray-500"></i>
                        <span>{{ $item->jumlah_terjual ?? 0 }} terjual</span>
                    </div>

                    {{-- Gambar Produk --}}
                    <img
                        src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-produk.png') }}"
                        alt="{{ $item->nama }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                </div>

                {{-- Konten --}}
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="font-semibold text-base md:text-lg text-gray-900 truncate" title="{{ $item->nama }}">
                            {{ $item->nama }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                            {{ $item->deskripsi ?? '-' }}
                        </p>

                        {{-- Rating dan Jumlah Ulasan --}}
                        @php
                            $jumlahUlasan = $item->penilaian->count();
                            $rataRating = $jumlahUlasan ? round($item->penilaian->avg('rating'), 1) : null;
                        @endphp

                        @if ($jumlahUlasan > 0)
                            <div class="flex items-center mt-2 gap-1 text-sm">
                                {{-- Satu ikon bintang --}}
                                <i data-lucide="star" class="w-4 h-4 text-yellow-400"></i>

                                {{-- Nilai rata-rata dan jumlah ulasan --}}
                                <span class="text-gray-700">
                                    {{ $rataRating }} ({{ $jumlahUlasan }} ulasan)
                                </span>
                            </div>
                        @else
                            <div class="text-sm text-gray-400 mt-2">Belum ada ulasan</div>
                        @endif

                        {{-- Info Toko --}}
                        @if ($item->toko)
                            <div class="mt-3 text-xs text-gray-600 flex flex-col gap-y-1">
                                {{-- Nama Toko --}}
                                <div class="flex items-center gap-1">
                                    <i data-lucide="store" class="w-4 h-4 text-indigo-500"></i>
                                    <span class="font-medium text-indigo-500">{{ $item->toko->nama_toko }}</span>
                                </div>

                                {{-- Kota (Origin) --}}
                                <div class="flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                                    <span>
                                        {{
                                            collect($origins)->firstWhere('id', $item->toko->origin)['label']
                                            ?? 'Kota tidak diketahui'
                                        }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <p class="text-lg font-bold text-indigo-700 mt-4">
                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Tombol Aksi: Beli & Tambah ke Keranjang --}}
                @if ($item->user->anggota && $item->user->anggota->status === 'rejected')
                    <button disabled class="w-full bg-gray-300 text-gray-600 py-2 rounded-xl cursor-not-allowed">
                        Tidak tersedia
                    </button>
                @else

                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        {{-- Tombol Beli --}}
                        <form action="{{ route('user.pembelian.create', $item->id) }}" method="GET" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700 transition-all flex justify-center items-center gap-2">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Beli
                            </button>
                        </form>

                        {{-- Tombol Tambah ke Keranjang --}}
                        <form action="{{ route('user.keranjang.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $item->id }}">
                            <input type="hidden" name="varian_id" value="{{ $item->varian->first()->id ?? '' }}">

                            <button type="submit" class="bg-green-500 text-white p-2 rounded-xl hover:bg-green-600 transition">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>
                @endif
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

{{-- SweetAlert Notification --}}
@if (session('success') || session('error') || session('welcome') || session('catatan_penolakan'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    html: `<p style="margin: 0;">{{ session('success') }}</p>`,
                    iconColor: '#10b981', // green-500
                    background: '#ffffff',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: `<p style="margin: 0;">{{ session('error') }}</p>`,
                    iconColor: '#ef4444', // red-500
                    background: '#ffffff',
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#ef4444',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif

            @if (session('welcome'))
                Swal.fire({
                    icon: 'info',
                    title: 'Selamat Datang',
                    html: `<p style="margin: 0;">{{ session('welcome') }}</p>`,
                    iconColor: '#3b82f6', // blue-500
                    background: '#ffffff',
                    confirmButtonText: 'Terima Kasih',
                    confirmButtonColor: '#3b82f6',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif
        });
    </script>

    <style>
        .swal-attractive-popup {
            border-radius: 1rem;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
            font-family: 'Segoe UI', sans-serif;
        }

        .swal-attractive-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .swal-attractive-button {
            font-size: 14px !important;
            font-weight: 600;
            padding: 10px 20px !important;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .swal-attractive-button:hover {
            filter: brightness(0.95);
        }
    </style>
@endif
@endsection
