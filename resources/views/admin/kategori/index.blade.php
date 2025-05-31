@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-semibold mb-6">Daftar Kategori</h1>

    {{-- Tombol Tambah Kategori --}}
    <a href="{{ route('admin.kategori.create') }}" 
       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded mb-6 transition">
        Tambah Kategori
    </a>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel daftar kategori --}}
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded shadow-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left w-12">#</th>
                    <th class="py-3 px-6 text-left">Nama</th>
                    <th class="py-3 px-6 text-left">Slug</th>
                    <th class="py-3 px-6 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($kategori as $item)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6">{{ $loop->iteration }}</td>
                        <td class="py-3 px-6">{{ $item->name }}</td>
                        <td class="py-3 px-6">{{ $item->slug }}</td>
                        <td class="py-3 px-6 text-center space-x-2">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.kategori.edit', $item->id) }}" 
                               class="inline-block bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-semibold py-1 px-3 rounded transition">
                                Edit
                            </a>

                            {{-- Form Hapus --}}
                            <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-1 px-3 rounded transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
