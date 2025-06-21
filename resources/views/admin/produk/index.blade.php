@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Produk</h1>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">No</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Gambar</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama Produk</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Pemilik</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Toko</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Kategori</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Harga</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($produk as $index => $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($item->gambar)
                                <img src="{{ asset('produk/' . $item->gambar) }}" alt="{{ $item->gambar }}" class="w-12 h-12 object-cover rounded-md">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->nama }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->user->anggota->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->toko->nama_toko ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->kategori->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.produk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
