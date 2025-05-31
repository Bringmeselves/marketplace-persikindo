@extends('layouts.app')

@section('title', 'Kelola Toko')

@section('content')
    {{-- Tombol Kelola Penjualan --}}
    <div class="mb-8 flex justify-center">
        <a href="{{ route('user.transaksi.penjualan') }}" 
           class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-100 transition font-semibold">
            🔄 Kelola Penjualan
        </a>
    </div>

    <div class="max-w-7xl mx-auto p-6 bg-white shadow-md rounded-xl mt-10">
        {{-- Profil Toko --}}
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-8">
            {{-- Foto Profil Toko Lingkaran --}}
            <div class="relative w-24 h-24 rounded-full overflow-hidden bg-gray-100 border border-gray-300 flex-shrink-0">
                <img 
                    src="{{ $toko->foto_toko && file_exists(public_path('storage/' . $toko->foto_toko))
                        ? asset('storage/' . $toko->foto_toko)
                        : asset('images/default-toko.png') }}"
                    alt="Foto Toko" 
                    class="w-full h-full object-cover"
                >
            </div>

            {{-- Info Toko --}}
            <div class="flex-1 space-y-3">
                <h1 class="text-3xl font-bold text-gray-900">{{ $toko->nama_toko }}</h1>
                
                {{-- Deskripsi --}}
                <div class="flex">
                    <span class="w-28 font-semibold text-gray-700">Deskripsi:</span>
                    <p class="text-sm text-gray-600 flex-1">{{ $toko->keterangan ?: '-' }}</p>
                </div>

                {{-- Kota Asal --}}
                <div class="flex">
                    <span class="w-28 font-semibold text-gray-700">Kota Asal:</span>
                    <p class="text-sm text-gray-600 flex-1">{{ $toko->city_name ?? '-' }}</p>
                </div>

                {{-- Alamat --}}
                <div class="flex">
                    <span class="w-28 font-semibold text-gray-700">Alamat:</span>
                    <p class="text-sm text-gray-600 flex-1">{{ $toko->alamat ?: '-' }}</p>
                </div>

                {{-- Nomor WA --}}
                <div class="flex">
                    <span class="w-28 font-semibold text-gray-700">Nomor WA:</span>
                    <p class="text-sm text-gray-600 flex-1">{{ $toko->nomer_wa ?: '-' }}</p>
                </div>
            </div>

            {{-- Tombol Edit Profil --}}
            <div class="mt-4 md:mt-0 ml-auto flex-shrink-0">
                <a href="{{ route('user.toko.edit', $toko->id) }}" 
                   class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2 rounded-md transition">
                    Edit Profil Toko
                </a>
            </div>
        </div>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-6 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tombol Tambah Produk --}}
        <div class="mb-8 flex justify-center">
            <a href="{{ route('user.produk.create', ['toko_id' => $toko->id]) }}" 
               class="bg-black text-white px-6 py-3 rounded-md hover:bg-gray-800 transition font-semibold">
                + Tambah Produk
            </a>
        </div>

        {{-- Daftar Produk --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse ($produkList as $produk)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden flex flex-col">
                    {{-- Gambar Produk --}}
                    <div class="w-full aspect-[4/3] bg-gray-100">
                        <img src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : asset('images/default-produk.png') }}" 
                             alt="Foto Produk" 
                             class="w-full h-full object-cover rounded-t-xl">
                    </div>

                    {{-- Informasi Produk --}}
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h2 class="font-bold text-lg text-gray-900 truncate">{{ $produk->nama }}</h2>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $produk->deskripsi }}</p>
                        </div>
                        <p class="text-lg font-bold text-gray-900 mt-3">Rp{{ number_format($produk->harga, 0, ',', '.') }}</p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-between items-center px-4 py-3 bg-gray-50 border-t border-gray-200">
                        <a href="{{ route('user.produk.edit', $produk->id) }}" 
                           class="text-black-600 hover:text-black-800 font-semibold text-sm">
                            Edit
                        </a>

                        <form action="{{ route('user.produk.destroy', $produk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-white-600 hover:text-white-800 font-semibold text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                {{-- Jika tidak ada produk --}}
                <p class="text-center text-gray-500 col-span-full">
                    Belum ada produk di toko ini.
                </p>
            @endforelse
        </div>
    </div>
@endsection
