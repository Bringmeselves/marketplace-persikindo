@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-12">

    {{-- HERO SECTION --}}
    @guest
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-10 mb-20 shadow-xl">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-white leading-tight">Marketplace UMKM Anggota Persikindo</h1>
            <p class="text-lg md:text-xl mb-6 text-white opacity-90">Dukung UMKM lokal dan temukan peluang usaha terbaik.</p>
            <div class="flex justify-center gap-4 flex-wrap">
                <a href="{{ route('login') }}" class="bg-white text-indigo-700 font-semibold px-6 py-2 rounded-full shadow-sm hover:bg-gray-100 transition-all">Login</a>
                <a href="{{ route('register') }}" class="bg-yellow-400 text-indigo-900 font-semibold px-6 py-2 rounded-full shadow-sm hover:bg-yellow-300 transition-all">Daftar Gratis</a>
            </div>
        </div>
    </div>
    @endguest

    {{-- FITUR UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
        @php
            $features = [
                ['title' => 'Aman & Terverifikasi', 'desc' => 'Hanya untuk anggota resmi Persikindo dan diawasi admin.'],
                ['title' => 'Dukung UMKM Lokal', 'desc' => 'Jelajahi produk dari pelaku UMKM seluruh Indonesia.'],
                ['title' => 'Transaksi Mudah', 'desc' => 'Pembayaran & pengiriman terintegrasi untuk kemudahan Anda.']
            ];
        @endphp

        @foreach($features as $feature)
        <div class="bg-white p-6 rounded-2xl shadow-md text-center hover:shadow-lg transition-all">
            <h3 class="text-xl font-semibold text-indigo-600 mb-2">{{ $feature['title'] }}</h3>
            <p class="text-sm text-gray-600">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
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
@endsection
