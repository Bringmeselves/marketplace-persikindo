@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-12">

    {{-- HERO SECTION SLIDER ala e-Commerce --}}
    <div class="relative mb-20">
        <div class="swiper mySwiper rounded-3xl overflow-hidden shadow-xl">
            <div class="swiper-wrapper">
                @php
                    $slides = [
                        ['image' => 'images/slide1.jpg', 'title' => 'Belanja Produk UMKM Terbaik', 'desc' => 'Jelajahi berbagai produk dari anggota Persikindo.'],
                        ['image' => 'images/slide2.jpg', 'title' => 'Dukung Usaha Lokal', 'desc' => 'Setiap pembelian Anda berarti untuk UMKM di Indonesia.'],
                        ['image' => 'images/slide3.jpg', 'title' => 'Transaksi Aman dan Mudah', 'desc' => 'Marketplace terpercaya untuk kebutuhan Anda.'],
                    ];
                @endphp

                @foreach ($slides as $slide)
                <div class="swiper-slide relative h-[400px] md:h-[500px] w-full">
                    <img src="{{ asset($slide['image']) }}" alt="Slide" class="w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-center items-center text-center px-4">
                        <h2 class="text-white text-3xl md:text-5xl font-extrabold mb-4 drop-shadow-md">{{ $slide['title'] }}</h2>
                        <p class="text-white text-lg md:text-xl drop-shadow-sm mb-6">{{ $slide['desc'] }}</p>
                        @guest
                        <div class="flex gap-4 flex-wrap justify-center">
                            <a href="{{ route('login') }}" class="bg-white text-indigo-700 font-semibold px-6 py-2 rounded-full hover:bg-gray-100 transition-all">Login</a>
                            <a href="{{ route('register') }}" class="bg-yellow-400 text-indigo-900 font-semibold px-6 py-2 rounded-full hover:bg-yellow-300 transition-all">Daftar Gratis</a>
                        </div>
                        @endguest
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Navigasi Panah -->
            <div class="swiper-button-next text-white"></div>
            <div class="swiper-button-prev text-white"></div>

            <!-- Navigasi Bulat -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    {{-- FITUR UTAMA --}}
<div class="mb-20">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Kenapa Belanja di Sini?</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @php
            $features = [
                ['icon' => 'shield-check', 'title' => 'Aman & Terverifikasi', 'desc' => 'Hanya untuk anggota resmi Persikindo dan diawasi oleh admin profesional.'],
                ['icon' => 'store', 'title' => 'Dukung UMKM Lokal', 'desc' => 'Jelajahi dan dukung ribuan produk dari pelaku UMKM di seluruh Indonesia.'],
                ['icon' => 'wallet', 'title' => 'Transaksi Mudah', 'desc' => 'Sistem pembayaran dan pengiriman yang terintegrasi dan user-friendly.'],
            ];
        @endphp

        @foreach($features as $feature)
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 group">
            <div class="flex justify-center mb-4">
                <div class="bg-indigo-100 text-indigo-600 p-4 rounded-full group-hover:bg-indigo-600 group-hover:text-white transition">
                    @switch($feature['icon'])
                        @case('shield-check')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m1-5.5L12 2L4 5.5v6c0 5.25 3.75 9.75 8 11c4.25-1.25 8-5.75 8-11v-6z" />
                            </svg>
                            @break
                        @case('store')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 7l1.34 8.27a2 2 0 001.98 1.73h10.36a2 2 0 001.98-1.73L20 7M9 10v4m6-4v4" />
                            </svg>
                            @break
                        @case('wallet')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2h3V9h-3z" />
                            </svg>
                            @break
                    @endswitch
                </div>
            </div>

            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $feature['title'] }}</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
    </div>
</div>

    {{-- PRODUK TERBARU --}}
    <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Produk Terbaru</h2>

    @if ($produk->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($produk as $item)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all flex flex-col overflow-hidden group">
                <div class="relative w-full aspect-[4/3] overflow-hidden bg-gray-100">
                    <img
                        src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-produk.png') }}"
                        alt="{{ $item->nama }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="font-semibold text-base md:text-lg text-gray-900 truncate" title="{{ $item->nama }}">
                            {{ $item->nama }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                            {{ $item->deskripsi ?? '-' }}
                        </p>

                        @if ($item->toko)
                        <div class="mt-3 text-xs text-gray-600 flex items-center gap-1">
                            <span class="font-medium text-indigo-500">{{ $item->toko->nama_toko }}</span>
                            <span>· {{ $item->toko->city_name }}</span>
                        </div>
                        @endif
                    </div>

                    <p class="text-lg font-bold text-indigo-700 mt-4">
                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                    </p>
                </div>

                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                    <form action="{{ route('user.pembelian.create', $item->id) }}" method="GET" onsubmit="return handleLoginRedirect(event)">
                        @csrf
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700 transition-all">
                            Beli
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 italic text-center mt-10">
            Belum ada produk tersedia.
        </p>
    @endif
</div>

{{-- Redirect jika belum login saat klik Beli --}}
<script>
function handleLoginRedirect(event) {
    @guest
        event.preventDefault();
        window.location.href = "{{ route('login') }}";
        return false;
    @endguest
    return true;
}
</script>

{{-- Swiper Init --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    });
</script>
@endsection
