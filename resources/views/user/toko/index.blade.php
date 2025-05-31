@extends('layouts.app')

@section('title', 'Daftar Toko')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white shadow-md rounded-xl mt-10">
    <h1 class="text-2xl font-bold mb-6">Daftar Toko Anda</h1>

    <!-- Pesan jika berhasil menambah atau memperbarui toko -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Daftar Toko (Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($tokoList as $toko)
            <div class="bg-gray-50 rounded-lg shadow-md overflow-hidden">
                <!-- Gambar Toko -->
                <img src="{{ $toko->foto_toko ? asset('storage/' . $toko->foto_toko) : asset('images/default-toko.png') }}" 
                     alt="Foto Toko" class="w-full h-40 object-cover">

                <!-- Informasi Toko -->
                <div class="p-4">
                    <h2 class="font-semibold text-lg text-indigo-600">{{ $toko->nama_toko }}</h2>
                    <p class="text-sm text-gray-600">{{ $toko->alamat }}</p>
                    <p class="text-sm text-gray-600">WA: {{ $toko->nomer_wa }}</p>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-between items-center p-4 bg-gray-100">
                    <a href="{{ route('user.toko.edit', $toko->id) }}" class="text-indigo-600 hover:text-indigo-800">
                        Edit
                    </a>
                    
                    <form action="{{ route('user.toko.destroy', $toko->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 col-span-full">
                Anda belum memiliki toko. 
                <a href="{{ route('user.toko.create') }}" class="text-indigo-600 hover:text-indigo-800">Buat toko baru</a>
            </p>
        @endforelse
    </div>
</div>
@endsection