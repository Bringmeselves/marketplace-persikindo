@extends('layouts.app')

@section('content')

<!-- Hero Toko dengan Informasi -->
<div class="relative w-full h-[60vh] overflow-hidden rounded-2xl shadow-lg mb-10">
    <img src="{{ $toko->foto_toko ? asset('storage/' . $toko->foto_toko) : asset('images/warung.jpg') }}"
         alt="{{ $toko->nama_toko }}"
         class="absolute inset-0 w-full h-full object-cover">
    
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/60"></div>
    
    <!-- Informasi Toko -->
    <div class="absolute inset-0 flex items-center justify-center px-4">
        <div class="text-center max-w-2xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white">{{ $toko->nama_toko }}</h1>
            
            @if ($toko->keterangan)
                <p class="text-lg md:text-xl mb-2 text-white">{{ $toko->keterangan }}</p>
            @endif

            <div class="space-y-1 text-sm md:text-base">
                @if ($toko->nomer_wa)
                    <p class="flex items-center justify-center gap-2 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a2 2 0 011.9 1.37l.58 1.74a2 2 0 001.9 1.37h1.64a2 2 0 001.9-1.37l.58-1.74A2 2 0 0116.72 3H20a2 2 0 012 2v14a2 2 0 01-2 2h-3.28a2 2 0 01-1.9-1.37l-.58-1.74a2 2 0 00-1.9-1.37h-1.64a2 2 0 00-1.9 1.37l-.58 1.74A2 2 0 017.28 21H4a2 2 0 01-2-2V5z"/>
                        </svg>
                        <span class="text-white">{{ $toko->nomer_wa }}</span>
                    </p>
                @endif

                @if ($toko->alamat)
                    <p class="flex items-center justify-center gap-2 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 21H10.586l-4.243-4.243A8 8 0 1117.657 16.657z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-white">{{ $toko->alamat }} @if($toko->city_name)- {{ $toko->city_name }}@endif</span>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Produk -->
<div class="container mx-auto px-4">
    <h3 class="text-xl font-semibold mb-4">Produk dari toko ini</h3>

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
                        </div>
                        <p class="text-xl font-extrabold text-gray-900 mt-2">
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

        <div class="mt-8 flex justify-center">
            {{ $produk->links('pagination::tailwind') }}
        </div>
    @else
        <div class="text-center text-gray-400 py-12">
            Toko ini belum memiliki produk.
        </div>
    @endif
</div>

@endsection
