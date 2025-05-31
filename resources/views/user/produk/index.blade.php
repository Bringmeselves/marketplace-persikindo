@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Daftar Produk Anda</h1>

    {{-- Menampilkan pesan sukses atau error --}}
    @if (session('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tampilkan daftar produk --}}
    @if ($produk->isEmpty())
        <div class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded">
            Anda belum memiliki produk. <a href="{{ route('user.produk.create') }}" class="text-blue-500 underline">Tambah Produk</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($produk as $item)
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h2 class="text-lg font-bold">{{ $item->nama }}</h2>
                    <p class="text-gray-600">{{ $item->deskripsi }}</p>
                    <p class="text-gray-600">Harga: Rp{{ number_format($item->harga, 0, ',', '.') }}</p>
                    <p class="text-gray-600">Stok: {{ $item->stok }}</p>
                    <p class="text-gray-600">Kategori: {{ $item->kategori->nama }}</p>
                    @if ($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Produk" class="w-full h-40 object-cover mt-4 rounded">
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('user.produk.edit', $item->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                        <form action="{{ route('user.produk.destroy', $item->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection