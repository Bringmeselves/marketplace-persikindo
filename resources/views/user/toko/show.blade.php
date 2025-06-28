@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    {{-- Profil Toko --}}
    <div class="bg-white shadow-md rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-start gap-6 border border-gray-100">
        
        {{-- Foto Toko --}}
        <div class="w-28 h-28 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center border border-gray-200">
            @if($toko->foto_toko && file_exists(public_path('storage/' . $toko->foto_toko)))
                <img src="{{ asset('storage/' . $toko->foto_toko) }}" alt="Foto Toko" class="w-full h-full object-cover">
            @else
                <i data-lucide="image-off" class="w-6 h-6 text-gray-400"></i>
            @endif
        </div>

        {{-- Detail Toko --}}
        <div class="flex-1 space-y-3">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="store" class="w-6 h-6 text-indigo-500"></i>
                {{ $toko->nama_toko }}
            </h1>

            {{-- Keterangan --}}
            @if ($toko->keterangan)
                <div x-data="{ expanded: false }" class="text-sm text-gray-600">
                    @php
                        $keterangan = $toko->keterangan;
                        $maxLength = 120;
                    @endphp
                    @if (strlen($keterangan) > $maxLength)
                        <p>
                            <span x-show="!expanded">{{ \Illuminate\Support\Str::limit($keterangan, $maxLength) }}</span>
                            <span x-show="expanded">{{ $keterangan }}</span>
                            <button @click="expanded = !expanded" class="text-indigo-600 hover:underline ml-1" x-text="expanded ? 'Sembunyikan' : 'Selengkapnya'"></button>
                        </p>
                    @else
                        <p>{{ $keterangan }}</p>
                    @endif
                </div>
            @endif

            {{-- Kontak & Lokasi --}}
            <div class="space-y-1 text-sm text-gray-600">
                @if ($toko->alamat)
                    <p class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                        {{ $toko->alamat }}
                    </p>
                @endif
                @if ($toko->city_name)
                    <p class="flex items-center gap-2">
                        <i data-lucide="city" class="w-4 h-4 text-gray-400"></i>
                        {{ $toko->city_name }}
                    </p>
                @endif
                @if ($toko->nomer_wa)
                    <p class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $toko->nomer_wa) }}" target="_blank" class="text-indigo-600 font-medium hover:underline">
                            {{ $toko->nomer_wa }}
                        </a>
                    </p>
                @endif
            </div>

            {{-- Tombol Chat untuk Pembeli --}}
            <form action="{{ route('user.chat.mulai', $toko->id) }}" method="GET" class="inline-block">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow w-auto">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    Chat Penjual
                </button>
            </form>
        </div>
    </div>

    {{-- Produk --}}
    <div>
        <h2 class="text-xl font-semibold flex items-center gap-2 mb-4">
            <i data-lucide="package" class="w-5 h-5 text-gray-500"></i>
            Produk dari toko ini
        </h2>

        @if ($produk->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($produk as $item)
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition duration-300 flex flex-col overflow-hidden group">
                        <div class="relative w-full aspect-[4/3] overflow-hidden bg-gray-100">
                            <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-produk.png') }}"
                                 alt="{{ $item->nama }}"
                                 class="w-full h-full object-cover transition group-hover:scale-105 duration-300">
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 truncate" title="{{ $item->nama }}">
                                    {{ $item->nama }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                    {{ $item->deskripsi ?? '-' }}
                                </p>
                            </div>
                            <p class="text-lg font-bold text-gray-900 mt-2">
                                Rp{{ number_format($item->harga, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
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

            <div class="mt-8 flex justify-center">
                {{ $produk->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center text-gray-400 py-12">
                <i data-lucide="box" class="w-8 h-8 mx-auto mb-2"></i>
                Toko ini belum memiliki produk.
            </div>
        @endif
    </div>

</div>
<!-- Alpine.js (untuk expandable keterangan) -->
<script src="https://unpkg.com/alpinejs" defer></script>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

@endsection
