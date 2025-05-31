@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-lg">
    <h1 class="text-2xl font-semibold mb-6">Tambah Kategori</h1>

    {{-- Form tambah kategori --}}
    <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Input Nama Kategori --}}
        <div>
            <label for="name" class="block mb-2 font-medium text-gray-700">Nama Kategori</label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                value="{{ old('name') }}" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            {{-- Error message --}}
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan dan Batal --}}
        <div class="flex space-x-4">
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-md transition">
                Simpan
            </button>

            <a href="{{ route('admin.kategori.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded-md transition flex items-center justify-center">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
